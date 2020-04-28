<?php
$input = new inputFilter();
$avatar_uuid = $input->postFilter("avatar_uuid");
$avatar_name = $input->postFilter("avatar_name");
$rental_uid = $input->postFilter("rental_uid");
$package_uid = $input->postFilter("package_uid");
$event_new = $input->postFilter("event_new","bool");
$event_renew = $input->postFilter("event_renew","bool");
$event_expire = $input->postFilter("event_expire","bool");
$event_remove = $input->postFilter("event_remove","bool");
$unixtime = $input->postFilter("unixtime","integer");
$expire_unixtime = $input->postFilter("expire_unixtime","integer");
$port = $input->postFilter("port","integer");

$failed_on = "";
$bits = explode(" ",$avatar_name);
if(strlen($avatar_uuid) != 36) $failed_on = "Avatar UUID length must be 36";
else if(count($bits) == 1) $failed_on = "Avatar name must be in 2 parts!";
else if(strlen($rental_uid) != 8) $failed_on = "rental uid length must be 8";
else if(strlen($package_uid) != 8) $failed_on = "package uid length must be 8";
$reply["status"] = false;
$reply["action"] = "clear";
function send_message($client_id,$message_text)
{
    $message = new message();
    $message->set_field("clientlink",$client_id);
    $message->set_field("message",$message_text);
    $message_status = $message->create_entry();
    if($message_status["status"] == true)
    {
        return true;
    }
    else
    {
        return $message_status["message"];
    }
}

if($failed_on == "")
{
    $client_helper = new client_helper();
    if($client_helper->load_or_create($avatar_uuid,$avatar_name) == true)
    {
        $client = $client_helper->get_client();

        if($event_new == true)
        {
            include("site/endpoint/event/stages/create.php");
        }
        else if($event_renew == true)
        {
            include("site/endpoint/event/stages/renew.php");
        }
        else if($event_expire == true)
        {
            include("site/endpoint/event/stages/expire.php");
        }
        else if($event_remove == true)
        {
            include("site/endpoint/event/stages/remove.php");
        }
    }
    else
    {
        $reply["message"] = "Unable to create/load client";
    }
}
else
{
    $reply["message"]= "Invaild request: ".$failed_on."";
}
?>
