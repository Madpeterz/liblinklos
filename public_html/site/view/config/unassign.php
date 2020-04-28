<?php
$template_parts["html_title"] .= " ~ Unassign";
$template_parts["page_title"] .= "unassign config:";
$template_parts["page_title"] .= $page;
$template_parts["page_actions"] = "";

$second_bot_config = new second_bot_config();
if($second_bot_config->load_by_field("config_uid",$page) == true)
{
    if($second_bot_config->get_clientlink() == $client->get_id())
    {
        $form = new form();
        $form->target("config/unassign/".$page."");
        $form->required(true);
        $form->col(6);
        $form->group("Warning</h4><p>If the config is currenly in use this will fail</p><h4>");
        echo $form->render("Unassign","danger");
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
