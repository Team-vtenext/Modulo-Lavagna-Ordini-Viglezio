	<?php
	
	function closeActiveInventoryRows($element, $user){	
		global $adb,$table_prefix;

		$employeeId = explode("x", $element['employeeId'])[1];
		
		$sqlEmployee = 'select ' . $table_prefix . '_employees.lastname, ' . $table_prefix . '_employees.firstname FROM ' . $table_prefix . '_employees
						INNER JOIN ' . $table_prefix . '_employeescf on ' . $table_prefix . '_employeescf.employeesid = ' . $table_prefix . '_employees.employeesid
						WHERE ' . $table_prefix . '_employees.employeesid = ?';
						
		$employeeRes = $adb->pquery($sqlEmployee, array($employeeId));
		
		if($employeeRes && $adb->num_rows($employeeRes) > 0){
			$assignedUser = $adb->query_result($employeeRes, 0, 'lastname') . ' ' . $adb->query_result($employeeRes, 0, 'firstname');
		}
		
		$sql = "SELECT id, lineitem_id FROM " . $table_prefix . "_inventoryproductrel WHERE user = ? AND current_status = 'In Corso'";
		$res = $adb->pquery($sql, array($assignedUser));
	
		if($res && $adb->num_rows($res) > 0){
			while($row = $adb->fetchByAssoc($res)) {
				$sql2 = 'update '.$table_prefix.'_inventoryproductrel SET current_status = ?, endtime=now() WHERE id = ? AND lineitem_id = ?';
				$res2 = $adb->pquery($sql2, array('Pausa',$row['id'], $row['lineitem_id']));
			}
		}
	}
