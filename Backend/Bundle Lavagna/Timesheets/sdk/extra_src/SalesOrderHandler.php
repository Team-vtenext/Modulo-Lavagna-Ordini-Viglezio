<?php

class SalesOrderHandler extends VTEventHandler
{
    public function handleEvent($eventName, $entityData)
    {
        global $adb;
        global $currentModule;
        global $table_prefix;
        
        $mode = $_REQUEST['mode'];
        
        if (!($entityData->focus instanceof SalesOrder)) {
            return;
        }

        if ($eventName == 'vtiger.entity.aftersave') {
            $MLUtils = ModLightUtils::getInstance();

            //query sql più o meno funzionante
            /*$sql = "SELECT board_no + 1 available_id
                    FROM " . $table_prefix . "_salesorder t
                    INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = t.salesorderid
                    WHERE (t.sostatus = ? OR t.sostatus = ? OR t.sostatus = ?) AND board_no IS NOT NULL AND board_no != 0 AND " . $table_prefix . "_crmentity.deleted = 0 AND NOT EXISTS
                    (
                    SELECT * 
                    FROM " . $table_prefix . "_salesorder
                    INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = " . $table_prefix . "_salesorder.salesorderid
                    WHERE (" . $table_prefix . "_salesorder.sostatus = ? OR " . $table_prefix . "_salesorder.sostatus = ? OR " . $table_prefix . "_salesorder.sostatus = ?) AND board_no IS NOT NULL AND board_no != 0 AND " . $table_prefix . "_crmentity.deleted = 0 AND board_no = t.board_no + 1
                )
                ORDER BY board_no
                LIMIT 1";

            $res = $adb->pquery($sql, array('Approved', 'On Hold', 'Under Processing', 'Approved', 'On Hold', 'Under Processing'));

            if ($res && $adb->num_rows($res) > 0 && $entityData->focus->column_fields['sostatus'] == 'Approved' && empty($entityData->focus->column_fields['board_no'])) {
                $sql = "UPDATE " . $table_prefix . "_salesorder SET board_no = ? WHERE salesorderid = ?";
                $adb->pquery($sql, array($adb->query_result($res, 0, 'available_id'), $entityData->focus->id));
            }*/

            $boardNumbers = array(); 

            $sql = "SELECT board_no FROM " . $table_prefix . "_salesorder 
                    INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = " . $table_prefix . "_salesorder.salesorderid
                    WHERE deleted = 0 AND sostatus = ? OR sostatus = ? OR sostatus = ? order by board_no";
            $res = $adb->pquery($sql, array('Approved', 'On Hold', 'Under Processing'));
            
            if($res && $adb->num_rows($res) > 0){
                while ($row = $adb->fetch_array($res)) {
                    $boardNumbers[] = $row['board_no'];
                }
            }
            
            $rangeArray = range(1,max($boardNumbers));                                                    
            
            $missing = array_diff($rangeArray,$boardNumbers);
            $missing = array_values($missing);
            
            if(!empty($missing))
                $board_no = $missing[0];
            else{
                $sql2 = "SELECT MAX(board_no + 1) as next_board_no FROM " . $table_prefix . "_salesorder 
                        INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = " . $table_prefix . "_salesorder.salesorderid
                        WHERE deleted = 0 AND sostatus = ? OR sostatus = ? OR sostatus = ? order by board_no";
                $res2 = $adb->pquery($sql2, array('Approved', 'On Hold', 'Under Processing'));
                if($res2 && $adb->num_rows($res2) > 0){
                    $board_no = $adb->query_result($res2, 0, 'next_board_no');
                }
            }

            if(($entityData->focus->column_fields['sostatus'] != 'Cancelled' && $entityData->focus->column_fields['sostatus'] != 'Delivered' && $entityData->focus->column_fields['sostatus'] != 'Created') && empty($entityData->focus->column_fields['board_no'])){
                $sql = "UPDATE " . $table_prefix . "_salesorder SET board_no = ? WHERE salesorderid = ?";
                $adb->pquery($sql, array($board_no, $entityData->focus->id));
            }
            else if($entityData->focus->column_fields['sostatus'] == 'Cancelled' || $entityData->focus->column_fields['sostatus'] == 'Delivered' || $entityData->focus->column_fields['sostatus'] == 'Created') {
                $sql = "UPDATE " . $table_prefix . "_salesorder SET board_no = NULL WHERE salesorderid = ?";
                $adb->pquery($sql, array($entityData->focus->id)); 
            }

            $IUtils = InventoryUtils::getInstance();
            $prodDetails = $IUtils->getAssociatedProducts('SalesOrder', $entityData->focus);

            foreach ($prodDetails as $i=>$prod) {
                if (empty($prod['raw']['timesheet_id'])) {
                    $sql = "UPDATE " . $table_prefix . "_inventoryproductrel SET timesheet_id = ? WHERE lineitem_id = ?";
                    $adb->pquery($sql, array($prod['raw']['lineitem_id'],$prod['raw']['lineitem_id']));
                }
            }

            $plate = "";
            $make = "";
            $model = "";
            $vehicle_type = "";
            $accountname = "";
            $contactname = "";
            $courtesy_car = "";
            $insurance_image = "";

            if (!empty($entityData->focus->column_fields['account_id'])) {
                $accountFocus = CRMEntity::getInstance('Accounts');
                $accountFocus->retrieve_entity_info_no_html($entityData->focus->column_fields['account_id'], 'Accounts');

                $accountname = $accountFocus->column_fields['accountname'];
            }

            if (!empty($entityData->focus->column_fields['contact_id'])) {
                $contactFocus = CRMEntity::getInstance('Contacts');
                $contactFocus->retrieve_entity_info_no_html($entityData->focus->column_fields['contact_id'], 'Contacts');

                $contactname = $contactFocus->column_fields['lastname'] . ' ' . $contactFocus->column_fields['firstname'];
            }
            if (!empty($entityData->focus->column_fields['courtesy_car'])) {
                $sql = "SELECT * FROM " . $table_prefix . "_crmentity 
                        INNER JOIN " . $table_prefix . "_employees ON " . $table_prefix . "_employees.employeesid = " . $table_prefix . "_crmentity.crmid
                        WHERE smownerid = ? AND employee_type = ?";
                
                $res = $adb->pquery($sql, array($entityData->focus->column_fields['courtesy_car'], 'Auto di Cortesia'));

                if ($res && $adb->num_rows($res) > 0) {
                    $userFocus = CRMEntity::getInstance('Users');
                    $userFocus->retrieve_entity_info_no_html($entityData->focus->column_fields['courtesy_car'], 'Users');

                    $courtesy_car = $userFocus->column_fields['last_name'] . ' (' . $userFocus->column_fields['first_name'] . ')';
                }
                else
                    $courtesy_car = "";
            }
            if (!empty($entityData->focus->column_fields['vehicle_id'])) {
                $focus = CRMEntity::getInstance('Vehicles');
                $focus->retrieve_entity_info_no_html($entityData->focus->column_fields['vehicle_id'], 'Vehicles');

                $plate = $focus->column_fields['plate'];
                $make = $focus->column_fields['make'];
                $model = $focus->column_fields['model'];
                $chassis = $focus->column_fields['chassis'];
                $vehicle_type = $focus->column_fields['vehicle_type'];
            } else {
                $sqlVehicle = "SELECT * FROM " . $table_prefix . "_vehicles
                WHERE " . $table_prefix . "_vehicles.plate = ? OR " . $table_prefix . "_vehicles.chassis = ?";

                $vehicleRes = $adb->pquery($sqlVehicle, array($entityData->focus->column_fields['plate'], $entityData->focus->column_fields['chassis']));
                if ($vehicleRes && $adb->num_rows($vehicleRes) > 0) {
                    $plate = $adb->query_result($vehicleRes, 0, 'plate');
                    $make = $adb->query_result($vehicleRes, 0, 'make');
                    $model = $adb->query_result($vehicleRes, 0, 'model');
                    $chassis = $adb->query_result($vehicleRes, 0, 'chassis');
                    $vehicle_type = $adb->query_result($vehicleRes, 0, 'vehicle_type');
                    $vehicleId = $adb->query_result($vehicleRes, 0, 'vehiclesid');
                } else {
                    $vehicleFocus = CRMEntity::getInstance('Vehicles');
                    $vehicleFocus->column_fields['plate'] = $entityData->focus->column_fields['plate'];
                    $vehicleFocus->column_fields['make'] = $entityData->focus->column_fields['make'];
                    $vehicleFocus->column_fields['model'] = $entityData->focus->column_fields['model'];
                    $vehicleFocus->column_fields['chassis'] = $entityData->focus->column_fields['chassis'];
                    $vehicleFocus->column_fields['vehicle_type'] = $entityData->focus->column_fields['vehicle_type'];
                    $vehicleFocus->save('Vehicles');
                    $vehicleId= $vehicleFocus->id;
                }
                $sqlVehicleId = "UPDATE " . $table_prefix . "_salesorder SET vehicle_id = ? WHERE salesorderid = ?";
                $adb->pquery($sqlVehicleId, array($vehicleId, $entityData->focus->id));
            }
            if (!empty($entityData->focus->column_fields['insurance_id'])) {
                $focus = CRMEntity::getInstance('Insurances');
                $focus->retrieve_entity_info_no_html($entityData->focus->column_fields['insurance_id'], 'Insurances');

                $insurance_image = $focus->column_fields['image_url'];
            }

            $sql = "UPDATE " . $table_prefix . "_salesorder SET account_name = ?, contact_name = ?, plate = ?, make = ?, model = ?, chassis = ?, vehicle_type = ?, courtesy_carname = ?, insurance_image = ? WHERE salesorderid = ?";
            $adb->pquery($sql, array($accountname, $contactname, $plate, $make, $model, $chassis, $vehicle_type, $courtesy_car, $insurance_image, $entityData->focus->id));

            /*$sql = "UPDATE " . $table_prefix . "_crmentity
                    INNER JOIN " . $table_prefix . "_modlighthours_summary ON " . $table_prefix . "_modlighthours_summary.modlighthours_summaryid = " . $table_prefix . "_crmentity.crmid
                    SET deleted = 1 WHERE " . $table_prefix . "_modlighthours_summary.parent_id = ?";
            
            $res = $adb->pquery($sql, array($entityData->focus->id));*/

            $sql = "SELECT * FROM " . $table_prefix . "_modlighthours_summary
                    INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = " . $table_prefix . "_modlighthours_summary.modlighthours_summaryid
                    WHERE " . $table_prefix . "_modlighthours_summary.parent_id = ? AND deleted = 0";

            $res = $adb->pquery($sql, array($entityData->focus->id));

            if($res && $adb->num_rows($res) == 0){
                $index = 0;
                foreach ($prodDetails as $key => $prod) {
                    if ($prod['entityType' . $key] == 'Services') {
                        $row = array();
                        $row['row'] = array('seq' => $index, 'service' => $prod['hdnProductId' . $key], 'total_hours' => 0, 'estimated_hours' => 0, 'productivity' => 0, 'actual_gain' => 0, 'estimated_earnings' => 0);
                        $line_record_id = $MLUtils->saveRow('ModLightHours_Summary', $index, $row, $entityData->focus);
                        $index++;
                    }
                }
            }

			$columns = $MLUtils->getColumns('SalesOrder', 'Hours_Summary');
			$values = $MLUtils->getValues('SalesOrder', $entityData->focus->id, 'Hours_Summary', $columns);

            $total_estimated_hours = 0;
            $total_hours = 0;
            $total_productivity = 0;
            $total_actual_gain = 0;
            $total_estimated_earnings = 0;

            if(empty($entityData->focus->column_fields['commission']))
                $entityData->focus->column_fields['commission'] = 0;

            if(empty($entityData->focus->column_fields['insurance_rate']))
                $entityData->focus->column_fields['insurance_rate'] = 0;

            foreach ($values as $key => $value) {
                $servicePrice = 0;
                $focus = CRMEntity::getInstance('Services');
                $focus->retrieve_entity_info_no_html($value['row']['service'], 'Services');
                $servicePrice = $focus->column_fields['unit_price'];

                if ($entityData->focus->column_fields['no_apprentice_hours'] == 1 || $entityData->focus->column_fields['no_apprentice_hours'] == 'on') {
                    $sql = "SELECT service_id, SUM(" . $table_prefix . "_timesheets.total_hours) AS total_hours  
                            FROM " . $table_prefix . "_timesheets 
                            INNER JOIN " . $table_prefix . "_employees ON " . $table_prefix . "_employees.employeesid = " . $table_prefix . "_timesheets.employee_id
                            WHERE " . $table_prefix . "_timesheets.salesorder_id = ? AND timesheet_status = 'Closed' AND service_id = ? AND " . $table_prefix . "_employees.employee_type != ?
                            GROUP BY service_id";
                    $res = $adb->pquery($sql, array($entityData->focus->id, $value['row']['service'], 'Apprentice'));
                }
                else {
                    $sql = "SELECT service_id, SUM(" . $table_prefix . "_timesheets.total_hours) AS total_hours  
                            FROM " . $table_prefix . "_timesheets 
                            WHERE " . $table_prefix . "_timesheets.salesorder_id = ? AND timesheet_status = 'Closed' AND service_id = ? GROUP BY service_id";
                    $res = $adb->pquery($sql, array($entityData->focus->id, $value['row']['service']));
                }

                if($res && $adb->num_rows($res) > 0){
                    $value['row']['total_hours'] = $adb->query_result($res, 0, 'total_hours');
                }
                $total_estimated_hours += $value['row']['estimated_hours'];
                $total_hours += $value['row']['total_hours'];

                if ($value['row']['total_hours'] != 0 && $value['row']['total_hours'] != null) {
                    $total_productivity += $value['row']['productivity'] = $value['row']['estimated_hours'] / $value['row']['total_hours'];
                    $total_actual_gain += $value['row']['actual_gain'] = ($value['row']['productivity'] * $entityData->focus->column_fields['insurance_rate'])*1 - ($value['row']['productivity'] * $entityData->focus->column_fields['insurance_rate'] / 100 * $entityData->focus->column_fields['commission']);
                }

                $total_estimated_earnings += $value['row']['estimated_earnings'] = ($value['row']['estimated_hours'] * $servicePrice) - ($value['row']['estimated_hours'] * $servicePrice / 100 * $entityData->focus->column_fields['commission']);
                $line_record_id = $MLUtils->saveRow('ModLightHours_Summary', 0, $value, $entityData->focus);
            }

            $sql = "UPDATE " . $table_prefix . "_salesorder SET 
                    total_hours = ?, 
                    total_estimated_hours = ?, 
                    total_productivity = ?, 
                    total_actual_gain = ?, 
                    total_estimated_earnings = ?
                    WHERE salesorderid = ?";
            
            $adb->pquery($sql, array($total_hours, $total_estimated_hours, $total_productivity, $total_actual_gain, $total_estimated_earnings, $entityData->focus->id));

        }
    }
}
