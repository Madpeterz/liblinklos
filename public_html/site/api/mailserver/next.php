<?php
$reply["hasmessage"] = 0;
$status = true;

$message_set = new message_set();
$message_set->load_newest(1,array(),array(),"id","ASC"); // lol loading oldest with newest command ^+^ hax
if($message_set->get_count() > 0)
{
    $message = $message_set->get_first();
    $client = new client();
    if($client->load($message->get_clientlink()) == true)
    {
        $remove_status = $message->remove_me();
        if($remove_status["status"] == true)
        {
            $reply["hasmessage"] = 1;
            $reply["avataruuid"] = $client->get_uuid();
            echo $message->get_message();
        }
        else
        {
            echo "Unable to remove sent message";
        }
    }
    else
    {
        $remove_status = $message->remove_me();
        if($remove_status["status"] == true)
        {
            echo "Unable to load client to send message to, deleting message to continue";
        }
        else
        {
            echo "Unable to delete broken message with id: ".$message->get_id()."";
        }
    }
}
else
{
    echo "nowork";
}
?>
