<?php

ini_set("memory_limit","128M");

$dbcfg["db_server"] = "localhost";
$dbcfg["db_port"] = ":3306";
$dbcfg["db_username"] = "board";
$dbcfg["db_password"] = "7vdZxvYi6X@C";
$dbcfg["db_name"] = "board_db_dev";
$dbcfg["db_type"] = "mysqli";
$dbcfg["db_status"] = "true";
$dbcfg["db_charset"] = "utf8";
$dbcfg["db_dieOnError"] = true;
$dbcfg["db_hostname"] = $dbcfg["db_server"].$dbcfg["db_port"];
if ($dbcfg["db_type"] == "mysql" && !function_exists("mysql_connect") && function_exists("mysqli_connect")) {
	$dbcfg["db_type"] = "mysqli";
}

$dbcfg["log_sql"] = false;
$dbcfgoption["persistent"] = true;
$dbcfgoption["autofree"] = false;
$dbcfgoption["debug"] = 0;
$dbcfgoption["seqname_format"] = "%s_seq";
$dbcfgoption["portability"] = 0;
$dbcfgoption["ssl"] = false;
$table_prefix = "vte";
$host_name = $dbcfg["db_hostname"];
$site_URL = "https://crmboard.vtenext.ch";
$root_directory = "/var/www/html/";

?>
	