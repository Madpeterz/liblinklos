<?php
$pending_command_set = new pending_command_set();
$pending_command_set->load_newest(1,array(),array(),"id","ASC"); // change of plan get the oldest
$collected_output = array();
if($pending_command_set->get_count() > 0)
{
    $pending_command = $pending_command_set->get_first();

    $reply["create"] = $pending_command->get_ActionCreate();
    $reply["remove"] = $pending_command->get_ActionRemove();
    $reply["start"] = $pending_command->get_ActionStart();
    $reply["stop"] = $pending_command->get_ActionStop();
    $reply["containerid"] = null;
    $reply["containername"] = null;
    $reply["containerenv"] = null;
    $reply["taskid"] = $pending_command->get_id();

    $second_bot_config = new second_bot_config();
    $client = new client();
    $running_bot = new running_bot();
    $client_rental = new client_rental();

    if($second_bot_config->load($pending_command->get_secondbotconfiglink()) == true)
    {
        if($client->load($second_bot_config->get_clientlink()) == true)
        {
            if($pending_command->get_ActionCreate() == true)
            {
                if($client_rental->load_by_field("secondbotconfiglink",$pending_command->get_secondbotconfiglink()) == true)
                {
                    $reply["containername"] = $client_rental->get_rental_uid();
                    $env_values = array(
                        "Basic_BotUserName"=>$second_bot_config->get_userName(),
                        "Basic_BotPassword"=>$second_bot_config->get_password(),
                        "Basic_HomeRegions"=>$second_bot_config->get_homeRegion(),

                        "Security_MasterUsername"=>$second_bot_config->get_master(),
                        "Security_SignedCommandkey"=>$second_bot_config->get_code(),
                        "Security_WebUIKey"=>$second_bot_config->get_code(),

                        "Setting_AllowRLV"=>array(false=>"false",true=>"true")[$second_bot_config->get_allowRLV()],
                        "Setting_AllowFunds"=>"true",
                        "Setting_LogCommands"=>"true",
                        "Setting_RelayImToAvatarUUID"=>$second_bot_config->get_Setting_RelayImToAvatarUUID(),
                        "Setting_DefaultSit_UUID"=>$second_bot_config->get_Setting_DefaultSit_UUID(),

                        "DiscordRelay_URL"=>$second_bot_config->get_DiscordRelayHook(),
                        "DiscordRelay_GroupUUID"=>$second_bot_config->get_discordGroupTarget(),
                        "DiscordFull_Enable"=>array(false=>"false",true=>"true")[$second_bot_config->get_DiscordFull_Enable()],
                        "DiscordFull_Token"=>$second_bot_config->get_DiscordFull_Token(),
                        "DiscordFull_ServerID"=>$second_bot_config->get_DiscordFull_ServerID(),

                    );
                    $addon = "";
                    $reply["containerenv"] = "";
                    foreach($env_values as $key => $value)
                    {
                        $reply["containerenv"] .= "".$addon."".$key."='".$value."'";
                        $addon = "::";
                    }
                    $reply["status"] = true;
                    $reply["message"]= "task";
                }
                else
                {
                    $reply["message"]= "Task required config link is broken [S5]";
                }
            }
            else if(($pending_command->get_ActionRemove() == true) || ($pending_command->get_ActionStop()) || ($pending_command->get_ActionStart()))
            {
                if($running_bot->load_by_field("secondbotconfiglink",$pending_command->get_secondbotconfiglink()) == true)
                {
                    $reply["status"] = true;
                    $reply["message"]= "task";
                    $reply["containerid"] = $running_bot->get_containerid();
                }
                else
                {
                    $reply["message"]= "Task is missing its running bot status! [S4]";
                }
            }
            else
            {
                $reply["message"]= "Task has no actions [S3]";
            }
        }
        else
        {
            $reply["message"]= "Unable to load client [S2]";
        }
    }
    else
    {
        $reply["message"]= "Unable to load config [S1]";
    }
}
else
{
    $reply["status"] = true;
    $reply["message"]= "nowork";
}
?>
