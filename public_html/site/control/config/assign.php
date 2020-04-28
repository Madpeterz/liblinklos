<?php
$input = new inputFilter();
$configuid = $input->postFilter("configuid");
$rentaluid = $input->postFilter("rentaluid");
$status = false;
$second_bot_config = new second_bot_config();
if($second_bot_config->load_by_field("config_uid",$configuid) == true)
{
    $target_client_rental = new client_rental();
    if($target_client_rental->load_by_field("rental_uid",$rentaluid) == true)
    {
        if($target_client_rental->get_clientlink() == $second_bot_config->get_clientlink())
        {
            if($target_client_rental->get_clientlink() == $client->get_id())
            {
                if($target_client_rental->get_secondbotconfiglink() == null)
                {
                    $client_rental = new client_rental();
                    if($client_rental->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == false)
                    {
                        $pending_command = new pending_command();
                        if($pending_command->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == false)
                        {
                            $running_bot = new running_bot();
                            if($running_bot->load_by_field("secondbotconfiglink",$second_bot_config->get_id()) == false)
                            {
                                $target_client_rental->set_field("secondbotconfiglink",$second_bot_config->get_id());
                                $save_status = $target_client_rental->save_changes();
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
                                        echo "Config assigned starting bot";
                                    }
                                    else
                                    {
                                        echo "Something failed please try again";
                                    }
                                }
                                else
                                {
                                    echo "Failed to asign the config!";
                                }
                            }
                            else
                            {
                                echo "There is a running bot that needs to stop first!";
                            }
                        }
                        else
                        {
                            echo "There is a pending command for this config that needs to clear first!";
                        }
                    }
                    else
                    {
                        echo "The config is already assigned to a rental!";
                    }
                }
                else
                {
                    echo "There is already a config assigned to that rental please remove it first!";
                }
            }
            else
            {
                echo "Unable to load rental to assign to"; // lies! your a dirty hacker!
            }
        }
        else
        {
            echo "Unable to load rental to assign to"; // lies! your a dirty hacker!
        }
    }
    else
    {
        echo "Unable to load rental to assign to";
    }
}
else
{
    echo "Unable to load config";
}
?>
