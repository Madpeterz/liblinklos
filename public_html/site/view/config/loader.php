<?php
$template_parts["html_title"] = "Configs";
$template_parts["page_actions"] = "<a href='[[url_base]]config/create'><button type='button' class='btn btn-success'>Create</button></a>";
$template_parts["page_title"] = "[[page_breadcrumb_icon]] [[page_breadcrumb_text]] / Configs ";
if(file_exists("site/view/config/".$area.".php") == true) include("site/view/config/".$area.".php");
else include("site/view/config/default.php");
?>
