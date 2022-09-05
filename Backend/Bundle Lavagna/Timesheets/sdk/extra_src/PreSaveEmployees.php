<?php 

		global $adb, $table_prefix;
		
		
		$sql = "SELECT temporary_pin FROM " . $table_prefix . "_employees WHERE temporary_pin = ? AND temporary_pin != 0 AND employeesid != ?";
		$res = $adb->pquery($sql, array($values['temporary_pin'], $values['record']));
		
		if($res && $adb->num_rows($res) > 0){
			$status = false;
			$message = "Un altro collaboratore ha lo stesso PIN provvisorio, prego selezionarne un altro";
		}
		else {
			$status = true;
		}

?>