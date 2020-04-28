<?php
$menu_items = array(
    "Dashboard" => array(
        "icon" => "fas fa-robot",
        "target" => "home",
        "active_on" => array("home","bot"),
    ),
    "Bot Config Library" => array(
        "icon" => "fas fa-cogs",
        "target" => "config",
        "active_on" => array("config"),
    ),
);

$output = "";
foreach($menu_items as $menu_key => $menu_config)
{
    $output .= '<li class="nav-item">';
    $output .= '<a href="[[url_base]]'.$menu_config["target"].'" class="nav-link';
    if(in_array($module,$menu_config["active_on"]) == true)
    {
        $output .= " active";
        $template_parts["page_breadcrumb_icon"] = '<i class="'.$menu_config["icon"].' text-success"></i>';
        $template_parts["page_breadcrumb_text"] = '<a href="[[url_base]]'.$menu_config["target"].'">'.$menu_key.'</a>';
    }
    $output .= '"><i class="'.$menu_config["icon"].' text-success"></i> '.$menu_key.'</a>';
    $output .= '</li>';
}
$template_parts["html_menu"] = $output;
?>
