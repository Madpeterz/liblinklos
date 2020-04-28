<?php
$input = new inputFilter();
$client_rental = new client_rental();
$second_bot_config = new second_bot_config();
$status = false;
if($client_rental->load_by_field("rental_uid",$page) == true)
{
    if($client_rental->get_clientlink() == $client->get_id())
    {
        if($client_rental->get_secondbotconfiglink() != null)
        {
            if($second_bot_config->load($client_rental->get_secondbotconfiglink()) == true)
            {
                if($second_bot_config->get_clientlink() == $client_rental->get_clientlink())
                {
                    $pending_command = new pending_command();
                    if($pending_command->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == false)
                    {
                        $pending_command = new pending_command();
                        $pending_command->set_field("ActionStop",1);
                        $pending_command->set_field("ActionStart",1);
                        $pending_command->set_field("secondbotconfiglink",$second_bot_config->get_id());
                        $create_status = $pending_command->create_entry();
                        if($create_status["status"] == true)
                        {
                            $status = true;
                            $redirect = "home";
                            echo "Command added to pending";
                        }
                        else
                        {
                            echo "Unable to request the bot be restarted";
                        }
                    }
                    else
                    {
                        echo "There is already a pending command";
                    }
                }
                else
                {
                    echo "Unable to load bot config [S2]";
                }
            }
            else
            {
                echo "Unable to load bot config [S1]";
            }
        }
        else
        {
            echo "No bot config assigned";
        }
    }
    else
    {
        echo "Unable to load rental";
    }
}
else
{
    echo "Unable to load rental";
}
?>
