<?php
define("require_id_on_load",true);
session_start();
require_once("site/vendor/yetonemorephpframework/db_objects/loader.php"); // db_objects
$framework_loading = array("functions","globals","url_loading","session","inputFilter","autoloader","forms");
foreach($framework_loading as $framework) { require_once("site/framework/".$framework.".php"); }
include("site/config/load.php"); // sql_config
require_once("site/vendor/yetonemorephpframework/mysqli/loader.php"); // sql_driver

// lets get some work done.
$sql = new mysqli_controler();
$session = new session_control();
$session->load_from_session();
include("site/vendor/vendor.php");
?>
