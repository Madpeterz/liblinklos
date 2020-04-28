<?php
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
else if($avatarpassword != "dont-change")
{
    if(strlen($avatarpassword) < 5) $failed_on .= " avatarpassword is to weak please go fix that now!";
}
$status = false;
if($failed_on == "")
{
    $second_bot_config = new second_bot_config();
    if($second_bot_config->load_by_field("config_uid",$page) == true)
    {
        if($second_bot_config->get_clientlink() == $client->get_id())
        {
            $where_fields = array(array("userName"=>"="));
            $where_values = array(array($avatarname=>"s"));
            $count_check = $sql->basic_count($second_bot_config->get_table(),$where_fields,$where_values);
            $expected_count = 0;
            if($second_bot_config->get_avataruuid() == $avataruuid)
            {
                $expected_count = 1;
            }
            if($count_check["status"] == true)
            {
                if($count_check["count"] == $expected_count)
                {
                    $second_bot_config->set_field("userName",$avatarname);
                    if($avatarpassword != "dont-change") $second_bot_config->set_field("password",$avatarpassword);
                    $second_bot_config->set_field("master",$master);
                    $second_bot_config->set_field("code",$code);
                    $second_bot_config->set_field("allowRLV",$allowrlv);
                    $second_bot_config->set_field("homeRegion",$homeregion);
                    $second_bot_config->set_field("discordGroupTarget",$discordgroupuuid);
                    $second_bot_config->set_field("DiscordRelayHook",$DiscordRelayHook);
                    $update_status = $second_bot_config->save_changes();
                    if($update_status["status"] == true)
                    {
                        $status = true;
                        $running_bot = new running_bot();
                        if($running_bot->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == true)
                        {
                            $pending_command = new pending_command();
                            $pending_command->set_field("ActionStop",1);
                            $pending_command->set_field("ActionRemove",1);
                            $pending_command->set_field("ActionCreate",1);
                            $pending_command->set_field("ActionStart",1);
                            $pending_command->set_field("secondbotconfiglink",$second_bot_config->get_id());
                            $create_status = $pending_command->create_entry();
                            if($create_status["status"] == true)
                            {
                                $status = true;
                                $redirect = "home";
                                echo "Config updated<br/>Restarting bot now";
                            }
                            else
                            {
                                echo "Unable to update running bot please try again later.";
                            }
                        }
                        else
                        {
                            $status = true;
                            $redirect = "home";
                            echo "Config updated - Please assign to a Bot";
                        }
                    }
                    else
                    {
                        echo "Unable to create config";
                    }
                }
                else
                {
                    echo "Selected username is already in use";
                }
            }
            else
            {
                echo "Unable to check if username in use";
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
    echo $failed_on;
}
?>
