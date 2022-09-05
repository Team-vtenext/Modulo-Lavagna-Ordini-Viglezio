<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if(file_exists('boardConfig.php')){
   require('boardConfig.php');
	chdir($root_directory);
	
	require_once('include/database/PearDatabase.php');
	require_once('include/utils/utils.php');

global $db;
}
if($_REQUEST['action'] != 'getConfigurationFile' && $_REQUEST['action'] != 'saveConfiguration'){
	$db = new PearDatabase($dbcfg['db_type'],$dbcfg['db_server'],$dbcfg['db_name'],$dbcfg['db_username'],$dbcfg['db_password']);
	$db->connect();
}
$data = json_decode(file_get_contents('php://input'), true);


switch($_REQUEST['action']){
	case 'saveSettings':
		$response = json_encode(saveSettings($data));
		break;
	case 'getSettings':
		$response = json_encode(getSettings());
		//die(print_r($settings));
		break;
	case 'login':
		$response = json_encode(login($data['value']));
		//$response = json_encode($data);
		break;
	case 'factoryReset':
		$response = json_encode(factoryReset($dbcfg["db_name"]));
		break;
	case 'logout':
		$response = json_encode(logout());
		break;
	case 'getCredentials':
		$response = json_encode(getCredentials());
		break;
	case 'getConfigurationFile':
		$response = json_encode(getConfigurationFile());
		break;
	case 'saveConfiguration':
		$respones = json_encode(saveConfiguration($data));
		break;
}

echo $response;


function saveConfiguration($data){
	$content = '<?php

ini_set("memory_limit","128M");

$dbcfg["db_server"] = "' . $data['database'] .'";
$dbcfg["db_port"] = ":3306";
$dbcfg["db_username"] = "' . $data['dbusername'] .'";
$dbcfg["db_password"] = "' . $data['dbpassword'] .'";
$dbcfg["db_name"] = "' . $data['databasename'] .'";
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
$site_URL = "' . $data['vteurl'] . '";
$root_directory = "/var/www/html/";

?>
	';

	$fp = fopen("boardConfig.php","wb");
	fwrite($fp,$content);
	fclose($fp);
	
	require('boardConfig.php');
	chdir($root_directory);
	
	require_once('include/database/PearDatabase.php');
	require_once('include/utils/utils.php');
	
	global $db;
	die(print_r($dbcfg));
	$db = new PearDatabase($dbcfg['db_type'],$dbcfg['db_server'],$dbcfg['db_name'],$dbcfg['db_username'],$dbcfg['db_password']);
	$db->connect();
	
	
	$settings = array('username' => $data['username'], 'password' => $data['password'], 'accesskey' => $data['accesskey'], 'pin' => $data['pin']);
	saveInitialSettings($settings);
	
}

function getConfigurationFile(){
	$filename = 'modules/SDK/src/modules/Webservices/boardConfig.php';
	if(!file_exists($filename))
		return array('success' => 'false');
	else
		return array('success' => 'true');
}

function getCredentials(){
	global $db;
	
	$sql = 'SELECT username, password FROM boardsettings';
	$data = array();
	$res = $db->query($sql);
	
	if($res && $db->num_rows($res) > 0){
		while($row = $db->fetchByAssoc($res)) {
			$data[] = $row;
		}
	}
	return $data;
}

function saveInitialSettings($data){
	global $db;
	
	$sql = 'REPLACE INTO boardsettings (id, username, password, accesskey, pin) VALUES(?,?,?,?,?)';
	$db->pquery($sql, array(1, 
							$data['username'],
							$data['password'],
							$data['accesskey'], 
							$data['pin'])); 
							
	return array('success' => 'true');
							
}

function saveSettings($data){
	global $db;
	
	$sql = "SELECT password from boardsettings where id = 1";
	$res = $db->query($sql);
	
	if($res && $db->num_rows($res) > 0){
		$data['password'] = $db->query_result($res,0,'password');
	}
	
	$sql = 'REPLACE INTO boardsettings (id, username, password, accesskey, topbarcol, mondaycol, tuesdaycol, wednesdaycol, thursdaycol, fridaycol, saturdaycol, sundaycol, pin, showcloseorder, pinrequired,
										topbartoggle, mondaytoggle, tuesdaytoggle, wednesdaytoggle, thursdaytoggle, fridaytoggle, saturdaytoggle, sundaytoggle,language) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
	$db->pquery($sql, array(1, 
							$data['username'],
							$data['password'],
							$data['accesskey'],
							$data['topbarcol'], 
							$data['mondaycol'], 
							$data['tuesdaycol'], 
							$data['wednesdaycol'],
							$data['thursdaycol'],
							$data['fridaycol'],
							$data['saturdaycol'],
							$data['sundaycol'],
							$data['pin'],
							$data['showcloseorder'] == 'on' ? 1 : 0,
							$data['pinrequired'] == 'on' ? 1 : 0,
							$data['topbartoggle'] == 'on' ? 1 : 0,
							$data['mondaytoggle'] == 'on' ? 1 : 0,
							$data['tuesdaytoggle'] == 'on' ? 1 : 0,
							$data['wednesdaytoggle'] == 'on' ? 1 : 0,
							$data['thursdaytoggle'] == 'on' ? 1 : 0,
							$data['fridaytoggle'] == 'on' ? 1 : 0,
							$data['saturdaytoggle'] == 'on' ? 1 : 0,
							$data['sundaytoggle'] == 'on' ? 1 : 0,
							$data['language']));
							
	return array('success' => 'true');
							
}

function getSettings(){
	global $db;
	
	$sql = 'SELECT * FROM boardsettings';
	$data = array();
	$res = $db->query($sql);
	
	if($res && $db->num_rows($res) > 0){
		while($row = $db->fetchByAssoc($res)) {
			$data = $row;
		}
	}
	return $data;
}

function login($pin){
	global $db;
	
	$sql = 'SELECT pin FROM boardsettings';
	$data = array();
	$res = $db->query($sql);
	
	if($res && $db->num_rows($res) > 0){
		while($row = $db->fetchByAssoc($res)) {
			if($pin == $row['pin'])
				return array('token' => 'ddfasfs43241324');
			else
				return array('error' => 'login errato');
		}
	}
	return $data;
}

function logout(){
	global $db;
	
	$sql = 'UPDATE boardsettings set username = null, accesskey = null, pin = null WHERE id = 1';
	$data = array();
	$res = $db->query($sql);
	
	if($res)
		$data['success'] = true;
	else
		$data['success'] = false;
	
	return $data;
}


function factoryReset($dbname){
	global $db;
	
	//$sql = 'DELETE FROM board_db.boardsettings WHERE id = 1';
	$sql = 'DROP database ' . $dbname;
	$data = array();
	$res = $db->query($sql);
	
	if($res)
		$data['success'] = true;
	else
		$data['success'] = false;
	
	return $data;
}


function getUserAccess(){
	global $db;
	
	$sql = 'SELECT username, accesskey FROM boardsettings';
	$data = array();
	$res = $db->query($sql);
	
	if($res && $db->num_rows($res) > 0){
		while($row = $db->fetchByAssoc($res)) {
			$data['username'] = $row['username'];
			$data['accesskey'] = $row['accesskey'];
		}
	}
	return $data;
}

?>