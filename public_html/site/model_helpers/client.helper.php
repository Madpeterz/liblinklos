<?php
class client_helper
{
    protected $client = null;
    protected $new_client = false;
    function get_client() : client
    {
        return $this->client;
    }
    function get_is_new_client() : bool
    {
        return $this->new_client;
    }
    function load_or_create(string $client_uuid,string $client_name,bool $show_errors=false) : bool
    {
        $this->client = new client();
        if(strlen($client_uuid) == 36)
        {
            if($this->client->load_by_field("uuid",$client_uuid) == true)
            {
                return true;
            }
            else
            {
                $this->client = new client();
                $this->client->set_field("name",$client_name);
                $this->client->set_field("uuid",$client_uuid);
                $this->client->set_field("lhash",sha1(time()));
                $this->client->set_field("phash",sha1(time()));
                $this->client->set_field("psalt",sha1(time()));
                $create_status = $this->client->create_entry();
                if($create_status["status"] == false)
                {
                    $this->new_client = true;
                    if($show_errors == true) echo "[client Helper] -> as unable to create client because:".$create_status["message"];
                }
                return $create_status["status"];
            }
        }
        else
        {
            if($show_errors == true) echo "[client Helper] -> UUID not vaild";
        }
        return false;
    }
}
?>
