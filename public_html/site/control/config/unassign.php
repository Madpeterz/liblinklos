<?php
$status = false;
$second_bot_config = new second_bot_config();
if($second_bot_config->load_by_field("config_uid",$page) == true)
{
    $target_client_rental = new client_rental();
    if($target_client_rental->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == true)
    {
        if($target_client_rental->get_clientlink() == $second_bot_config->get_clientlink())
        {
            if($target_client_rental->get_clientlink() == $client->get_id())
            {
                $running_bot = new running_bot();
                if($running_bot->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == false)
                {
                    $pending_command = new pending_command();
                    if($pending_command->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == false)
                    {
                        $target_client_rental->set_field("secondbotconfiglink",null);
                        $save_status = $target_client_rental->save_changes();
                        if($save_status["status"] == true)
                        {
                            echo "Unassigned";
                            $redirect = "home";
                            $status = true;
                        }
                        else
                        {
                            echo "Unable to unassign bot right now";
                        }
                    }
                    else
                    {
                        echo "Bot has pending commands, please wait and try again";
                    }
                }
                else
                {
                    echo "Bot is currently marked as running, please stop the bot to unassign!";
                }
            }
            else
            {
                echo "Client linking error [CR1 vs C1] please contact support";
            }
        }
        else
        {
            echo "Client linking error [S1 vs C1] please contact support";
        }
    }
    else
    {
        echo "Unable to find assigned to rental";
    }
}
else
{
    echo "Unable to find config";
}
?>
