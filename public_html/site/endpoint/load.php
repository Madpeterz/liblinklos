<?php
include("site/framework/loader_light.php");
$input = new inputFilter();
$senttoken = $input->postFilter("token");
$timewindow = 30;
$timeloop = 0;
$vaild = false;
$now = time();
$reply = array();
$reply["status"] = false;
$reply["message"] = "Not set";
while(($timeloop < $timewindow) && ($vaild == false))
{
    $servertokencheckA = sha1("".($now+$timeloop)."".$module."".$area."".getenv('ENDPOINT_CODE')."");
    $servertokencheckB = sha1("".($now-$timeloop)."".$module."".$area."".getenv('ENDPOINT_CODE')."");
    if(($servertokencheckA == $senttoken) || ($servertokencheckB == $senttoken))
    {
        $vaild = true;
        break;
    }
    $timeloop++;
}
if($vaild == true)
{
    $testfile = "site/endpoint/".$module."/".$area.".php";
    if(file_exists($testfile) == true)
    {
        include($testfile);
    }
    else
    {
        $reply["message"]="Failed: endpoint does not support that request";
    }
}
else
{
    $reply["message"]="Failed: Invaild token";
}
if($reply["status"] == false)
{
    $sql->flagError();
}
echo json_encode($reply);
?>
