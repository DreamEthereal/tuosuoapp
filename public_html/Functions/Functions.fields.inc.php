<?php
//dezend by http://www.yunlu99.com/
function listfields($name, $table = '', $progURL = '')
{
	global $DB;
	global $table_prefix;
	global $_GET;
	global $_POST;
	global $EnableQCoreClass;
	global $lang;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';

	if ($progURL != '') {
		$_obf_wcUo_agEnZJxnQ__ = '../Images/';
		$_obf_Il8i = $progURL;
	}

	$EnableQCoreClass->setTemplateFile($table . 'ListFieldsFile', $table . 'ListFields.html');
	$EnableQCoreClass->replace('fields', '');
	$EnableQCoreClass->set_CycBlock($table . 'ListFieldsFile', 'FIELDS', 'fields');
	$EnableQCoreClass->replace('newURL', $_obf_Il8i . '&fAction=Add');
	$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' ';
	$_obf_xCnI .= ' ORDER BY orderByID ASC ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_9p7zLgNm8EUtoAg_ = $DB->_getNumRows($_obf_3I8RfSDT);
	$EnableQCoreClass->replace('recNum', $_obf_9p7zLgNm8EUtoAg_);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$EnableQCoreClass->replace('editURL', $_obf_Il8i . '&fAction=Edit&fieldsID=' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_]);
		$EnableQCoreClass->replace('deleteURL', $_obf_Il8i . '&fAction=Delete&fieldsID=' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_]);
		$EnableQCoreClass->replace('upURL', $_obf_Il8i . '&fAction=Order&Compositor=ASC&ID=' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '&OrderID=' . $_obf_9WwQ['orderByID']);
		$EnableQCoreClass->replace('downURL', $_obf_Il8i . '&fAction=Order&Compositor=DESC&ID=' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '&OrderID=' . $_obf_9WwQ['orderByID']);

		if ($_obf_9WwQ['isCheckNull'] == 1) {
			$EnableQCoreClass->replace('name', $_obf_9WwQ['optionFieldName'] . '<span class=red>*</span>');
		}
		else {
			$EnableQCoreClass->replace('name', $_obf_9WwQ['optionFieldName']);
		}

		if (($_obf_9WwQ['types'] == 'text') || ($_obf_9WwQ['types'] == 'textarea')) {
			$EnableQCoreClass->replace('length', $_obf_9WwQ['length']);
		}
		else {
			$EnableQCoreClass->replace('length', $lang['empty']);
		}

		if ($_obf_9WwQ['isPublic'] == 1) {
			$EnableQCoreClass->replace('isPublic', '<IMG SRC=\'' . $_obf_wcUo_agEnZJxnQ__ . 'show.gif\'>');
			$EnableQCoreClass->replace('is_Public', '');
		}
		else {
			$EnableQCoreClass->replace('is_Public', 'selected');
			$EnableQCoreClass->replace('isPublic', '<IMG SRC=\'' . $_obf_wcUo_agEnZJxnQ__ . 'hide.gif\'>');
		}

		$EnableQCoreClass->replace('orderByID', $_obf_9WwQ['orderByID']);
		$EnableQCoreClass->replace('fieldsID', $_obf_9WwQ[$_obf_jpXiA_gmtJs_]);

		switch ($_obf_9WwQ['types']) {
		case 'text':
			$EnableQCoreClass->replace('types', $lang['fields_text']);
			break;

		case 'textarea':
			$EnableQCoreClass->replace('types', $lang['fields_textarea']);
			break;

		case 'radio':
			$EnableQCoreClass->replace('types', $lang['fields_radio']);
			break;

		case 'checkbox':
			$EnableQCoreClass->replace('types', $lang['fields_checkbox']);
			break;

		case 'select':
			$EnableQCoreClass->replace('types', $lang['fields_select']);
			break;
		}

		$EnableQCoreClass->parse('fields', 'FIELDS', true);
	}

	$EnableQCoreClass->parse($table . 'ListFields', $table . 'ListFieldsFile');
	$EnableQCoreClass->output($table . 'ListFields');
}

function displayaddfields($name, $table = '', $thisProg = '')
{
	global $_GET;
	global $EnableQCoreClass;
	global $lang;

	if ($thisProg != '') {
		$_obf_jSa0r0YnJCAhiePU_vc_ = $table . 'AddFields.html';
	}

	$EnableQCoreClass->setTemplateFile('AddFieldsFile', $_obf_jSa0r0YnJCAhiePU_vc_);
	$EnableQCoreClass->replace('DefineFieldsURL', $thisProg);
	$EnableQCoreClass->replace('name', $lang[$name]);
	$EnableQCoreClass->parse('AddFields', 'AddFieldsFile');
	$EnableQCoreClass->output('AddFields');
}

