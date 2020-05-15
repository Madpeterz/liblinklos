<?php
$template_parts["html_title"] .= " ~ Create";
$template_parts["page_title"] .= "Create new config";
$template_parts["page_actions"] = "";
$form = new form();
$form->target("config/create");
$form->required(true);
$form->col(6);
$form->group("Basics");
    $form->text_input("avatarname","AV Name",125,null,"Second Bot");
    $form->text_input("avatarpassword","AV Password",125,null,"Not hidden!");
    $form->text_input("homeregion","Homeregion (Collection csv)",125,"\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\"","\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\",\"http://maps.secondlife.com/secondlife/Ippopotamo/81/175/22\"");
$form->col(6);
$form->group("Security");
    $form->text_input("master","Master",125,"00000000-0000-0000-0000-000000000000","Bot Master");
    $form->text_input("code","LSL & HTTP interact code",125,"12CharMinmiumCode","Dont Tell Anyone. Must be 12 Chars");
$form->col(6);
$form->group("Settings");
    $form->select("allowrlv","RLV support",0,array(0=>"No",1=>"Yes"));
    $form->text_input("Setting_RelayImToAvatarUUID","IM to UUID relay",36,"00000000-0000-0000-0000-000000000000","");
    $form->text_input("Setting_DefaultSit_UUID","Default Sit UUID",36,"00000000-0000-0000-0000-000000000000","");
$form->col(6);
$form->group("HTTP interface");
    $form->select("Http_Enable","Enable",0,array(false=>"No",1=>"Yes"));
    $form->text_input("Security_WebUIKey","Web HTTP Interface Password",125,"12CharMinmiumCode","");
    $form->direct_add("<p>The web UI is still being worked<br/> on and is not ready for primetime yet</p>");
$form->col(6);
$form->group("Discord [Relay]");
    $form->text_input("DiscordRelayHook","Discord [Relay] webhook",125,"https:/discordapp.com/api/webhooks/XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX","http://... for non full discord initegration");
    $form->text_input("discordgroupuuid","Discord [Relay] group",125,"00000000-0000-0000-0000-000000000000","Target group UUID");
$form->col(6);
$form->group("Discord [Full]");
    $form->select("DiscordFull_Enable","Enable",0,array(false=>"No",1=>"Yes"));
    $form->text_input("DiscordFull_Token","Client token",200,"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx","Find in discord dev bot");
    $form->text_input("DiscordFull_ServerID","Server id",200,"000000000000000000","Find under widgets in discord");
echo $form->render("Create","primary");
echo "<hr/>";
?>
