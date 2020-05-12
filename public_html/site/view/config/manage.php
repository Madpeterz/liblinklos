<?php
$template_parts["html_title"] .= " ~ Manage";
$template_parts["page_title"] .= "Editing config";
$template_parts["page_actions"] = "<a href='[[url_base]]config/remove/".$page."'><button type='button' class='btn btn-danger'>Remove</button></a>";

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
        $form->group("Security");
            $form->text_input("master","Master",125,$second_bot_config->get_master(),"Bot Master");
            $form->text_input("code","LSL interact code",125,$second_bot_config->get_code(),"Dont Tell Anyone");

        $form->col(6);
        $form->group("Settings");
            $form->select("allowrlv","RLV support",$second_bot_config->get_allowRLV(),array(false=>"No",true=>"Yes"));
        $form->col(6);
        $form->group("HTTP interface");
            $form->direct_add("<p>Soon</p>");

        $form->col(6);
        $form->group("Discord [Relay]");
            $form->text_input("DiscordRelayHook","Discord [Relay] webhook",125,$second_bot_config->get_DiscordRelayHook(),"http://...");
            $form->text_input("discordgroupuuid","Discord [Relay] group",125,$second_bot_config->get_discordGroupTarget(),"Target group UUID");
        $form->group("Discord [Full]");
            $form->direct_add("<p>Soon</p>");
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