function addfieldssubmit($table, $name = '', $thisProg = '')
{
	global $_GET;
	global $_POST;
	global $lang;
	global $DB;
	global $table_prefix;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $table . 'option';
	$_obf_xCnI = ' SELECT optionFieldName FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE optionFieldName = \'' . $_POST['name'] . '\' LIMIT 0,1 ';
	$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);

	if ($_obf_9WwQ) {
		_showerror($lang['error_system'], $lang['option_name_is_exist']);
	}

	if (isset($_POST['defaultText'])) {
		$_obf_VgKtFeg_ = $_POST['defaultText'];
	}
	else if (isset($_POST['defaultTextarea'])) {
		$_obf_VgKtFeg_ = $_POST['defaultTextarea'];
	}
	else {
		$_obf_VgKtFeg_ = $_POST['defaultList'];
	}

	$_obf_xCnI = ' INSERT INTO ' . $_obf_OO_eMufPOODjxTRL . ' SET optionFieldName=\'' . trim($_POST['name']) . '\',length=\'' . $_POST['length'] . '\',rows=\'' . $_POST['rows'] . '\',types=\'' . $_POST['types'] . '\',isPublic=\'' . $_POST['isPublic'] . '\',isCheckNull=\'' . $_POST['isCheckNull'] . '\',content=\'' . $_POST['optionContent'] . '\',value=\'' . $_obf_VgKtFeg_ . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minNum=\'' . $_POST['MinValue'] . '\',maxNum=\'' . $_POST['MaxValue'] . '\' ';
	$DB->query($_obf_xCnI);
	updateorderid($table . 'option');
	writetolog($lang['add_' . $table . '_fields'] . ':' . $_POST['name']);
	_showmessage($lang['add_' . $table . '_fields'] . ':' . $_POST['name'], true);
}

function displayeditfields($table, $name = '', $thisProg = '')
{
	global $_GET;
	global $EnableQCoreClass;
	global $DB;
	global $table_prefix;
	global $lang;
	$EnableQCoreClass->setTemplateFile('EditFieldsFile', $name . 'EditFields.html');
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $table . 'option';
	$_obf_jpXiA_gmtJs_ = $table . 'optionID';

	if ($_obf_wW371YQ1XWQ_ == '') {
		$_obf_wW371YQ1XWQ_ = $_GET['fieldsID'];
	}

	$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE ' . $_obf_jpXiA_gmtJs_ . '=' . $_obf_wW371YQ1XWQ_ . ' LIMIT 0,1 ';
	$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);
	$EnableQCoreClass->replace('fieldsType', $_obf_9WwQ['types']);
	$EnableQCoreClass->replace('name', $_obf_9WwQ['optionFieldName']);

	switch ($_obf_9WwQ['types']) {
	case 'text':
		$EnableQCoreClass->replace('text', 'selected');
		$EnableQCoreClass->replace('defaultText', $_obf_9WwQ['value']);
		$EnableQCoreClass->replace('defaultTextarea', '');
		$EnableQCoreClass->replace('defaultList', '');
		$EnableQCoreClass->replace('rows', '5');
		break;

	case 'textarea':
		$EnableQCoreClass->replace('textarea', 'selected');
		$EnableQCoreClass->replace('defaultTextarea', $_obf_9WwQ['value']);
		$EnableQCoreClass->replace('defaultText', '');
		$EnableQCoreClass->replace('defaultList', '');
		$EnableQCoreClass->replace('rows', $_obf_9WwQ['rows']);
		break;

	case 'radio':
		$EnableQCoreClass->replace('radio', 'selected');
		$EnableQCoreClass->replace('defaultList', $_obf_9WwQ['value']);
		$EnableQCoreClass->replace('defaultTextarea', '');
		$EnableQCoreClass->replace('defaultText', '');
		$EnableQCoreClass->replace('rows', '5');
		break;

	case 'checkbox':
		$EnableQCoreClass->replace('checkbox', 'selected');
		$EnableQCoreClass->replace('defaultList', $_obf_9WwQ['value']);
		$EnableQCoreClass->replace('defaultTextarea', '');
		$EnableQCoreClass->replace('defaultText', '');
		$EnableQCoreClass->replace('rows', '5');
		break;

	case 'select':
		$EnableQCoreClass->replace('select', 'selected');
		$EnableQCoreClass->replace('defaultList', $_obf_9WwQ['value']);
		$EnableQCoreClass->replace('defaultTextarea', '');
		$EnableQCoreClass->replace('defaultText', '');
		$EnableQCoreClass->replace('rows', '5');
		break;
	}

	$EnableQCoreClass->replace('length', $_obf_9WwQ['length']);
	if (($_obf_9WwQ['types'] == 'select') || ($_obf_9WwQ['types'] == 'checkbox') || ($_obf_9WwQ['types'] == 'radio')) {
		$EnableQCoreClass->replace('optionContent', $_obf_9WwQ['content']);
	}
	else {
		$EnableQCoreClass->replace('optionContent', '');
	}

	$_obf_f_oOmkcOAKw_ = 'isPublic' . $_obf_9WwQ['isPublic'];
	$EnableQCoreClass->replace($_obf_f_oOmkcOAKw_, 'selected');

	if ($_obf_9WwQ['isCheckNull'] == 1) {
		$EnableQCoreClass->replace('isCheckNull', 'checked');
	}

	$EnableQCoreClass->replace('isCheckType', $_obf_9WwQ['isCheckType']);
	$_obf_tSf0_Xg0mlFfkgY_ = 'isCheckType' . $_obf_9WwQ['isCheckType'];
	$EnableQCoreClass->replace($_obf_tSf0_Xg0mlFfkgY_, 'selected');

	if ($_obf_9WwQ['minNum'] == 0) {
		if ($_obf_9WwQ['maxNum'] == 0) {
			$EnableQCoreClass->replace('MinValue', '');
		}
		else {
			$EnableQCoreClass->replace('MinValue', '0');
		}
	}
	else {
		$EnableQCoreClass->replace('MinValue', $_obf_9WwQ['minNum']);
	}

	if ($_obf_9WwQ['maxNum'] == 0) {
		$EnableQCoreClass->replace('MaxValue', '');
	}
	else {
		$EnableQCoreClass->replace('MaxValue', $_obf_9WwQ['maxNum']);
	}

	$EnableQCoreClass->replace('fieldsID', $_GET['fieldsID']);
	$EnableQCoreClass->replace('DefineFieldsURL', $thisProg);
	$EnableQCoreClass->parse('EditFields', 'EditFieldsFile');
	$EnableQCoreClass->output('EditFields');
}

