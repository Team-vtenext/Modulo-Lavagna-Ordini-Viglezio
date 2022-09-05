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

class Presences extends CRMEntity {
	var $db, $log; // Used in class functions of CRMEntity

	var $table_name;
	var $table_index= 'presencesid';
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
		'Card Id'=> 'card_id',
		'Assigned To' => 'assigned_user_id'
	);

	// Make the field link to detail view from list view (Fieldname)
	var $list_link_field = 'card_id';

	// For Popup listview and UI type support
	var $search_fields = Array();
	var $search_fields_name = Array(
		/* Format: Field Label => fieldname */
		'Card Id'=> 'card_id'
	);

	// For Popup window record selection
	var $popup_fields = Array('card_id');

	// Placeholder for sort fields - All the fields will be initialized for Sorting through initSortFields
	var $sortby_fields = Array();

	// For Alphabetical search
	var $def_basicsearch_col = 'card_id';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'card_id';

	// Required Information for enabling Import feature
	var $required_fields = Array('card_id'=>1);

	var $default_order_by = 'card_id';
	var $default_sort_order='ASC';
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vte_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'card_id'); // crmv@177975
	//crmv@10759
	var $search_base_field = 'card_id';
	//crmv@10759 e

	function __construct() {
		global $log, $table_prefix; // crmv@64542
		parent::__construct(); // crmv@37004
		$this->table_name = $table_prefix.'_presences';
		$this->customFieldTable = Array($table_prefix.'_presencescf', 'presencesid');
		$this->entity_table = $table_prefix."_crmentity";
		$this->tab_name = Array($table_prefix.'_crmentity', $table_prefix.'_presences', $table_prefix.'_presencescf');
		$this->tab_name_index = Array(
			$table_prefix.'_crmentity' => 'crmid',
			$table_prefix.'_presences'   => 'presencesid',
			$table_prefix.'_presencescf' => 'presencesid'
		);
		$this->list_fields = Array(
			/* Format: Field Label => Array(tablename, columnname) */
			// tablename should not have prefix 'vte_'
			'Card Id'=> Array($table_prefix.'_presences', 'card_id'),
			'Assigned To' => Array($table_prefix.'_crmentity','smownerid')
		);
		$this->search_fields = Array(
			/* Format: Field Label => Array(tablename, columnname) */
			// tablename should not have prefix 'vte_'
			'Card Id'=> Array($table_prefix.'_presences', 'card_id')
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
			$myself = Vtecrm_Module::getInstance('Presences');
			$block = Vtecrm_Block::getInstance('LBL_CUSTOM_INFORMATION', $myself);
			$blockid = $block->id;

			SDK::setLanguageEntry('Presences', 'it_it', 'LBL_REGISTRATIONS', 'Registrazioni');
			SDK::setLanguageEntry('Presences', 'en_us', 'LBL_REGISTRATIONS', 'Registrations');

			SDK::setLanguageEntry('Presences', 'it_it', 'Registrations', 'Registrazioni');
			SDK::setLanguageEntry('Presences', 'en_us', 'Registrations', 'Registrations');
			SDK::setLanguageEntry('ModLightRegistrations', 'it_it', 'Registrations', 'Registrazioni');
			SDK::setLanguageEntry('ModLightRegistrations', 'en_us', 'Registrations', 'Registrations');

			SDK::setLanguageEntry('ModLightRegistrations', 'it_it', 'Type', 'Tipo');
			SDK::setLanguageEntry('ModLightRegistrations', 'en_us', 'Type', 'Type');
			SDK::setLanguageEntry('ModLightRegistrations', 'it_it', 'Hour', 'Ora');
			SDK::setLanguageEntry('ModLightRegistrations', 'en_us', 'Hour', 'Hour');
			SDK::setLanguageEntry('ModLightRegistrations', 'it_it', 'Entrance', 'Entrata');
			SDK::setLanguageEntry('ModLightRegistrations', 'en_us', 'Entrance', 'Entrance');
			SDK::setLanguageEntry('ModLightRegistrations', 'it_it', 'Leaving', 'Uscita');
			SDK::setLanguageEntry('ModLightRegistrations', 'en_us', 'Leaving', 'Leaving');

			$properties = array(
				'label' => 'Registrations',
				'columns' => Zend_Json::encode(array(
					array(
						'fieldname' => 'type',
						'label' => 'Type',
						'readonly' => 1,
						'uitype' => 15,
						'picklistvalues' => "Entrance\nLeaving",
						'mandatory' => false,
						'newline' => false,
					),
					array(
						'fieldname' => 'hour',
						'label' => 'Hour',
						'uitype' => 73,
						'readonly' => 1,
						'mandatory' => false,
						'newline' => false,
					),
				)),
			);

			$MLUtils = ModLightUtils::getInstance();
			$MLUtils->addTableField($blockid, null, $properties, 'Registrations');

			$employees = Vtecrm_Module::getInstance('Employees');
			$employees->setRelatedList(Vtecrm_Module::getInstance('Presences'), 'Presences', array('ADD','SELECT'), 'get_dependents_list');

			$moduleInstance = Vtecrm_Module::getInstance('Employees');
			$block2 = Vtecrm_Block::getInstance('LBL_EMPLOYEES_INFORMATION', $moduleInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'card_id';
			$fieldInstance->column = 'card_id';
			$fieldInstance->columntype = 'C(200)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Card Id';
			$block2->addField($fieldInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'temporary_pin';
			$fieldInstance->column = 'temporary_pin';
			$fieldInstance->columntype = 'C(200)';
			$fieldInstance->uitype = 1;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->label = 'Temporary PIN';
			$block2->addField($fieldInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'employee_status';
			$fieldInstance->column = 'employee_status';
			$fieldInstance->columntype = 'C(255)';
			$fieldInstance->uitype = 15;
			$fieldInstance->typeofdata = 'V~O';
			$fieldInstance->setPicklistValues(array('Active', 'Inactive'));
			$fieldInstance->label = 'Employee Status';
			$block2->addField($fieldInstance);

			$fieldInstance = new Vtecrm_Field();
			$fieldInstance->name = 'is_admin';
			$fieldInstance->label = 'Is Admin';
			$fieldInstance->table = $module->basetable;
			$fieldInstance->column = 'is_admin';
			$fieldInstance->uitype = 56;
			$fieldInstance->columntype = 'I(1)';
			$fieldInstance->typeofdata = 'C~O';
			$fieldInstance->displaytype = 1;
			$fieldInstance->quickcreate = 0;
			$block2->addField($fieldInstance);
			
			SDK::setLanguageEntry('Employees', 'it_it', 'Is Admin', 'Amministratore');
			SDK::setLanguageEntry('Employees', 'en_us', 'Is Admin', 'Is Admin');
			SDK::setLanguageEntry('Employees', 'it_it', 'Card Id', 'ID Tessera');
			SDK::setLanguageEntry('Employees', 'en_us', 'Card Id', 'Card Id');
			SDK::setLanguageEntry('Employees', 'it_it', 'Temporary PIN', 'PIN Provvisorio');
			SDK::setLanguageEntry('Employees', 'en_us', 'Temporary PIN', 'Temporary PIN');
			SDK::setLanguageEntry('Employees', 'it_it', 'Employee Status', 'Stato');
			SDK::setLanguageEntry('Employees', 'en_us', 'Employee Status', 'Employee Status');
			SDK::setLanguageEntry('Employees', 'it_it', 'Active', 'Active');
			SDK::setLanguageEntry('Employees', 'en_us', 'Active', 'Active');
			SDK::setLanguageEntry('Employees', 'it_it', 'Inactive', 'Inactive');
			SDK::setLanguageEntry('Employees', 'en_us', 'Inactive', 'Inactive');			

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
