<?php
$client_rental = new client_rental();
if($client_rental->load_by_field("rental_uid",$rental_uid) == true)
{
    $all_ok = true;
    if($client_rental->get_secondbotconfiglink() != null)
    {
        $pending_command_set = new pending_command_set();
        $pending_command_set->load_on_field("secondbotconfiglink",$client_rental->get_secondbotconfiglink());
        if($pending_command_set->get_count() > 0)
        {
            $all_ok = false;
            $reply["status"] = true;
            $reply["action"] = "delay";
            $reply["message"] = "There are pending commands for this rental, unable to remove it right now!";
        }
    }
    if($all_ok == true)
    {
        if($client_rental->get_onhold() == false)
        {
            $client_rental->set_field("onhold",true);
            $save_status = $client_rental->save_changes();
            if($save_status == true)
            {
                $all_ok = false;
                $reply["status"] = true;
                $reply["action"] = "delay";
                $reply["message"] = "Rental not marked as on hold, putting it on hold now!";
            }
            else
            {
                $reply["message"] = "Unable to save changes to rental: ".$save_status["message"]."";
            }
        }
    }
    if($all_ok == true)
    {
        $running_bot = new running_bot();
        if($running_bot->load_by_field("clientrentallink",$client_rental->get_id()) == true)
        {
            $all_ok = false;
            $pending_command = new pending_command();
            $pending_command->set_field("ActionStop",1);
            $pending_command->set_field("ActionRemove",1);
            $pending_command->set_field("secondbotconfiglink",$second_bot_config->get_id());
            $create_status = $pending_command->create_entry();
            if($create_status["status"] == false)
            {
                $reply["status"] = true;
                $reply["action"] = "delay";
                $reply["message"] = "Unable to remove when there is a bot running, requested stop and remove retry soonTM";
            }
            else
            {
                $reply["message"] = "Unable to request bot be stopped: ".$create_status["message"]."";
            }
        }
    }
    if($all_ok == true)
    {
        $remove_status = $client_rental->remove_me();
        if($remove_status["status"] == true)
        {
            $reply["status"] = true;
            $reply["message"] = "event_ok";
        }
        else
        {
            $reply["message"] = "Unable to remove rental: ".$remove_status["message"]."";
        }
    }
}
else
{
    $reply["message"] = "Unable to find rental";
}
?>