function editfieldssubmit($table, $name = '', $thisProg = '')
{
	global $_GET;
	global $_POST;
	global $lang;
	global $DB;
	global $table_prefix;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $table . 'option';
	$_obf_jpXiA_gmtJs_ = $table . 'optionID';
	$_obf_xCnI = ' SELECT optionFieldName FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE optionFieldName = \'' . $_POST['name'] . '\' AND optionFieldName != \'' . $_POST['ori_optionFieldName'] . '\' LIMIT 0,1 ';
	$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);

	if ($_obf_9WwQ) {
		_showerror($lang['error_system'], $lang['option_name_is_exist']);
	}

	if (isset($_POST['defaultText'])) {
		$_obf_VgKtFeg_ = $_POST['defaultText'];
	}
	else if (isset($_POST['defaultTextarea'])) {
		$_obf_VgKtFeg_ = $_POST['defaultTextarea'];
	}
	else {
		$_obf_VgKtFeg_ = $_POST['defaultList'];
	}

	$_obf_xCnI = ' UPDATE ' . $_obf_OO_eMufPOODjxTRL . ' SET optionFieldName=\'' . trim($_POST['name']) . '\',length=\'' . $_POST['length'] . '\',rows=\'' . $_POST['rows'] . '\',types=\'' . $_POST['types'] . '\',isPublic=\'' . $_POST['isPublic'] . '\',isCheckNull=\'' . $_POST['isCheckNull'] . '\',content=\'' . $_POST['optionContent'] . '\',value=\'' . $_obf_VgKtFeg_ . '\',isCheckType=\'' . $_POST['isCheckType'] . '\',minNum=\'' . $_POST['MinValue'] . '\',maxNum=\'' . $_POST['MaxValue'] . '\' ';
	$_obf_xCnI .= '  WHERE ' . $_obf_jpXiA_gmtJs_ . '=\'' . $_POST['fieldsID'] . '\' ';
	$DB->query($_obf_xCnI);
	writetolog($lang['edit_' . $table . '_fields'] . ':' . $_POST['name']);
	_showmessage($lang['edit_' . $table . '_fields'] . ':' . $_POST['name'], true);
}

