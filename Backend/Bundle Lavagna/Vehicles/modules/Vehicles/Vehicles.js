/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

//crmv@29190 crmv@69568
function set_return(product_id, product_name) {
	var formName = getReturnFormName();
	var form = (formName ? getReturnForm(formName) : null);
	if (form) {
		form.parent_name.value = product_name;
		form.parent_id.value = product_id;
		disableReferenceField(form.parent_name,form.parent_id,form.parent_id_mass_edit_check);
	}
}

function set_return_vehicle_information(vehicle_id, vehicle_name, plate, chassis, make, model, vehicle_type) {
	//crmv@29190
	var formName = getReturnFormName();
	var form = getReturnForm(formName);
	//crmv@29190e
	form.elements["vehicle_id_display"].value = vehicle_name; 
	form.elements["vehicle_id"].value = vehicle_id;
	disableReferenceField(form.elements["vehicle_id_display"],form.elements["vehicle_id"],form.elements["vehicle_id_mass_edit_check"]);	//crmv@29190
	if (enableAdvancedFunction(form)) {	//crmv@29190
		if(typeof(form.plate) != 'undefined')
			form.plate.value = plate;
		if(typeof(form.chassis) != 'undefined')
			form.chassis.value = chassis;
		if(typeof(form.make) != 'undefined')
			form.make.value = make;
		if(typeof(form.model) != 'undefined')
			form.model.value = model;
		if(typeof(form.vehicle_type) != 'undefined')
			form.vehicle_type.value = vehicle_type;
		//crmv@21048me
	}
}

//crmv@29190e crmv@69568e
