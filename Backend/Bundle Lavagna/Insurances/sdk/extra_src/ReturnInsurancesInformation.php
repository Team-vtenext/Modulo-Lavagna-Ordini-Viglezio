<?php
/* crmv@42247 crmv@65940 crmv@107331 */

global $default_charset,$adb,$table_prefix,$autocomplete_return_function;

$forfield = htmlspecialchars($_REQUEST['forfield'], ENT_QUOTES, $default_charset);
$list_result_count = $i-1;

$value = $this->getValue($ui_col_array,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"search",$focus->popup_type);

$current_mod = $_REQUEST['srcmodule']; // crmv@166552

if(isset($forfield) && $forfield != '' && $focus->popup_type != 'detailview') {
	// value as it is in the DB
	$rawValue = ListViewUtils::decodeFromDb($value, true);
	
	// value ready for js inclusion
	$valueJs = ListViewUtils::encodeForJs($rawValue);
	
	// value with HTML enitities to be used inside HTML attributes
	$valueHtml = ListViewUtils::encodeForHtml($rawValue);
		
	// columns to retrieve
	$columns = array(
		'price_rate', 'image_url');
	$popupValues = array();
	
	$result = $adb->pquery(
		"SELECT ".implode(', ', $columns)."
		FROM {$table_prefix}_insurances 
		WHERE {$table_prefix}_insurances.insurancesid = ?", 
		array($entity_id)
	); 
	if ($result && $adb->num_rows($result)>0) {
		// get the row exactly as it is in the DB
		$popupValues = $adb->fetchByAssoc($result, -1, false);
	}

	$fnName = 'set_return_insurance_information';
	$argsKeys = array(
			'price_rate', 'image_url'
		);

	// prepare the list of arguments
	$argList = array();
	foreach ($argsKeys as $key) {
		$argList[] = ListViewUtils::encodeForJs($popupValues[$key], '"');
	}
	$args = '"'.implode('", "', $argList).'"';
	
	// prepare the autocomplete function
	$autocomplete_return_function[$entity_id] = "$fnName($entity_id, \"$valueJs\", {$args});";
	$autocompleteHtml = ListViewUtils::encodeForHtmlAttr($autocomplete_return_function[$entity_id], "'");
	
	// html values
	$value = '<a href="index.php?module=Insurances&action=DetailView&record='.$entity_id.'&parenttab=Marketing" />';
	$value .= "<a href='javascript:void(0);' onclick='{$autocompleteHtml}closePopup();'>$valueHtml</a>"; //crmv@21048m
	
}