function displayaddoption($name, $type = 0)
{
	global $DB;
	global $table_prefix;
	global $EnableQCoreClass;
	global $_GET;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';

	if ($type == 1) {
		$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' ORDER BY orderByID ASC ';
	}
	else {
		$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC ';
	}

	$EnableQCoreClass->replace('optionList', '');
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_ULS41jZEkQnJwA__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_eYd9uiBXf_hWTZE_ = 'option_' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_];

		switch ($_obf_9WwQ['types']) {
		case 'text':
			$EnableQCoreClass->setTemplateFile('OptionTextFile', 'OptionText.html');
			$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'option_name' => $_obf_eYd9uiBXf_hWTZE_, 'option_type' => $_obf_9WwQ['types'], 'option_size' => $_obf_9WwQ['length'], 'option_value' => $_obf_9WwQ['value']));

			if ($_obf_9WwQ['isCheckNull'] == 1) {
				$EnableQCoreClass->replace('isCheckNull', '<span class=red>*</span>');
			}
			else {
				$EnableQCoreClass->replace('isCheckNull', '&nbsp;&nbsp;');
			}

			$_obf_ov6OSOm_RdXUqg__ = $EnableQCoreClass->parse('OptionText', 'OptionTextFile');
			$EnableQCoreClass->replace('optionList', $_obf_ov6OSOm_RdXUqg__, true);
			break;

		case 'textarea':
			$EnableQCoreClass->setTemplateFile('OptionTextareaFile', 'OptionTextarea.html');
			$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'option_name' => $_obf_eYd9uiBXf_hWTZE_, 'option_cols' => $_obf_9WwQ['length'], 'option_rows' => $_obf_9WwQ['rows'], 'option_value' => $_obf_9WwQ['value']));

			if ($_obf_9WwQ['isCheckNull'] == 1) {
				$EnableQCoreClass->replace('isCheckNull', '<span class=red>*</span>');
			}
			else {
				$EnableQCoreClass->replace('isCheckNull', '&nbsp;&nbsp;');
			}

			$_obf_lLOQ90UQ6czWO1p7rYA_ = $EnableQCoreClass->parse('OptionTextarea', 'OptionTextareaFile');
			$EnableQCoreClass->replace('optionList', $_obf_lLOQ90UQ6czWO1p7rYA_, true);
			break;

		case 'select':
			$EnableQCoreClass->setTemplateFile('OptionSelectFile', 'OptionSelect.html');
			$_obf_gE5EiR_Z = explode("\n", $_obf_9WwQ['content']);
			$_obf_42IjFAbCGfQcHg__ = '';
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < count($_obf_gE5EiR_Z); $_obf_7w__++) {
				$_obf_gE5EiR_Z[$_obf_7w__] = str_replace("\r", '', $_obf_gE5EiR_Z[$_obf_7w__]);

				if (trim($_obf_gE5EiR_Z[$_obf_7w__]) != '') {
					if ($_obf_7w__ == $_obf_9WwQ['value'] - 1) {
						$_obf_42IjFAbCGfQcHg__ .= '<option value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" selected>' . $_obf_gE5EiR_Z[$_obf_7w__] . '</option>' . "\n" . '';
					}
					else {
						$_obf_42IjFAbCGfQcHg__ .= '<option value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '">' . $_obf_gE5EiR_Z[$_obf_7w__] . '</option>' . "\n" . '';
					}
				}
			}

			$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'selectList' => $_obf_42IjFAbCGfQcHg__, 'option_name' => $_obf_eYd9uiBXf_hWTZE_));
			$_obf_DTFSrksXVtuFDQwi = $EnableQCoreClass->parse('OptionSelect', 'OptionSelectFile');
			$EnableQCoreClass->replace('optionList', $_obf_DTFSrksXVtuFDQwi, true);
			break;

		case 'checkbox':
			$EnableQCoreClass->setTemplateFile('OptionCheckboxFile', 'OptionCheckbox.html');
			$_obf_gE5EiR_Z = explode("\n", $_obf_9WwQ['content']);
			$_obf_C93E20OmhDLwI8BL = '';
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < count($_obf_gE5EiR_Z); $_obf_7w__++) {
				$_obf_gE5EiR_Z[$_obf_7w__] = str_replace("\r", '', $_obf_gE5EiR_Z[$_obf_7w__]);

				if (trim($_obf_gE5EiR_Z[$_obf_7w__]) != '') {
					if ($_obf_7w__ == $_obf_9WwQ['value'] - 1) {
						$_obf_C93E20OmhDLwI8BL .= '<input type="checkbox" name="' . $_obf_eYd9uiBXf_hWTZE_ . '[]" id="' . $_obf_eYd9uiBXf_hWTZE_ . '[]"  value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" checked>' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
					else {
						$_obf_C93E20OmhDLwI8BL .= '<input type="checkbox" name="' . $_obf_eYd9uiBXf_hWTZE_ . '[]" id="' . $_obf_eYd9uiBXf_hWTZE_ . '[]"  value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '">' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
				}
			}

			$EnableQCoreClass->replace(array('value' => $_obf_C93E20OmhDLwI8BL, 'option_field_name' => $_obf_9WwQ['optionFieldName']));
			$_obf_mV_FW8Ysy6hMhtexLMM_ = $EnableQCoreClass->parse('OptionCheckbox', 'OptionCheckboxFile');
			$EnableQCoreClass->replace('optionList', $_obf_mV_FW8Ysy6hMhtexLMM_, true);
			break;

		case 'radio':
			$EnableQCoreClass->setTemplateFile('OptionRadioFile', 'OptionRadio.html');
			$_obf_gE5EiR_Z = explode("\n", $_obf_9WwQ['content']);
			$_obf_D9BVP_l5UCkA = '';
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < count($_obf_gE5EiR_Z); $_obf_7w__++) {
				$_obf_gE5EiR_Z[$_obf_7w__] = str_replace("\r", '', $_obf_gE5EiR_Z[$_obf_7w__]);

				if (trim($_obf_gE5EiR_Z[$_obf_7w__]) != '') {
					if ($_obf_7w__ == $_obf_9WwQ['value'] - 1) {
						$_obf_D9BVP_l5UCkA .= '<input type="radio" name="' . $_obf_eYd9uiBXf_hWTZE_ . '" id="' . $_obf_eYd9uiBXf_hWTZE_ . '" value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" checked>' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
					else {
						$_obf_D9BVP_l5UCkA .= '<input type="radio" name="' . $_obf_eYd9uiBXf_hWTZE_ . '" id="' . $_obf_eYd9uiBXf_hWTZE_ . '" value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" >' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
				}
			}

			$EnableQCoreClass->replace(array('value' => $_obf_D9BVP_l5UCkA, 'option_field_name' => $_obf_9WwQ['optionFieldName']));
			$_obf_yV6GippGePztxuk_ = $EnableQCoreClass->parse('OptionRadio', 'OptionRadioFile');
			$EnableQCoreClass->replace('optionList', $_obf_yV6GippGePztxuk_, true);
			break;
		}
	}
}

