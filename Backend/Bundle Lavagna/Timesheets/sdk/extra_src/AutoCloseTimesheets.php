<?php
require('config.inc.php');
chdir($root_directory);

require_once 'include/utils/utils.php';
require_once 'modules/Users/Users.php';
require_once 'data/CRMEntity.php';
require_once 'include/database/PearDatabase.php';

global $adb, $table_prefix, $log, $current_user;

$log =& LoggerManager::getLogger('AutoCloseTimesheets');

$sql = "UPDATE " . $table_prefix . "_timesheets SET timesheet_status = 'Autoclosed', end_time = '68400' WHERE timesheet_status = 'In Progress'";
$adb->query($sql);

$sql2 = "UPDATE " . $table_prefix . "_employees SET temporary_pin = null";
$adb->query($sql2);