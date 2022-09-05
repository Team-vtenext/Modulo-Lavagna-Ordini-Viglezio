<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require('boardConfig.php');
require('boardBackend.php');
chdir($root_directory);


$apiURL = "https://viglezio.vtenext.ch/restapi/v1/vtews";
$data = json_decode(file_get_contents('php://input'), true);

$authData = getUserAccess();
$authentication = base64_encode($authData['username'] . ':' . $authData['accesskey']);


switch ($_REQUEST['action']) {
    case 'getOrders':

        $response = getOrders($apiURL, $authentication);
        break;
    case 'getJobs':

        $response = getOtherTimesheets($apiURL, $authentication);
        break;
    case 'checkLogin':

        $response = checkLogin($apiURL, $authentication, $data['cardId'], $data['isAdmin']);
        break;
    case 'checkEmployeeCard':

        $response = checkEmployeeCardExist2($apiURL, $authentication, $data['cardId'], $data['isPin'], $data['isAdmin']);
        break;
    case 'getOtherServices':

        $response = getOtherServices($apiURL, $authentication);
        break;
    case 'closeAsAdmin':

        $response = closeAsAdmin($apiURL, $authentication, $data);
        break;
    case 'createOtherTimesheet':

        $response = createOtherTimesheet($apiURL, $authentication, $data);
        break;
    case 'closeSalesOrder':
        $crmid = $data['crmid'];

        $response = closeSalesOrder($apiURL, $authentication, $crmid);
        break;
    case 'closeOtherTimesheet':

        $response = closeOtherTimesheet($apiURL, $authentication, $data);
        break;
    case 'createEntryLine':

        $response = createEntryLine($apiURL, $authentication, $data);
        break;
    case 'getTodayEntryLines':

        $response = getTodayEntryLines($apiURL, $authentication, $data);
        break;
    case 'updateInventoryRow':
        $cardId = $data['authentication'];
        $crmid = explode("x", $data['crmid'])[1];
        $lineItemId = $data['lineItemId'];
        $autoClose = $data['autoClose'];

        $response = updateInventoryRow($apiURL, $authentication, $crmid, $lineItemId, $cardId, $autoClose);
        break;
    case 'saveSettings':
        saveSettings($data);
        break;
}

echo $response;

function updateInventoryRow($apiURL, $authentication, $crmid, $lineItemId, $cardId, $autoClose)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/update_inventory_row",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('element' => json_encode(array("crmid" => $crmid, "lineItemId" => $lineItemId, "card" => $cardId, "autoClose" => $autoClose))),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);

    return json_encode($response['data']);
}

function closeSalesOrder($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/update",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid, 'columns' => json_encode(array('sostatus' => 'Delivered'))),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    if ($response['status'] == 200) {
        return json_encode(array('error' => 'LBL_ORDER_CLOSED'));
    } else {
        return json_encode(array('error' => 'LBL_ERROR'));
    }

    curl_close($curl);
}

function createOtherTimesheet($apiURL, $authentication, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/create_other_timesheet",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('element' => json_encode(array("card" => $data['cardId'], "service_id" => $data['service_id'], "service_name" => $data['servicename']))),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);

    return json_encode(array('status' => 'success', 'message' => $response['data']));
}