function displayeditoption($name, $tableNameID = '', $type = 0)
{
	global $DB;
	global $table_prefix;
	global $EnableQCoreClass;
	global $_GET;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_STTxwTVQwqUIUlOAoFZFkQ__ = $table_prefix . $name . 'optionvalue';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';
	$_obf_0Y6EFyiS = $name . 'ID';
	$EnableQCoreClass->replace('optionList', '');

	if ($type == 1) {
		$_obf_xCnI = 'SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' ORDER BY orderByID ASC,' . $_obf_jpXiA_gmtJs_ . ' DESC ';
	}
	else {
		$_obf_xCnI = 'SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC,' . $_obf_jpXiA_gmtJs_ . ' DESC ';
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_ULS41jZEkQnJwA__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_xCnI = ' SELECT value,' . $_obf_jpXiA_gmtJs_ . ',' . $_obf_0Y6EFyiS . ' FROM ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' WHERE ' . $_obf_jpXiA_gmtJs_ . '=\'' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '\' AND ' . $_obf_0Y6EFyiS . '=\'' . $tableNameID . '\'  ';
		$_obf_rooQO5CR1g__ = $DB->queryFirstRow($_obf_xCnI);
		$_obf_eYd9uiBXf_hWTZE_ = 'option_' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_];

		switch ($_obf_9WwQ['types']) {
		case 'text':
			$EnableQCoreClass->setTemplateFile('OptionTextFile', 'OptionText.html');
			$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'option_name' => $_obf_eYd9uiBXf_hWTZE_, 'option_type' => $_obf_9WwQ['types'], 'option_size' => $_obf_9WwQ['length'], 'option_value' => $_obf_rooQO5CR1g__['value']));

			if ($_obf_9WwQ['isCheckNull'] == 1) {
				$EnableQCoreClass->replace('isCheckNull', '<span class=red>*</span>');
			}
			else {
				$EnableQCoreClass->replace('isCheckNull', '&nbsp;&nbsp;');
			}

			$_obf_ov6OSOm_RdXUqg__ = $EnableQCoreClass->parse('OptionText', 'OptionTextFile');
			$EnableQCoreClass->replace('optionList', $_obf_ov6OSOm_RdXUqg__, true);
			break;

		case 'textarea':
			$EnableQCoreClass->setTemplateFile('OptionTextareaFile', 'OptionTextarea.html');
			$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'option_name' => $_obf_eYd9uiBXf_hWTZE_, 'option_cols' => $_obf_9WwQ['length'], 'option_rows' => $_obf_9WwQ['rows'], 'option_value' => $_obf_rooQO5CR1g__['value']));

			if ($_obf_9WwQ['isCheckNull'] == 1) {
				$EnableQCoreClass->replace('isCheckNull', '<span class=red>*</span>');
			}
			else {
				$EnableQCoreClass->replace('isCheckNull', '&nbsp;&nbsp;');
			}

			$_obf_lLOQ90UQ6czWO1p7rYA_ = $EnableQCoreClass->parse('OptionTextarea', 'OptionTextareaFile');
			$EnableQCoreClass->replace('optionList', $_obf_lLOQ90UQ6czWO1p7rYA_, true);
			break;

		case 'select':
			$EnableQCoreClass->setTemplateFile('OptionSelectFile', 'OptionSelect.html');
			$_obf_gE5EiR_Z = explode("\n", $_obf_9WwQ['content']);
			$_obf_42IjFAbCGfQcHg__ = '';
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < count($_obf_gE5EiR_Z); $_obf_7w__++) {
				$_obf_gE5EiR_Z[$_obf_7w__] = str_replace("\r", '', $_obf_gE5EiR_Z[$_obf_7w__]);

				if (trim($_obf_gE5EiR_Z[$_obf_7w__]) != '') {
					if ($_obf_gE5EiR_Z[$_obf_7w__] == $_obf_rooQO5CR1g__['value']) {
						$_obf_42IjFAbCGfQcHg__ .= '<option value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" selected>' . $_obf_gE5EiR_Z[$_obf_7w__] . '</option>' . "\n" . '';
					}
					else {
						$_obf_42IjFAbCGfQcHg__ .= '<option value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '">' . $_obf_gE5EiR_Z[$_obf_7w__] . '</option>' . "\n" . '';
					}
				}
			}

			$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'selectList' => $_obf_42IjFAbCGfQcHg__, 'option_name' => $_obf_eYd9uiBXf_hWTZE_));
			$_obf_DTFSrksXVtuFDQwi = $EnableQCoreClass->parse('OptionSelect', 'OptionSelectFile');
			$EnableQCoreClass->replace('optionList', $_obf_DTFSrksXVtuFDQwi, true);
			break;

		case 'checkbox':
			$EnableQCoreClass->setTemplateFile('OptionCheckboxFile', 'OptionCheckbox.html');
			$_obf_gE5EiR_Z = explode("\n", $_obf_9WwQ['content']);
			$_obf_C93E20OmhDLwI8BL = '';
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < count($_obf_gE5EiR_Z); $_obf_7w__++) {
				$_obf_gE5EiR_Z[$_obf_7w__] = str_replace("\r", '', $_obf_gE5EiR_Z[$_obf_7w__]);

				if (trim($_obf_gE5EiR_Z[$_obf_7w__]) != '') {
					if (in_array($_obf_gE5EiR_Z[$_obf_7w__], explode(',', $_obf_rooQO5CR1g__['value']))) {
						$_obf_C93E20OmhDLwI8BL .= '<input type="checkbox" name="' . $_obf_eYd9uiBXf_hWTZE_ . '[]" id="' . $_obf_eYd9uiBXf_hWTZE_ . '[]"  value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" checked>' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
					else {
						$_obf_C93E20OmhDLwI8BL .= '<input type="checkbox" name="' . $_obf_eYd9uiBXf_hWTZE_ . '[]" id="' . $_obf_eYd9uiBXf_hWTZE_ . '[]"  value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '">' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
				}
			}

			$EnableQCoreClass->replace(array('value' => $_obf_C93E20OmhDLwI8BL, 'option_field_name' => $_obf_9WwQ['optionFieldName']));
			$_obf_mV_FW8Ysy6hMhtexLMM_ = $EnableQCoreClass->parse('OptionCheckbox', 'OptionCheckboxFile');
			$EnableQCoreClass->replace('optionList', $_obf_mV_FW8Ysy6hMhtexLMM_, true);
			break;

		case 'radio':
			$EnableQCoreClass->setTemplateFile('OptionRadioFile', 'OptionRadio.html');
			$_obf_gE5EiR_Z = explode("\n", $_obf_9WwQ['content']);
			$_obf_D9BVP_l5UCkA = '';
			$_obf_7w__ = 0;

			for (; $_obf_7w__ < count($_obf_gE5EiR_Z); $_obf_7w__++) {
				$_obf_gE5EiR_Z[$_obf_7w__] = str_replace("\r", '', $_obf_gE5EiR_Z[$_obf_7w__]);

				if (trim($_obf_gE5EiR_Z[$_obf_7w__]) != '') {
					if ($_obf_gE5EiR_Z[$_obf_7w__] == $_obf_rooQO5CR1g__['value']) {
						$_obf_D9BVP_l5UCkA .= '<input type="radio" name="' . $_obf_eYd9uiBXf_hWTZE_ . '" id="' . $_obf_eYd9uiBXf_hWTZE_ . '" value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" checked>' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
					else {
						$_obf_D9BVP_l5UCkA .= '<input type="radio" name="' . $_obf_eYd9uiBXf_hWTZE_ . '" id="' . $_obf_eYd9uiBXf_hWTZE_ . '" value="' . $_obf_gE5EiR_Z[$_obf_7w__] . '" >' . $_obf_gE5EiR_Z[$_obf_7w__] . "\n";
					}
				}
			}

			$EnableQCoreClass->replace(array('value' => $_obf_D9BVP_l5UCkA, 'option_field_name' => $_obf_9WwQ['optionFieldName']));
			$_obf_yV6GippGePztxuk_ = $EnableQCoreClass->parse('OptionRadio', 'OptionRadioFile');
			$EnableQCoreClass->replace('optionList', $_obf_yV6GippGePztxuk_, true);
			break;
		}
	}
}

