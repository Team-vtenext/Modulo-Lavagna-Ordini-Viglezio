	<?php

function create_other_timesheet($element, $user)
{
    global $adb,$table_prefix;

    $cardId = $element['card'];
    $service_id = explode('x', $element['service_id'])[1];
    $servicename = $element['service_name'];
        
    $sqlEmployee = 'select ' . $table_prefix . '_employees.employeesid, lastname, firstname FROM ' . $table_prefix . '_employees
					    WHERE ' . $table_prefix . '_employees.temporary_pin = ? or ' . $table_prefix . '_employees.card_id = ?';

    $resEmployee = $adb->pquery($sqlEmployee, array($cardId, $cardId));

    if ($resEmployee && $adb->num_rows($resEmployee) > 0) {
        $employeesid = $adb->query_result($resEmployee, 0, 'employeesid');
    } else {
        return "LBL_NO_USER_CARD";
    }

    $sql = "SELECT * FROM " . $table_prefix . "_timesheets
            INNER JOIN " . $table_prefix . "_crmentity ON " . $table_prefix . "_crmentity.crmid = " . $table_prefix . "_timesheets.timesheetsid
            WHERE employee_id = ? AND timesheet_status = 'In Progress' AND deleted = 0";
    $res = $adb->pquery($sql, array($employeesid));
        
    if ($res && $adb->num_rows($res) > 0) {
        return "LBL_OTHER_TIMESHEET_PROGRESS";
    } else {

        $sql = "SELECT presencesid FROM " . $table_prefix  ."_presences WHERE presence_date = ? AND employee_id = ?";
        $res3 = $adb->pquery($sql, array(date("Y-m-d"), $employeesid));

        if ($res3 && $adb->num_rows($res3) == 0) {
            $presenceFocus = CRMEntity::getInstance('Presences');
            $presenceFocus->column_fields["card_id"] = $cardId;
            $presenceFocus->column_fields['presence_date'] = date("Y-m-d");
            $presenceFocus->column_fields["employee_id"] = $employeesid;
            $presenceFocus->column_fields["assigned_user_id"] = $user->id;
            $presenceFocus->save('Presences');

            $MLUtils = ModLightUtils::getInstance();
            $row = array();
            $row['row'] = array('seq' => 0, 'type' => 'Entrance', 'hour' => date("H:i"));
            $line_record_id = $MLUtils->saveRow('ModLightRegistrations', 0, $row, $presenceFocus);
        } elseif ($res3 && $adb->num_rows($res3) > 0) {
            $presenceId = $adb->query_result($res3, 0, 'presencesid');


            $sql = "SELECT * FROM " . $table_prefix . "_modlightregistrations WHERE parent_id = ? ORDER BY hour desc LIMIT 1";
            $res4 = $adb->pquery($sql, array($presenceId));

            if ($res4 && $adb->num_rows($res4) > 0) {
                $type = $adb->query_result($res4, 0, 'type');

                if ($type == 'Leaving') {
                    $presenceFocus = CRMEntity::getInstance('Presences');
                    $presenceFocus->retrieve_entity_info($presenceId, 'Presences');
                    $presenceFocus->id = $presenceId;

                    $MLUtils = ModLightUtils::getInstance();
                    $row = array();
                    $row['row'] = array('seq' => 0, 'type' => 'Entrance', 'hour' => date("H:i"));
                    $line_record_id = $MLUtils->saveRow('ModLightRegistrations', 0, $row, $presenceFocus);

                }
            }
        }

        $focus = CRMEntity::getInstance('Timesheets');
        $focus->column_fields["timesheet_name"] = $servicename;
        $focus->column_fields["service_id"] = $service_id;
        $focus->column_fields["start_time"] = date("H:i");
        $focus->column_fields['timesheet_date'] = date("Y-m-d");
        $focus->column_fields['timesheet_status'] = 'In Progress';
        $focus->column_fields["employee_id"] = $employeesid;
        $focus->column_fields["assigned_user_id"] = $user->id;
        $focus->save('Timesheets');
    
        return "LBL_REGISTRATION_DONE";
    }
}