function createEntryLine($apiURL, $authentication, $data)
{
    $employee = json_decode(checkLogin($apiURL, $authentication, $data['authentication'], false), true);

    if ($employee['status'] == 'success') {
        $employeeId = $employee['employee_id'];
    }

    if ($employeeId != "" && $employeeId != null) {
        $response = getTodayEntryLines($apiURL, $authentication, $data);

        $response = json_decode($response, true);
        if (empty($response['data'][0]['id'])) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiURL . "/create",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('elementType' => 'Presences', 'element' => json_encode(array('employee_id' => $employeeId, 'presence_date' => date('Y-m-d'), 'card_id' => $data['authentication']))),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . $authentication
                ),
            ));

            $response = json_decode(curl_exec($curl), true);

            curl_close($curl);

            $data['id'] = $response['data']['id'];
            if ($data['type'] == 'Leaving') {
                closeAllActiveUserTimesheetsRows($apiURL, $authentication, $employeeId);
            }
            $response = createTableEntryLine($apiURL, $authentication, $data);
        } else {
            $lastEntry = getLastEntryTableLine($apiURL, $authentication, $response['data'][0]['id']);

            if ($data['type'] != $lastEntry[0]['type']) {
                $data['id'] = $response['data'][0]['id'];
                if ($data['type'] == 'Leaving') {
                    closeAllActiveUserTimesheetsRows($apiURL, $authentication, $employeeId);
                }
                $response = createTableEntryLine($apiURL, $authentication, $data);
            }
            else {
                return json_encode(array('status' => 'error', 'message' => 'LBL_' . strtoupper($data['type']) . '_ALREADY_REGISTERED'));
            }
        }

        return json_encode(array('status' => 'success', 'message' => 'LBL_REGISTRATION_DONE'));
    } else {
        return json_encode(array('status' => 'error', 'message' => 'LBL_NO_USER_CARD'));
    }
}

function updateEntryLine($apiURL, $authentication, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/update",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $data['id'], 'columns' => json_encode(array('description' => ''))),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return json_encode($response['data']);
}

function closeAsAdmin($apiURL, $authentication, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/update",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $data['crmid'], 'columns' => json_encode(array('timesheet_status' => 'Closed', 'end_time' => date('H:i:s')))),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);

    return json_encode(array('status' => 'success', 'message' => 'LBL_FINISHED_WORKING'));
}


function closeOtherTimesheet($apiURL, $authentication, $data)
{
    $employee = json_decode(checkLogin($apiURL, $authentication, $data['cardId'], $data['isAdmin']), true);

    if ($employee['status'] == 'success') {
        $employeeId = $employee['employee_id'];
    }

    if ($employeeId != "" && $employeeId != null) {

        $timesheetEmployeeId = retrieveOtherTimesheetEmployee($apiURL, $authentication, $data['crmid']);

        if ($employeeId == $timesheetEmployeeId || $employeeId == 'admin') {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiURL . "/update",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('id' => $data['crmid'], 'columns' => json_encode(array('timesheet_status' => 'Closed', 'end_time' => date('H:i:s')))),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . $authentication
                ),
            ));

            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);

            return json_encode(array('status' => 'success', 'message' => 'LBL_FINISHED_WORKING'));
        } else {
            return json_encode(array('status' => 'error', 'message' => 'LBL_WRONG_USER'));
        }
    } else {
        return json_encode(array('status' => 'error', 'message' => 'LBL_NO_USER_CARD'));
    }
}


function createTableEntryLine($apiURL, $authentication, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/create",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('elementType' => 'ModLightRegistrations', 'element' => json_encode(array('type' => $data['type'], 'hour' => date('H:i:s'), 'parent_id' => $data['id'], 'seq' => 0, 'createdtime' => date('Y-m-d H:i:s'), 'modifiedtime' => date('Y-m-d H:i:s')))),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return json_encode($response['data']);
}

function closeAllActiveUserTimesheetsRows($apiURL, $authentication, $employeeId)
{
    $progressTimesheets = json_decode(getProgressTimesheets($apiURL, $authentication, $employeeId), true);

    if (!empty($progressTimesheets['data'])) {
        $curl = curl_init();

        foreach ($progressTimesheets['data'] as $timesheet) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiURL . "/update",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('id' => $timesheet['id'], 'columns' => json_encode(array('timesheet_status' => 'Closed', 'end_time' => date('H:i:s')))),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic " . $authentication
                ),
            ));

            $response = json_decode(curl_exec($curl), true);
        }

        curl_close($curl);
    }
    return json_encode($response['data']);
}

function getLastEntryTableLine($apiURL, $authentication, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT type, hour from ModLightRegistrations WHERE parent_id = '" . $data . "' ORDER by hour DESC LIMIT 1;"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));
    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);

    return $response['data'];
}