function _checkoptioninput($name, $types = '', $checktype = 0)
{
	global $DB;
	global $table_prefix;
	global $EnableQCoreClass;
	global $lang;
	global $_POST;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';

	if ($checktype == 1) {
		$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE ( isCheckNull=\'1\' OR minNum != 0 ) ORDER BY orderByID ASC ';
	}
	else {
		$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' AND ( isCheckNull=\'1\' OR minNum != 0 ) ORDER BY orderByID ASC ';
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_aMwm_YI_ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_XBK2j_yQ_HVeDw__ = 'option_' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_];

		switch ($_obf_9WwQ['types']) {
		case 'text':
			if ($_obf_9WwQ['isCheckNull'] == 1) {
				$_obf_aMwm_YI_ .= '	if (!CheckNotNull(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',\'' . $_obf_9WwQ['optionFieldName'] . '\')) {return false;} ' . "\n" . ' ';
			}

			if ($_obf_9WwQ['isCheckType'] != 0) {
				switch ($_obf_9WwQ['isCheckType']) {
				case '1':
					$_obf_aMwm_YI_ .= '	if (!CheckEmail(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',\'' . $_obf_9WwQ['optionFieldName'] . '\')) {return false;} ' . "\n" . ' ';
					break;

				case '2':
					$_obf_aMwm_YI_ .= '	if (!CheckStringLength(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',\'' . $_obf_9WwQ['optionFieldName'] . '\',' . $_obf_9WwQ['minNum'] . ',' . $_obf_9WwQ['maxNum'] . ')) {return false;} ' . "\n" . ' ';
					break;

				case '3':
					$_obf_aMwm_YI_ .= '	if (!CheckNoChinese(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',\'' . $_obf_9WwQ['optionFieldName'] . '\')) {return false;} ' . "\n" . ' ';
					break;

				case '4':
					$_obf_aMwm_YI_ .= '	if (!CheckIsValue(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',\'' . $_obf_9WwQ['optionFieldName'] . '\',' . $_obf_9WwQ['minNum'] . ',' . $_obf_9WwQ['maxNum'] . ')) {return false;} ' . "\n" . ' ';
					break;

				case '5':
					$_obf_aMwm_YI_ .= '	if (!CheckPhone(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',\'' . $_obf_9WwQ['optionFieldName'] . '\')) {return false;} ' . "\n" . ' ';
					break;

				case '6':
					$_obf_aMwm_YI_ .= '		if (!CheckDate(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;

				case '7':
					$_obf_aMwm_YI_ .= '		if (!CheckIDCardNo(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;

				case '8':
					$_obf_aMwm_YI_ .= '		if (!CheckCorpCode(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;

				case '9':
					$_obf_aMwm_YI_ .= '		if (!CheckPostalCode(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;

				case '10':
					$_obf_aMwm_YI_ .= '		if (!CheckURL(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;

				case '11':
					$_obf_aMwm_YI_ .= '		if (!CheckMobile(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;

				case '12':
					$_obf_aMwm_YI_ .= '		if (!CheckChinese(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ', \'' . $_obf_9WwQ['optionFieldName'] . '\')){return false;} ' . "\n" . '';
					break;
				}
			}

			if ($types == 'phpCheck') {
				if (($_POST[$_obf_XBK2j_yQ_HVeDw__] == '') && ($_obf_9WwQ['isCheckNull'] == 1)) {
					_showerror($lang['error_system'], $_obf_9WwQ['optionFieldName'] . $lang['empty_fields']);
				}
			}

			break;

		case 'textarea':
			if ($_obf_9WwQ['isCheckNull'] == 1) {
				$_obf_aMwm_YI_ .= '	if (!CheckNotNull(document.Check_Form.' . $_obf_XBK2j_yQ_HVeDw__ . ',"' . $_obf_9WwQ['optionFieldName'] . '")) {return false;} ' . "\n" . ' ';
			}

			if ($types == 'phpCheck') {
				if (($_POST[$_obf_XBK2j_yQ_HVeDw__] == '') && ($_obf_9WwQ['isCheckNull'] == 1)) {
					_showerror($lang['error_system'], $_obf_9WwQ['optionFieldName'] . $lang['empty_fields']);
				}
			}

			break;
		}
	}

	$EnableQCoreClass->replace('optionCheckInput', $_obf_aMwm_YI_);
}

