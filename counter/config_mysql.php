<?php
// ------------------------------------------------ //
// MySQL DATABASE SETUP (ignore if not using MySQL) //
// ------------------------------------------------ //
// MySQL database username:
$DB['username'] = "ofbcuoud_brightp";
// MySQL database password:
$DB['password'] = "ramo1019";
// MySQL database name:
$DB['name'] = "ofbcuoud_rhul";
// MySQL database hostname:
$DB['host'] = "localhost";
// MySQL database ext (default is "ep")
$DB['ext'] = "elaria";

// -- Do Not Edit Below This Point -- //

if (eregi("config_mysql.php",$_SERVER['PHP_SELF'])) {
    Header("Location: index.php");
    die();
}