<?php
	// Do not edit this file, rerun gen.php to update!
	class pending_command_set extends collectionSet { }
	
	class pending_command extends genClass
	{
		protected $use_table = "pending_command";
		protected $dataset = array(
			"id" => array("type"=>"int","value"=>null),
			"ActionStop" => array("type"=>"bool","value"=>0),
			"ActionStart" => array("type"=>"bool","value"=>0),
			"ActionRemove" => array("type"=>"bool","value"=>0),
			"ActionCreate" => array("type"=>"bool","value"=>0),
			"secondbotconfiglink" => array("type"=>"int","value"=>null),
		);
		public function get_id()
		{
			return $this->get_field("id");
		}
		public function get_ActionStop()
		{
			return $this->get_field("ActionStop");
		}
		public function get_ActionStart()
		{
			return $this->get_field("ActionStart");
		}
		public function get_ActionRemove()
		{
			return $this->get_field("ActionRemove");
		}
		public function get_ActionCreate()
		{
			return $this->get_field("ActionCreate");
		}
		public function get_secondbotconfiglink()
		{
			return $this->get_field("secondbotconfiglink");
		}
	}
?>