function insertoptionvalue($name, $lastID = '', $type = 0)
{
	global $DB;
	global $table_prefix;
	global $_GET;
	global $_POST;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';
	$_obf_STTxwTVQwqUIUlOAoFZFkQ__ = $table_prefix . $name . 'optionvalue';
	$_obf_0Y6EFyiS = $name . 'ID';

	if ($type == 1) {
		$_obf_xCnI = ' SELECT ' . $_obf_jpXiA_gmtJs_ . ',types FROM ' . $_obf_OO_eMufPOODjxTRL . ' ORDER BY orderByID ASC ';
	}
	else {
		$_obf_xCnI = ' SELECT ' . $_obf_jpXiA_gmtJs_ . ',types FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC ';
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_gE5EiR_Z = 'option_' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_];
		$_obf_ULS41jZEkQnJwA__ = $_POST[$_obf_gE5EiR_Z];

		if ($_obf_9WwQ['types'] == 'checkbox') {
			$_obf_BLjwJprZkL4ySU4zQWEz = '';
			if (is_array($_obf_ULS41jZEkQnJwA__) && sizeof($_obf_ULS41jZEkQnJwA__)) {
				$_obf_BLjwJprZkL4ySU4zQWEz = implode(',', $_obf_ULS41jZEkQnJwA__);
			}
		}
		else {
			$_obf_BLjwJprZkL4ySU4zQWEz = $_obf_ULS41jZEkQnJwA__;
		}

		$_obf_xCnI = ' INSERT INTO ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' SET ' . $_obf_0Y6EFyiS . '=\'' . $lastID . '\',' . $_obf_jpXiA_gmtJs_ . '=\'' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '\',value=\'' . $_obf_BLjwJprZkL4ySU4zQWEz . '\' ';
		$DB->query($_obf_xCnI);
	}
}

function editoptionvalue($name, $editID = '', $type = 0)
{
	global $DB;
	global $table_prefix;
	global $_GET;
	global $_POST;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';
	$_obf_STTxwTVQwqUIUlOAoFZFkQ__ = $table_prefix . $name . 'optionvalue';
	$_obf_0Y6EFyiS = $name . 'ID';

	if ($type == 1) {
		$_obf_xCnI = ' SELECT ' . $_obf_jpXiA_gmtJs_ . ',types FROM ' . $_obf_OO_eMufPOODjxTRL . ' ORDER BY orderByID ASC ';
	}
	else {
		$_obf_xCnI = ' SELECT ' . $_obf_jpXiA_gmtJs_ . ',types FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC ';
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_gE5EiR_Z = 'option_' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_];
		$_obf_ULS41jZEkQnJwA__ = $_POST[$_obf_gE5EiR_Z];
		$_obf_KUp_cWyZUurd1_ZCIg__ = $_obf_9WwQ[$_obf_jpXiA_gmtJs_];

		if ($_obf_9WwQ['types'] == 'checkbox') {
			$_obf_BLjwJprZkL4ySU4zQWEz = '';
			if (is_array($_obf_ULS41jZEkQnJwA__) && sizeof($_obf_ULS41jZEkQnJwA__)) {
				$_obf_BLjwJprZkL4ySU4zQWEz = implode(',', $_obf_ULS41jZEkQnJwA__);
			}
		}
		else {
			$_obf_BLjwJprZkL4ySU4zQWEz = $_obf_ULS41jZEkQnJwA__;
		}

		$_obf_xCnI = ' SELECT ' . $_obf_0Y6EFyiS . ' FROM ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' WHERE ' . $_obf_0Y6EFyiS . '=\'' . $editID . '\' AND ' . $_obf_jpXiA_gmtJs_ . '=\'' . $_obf_KUp_cWyZUurd1_ZCIg__ . '\' ';
		$_obf_9WwQ = $DB->queryFirstRow($_obf_xCnI);

		if (0 < $_obf_9WwQ[$_obf_0Y6EFyiS]) {
			$_obf_xCnI = ' UPDATE ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' SET value=\'' . $_obf_BLjwJprZkL4ySU4zQWEz . '\' WHERE ' . $_obf_0Y6EFyiS . '=\'' . $editID . '\' AND ' . $_obf_jpXiA_gmtJs_ . '=\'' . $_obf_KUp_cWyZUurd1_ZCIg__ . '\' ';
		}
		else {
			$_obf_xCnI = ' INSERT ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' SET value=\'' . $_obf_BLjwJprZkL4ySU4zQWEz . '\',' . $_obf_0Y6EFyiS . '=\'' . $editID . '\',' . $_obf_jpXiA_gmtJs_ . '=\'' . $_obf_KUp_cWyZUurd1_ZCIg__ . '\' ';
		}

		$DB->query($_obf_xCnI);
	}
}