function getEntryLinesTable($apiURL, $authentication, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT type, hour from ModLightRegistrations WHERE parent_id = '" . $data . "' ORDER by hour;"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response['data'];
}

function getTodayEntryLines($apiURL, $authentication, $data)
{
    $employee = json_decode(checkLogin($apiURL, $authentication, $data['authentication'], false), true);

    if ($employee['status'] == 'success') {
        $employeeId = $employee['employee_id'];
    }

    if ($employeeId != "" && $employeeId != null) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiURL . "/query",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('query' => "SELECT employee_id, id from Presences WHERE presence_date = '" . date('Y-m-d') . "' AND employee_id = '" . $employeeId . "';"),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . $authentication
            ),
        ));

        $response = json_decode(curl_exec($curl), true);

        $res = getTodayTimesheets($apiURL, $authentication, $employeeId);

        if (!empty($response)) {
            foreach ($response['data'] as $innerKey => $innerArray) {
                foreach ($innerArray as $key => $value) {
                    if ($key == 'employee_id') {
                        $response['data'][$innerKey][$key] = retrieveEmployeeInfo($apiURL, $authentication, $value);
                    }
                    if ($key == 'id') {
                        $response['data'][$innerKey]['lines'] = getEntryLinesTable($apiURL, $authentication, $value);
                    }
                }
                $tt = array_merge($response['data'][$innerKey]['lines'], $res['data']);
                $hours = array_column($tt, 'hour');
                array_multisort($hours, SORT_ASC, $tt);
                $response['data'][$innerKey]['lines'] = $tt;
            }
            if (empty($response['data'])) {
                return json_encode(array('error' => 'LBL_NO_REGISTRATION_CURRENT_DAY'));
            }

            curl_close($curl);
            return json_encode($response);
        } else {
            return json_encode(array('error' => 'LBL_NO_REGISTRATION'));
        }
    } else {
        return json_encode(array('error' => 'LBL_NO_USER_CARD'));
    }
}

function getTodayTimesheets($apiURL, $authentication, $employeeId)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT * from Timesheets WHERE timesheet_status = 'Closed' AND employee_id = '" . $employeeId . "' AND timesheet_date = '" . date('Y-m-d') . "';"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    foreach ($response['data'] as $key => $val) {
        $response['data'][$key]['hour'] = $response['data'][$key]['start_time'];
    }

    curl_close($curl);
    return $response;
}

function getTimesheets($apiURL, $authentication, $relatedId)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT * from Timesheets WHERE salesorder_id = '" . $relatedId . "';"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response;
}

function getOtherTimesheets($apiURL, $authentication)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT * FROM Timesheets WHERE timesheet_status = 'In Progress' AND salesorder_id = '0x0';"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return json_encode($response);
}

function getProgressTimesheets($apiURL, $authentication, $employee_id)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT * FROM Timesheets WHERE timesheet_status = 'In Progress' AND employee_id = '" . $employee_id . "';"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return json_encode($response);
}

function getOtherServices($apiURL, $authentication)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT * FROM Services WHERE servicecategory != 'Garage Works' AND servicecategory != 'Administration';"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return json_encode($response);
}

function getOrders($apiURL, $authentication)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => "SELECT id, board_no from SalesOrder WHERE sostatus IN('Approved','Under Processing', 'On Hold') order by board_no ASC;"),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));
    $completedata = array();

    $response = json_decode(curl_exec($curl), true);

    foreach ($response['data'] as $data) {
        $inventoryEntity = getOrderInventory($apiURL, $authentication, $data['id']);
        if (!empty($inventoryEntity['product_block']['products'])) {
            $completedata['data'][] = $inventoryEntity;
        }
    }

    curl_close($curl);
    return json_encode($completedata);
}

function getToken($apiURL, $authentication, $username)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/getchallenge",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('username' => $username),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return json_encode($response);
}

function retrieveOtherTimesheetEmployee($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/retrieve",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response['data']['employee_id'];
}


