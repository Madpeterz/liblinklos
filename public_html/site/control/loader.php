<?php
include("site/framework/loader_light.php");
$redirect = null;
$status = true;
$soft_fail = false;
$reply = array();
if($session->get_logged_in() == true)
{
    $client = $session->get_member();
    if(file_exists("site/control/".$module."/".$area.".php") == true)
    {
        include("site/control/".$module."/".$area.".php");
    }
    else
    {
        $status = false;
        echo "Unknown module/area selected please check and try again";
    }
}
else
{
    if(file_exists("site/control/login/".$area.".php") == true)
    {
        include("site/control/login/".$area.".php");
    }
    else
    {
        $status = false;
        echo "Unknown module/area selected please check and try again";
    }
}
if($status == false)
{
    if($soft_fail == false)
    {
        $sql->flagError();
    }
}
$buffer = ob_get_contents();
ob_clean();
$reply["status"] = $status;
$reply["message"] = $buffer;
if($redirect != null)
{
    if($redirect == "here") $redirect = "";
    $reply["redirect"] = "".$template_parts["url_base"]."".$redirect."";
}
echo json_encode($reply);
?>
