<?php
$template_parts["html_title"] .= " ~ Start";
$template_parts["page_title"] .= "Start bot";
$template_parts["page_title"] .= $page;
$template_parts["page_actions"] = "";

$client_rental = new client_rental();
if($client_rental->load_by_field("rental_uid",$page) == true)
{
    if($client_rental->get_clientlink() == $client->get_id())
    {
        $form = new form();
        $form->target("bot/start/".$page."");
        $form->required(true);
        $form->col(6);
        $form->group("Warning</h4><p>if there are any pending commands this will fail</p><h4>");
        echo $form->render("Start now","success");
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
