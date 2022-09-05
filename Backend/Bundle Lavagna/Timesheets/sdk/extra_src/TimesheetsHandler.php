<?php

class TimesheetsHandler extends VTEventHandler
{
    public function handleEvent($eventName, $entityData)
    {
        global $adb;
        global $currentModule;
        global $table_prefix;
        
        $mode = $_REQUEST['mode'];
        
        if (!($entityData->focus instanceof Timesheets)) {
            return;
        }

        if ($eventName == 'vtiger.entity.aftersave') {
            $sqlUpdateRow = "UPDATE " . $table_prefix . "_inventoryproductrel SET work_in_progress = ? WHERE timesheet_id = ?";

            if (!empty($entityData->focus->column_fields['salesorder_lineid']) && !empty($entityData->focus->column_fields['salesorder_id'])) {
                if ($entityData->focus->column_fields['timesheet_status'] == 'In Progress') {
                    $adb->pquery($sqlUpdateRow, array(1, $entityData->focus->column_fields['salesorder_lineid']));
                    $focus = CRMEntity::getInstance('SalesOrder');
                    $focus->retrieve_entity_info_no_html($entityData->focus->column_fields['salesorder_id'], 'SalesOrder');
                    $focus->column_fields['sostatus'] = 'Under Processing';
                    $focus->mode = 'edit';
                    $focus->save('SalesOrder');
                } else {
                    $sql = "SELECT * FROM " . $table_prefix . "_timesheets 
                            INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = " . $table_prefix . "_timesheets.timesheetsid
                            WHERE salesorder_lineid = ? AND timesheetsid != ? AND " . $table_prefix . "_timesheets.timesheet_status = ? AND deleted = 0";

                    $res = $adb->pquery($sql, array($entityData->focus->column_fields['salesorder_lineid'], $entityData->focus->id, 'In Progress'));

                    if ($res && $adb->num_rows($res) == 0) {
                        $adb->pquery($sqlUpdateRow, array(0, $entityData->focus->column_fields['salesorder_lineid']));
                    } else {
                        $adb->pquery($sqlUpdateRow, array(1, $entityData->focus->column_fields['salesorder_lineid']));
                    }

                    if (!empty($entityData->focus->column_fields['salesorder_id'])) {
                        $sql = "SELECT * FROM " . $table_prefix . "_timesheets
                                INNER JOIN " . $table_prefix . "_salesorder ON " . $table_prefix . "_salesorder.salesorderid = " . $table_prefix . "_timesheets.salesorder_id
                                WHERE salesorder_id = ? AND timesheet_status = ? AND sostatus != ? AND sostatus != ?";
                        $res2 = $adb->pquery($sql, array($entityData->focus->column_fields['salesorder_id'], 'In Progress', 'Delivered', 'Cancelled'));

                        if ($res2 && $adb->num_rows($res2) == 0) {
                            $focus = CRMEntity::getInstance('SalesOrder');
                            $focus->retrieve_entity_info_no_html($entityData->focus->column_fields['salesorder_id'], 'SalesOrder');
                            $focus->column_fields['sostatus'] = 'On Hold';
                            $focus->mode = 'edit';
                            $focus->save('SalesOrder');
                        }
                    }
                }
            }

            $servicename = '';
            $employeename = '';

            $sql = "SELECT start_time, end_time FROM " . $table_prefix . "_timesheets WHERE timesheetsid = ?";
            $res = $adb->pquery($sql, array($entityData->focus->id));

            if ($res && $adb->num_rows($res) > 0) {
                $start_time = $adb->query_result($res, 0, 'start_time');
                $end_time = $adb->query_result($res, 0, 'end_time');

                if (!empty($start_time) && !empty($end_time)) {
                    $total_hours = ($end_time - $start_time)/3600;

                    $sql = "UPDATE " . $table_prefix . "_timesheets SET total_hours = ? WHERE timesheetsid = ?";
                    $adb->pquery($sql, array($total_hours, $entityData->focus->id));
                }
            }

            if (!empty($entityData->focus->column_fields['service_id'])) {
                $serviceFocus = CRMEntity::getInstance('Services');
                $serviceFocus->retrieve_entity_info_no_html($entityData->focus->column_fields['service_id'], 'Services');

                $servicename = $serviceFocus->column_fields['servicename'];
            }

            $sql = "UPDATE " . $table_prefix . "_timesheets SET servicename = ? WHERE timesheetsid = ?";
            $adb->pquery($sql, array($servicename, $entityData->focus->id));

            if (!empty($entityData->focus->column_fields['employee_id'])) {
                $employeeFocus = CRMEntity::getInstance('Employees');
                $employeeFocus->retrieve_entity_info_no_html($entityData->focus->column_fields['employee_id'], 'Employees');
    
                $employeename = $employeeFocus->column_fields['lastname'] . ' ' . $employeeFocus->column_fields['firstname'];
            }
            
            $sql = "UPDATE " . $table_prefix . "_timesheets SET employeename = ? WHERE timesheetsid = ?";
            $adb->pquery($sql, array($employeename, $entityData->focus->id));
        }
    }
}
