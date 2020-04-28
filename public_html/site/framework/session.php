<?php
class session_control extends error_logging
{
	protected $main_class_object = null;
	protected $logged_in = false;
	protected $session_values = array("lhash");
	protected $lhash = 0;
	protected function populate_session_dataset() : bool
	{
		$this->lhash = $this->main_class_object->get_lhash();
		$this->update_session();
		return true;
	}
	public function end_session()
	{
		$this->why_logged_out = "Session ended";
		session_destroy();
	}
	protected $why_logged_out = "Not logged in at all";
	public function get_why_logged_out()
	{
		return $this->why_logged_out;
	}

	protected function create_lhash(bool $update_session_after=true) : bool
	{
		if($this->create_main_object() == true)
		{
			$new_lhash = $this->hash_password(time(),rand(1000,4000),microtime(),$this->main_class_object->get_lhash());
			$this->main_class_object->set_field("lhash",$new_lhash);
			$save_status = $this->main_class_object->save_changes();
			if($save_status["status"] == true)
			{
				$this->lhash = $new_lhash;
				if($update_session_after == true)
				{
					$this->update_session();
					global $sql;
					$sql->sqlSave();
				}
				return true;
			}
			else $this->why_logged_out = $save_status["message"];
		}
		else $this->why_logged_out = "Unable to create root session object";
		return false;
	}
	public function hash_password(string $arg1="",?string $arg2="",?string $arg3="",?string $arg4="",int $length=42) : string
	{
		$newhash = hash("sha256",implode("",array($arg1,$arg2,$arg3,$arg4)));
		if(strlen($newhash) > $length)
		{
			$newhash = substr($newhash,0,$length);
		}
		return $newhash;
	}
	protected function vaildate_lhash() : bool
	{
		if($this->create_main_object(true) == true)
		{
			if($this->lhash == $this->main_class_object->get_lhash())
			{
				return $this->create_lhash(true);
			}
			else $this->why_logged_out = "session lhash does not match db";
		}
		else $this->why_logged_out = "Unable to create root session object";
		return false;
	}
	protected function create_main_object(bool $also_load_object_from_session_lhash=true) : bool
	{
		if($this->main_class_object == null)
		{
			$this->main_class_object = new client();
		}
		$load_ok = true;
		if($also_load_object_from_session_lhash == true)
		{
			if($this->main_class_object->get_id() == null)
			{
				$load_ok = $this->main_class_object->load_by_field("lhash",$this->lhash);
			}
		}
		return $load_ok;
	}
	protected function update_session()
	{
		foreach($this->session_values as $value)
		{
			$_SESSION[$value] = $this->$value;
		}
	}
	public function get_logged_in() : bool
	{
		return $this->logged_in;
	}
	public function load_from_session() : bool
	{
		if(isset($_SESSION))
		{
			$required_values_set = true;
			foreach($this->session_values as $value)
			{
				if(isset($_SESSION[$value]) == false)
				{
					$required_values_set = false;
					break;
				}
			}
			if($required_values_set == true)
			{
				foreach($this->session_values as $value)
				{
					$this->$value = $_SESSION[$value];
				}
				$this->update_session();
				$this->logged_in = $this->vaildate_lhash();
				if($this->logged_in == false) $this->end_session();
				else $this->why_logged_out = "Session lost link";
				return $this->logged_in;
			}
			else $this->why_logged_out = "-";
		}
		else $this->why_logged_out = "-";
		return false;
	}
	public function update_password(string $new_password) : array
	{
		if($this->main_class_object != null)
		{
			$psalt = $this->hash_password(
				time(),
				$this->main_class_object->get_id(),
				$this->main_class_object->get_psalt(),
				1
			);
			$phash = $this->hash_password(
				$new_password,
				$this->main_class_object->get_id(),
				$psalt,
				1
			);
			$this->main_class_object->set_field("psalt",$psalt);
			$this->main_class_object->set_field("phash",$phash);
			return $this->main_class_object->save_changes();
		}
		else return array("status"=>false,"message"=>"update_password requires the user object to be loaded!");
	}
	public function userpassword_check(string $input_password) : bool
	{
		if($this->create_main_object(true) == true)
		{
			$expected_hash = null;
			if($expected_hash == null) $expected_hash = $this->main_class_object->get_phash();
			$check_hash = $this->hash_userpassword($input_password);
			if($check_hash["status"] == true)
			{
				if($check_hash["phash"] == $expected_hash)
				{
					return true;
				}
				else echo "bad hash 1";
			}
			else echo "bad hash 2";
		}
		else echo "bad hash 3";
		return false;
	}
	public function hash_userpassword(string $input_password,bool $create_new_psalt=false) : array
	{
		if($this->main_class_object != null)
		{
			$p_salt = $this->main_class_object->get_psalt();
			if($create_new_psalt == true)
			{
				$p_salt = $this->hash_password(
					time(),
					$this->main_class_object->get_id(),
					$this->main_class_object->get_psalt(),
					1
				);
			}
			return array(
			"status"=>true,
			"message"=>"hashed",
			"new_salt"=>$create_new_psalt,
			"salt_value" => $p_salt,
			"phash"=>$this->hash_password(
				$input_password,
				$this->main_class_object->get_id(),
				$p_salt,
				1
				)
			);
		}
		else return array("status"=>false,"message"=>"hash_userpassword requires the user object to be loaded!");
	}
	public function attach_member(client $client)
	{
		$this->main_class_object = $client;
	}
	public function get_member() : ?client
	{
		return $this->main_class_object;
	}
	public function login_with_username_password(string $username,string $password) : bool
	{
		if($this->create_main_object(false) == true)
		{
			if($this->main_class_object->load_by_field("name",$username) == true)
			{
				if($this->userpassword_check($password) == true)
				{
					// login ok build session.
					return $this->populate_session_dataset();
				}
				else
				{
					$this->main_class_object = null; // remove link to that account
				}
			}
		}
		return false;
	}
}
?>