function retrieveAccountInfo($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/retrieve",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response['data']['accountname'];
}

function checkLogin($apiURL, $authentication, $cardId, $isAdmin)
{
    $curl = curl_init();

    $query = "SELECT id FROM Employees WHERE card_id = '" . $cardId . "' OR temporary_pin = '" . $cardId . "';";

    if ($isAdmin) {
        $query = "SELECT id FROM Employees WHERE card_id = '" . $cardId . "' AND is_admin = true OR temporary_pin = '" . $cardId . "' AND is_admin = true;";
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => $query),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    if (!empty($response['data'][0]['id']) && $isAdmin) {
        $employee_id = 'admin';
    } else {
        $employee_id = $response['data'][0]['id'];
    }

    curl_close($curl);
    return json_encode(array('status' => 'success', 'employee_id' => $employee_id));
}


function checkEmployeeCardExist2($apiURL, $authentication, $cardId, $isPin, $isAdmin)
{
    $curl = curl_init();

    if ($isPin) {
        $query = "SELECT id from Employees WHERE temporary_pin = '" . $cardId . "' AND employee_status = 'Active'";
    } else {
        $query = "SELECT id from Employees WHERE card_id = '" . $cardId . "' AND employee_status = 'Active'";
    }

    if ($isAdmin) {
        $query .= " AND is_admin = " . ($isAdmin ? 1 : 0) . ";";
    } else {
        $query .= ";";
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => $query),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    if (!empty($response['data'][0]['id']) && $isAdmin) {
        $employee_id = 'admin';
    } else {
        $employee_id = $response['data'][0]['id'];
    }

    curl_close($curl);
    return json_encode(array('status' => 'success', 'employee_id' => $employee_id));
}

function checkEmployeeCardExist($apiURL, $authentication, $cardId, $isPin)
{
    $curl = curl_init();

    if ($isPin) {
        $query = "SELECT id from Employees WHERE temporary_pin = '" . $cardId . "' AND employee_status = 'Active';";
    } else {
        $query = "SELECT id from Employees WHERE card_id = '" . $cardId . "' AND employee_status = 'Active';";
    }

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/query",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('query' => $query),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);
    curl_close($curl);
    return $response['data'][0]['id'];
}

function retrieveEmployeeInfo($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/retrieve",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response['data']['lastname'] . ' ' . $response['data']['firstname'];
}

function retrieveContactInfo($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/retrieve",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    return $response['data']['firstname'] . ' ' . $response['data']['lastname'];
}

function retrieveRentCarInfo($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/retrieve",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    curl_close($curl);
    if ($response['data']['title'] == 'Auto di Cortesia') {
        return array("targa" => $response['data']['user_name'], "marca" => $response['data']['first_name'], "modello" => $response['data']['last_name']);
    } else {
        return array("targa" => "Nessuna", "marca" => "", "modello" => "");
    }
}

function getOrderInventory($apiURL, $authentication, $crmid)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiURL . "/retrieveinventory",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array('id' => $crmid),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . $authentication
        ),
    ));

    $response = json_decode(curl_exec($curl), true);

    $newProductBlock = array();

    $res = getTimesheets($apiURL, $authentication, $response['data']['id']);
    $response['data']['timesheets'] = $res['data'];

    foreach ($response['data']['product_block']['products'] as $row => $innerArray) {
        foreach ($innerArray as $key => $value) {
            if ($key == 'entityType' && $value == 'Services') {
                $response['data']['product_block']['products'][$row]['productName'] = html_entity_decode($response['data']['product_block']['products'][$row]['productName']);
                $response['data']['product_block']['products'][$row]['productDescription'] = nl2br(html_entity_decode($response['data']['product_block']['products'][$row]['productDescription']));
                $newProductBlock[] = $response['data']['product_block']['products'][$row];
            }
        }
    }
    $response['data']['product_block']['products'] = $newProductBlock;

    curl_close($curl);
    return $response['data'];
}
