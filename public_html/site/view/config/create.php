<?php
$template_parts["html_title"] .= " ~ Create";
$template_parts["page_title"] .= "Create new config";
$template_parts["page_actions"] = "";
$form = new form();
$form->target("config/create");
$form->required(true);
$form->col(6);
$form->group("Basics");
    $form->text_input("avatarname","Name",125,null,"Second Bot");
    $form->text_input("avatarpassword","Password",125,null,"Not hidden!");
    $form->text_input("homeregion","Homeregion (Collection csv)",125,null,"\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\",\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\"");
$form->group("Security");
    $form->text_input("master","Master",125,null,"Bot Master");
    $form->text_input("code","LSL interact code",125,null,"Dont Tell Anyone");

$form->col(6);
$form->group("Settings");
    $form->select("allowrlv","RLV support",false,array(false=>"No",true=>"Yes"));
$form->col(6);
$form->group("HTTP interface]");
    $form->direct_add("<p>Soon</p>");

$form->col(6);
$form->group("Discord [Relay]");
    $form->text_input("DiscordRelayHook","Discord [Relay] webhook",125,null,"http://...");
    $form->text_input("discordgroupuuid","Discord [Relay] group",125,null,"Target group UUID");
$form->group("Discord [Full]");
    $form->direct_add("<p>Soon</p>");
echo $form->render("Create","primary");
echo "<hr/>";
?>
