<?php
$pending_command = new pending_command();
$input = new inputFilter();
$taskid = $input->postFilter("taskid","integer");
if($pending_command->load($taskid) == true)
{
    $second_bot_config = new second_bot_config();
    $client = new client();
    $running_bot = new running_bot();
    $client_rental = new client_rental();
    if($second_bot_config->load($pending_command->get_secondbotconfiglink()) == true)
    {
        if($client->load($second_bot_config->get_clientlink()) == true)
        {
            if($client_rental->load_by_field("secondbotconfiglink",$pending_command->get_secondbotconfiglink()) == true)
            {
                $containerid = null;
                $running_ok = true;
                if($pending_command->get_ActionCreate() == true)
                {
                    $running_bot = new running_bot();
                    $running_bot->set_field("containerid",$input->postFilter("containerid"));
                    $running_bot->set_field("clientrentallink",$client_rental->get_id());
                    $running_bot->set_field("secondbotconfiglink",$second_bot_config->get_id());
                    $create_status = $running_bot->create_entry();
                    if($create_status["status"] == false)
                    {
                        $reply["message"]= "Unable to add running bot container [C1]";
                        $running_ok = false;
                    }
                }
                else if($pending_command->get_ActionRemove() == true)
                {
                    if($running_bot->load_by_field("secondbotconfiglink",$pending_command->get_secondbotconfiglink()) == true)
                    {
                        $remove_status = $running_bot->remove_me();
                        if($remove_status["status"] == false)
                        {
                            $reply["message"]= "Unable to clear running bot container [R2]";
                            $running_ok = false;
                        }
                    }
                    else
                    {
                        $reply["message"]= "Unable to clear running bot container [R1]";
                        $running_ok = false;
                    }
                }
                if($running_ok == true)
                {
                    $remove_status = $pending_command->remove_me();
                    if($remove_status["status"] == true)
                    {
                        $reply["status"] = true;
                        $reply["message"]= "Task cleared";
                    }
                    else
                    {
                        $reply["message"]= "Unable to clear task [F1]";
                    }
                }
            }
            else
            {
                $reply["message"]= "Unable to load rental [S4]";
            }
        }
        else
        {
            $reply["message"]= "Unable to load client [S3]";
        }
    }
    else
    {
        $reply["message"]= "Unable to load config [S2]";
    }
}
else
{
    $reply["message"]= "No task found with that id [S1]";
}
?>
