<?php
$reply = array();
$checkfile = "site/api/".$required_sl_values["method"]."/".$required_sl_values["action"].".php";
if(file_exists($checkfile) == true)
{
    // $reseller, $object_owner_avatar, $owner_override, $region, $object
    include($checkfile);
}
else
{
    $status = false;
    echo "Not supported: ".$required_sl_values["method"]."/".$required_sl_values["action"]."";
}
?>
