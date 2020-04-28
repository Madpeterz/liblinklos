<?php
//test
$hashcheck = sha1("".$sentunixtime."".$staticpart."".getenv('LSL_CODE')."");
if($hashcheck == $hash)
{
    include("site/api/start_final.php");
}
else
{
    echo "Unable to vaildate hash";
}
?>
