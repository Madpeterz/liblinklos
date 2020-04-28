<?php
$template_parts["html_title"] = "Bots";
$template_parts["page_actions"] = "";
$template_parts["page_title"] = "[[page_breadcrumb_icon]] [[page_breadcrumb_text]] / ";
if(file_exists("site/view/bot/".$area.".php") == true) include("site/view/bot/".$area.".php");
else include("site/view/config/default.php");
?>
