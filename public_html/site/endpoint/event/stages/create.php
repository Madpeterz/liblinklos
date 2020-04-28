<?php
$client_rental = new client_rental();
$client_rental->set_field("rental_uid",$rental_uid);
$client_rental->set_field("clientlink",$client->get_id());
$client_rental->set_field("expires",$expire_unixtime);
$onhold = false;
if($expire_unixtime < time())
{
    $onhold = true;
}
$client_rental->set_field("onhold",$onhold);
$create_status = $client_rental->create_entry();
if($create_status["status"] == true)
{
    $all_ok = true;
    $text = "Welcome to ".getenv('SITE_TITLE')." \n if this is your very first bot please vist: ".getenv('SITE_HOST')." and select \"Lost/Setup password\" \n to enable your web login!";
    $message_status = send_message($client->get_id(),$text);
    if($message_status !== true)
    {
        $all_ok = false;
        $reply["message"]= "Unable to create message to be sent!: ".$message_status."";
    }
    if($all_ok == true)
    {
        $reply["status"] = true;
        $reply["message"]= "event_ok";
    }
}
else
{
    $reply["message"]= "Unable to create rental!: ".$create_status["message"]."";
}
?>
