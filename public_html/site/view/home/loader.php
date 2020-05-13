<?php
$template_parts["page_title"] = "Bots";
$template_parts["page_actions"] = "";
$template_parts["html_title"] = "Bots";
$template_parts["page_actions"] = "";


$second_bot_config_set = new second_bot_config_set();
$second_bot_config_set->load_on_field("clientlink",$client->get_id());

$client_rental_set = new client_rental_set();
$client_rental_set->load_on_field("clientlink",$client->get_id());

$running_bot_set = new running_bot_set();
$running_bot_set->load_ids($second_bot_config_set->get_all_ids(),"secondbotconfiglink");

$pending_command_set = new pending_command_set();
$pending_command_set->load_ids($second_bot_config_set->get_all_ids(),"secondbotconfiglink");


$table_head = array("Bot name","Status","Service package / Rental ID","Expires","WebUI"," ");
$table_body = array();
$template_parts["page_title"] .= " Assignable slots";

$unassigned_configs = array();
$unassigned_rentals = array();

foreach($client_rental_set->get_all_ids() as $client_rental_id)
{
    $client_rental = $client_rental_set->get_object_by_id($client_rental_id);
    $unassigned_rentals[$client_rental->get_rental_uid()] = $client_rental->get_rental_uid();
}
foreach($second_bot_config_set->get_all_ids() as $secondbot_bot_config_id)
{
    $second_bot_config= $second_bot_config_set->get_object_by_id($secondbot_bot_config_id);
    $unassigned_configs[$second_bot_config->get_config_uid()] = $second_bot_config->get_userName();
}



$loop = 1;
foreach($client_rental_set->get_all_ids() as $client_rental_id)
{
    $client_rental = $client_rental_set->get_object_by_id($client_rental_id);
    $second_bot_config = $second_bot_config_set->get_object_by_id($client_rental->get_secondbotconfiglink());

    $status = "Unassigned";
    $assigned_bot = "-";
    $unassign = "";

    $weburl = "/";
    if($client_rental->get_onhold() == true)
    {
        $status = "Expired [On hold]";
        if($second_bot_config != null)
        {
            unset($unassigned_rentals[$client_rental->get_rental_uid()]);
            unset($unassigned_configs[$second_bot_config->get_config_uid()]);
            $assigned_bot = '<a href="[[url_base]]config/manage/'.$second_bot_config->get_config_uid().'">'.$second_bot_config->get_userName().'</a>';
            $unassign = '<a href="[[url_base]]config/unassign/'.$second_bot_config->get_config_uid().'">Change bot package</a>';
        }
    }
    else
    {
        if($second_bot_config != null)
        {
            unset($unassigned_rentals[$client_rental->get_rental_uid()]);
            unset($unassigned_configs[$second_bot_config->get_config_uid()]);
            $pending_command = $pending_command_set->get_object_by_field("secondbotconfiglink",$second_bot_config->get_id());
            $assigned_bot = '<a href="[[url_base]]config/manage/'.$second_bot_config->get_config_uid().'">'.$second_bot_config->get_userName().'</a>';
            if($pending_command == null)
            {
                $running_bot = $running_bot_set->get_object_by_field("secondbotconfiglink",$second_bot_config->get_id());
                if($running_bot == null)
                {
                    $status = '<a href="bot/start/'.$client_rental->get_rental_uid().'"><button type="button" class="btn btn-outline-success">Start bot</button></a>';
                    $unassign = '<a href="[[url_base]]config/unassign/'.$second_bot_config->get_config_uid().'">Change bot package</a>';
                }
                else
                {
                    $unassign = "#";
                    $status = '<a href="bot/stop/'.$client_rental->get_rental_uid().'"><button type="button" class="btn btn-outline-danger">Shutdown</button></a>';
                    $status .= ' <a href="bot/restart/'.$client_rental->get_rental_uid().'"><button type="button" class="btn btn-outline-info">Relog</button></a>';
                    if($second_bot_config->get_Http_Enable() == true)
                    {
                        $weburl = '<a href="https://bot'.$client_rental->get_rental_uid().'.'.getenv('BOT_DOMAIN').'/" target="_blank">View</a>';
                    }
                }
            }
            else
            {
                $unassign = "#";
                $status = "Pending update wait then refresh";
            }
        }
    }
    $package = "SecondBotHosted";
    $entry = array();
    $entry[] = $assigned_bot;
    $entry[] = $status;
    $entry[] = $package. " ".$client_rental->get_rental_uid();
    $entry[] = date("l jS \of F Y h:i:s A",$client_rental->get_expires());
    $entry[] = $weburl;
    $entry[] = $unassign;
    $table_body[] = $entry;
}
echo render_table($table_head,$table_body);
echo "<br/><p>The webUI is still being worked<br/> on and is not ready for primetime yet expect it to change!</p>";
echo "<hr/>";
if((count($unassigned_configs) > 0) && (count($unassigned_rentals) > 0))
{
    $form = new form();
    $form->target("config/assign");
    $form->required(true);
    $form->col(6);
    $form->group("Bot");
        $form->select("configuid","Config",1,$unassigned_configs);
    $form->col(6);
    $form->group("Service");
        $form->select("rentaluid","Package",1,$unassigned_rentals);
    echo $form->render("Assign package to bot","success");
}
?>
