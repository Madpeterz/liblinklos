<?php
$template_parts["html_title"] .= " ~ Manage";
$template_parts["page_title"] .= "Editing config";
$template_parts["page_actions"] = "<a href='[[url_base]]config/remove/".$page."'><button type='button' class='btn btn-danger'>Remove</button></a>";

function get_truefalse($a)
{
    if($a == "1") return 1;
    else if($a == "true") return 1;
    else return 0;
}

$second_bot_config = new second_bot_config();
if($second_bot_config->load_by_field("config_uid",$page) == true)
{
    if($second_bot_config->get_clientlink() == $client->get_id())
    {
        $form = new form();
        $form->target("config/update/".$page."");
        $form->required(true);
        $form->col(6);
        $form->group("Basics");
            $form->text_input("avatarname","Name",125,$second_bot_config->get_userName(),"Second Bot");
            $form->text_input("avatarpassword","Password",125,"dont-change","Not hidden!");
            $form->text_input("homeregion","Homeregion (Collection csv)",125,$second_bot_config->get_homeRegion(),"\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\",\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\"");
        $form->col(6);
        $form->group("Security");
            $form->text_input("master","Master",125,$second_bot_config->get_master(),"Bot Master");
            $form->text_input("code","LSL interact code",125,$second_bot_config->get_code(),"Dont Tell Anyone");
        $form->col(6);
        $form->group("Settings");
            $form->select("allowrlv","RLV support",get_truefalse($second_bot_config->get_allowRLV()),array(0=>"No",1=>"Yes"));
            $form->text_input("Setting_RelayImToAvatarUUID","IM to UUID relay",36,$second_bot_config->get_Setting_RelayImToAvatarUUID(),"");
            $form->text_input("Setting_DefaultSit_UUID","Default Sit UUID",36,$second_bot_config->get_Setting_DefaultSit_UUID(),"");
        $form->col(6);
        $form->group("HTTP interface");
            $form->select("Http_Enable","Enable",get_truefalse($second_bot_config->get_Http_Enable()),array(false=>"No",1=>"Yes"));
            $form->text_input("Security_WebUIKey","Web UI key",125,$second_bot_config->get_Security_WebUIKey(),"");
            $form->direct_add("<p>The web UI is still being worked<br/> on and is not ready for primetime yet</p>");
        $form->col(6);
        $form->group("Discord [Relay]");
            $form->text_input("DiscordRelayHook","Discord [Relay] webhook",125,$second_bot_config->get_DiscordRelayHook(),"http://...");
            $form->text_input("discordgroupuuid","Discord [Relay] group",125,$second_bot_config->get_discordGroupTarget(),"Target group UUID");
        $form->col(6);
        $form->group("Discord [Full]");
            $form->select("DiscordFull_Enable","Enable",get_truefalse($second_bot_config->get_DiscordFull_Enable()),array(0=>"No",1=>"Yes"));
            $form->text_input("DiscordFull_Token","Client token",200,$second_bot_config->get_DiscordFull_Token(),"Find in discord dev bot");
            $form->text_input("DiscordFull_ServerID","Server id",200,$second_bot_config->get_DiscordFull_ServerID(),"Find under widgets in discord");
        echo $form->render("Update","primary");
    }
    else
    {
        redirect("config?bubblemessage=Unable to load config&bubbletype=warning");
    }
}
else
{
    redirect("config?bubblemessage=Unable to load config&bubbletype=warning");
}
?>