function deleteoptionvalue($name, $ID = '')
{
	global $DB;
	global $table_prefix;
	$_obf_STTxwTVQwqUIUlOAoFZFkQ__ = $table_prefix . $name . 'optionvalue';
	$_obf_0Y6EFyiS = $name . 'ID';
	$_obf_xCnI = ' DELETE FROM ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' WHERE ' . $_obf_0Y6EFyiS . '=' . $ID . ' ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
}

function displayviewoption($name, $tableNameID = '', $type = 0)
{
	global $DB;
	global $table_prefix;
	global $EnableQCoreClass;
	global $_GET;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_STTxwTVQwqUIUlOAoFZFkQ__ = $table_prefix . $name . 'optionvalue';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';
	$_obf_0Y6EFyiS = $name . 'ID';

	if ($type == 1) {
		$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' ORDER BY orderByID ASC ';
	}
	else {
		$_obf_xCnI = ' SELECT * FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC ';
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$EnableQCoreClass->setTemplateFile('OptionViewFile', 'OptionView.html');
	$_obf_RoRl7rHYXnHClg__ = '';
	$_obf_lw__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		$_obf_xCnI = ' SELECT value,' . $_obf_jpXiA_gmtJs_ . ',' . $_obf_0Y6EFyiS . ' FROM ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' WHERE ' . $_obf_jpXiA_gmtJs_ . '=\'' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '\' AND ' . $_obf_0Y6EFyiS . '=\'' . $tableNameID . '\' ';
		$_obf_ZryYZkD_kA__ = $DB->queryFirstRow($_obf_xCnI);
		$EnableQCoreClass->replace(array('option_field_name' => $_obf_9WwQ['optionFieldName'], 'option_value' => $_obf_ZryYZkD_kA__['value']));
		$_obf_lw__ = $EnableQCoreClass->parse('OptionView', 'OptionViewFile');
		$_obf_RoRl7rHYXnHClg__ .= $_obf_lw__;
	}

	$EnableQCoreClass->replace('optionList', $_obf_RoRl7rHYXnHClg__);
}

function _obf_OV8PBS1tewk7XhNhbnJb($name, $value = '', $type = 0)
{
	global $DB;
	global $table_prefix;
	global $EnableQCoreClass;
	$_obf_OO_eMufPOODjxTRL = $table_prefix . $name . 'option';
	$_obf_jpXiA_gmtJs_ = $name . 'optionID';
	$_obf_0Y6EFyiS = $name . 'ID';

	if ($type == 1) {
		$_obf_xCnI = ' SELECT ' . $_obf_jpXiA_gmtJs_ . ',optionFieldName FROM ' . $_obf_OO_eMufPOODjxTRL . ' ORDER BY orderByID ASC ';
	}
	else {
		$_obf_xCnI = ' SELECT ' . $_obf_jpXiA_gmtJs_ . ',optionFieldName FROM ' . $_obf_OO_eMufPOODjxTRL . ' WHERE isPublic=\'1\' ORDER BY orderByID ASC ';
	}

	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
	$_obf_ZXuOB9OOxkxhJw__ = '';

	while ($_obf_9WwQ = $DB->queryArray($_obf_3I8RfSDT)) {
		if ($_obf_9WwQ[$_obf_jpXiA_gmtJs_] == $value) {
			$_obf_ZXuOB9OOxkxhJw__ .= '<option value="' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '" selected>' . $_obf_9WwQ['optionFieldName'] . '</option>' . "\n" . '';
		}
		else {
			$_obf_ZXuOB9OOxkxhJw__ .= '<option value="' . $_obf_9WwQ[$_obf_jpXiA_gmtJs_] . '">' . $_obf_9WwQ['optionFieldName'] . '</option>' . "\n" . '';
		}
	}

	$EnableQCoreClass->replace('fieldsList', $_obf_ZXuOB9OOxkxhJw__);
}

function _obf_bAY3YCY4BGJdPilk($name, $ID = '')
{
	global $DB;
	global $table_prefix;
	$_obf_STTxwTVQwqUIUlOAoFZFkQ__ = $table_prefix . $name . 'optionvalue';
	$_obf_0Y6EFyiS = $name . 'ID';
	$_obf_xCnI = ' DELETE FROM ' . $_obf_STTxwTVQwqUIUlOAoFZFkQ__ . ' WHERE ' . $_obf_0Y6EFyiS . '=' . $ID . ' ';
	$_obf_3I8RfSDT = $DB->query($_obf_xCnI);
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
