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
            $remove_status = $pending_command_set->purge_collection_set();
            if($remove_status["status"] == false)
            {
                $all_ok = false;
                $reply["message"] = "Unable to clear pending commands: ".$remove_status["message"]."";
            }
        }
        if($all_ok == true)
        {
            $running_bot = new running_bot();
            if($running_bot->load_by_field("clientrentallink",$client_rental->get_id()) == true)
            {
                $pending_command = new pending_command();
                $pending_command->set_field("ActionStop",1);
                $pending_command->set_field("ActionRemove",1);
                $pending_command->set_field("secondbotconfiglink",$client_rental->get_secondbotconfiglink());
                $create_status = $pending_command->create_entry();
                if($create_status["status"] == false)
                {
                    $all_ok = false;
                    $reply["message"] = "Unable to stop running bot: ".$create_status["message"]."";
                }
            }
        }
    }
    if($all_ok == true)
    {
        $client_rental->set_field("onhold",true);
        $save_status = $client_rental->save_changes();
        if($save_status == true)
        {
            $reply["status"] = true;
            $reply["message"] = "event_ok";
        }
        else
        {
            $reply["message"] = "Unable to save changes to rental: ".$save_status["message"]."";
        }
    }
}
else
{
    $reply["message"] = "Unable to find rental";
}
?>
