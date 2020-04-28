<?php
$client_rental = new client_rental();
if($client_rental->load_by_field("rental_uid",$rental_uid) == true)
{
    $was_on_hold = $client_rental->get_onhold();
    $client_rental->set_field("expires",$expire_unixtime);
    $onhold = false;
    if($expire_unixtime < time())
    {
        $onhold = true;
    }
    $client_rental->set_field("onhold",$onhold);
    $update_status = $client_rental->save_changes();
    if($update_status["status"] == true)
    {
        $all_ok = true;
        if($was_on_hold == true)
        {
            $text = "Bot slot reenabled, please login and start your bot!";
            $message_status = send_message($client->get_id(),$text);
            if($message_status !== true)
            {
                $all_ok = false;
                $reply["message"] = "Unable to create message to be sent!: ".$message_status."";
            }
        }
        if($all_ok == true)
        {
            $reply["status"] = true;
            $reply["message"] = "event_ok";
        }
    }
    else
    {
        $reply["message"] = "Unable to update rental: ".$update_status["message"]."";
    }
}
else
{
    $reply["message"] = "Unable to find rental";
}
?>
