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

function set_return_insurance_information(insurance_id, insurance_name, insurance_rate, image_url) {
	//crmv@29190
	var formName = getReturnFormName();
	var form = getReturnForm(formName);
	//crmv@29190e
	form.elements["insurance_id_display"].value = insurance_name; 
	form.elements["insurance_id"].value = insurance_id;
	disableReferenceField(form.elements["insurance_id_display"],form.elements["insurance_id"],form.elements["insurance_id_mass_edit_check"]);	//crmv@29190
	if (enableAdvancedFunction(form)) {	//crmv@29190
		if(typeof(form.insurance_rate) != 'undefined')
			form.insurance_rate.value = insurance_rate;
		if(typeof(form.insurance_image) != 'undefined')
			form.insurance_image.value = image_url;
		//crmv@21048me
	}
}
//crmv@29190e crmv@69568e
