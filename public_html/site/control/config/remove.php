<?php
$input = new inputFilter();
$accept = $input->postFilter("accept");
$redirect = "config";
$status = false;
if($accept == "Accept")
{
    $second_bot_config = new second_bot_config();
    if($second_bot_config->load_by_field("config_uid",$page) == true)
    {
        if($second_bot_config->get_clientlink() == $client->get_id())
        {
            $remove_status = $second_bot_config->remove_me();
            if($remove_status["status"] == true)
            {
                $status = true;
                echo "Config removed";
            }
            else
            {
                echo "Unable to config";
            }
        }
        else
        {
            echo "Unable to find config";
        }
    }
    else
    {
        echo "Unable to find config";
    }
}
else
{
    echo "Did not Accept";
    $redirect ="config/manage/".$page."";
}
?>
