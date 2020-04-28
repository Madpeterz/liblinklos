<?php
$second_bot_config = new second_bot_config();
$input = new inputFilter();
$avatarname = $input->postFilter("avatarname");
$avatarpassword = $input->postFilter("avatarpassword");
$homeregion = $input->postFilter("homeregion");
$master = $input->postFilter("master");
$code = $input->postFilter("code");
$allowrlv = $input->postFilter("allowrlv","bool");
$discordgroupuuid = $input->postFilter("discordgroupuuid");
$DiscordRelayHook = $input->postFilter("DiscordRelayHook");

$failed_on = "";
if(count(explode(" ",$avatarname)) == 1) $avatarname .= " Resident";
if(count(explode(" ",$master)) == 1) $master .= " Resident";
if(strlen($code) < 9) $failed_on .= " code must be 9 or longer";
if(strlen($avatarname) < 5) $failed_on .= " avatarname must be 5 or longer";
else if(strlen($avatarname) > 125) $failed_on .= " avatarname must be 125 or less";
else if(strlen($avatarpassword) < 5) $failed_on .= " avatarpassword is to weak please go fix that now!";
else if($second_bot_config->load_by_field("userName",$avatarname) == true) $failed_on .= " There is already a config assigned to that avatar";
$status = false;
if($failed_on == "")
{
    $second_bot_config = new second_bot_config();
    $uid = $second_bot_config->create_uid("config_uid",8,10);
    if($uid["status"] == true)
    {
        $second_bot_config->set_field("config_uid",$uid["uid"]);
        $second_bot_config->set_field("clientlink",$client->get_id());
        $second_bot_config->set_field("userName",$avatarname);
        $second_bot_config->set_field("password",$avatarpassword);
        $second_bot_config->set_field("master",$master);
        $second_bot_config->set_field("code",$code);
        $second_bot_config->set_field("allowRLV",$allowrlv);
        $second_bot_config->set_field("homeRegion",$homeregion);
        $second_bot_config->set_field("discordGroupTarget",$discordgroupuuid);
        $second_bot_config->set_field("DiscordRelayHook",$DiscordRelayHook);

        $create_status = $second_bot_config->create_entry();
        if($create_status["status"] == true)
        {
            $status = true;
            $client_rental_set = new client_rental_set();
            $client_rental_set->load_on_field("clientlink",$client->get_id());
            if($client_rental_set->get_count() == 1)
            {
                $client_rental = $client_rental_set->get_first();
                if($client_rental->get_secondbotconfiglink() == null)
                {
                    $client_rental->set_field("secondbotconfiglink",$second_bot_config->get_id());
                    $save_status = $client_rental->save_changes();
                    if($save_status["status"] == true)
                    {
                        $pending_command = new pending_command();
                        $pending_command->set_field("ActionCreate",1);
                        $pending_command->set_field("ActionStart",1);
                        $pending_command->set_field("secondbotconfiglink",$second_bot_config->get_id());
                        $create_status = $pending_command->create_entry();
                        if($create_status["status"] == true)
                        {
                            $status = true;
                            $redirect = "home";
                            echo "Config created, assigned to bot and starting bot";
                        }
                        else
                        {
                            echo "Something failed please try again";
                        }
                    }
                    else
                    {
                        echo "Something failed please try again";
                    }
                }
                else
                {
                    echo "Config created";
                    $redirect = "config";
                }
            }
            else
            {
                echo "Config created";
                $redirect = "config";
            }
        }
        else
        {
            echo "Unable to create config ".$create_status["message"]."";
        }
    }
    else
    {
        echo "Unable to create config uid";
    }
}
else
{
    echo $failed_on;
}
?>
