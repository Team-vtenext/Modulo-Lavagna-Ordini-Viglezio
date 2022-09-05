<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class Timesheets extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name;
	var $table_index= 'timesheetsid';
	var $column_fields = Array();

	/** Indicator if this is a custom module or standard module */
	var $IsCustomModule = true;

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array();

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array();

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array();

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array ();
	var $list_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Timesheet Name'=> 'timesheet_name',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'timesheet_name';

	// For Popup listview and UI type support
	var $search_fields = Array();
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Timesheet Name'=> 'timesheet_name'
	);

	// For Popup window record selection
	var $popup_fields = Array('timesheet_name');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'timesheet_name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'timesheet_name';

	// Required Information for enabling Import feature
	var $required_fields = Array('timesheet_name'=>1);

	var $default_order_by = 'timesheet_name';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vte_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'timesheet_name'); // crmv@177975
	//crmv@10759
	var $search_base_field = 'timesheet_name';
	//crmv@10759 e

	function __construct() {
		global $log, $table_prefix; // crmv@64542
		parent::__construct(); // crmv@37004
		$this->table_name = $table_prefix.'_timesheets';
		$this->customFieldTable = Array($table_prefix.'_timesheetscf', 'timesheetsid');
		$this->entity_table = $table_prefix."_crmentity";
		$this->tab_name = Array($table_prefix.'_crmentity', $table_prefix.'_timesheets', $table_prefix.'_timesheetscf');
		$this->tab_name_index = Array(
			$table_prefix.'_crmentity' => 'crmid',
			$table_prefix.'_timesheets'   => 'timesheetsid',
			$table_prefix.'_timesheetscf' => 'timesheetsid'
		);
		$this->list_fields = Array(
			/* Format: Field Label => Array(tablename, columnname) */
			// tablename should not have prefix 'vte_'
			'Timesheet Name'=> Array($table_prefix.'_timesheets', 'timesheet_name'),
			'Assigned To' => Array($table_prefix.'_crmentity','smownerid')
		);
		$this->search_fields = Array(
			/* Format: Field Label => Array(tablename, columnname) */
			// tablename should not have prefix 'vte_'
			'Timesheet Name'=> Array($table_prefix.'_timesheet', 'timesheet_name')
		);
		$this->column_fields = getColumnFields(get_class()); // crmv@64542
		$this->db = PearDatabase::getInstance();
		$this->log = $log;
	}

	/*
	// moved in CRMEntity
	function getSortOrder() { }
	function getOrderBy() { }
	*/

	// crmv@64542
	function save_module($module) {
		global $adb,$table_prefix,$iAmAProcess;
		
		// save the products block
		if (!empty($module) && isInventoryModule($module)) {
			//in ajax save we should not call this function, because this will delete all the existing product values
			if(!empty($_REQUEST) && isset($_REQUEST['totalProductCount']) && $_REQUEST['action'] != "{$module}Ajax" && $_REQUEST['ajxaction'] != 'DETAILVIEW' && $_REQUEST['action'] != 'MassEditSave' && !$iAmAProcess) { // crmv@138794 crmv@196424
				$InventoryUtils = InventoryUtils::getInstance();
				//Based on the total Number of rows we will save the product relationship with this entity
				$InventoryUtils->saveInventoryProductDetails($this, $module);
			}

			// Update the currency id and the conversion rate for the module
			$update_query = "UPDATE {$this->table_name} SET currency_id=?, conversion_rate=? WHERE {$this->table_index} = ?";
			$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
			$adb->pquery($update_query, $update_params);
		}
		
		// You can add more options here
		// ...
	}
	// crmv@64542e

	/**
	 * Return query to use based on given modulename, fieldname
	 * Useful to handle specific case handling for Popup
	 */
	function getQueryByModuleField($module, $fieldname, $srcrecord) {
		// $srcrecord could be empty
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		global $adb,$table_prefix;
		if($event_type == 'module.postinstall') {
			$moduleInstance = Vtiger_Module::getInstance($modulename);
			if ($moduleInstance->is_mod_light) {	//crmv@106857
				$moduleInstance->hide(array('hide_module_manager'=>1,'hide_profile'=>1,'hide_report'=>1));
			} else {
				//crmv@29617
				$result = $adb->pquery('SELECT isentitytype FROM '.$table_prefix.'_tab WHERE name = ?',array($modulename));
				if ($result && $adb->num_rows($result) > 0 && $adb->query_result($result,0,'isentitytype') == '1') {
	
					$ModCommentsModuleInstance = Vtiger_Module::getInstance('ModComments');
					if ($ModCommentsModuleInstance) {
						$ModCommentsFocus = CRMEntity::getInstance('ModComments');
						$ModCommentsFocus->addWidgetTo($modulename);
					}
	
					// crmv@164120 - removed changelog
					// crmv@164122 - removed modnot

					$MyNotesModuleInstance = Vtiger_Module::getInstance('MyNotes');
					if ($MyNotesModuleInstance) {
						$MyNotesCommonFocus = CRMEntity::getInstance('MyNotes');
						$MyNotesCommonFocus->addWidgetTo($modulename);
					}
				}
				//crmv@29617e
				
				//crmv@92272
				$ProcessesFocus = CRMEntity::getInstance('Processes');
				$ProcessesFocus->enable($modulename);
				//crmv@92272e
				
				//crmv@105882 - initialize home for all users
				require_once('include/utils/ModuleHomeView.php');
				$MHW = ModuleHomeView::install($modulename);
				//crmv@105882e
			}

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Courtesy Car', 'Auto di Cortesia');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Coutesy Car', 'Courtesy Car');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Courtesy Car Name', 'Nome Auto di Cortesia');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Coutesy Car Name', 'Courtesy Car Name');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Board No', 'No Lavagna');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Board No', 'Board No');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Account Name', 'Nome Azienda');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Account Name', 'Account Name');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Contact Name', 'Nome Contatto');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Contact Name', 'Contact Name');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Chassis', 'Telaio');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Chassis', 'Chassis');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Plate', 'Targa');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Plate', 'Plate');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Make', 'Marca');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Make', 'Make');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Model', 'Modello');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Model', 'Model');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Insurance Image', 'Immagine Assicurazione');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Insurance Image', 'Insurance Image');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Vehicle Type', 'Tipo Veicolo');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Vehicle Type', 'Vehicle Type');
			
			$moduleInstance = Vtecrm_Module::getInstance('SalesOrder');
			$blockInstance = Vtecrm_Block::getInstance('LBL_SO_INFORMATION', $moduleInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'board_no';
			$fieldInstance->column = 'board_no';
			$fieldInstance->columntype = 'I(11)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'I~O';
			$fieldInstance->label = 'Board No';
			$blockInstance->addField($fieldInstance);


			$moduleInstance = Vtecrm_Module::getInstance('SalesOrder');
			$blockInstance = new Vtecrm_Block();
			$blockInstance->label = 'LBL_VEHICLE_INFORMATION';
			$moduleInstance->addBlock($blockInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'chassis';
			$fieldInstance->column = 'chassis';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Chassis';
			$blockInstance->addField($fieldInstance);
			
			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'plate';
			$fieldInstance->column = 'plate';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Plate';
			$blockInstance->addField($fieldInstance);
			
			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'make';
			$fieldInstance->column = 'make';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Make';
			$blockInstance->addField($fieldInstance);
			
			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'model';
			$fieldInstance->column = 'model';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Model';
			$blockInstance->addField($fieldInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'vehicle_type';
			$fieldInstance->column = 'vehicle_type';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 15;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Vehicle Type';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Car', 'Auto');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Car', 'Car');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Van', 'Furgone');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Van', 'Van');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Motorbike', 'Moto');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Motorbike', 'Motorbike');
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Other', 'Altro');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Other', 'Other');

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'LBL_VEHICLE_INFORMATION', 'Informazioni Veicolo');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'LBL_VEHICLE_INFORMATION', 'Vehicle Information');

			$moduleInstance = Vtecrm_Module::getInstance('SalesOrder');
			$blockInstance = new Vtecrm_Block();
			$blockInstance->label = 'LBL_TECHNICAL_INFORMATION';

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'LBL_TECHNICAL_INFORMATION', 'Informazioni Tecniche');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'LBL_TECHNICAL_INFORMATION', 'Technical Information');

			$panelInstance = new Vtecrm_Panel();
			$panelInstance->label = 'LBL_TECHNICAL_INFORMATION';
			$moduleInstance->addPanel($panelInstance);
			$panelInstance->addBlock($blockInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'courtesy_car';
			$fieldInstance->column = 'courtesy_car';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 52;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Courtesy Car';
			$blockInstance->addField($fieldInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'courtesy_carname';
			$fieldInstance->column = 'courtesy_carname';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Courtesy Car Name';
			$blockInstance->addField($fieldInstance);
			
			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'account_name';
			$fieldInstance->column = 'account_name';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Account Name';
			$blockInstance->addField($fieldInstance);
			
			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'contact_name';
			$fieldInstance->column = 'contact_name';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Contact Name';
			$blockInstance->addField($fieldInstance);

			Vtecrm_Event::register('Timesheets', 'vtiger.entity.aftersave', 'TimesheetsHandler', 'modules/SDK/src/modules/Timesheets/TimesheetsHandler.php');
			Vtecrm_Event::register('SalesOrder', 'vtiger.entity.aftersave', 'SalesOrderHandler', 'modules/SDK/src/modules/SalesOrder/SalesOrderHandler.php');

			require_once('include/utils/ProdBlockUtils.php');
			$PBUtils = ProdBlockUtils::getInstance();
			
			$columnid = $PBUtils->getColumnByLabel('SalesOrder', 'LBL_TOOLS');
						
			$fieldData = [
				'columnid' => $columnid, 
				'fakeid' => $PBUtils->getNextFieldFakeid(),
				'fieldlabel' => 'ID Lavorazione',
				'fieldname' => 'timesheet_id',
				'uitype' => 1, 
				'readonly' => 100, 
				'system' => 1, 
				'active' => 1,
				'extra' => array('hide_detail' => true, 'dbcolumntype' => 'C(200)'),
			];
			$PBUtils->insertField('SalesOrder', $fieldData, true);

			$columnid = $PBUtils->getColumnByLabel('SalesOrder', 'LBL_TOOLS');
						
			$fieldData = [
				'columnid' => $columnid, 
				'fakeid' => $PBUtils->getNextFieldFakeid(),
				'fieldlabel' => 'No Officina',
				'fieldname' => 'no_garage',
				'uitype' => 56, 
				'readonly' => 1, 
				'system' => 1, 
				'active' => 1,
				'extra' => array('hide_detail' => true, 'dbcolumntype' => 'C(1)'),
			];
			$PBUtils->insertField('SalesOrder', $fieldData, true);

			$columnid = $PBUtils->getColumnByLabel('SalesOrder', 'LBL_QTY_IN_STOCK');

			$fieldData = [
				'columnid' => $columnid, 
				'fakeid' => $PBUtils->getNextFieldFakeid(),
				'fieldlabel' => 'Lavorazione In Corso',
				'fieldname' => 'work_in_progress',
				'uitype' => 56, 
				'readonly' => 99, 
				'system' => 1, 
				'active' => 1,
				'extra' => array('dbcolumntype' => 'C(1)'),
			];
			$PBUtils->insertField('SalesOrder', $fieldData, true);

			$employees = Vtecrm_Module::getInstance('Employees');
			$employees->setRelatedList(Vtecrm_Module::getInstance('Timesheets'), 'Timesheets', array('ADD','SELECT'), 'get_dependents_list');

			$saleOrder = Vtecrm_Module::getInstance('SalesOrder');
			$saleOrder->setRelatedList(Vtecrm_Module::getInstance('Timesheets'), 'Timesheets', array('ADD','SELECT'), 'get_dependents_list');

			$myself = Vtecrm_Module::getInstance('SalesOrder');
			$blockInstance = new Vtecrm_Block();
			$blockInstance->label = 'LBL_HOURS_SUMMARY';
			$myself->addBlock($blockInstance);
			$block = Vtecrm_Block::getInstance('LBL_HOURS_SUMMARY', $myself);
			$blockid = $block->id;
			
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'LBL_HOURS_SUMMARY', 'Riepilogo Ore');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'LBL_HOURS_SUMMARY', 'Hours Summary');
			
			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Hours_Summary', 'Sommario Ore');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Hours_Summary', 'Hours Summary');
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Hours Summary', 'Sommario Ore');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Hours Summary', 'Hours Summary');
			
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Service', 'Servizio');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Service', 'Service');
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Total Hours', 'Ore Totali');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Total Hours', 'Total Hours');
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Estimated Hours', 'Ore Stimate Perizia');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Estimated Hours', 'Estimated Expert Hours');
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Productivity', 'Produttività');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Productivity', 'Productivity');
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Actual Gain', 'Guadagno Effettivo');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Actual Gain', 'Actual Gain');
			SDK::setLanguageEntry('ModLightHours_Summary', 'it_it', 'Estimated Earnings', 'Guadagno Stimato');
			SDK::setLanguageEntry('ModLightHours_Summary', 'en_us', 'Estimated Earnings', 'Estimated Earnings');
			
			$properties = array(
				'label' => 'Hours_Summary',
				'columns' => Zend_Json::encode(array(
					array(
						'fieldname' => 'service',
						'label' => 'Service',
						'readonly' => 1,
						'uitype' => 10,
						'relatedmods' => array('Services'),
						'mandatory' => false,
						'newline' => false,
					),
					array(
						'fieldname' => 'total_hours',
						'label' => 'Total Hours',
						'uitype' => 1,
						'typeofdata' => 'N~O',
						'readonly' => 99,
						'mandatory' => false,
						'newline' => false,
					),
					array(
						'fieldname' => 'estimated_hours',
						'label' => 'Estimated Hours',
						'uitype' => 1,
						'typeofdata' => 'N~O',
						'readonly' => 1,
						'mandatory' => false,
						'newline' => false,
					),
					array(
						'fieldname' => 'productivity',
						'label' => 'Productivity',
						'uitype' => 1,
						'typeofdata' => 'N~O',
						'readonly' => 99,
						'mandatory' => false,
						'newline' => false,
					),
					array(
						'fieldname' => 'actual_gain',
						'label' => 'Actual Gain',
						'uitype' => 1,
						'typeofdata' => 'N~O',
						'readonly' => 99,
						'mandatory' => false,
						'newline' => false,
					),
					array(
						'fieldname' => 'estimated_earnings',
						'label' => 'Estimated Earnings',
						'uitype' => 1,
						'typeofdata' => 'N~O',
						'readonly' => 99,
						'mandatory' => false,
						'newline' => false,
					),
				)),
			);
			
			$MLUtils = ModLightUtils::getInstance();
			$MLUtils->addTableField($blockid, null, $properties, 'Hours_Summary');

			$moduleInstance = Vtecrm_Module::getInstance('SalesOrder');
			$blockInstance = Vtecrm_Block::getInstance('LBL_HOURS_SUMMARY', $moduleInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'commission';
			$fieldInstance->column = 'commission';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'N~O';
			$fieldInstance->label = 'Commission';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Commission', 'Provvigione');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Commission', 'Commission');

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'no_apprentice_hours';
			$fieldInstance->column = 'no_apprentice_hours';
			$fieldInstance->columntype = 'I(1)';
			$fieldInstance->uitype = 56;
			$fieldInstance->typeofdata = 'C~O';
			$fieldInstance->label = 'No Apprentice Hours';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'No Apprentice Hours', 'No Ore Apprendista');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'No Apprentice Hours', 'No Apprentice Hours');

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'total_hours';
			$fieldInstance->column = 'total_hours';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'N~O';
			$fieldInstance->label = 'Total Hours';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Total Hours', 'Ore Totali');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Total Hours', 'Total Hours');

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'total_estimated_hours';
			$fieldInstance->column = 'total_estimated_hours';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'N~O';
			$fieldInstance->label = 'Total Estimated Hours';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Total Estimated Hours', 'Ore Stimate Totali');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Total Estimated Hours', 'Total Estimated Hours');

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'total_productivity';
			$fieldInstance->column = 'total_productivity';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'N~O';
			$fieldInstance->label = 'Total Productivity';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Total Productivity', 'Totale Produttività');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Total Productivity', 'Total Productivity');

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'total_actual_gain';
			$fieldInstance->column = 'total_actual_gain';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'N~O';
			$fieldInstance->label = 'Total Actual Gain';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Total Actual Gain', 'Totale Guadagno Effettivo');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Total Actual Gain', 'Total Actual Gain');

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'total_estimated_earnings';
			$fieldInstance->column = 'total_estimated_earnings';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 7;
			$fieldInstance->typeofdata = 'N~O';
			$fieldInstance->label = 'Total Estimated Earnings';
			$blockInstance->addField($fieldInstance);

			SDK::setLanguageEntry('SalesOrder', 'it_it', 'Total Estimated Earnings', 'Totale Guadagno Stimato');
			SDK::setLanguageEntry('SalesOrder', 'en_us', 'Total Estimated Earnings', 'Total Estimated Earnings');

			SDK::setRestOperation('create_other_timesheet', 'modules/SDK/src/modules/Webservices/createOtherTimesheet.php', 'create_other_timesheet', array('element'=>'encoded'));
			SDK::setRestOperation('update_inventory_row', 'modules/SDK/src/modules/Webservices/updateInventoryRow.php', 'update_inventory_row', array('element'=>'encoded'));			

			require_once('include/utils/CronUtils.php');
			$CU = CronUtils::getInstance();
			
			$cj = new CronJob();
			$cj->name = 'AutoCloseTimesheets';
			$cj->active = 1;
			$cj->singleRun = false;
			$cj->fileName = 'cron/modules/Timesheets/AutoCloseTimesheets.php';
			$cj->runHours = '23:00';
			$cj->timeout = 3600; //seconds
			$cj->repeat = 0; //seconds
			$CU->insertCronJob($cj);


		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
	}

}
