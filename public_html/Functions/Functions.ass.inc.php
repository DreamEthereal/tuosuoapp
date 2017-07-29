<?php
//dezend by http://www.yunlu99.com/
function _getoptasscond($questionID, $optionID)
{
	global $DB;
	global $QtnListArray;
	global $OassListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;

	switch ($QtnListArray[$questionID]['questionType']) {
	case '2':
	case '24':
		$_obf_hK1rtGFfODEDEg__ = $RadioListArray[$questionID][$optionID]['isLogicAnd'];
		break;

	case '3':
	case '25':
		$_obf_hK1rtGFfODEDEg__ = $CheckBoxListArray[$questionID][$optionID]['isLogicAnd'];
		break;

	case '6':
	case '7':
	case '19':
	case '28':
		$_obf_hK1rtGFfODEDEg__ = $AnswerListArray[$questionID][$optionID]['isLogicAnd'];
		break;
	}

	$_obf_XA__ = 0;
	$_obf___CY4VdCRg__ = '';

	if (!isset($OassListArray[$questionID][$optionID])) {
		return $_obf___CY4VdCRg__;
	}

	foreach ($OassListArray[$questionID][$optionID] as $_obf_iguicuzFU_U_ => $_obf_GF_EUqXCjkab_luaDYnbgQ__) {
		$_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_ = $QtnListArray[$_obf_iguicuzFU_U_]['questionType'];

		switch ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_) {
		case '1':
		case '2':
		case '3':
		case '4':
		case '24':
		case '25':
		case '30':
		case '17':
			$_obf_utN1F_H7cX3u = count($_obf_GF_EUqXCjkab_luaDYnbgQ__[0]);
			$_obf_7w__ = 0;

			if (2 <= $_obf_utN1F_H7cX3u) {
				$_obf___CY4VdCRg__ .= '(';
			}

			foreach ($_obf_GF_EUqXCjkab_luaDYnbgQ__[0] as $_obf_wCoZV9JhZUYDx8qNn_Y_) {
				$_obf_7w__++;

				if ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_ == '4') {
					switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
					case 1:
						$_obf_DEYYWPewU5w_ = '==';
						break;

					case 2:
						$_obf_DEYYWPewU5w_ = '<';
						break;

					case 3:
						$_obf_DEYYWPewU5w_ = '<=';
						break;

					case 4:
						$_obf_DEYYWPewU5w_ = '>';
						break;

					case 5:
						$_obf_DEYYWPewU5w_ = '>=';
						break;

					case 6:
						$_obf_DEYYWPewU5w_ = '!=';
						break;
					}

					if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
					}
					else {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
					}

					$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isNaN(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
				}
				else {
					if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
					}
					else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
					}
					else {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
					}

					switch ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_) {
					case '1':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '2':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
									$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == \'' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . '\')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
								$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '3':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[3] == 2) {
							switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
							case 1:
								$_obf_DEYYWPewU5w_ = '==';
								break;

							case 2:
								$_obf_DEYYWPewU5w_ = '<';
								break;

							case 3:
								$_obf_DEYYWPewU5w_ = '<=';
								break;

							case 4:
								$_obf_DEYYWPewU5w_ = '>';
								break;

							case 5:
								$_obf_DEYYWPewU5w_ = '>=';
								break;

							case 6:
								$_obf_DEYYWPewU5w_ = '!=';
								break;
							}

							if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
							}
							else {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
							}

							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value.split(\',\').length ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
									$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
								$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '17':
						if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
								if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '24':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '25':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[3] == 2) {
							switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
							case 1:
								$_obf_DEYYWPewU5w_ = '==';
								break;

							case 2:
								$_obf_DEYYWPewU5w_ = '<';
								break;

							case 3:
								$_obf_DEYYWPewU5w_ = '<=';
								break;

							case 4:
								$_obf_DEYYWPewU5w_ = '>';
								break;

							case 5:
								$_obf_DEYYWPewU5w_ = '>=';
								break;

							case 6:
								$_obf_DEYYWPewU5w_ = '!=';
								break;
							}

							if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
							}
							else {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
							}

							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value.split(\',\').length ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '30':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[0] == 1) {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) == 1 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) == 0 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[0] == 1) {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) != 1 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) != 0 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;
					}
				}
			}

			if (2 <= $_obf_utN1F_H7cX3u) {
				$_obf___CY4VdCRg__ .= ')';
			}

			break;

		case '6':
		case '7':
		case '19':
		case '28':
		case '23':
		case '10':
		case '15':
		case '16':
		case '20':
		case '21':
		case '22':
		case '31':
			$_obf_8VWbvil60WRLZQ__ = count($_obf_GF_EUqXCjkab_luaDYnbgQ__);
			$_obf_5w__ = 0;

			if (2 <= $_obf_8VWbvil60WRLZQ__) {
				$_obf___CY4VdCRg__ .= '(';
			}

			foreach ($_obf_GF_EUqXCjkab_luaDYnbgQ__ as $_obf__rwP784_ => $_obf_UAkwn37eaIrRkbJufA__) {
				$_obf_7w__ = 0;
				$_obf_utN1F_H7cX3u = count($_obf_UAkwn37eaIrRkbJufA__);

				if (2 <= $_obf_utN1F_H7cX3u) {
					$_obf___CY4VdCRg__ .= '(';
				}

				foreach ($_obf_UAkwn37eaIrRkbJufA__ as $_obf_wCoZV9JhZUYDx8qNn_Y_) {
					$_obf_7w__++;

					switch ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_) {
					case '23':
					case '10':
					case '16':
					case '20':
					case '22':
						switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
						case 1:
							$_obf_DEYYWPewU5w_ = '==';
							break;

						case 2:
							$_obf_DEYYWPewU5w_ = '<';
							break;

						case 3:
							$_obf_DEYYWPewU5w_ = '<=';
							break;

						case 4:
							$_obf_DEYYWPewU5w_ = '>';
							break;

						case 5:
							$_obf_DEYYWPewU5w_ = '>=';
							break;

						case 6:
							$_obf_DEYYWPewU5w_ = '!=';
							break;
						}

						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}

						$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && !isNaN(getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						break;

					case '15':
					case '21':
						switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
						case 1:
							$_obf_DEYYWPewU5w_ = '==';
							break;

						case 2:
							$_obf_DEYYWPewU5w_ = '<';
							break;

						case 3:
							$_obf_DEYYWPewU5w_ = '<=';
							break;

						case 4:
							$_obf_DEYYWPewU5w_ = '>';
							break;

						case 5:
							$_obf_DEYYWPewU5w_ = '>=';
							break;

						case 6:
							$_obf_DEYYWPewU5w_ = '!=';
							break;
						}

						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}

						switch ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect']) {
						case '0':
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') != \'\' && getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') != 99 && parseInt(getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') * ' . $QtnListArray[$_obf_iguicuzFU_U_]['weight'] . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != 99 && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value * ' . $QtnListArray[$_obf_iguicuzFU_U_]['weight'] . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}

							break;

						case '1':
						case '2':
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && !isNaN(getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							break;
						}

						break;

					case '6':
					case '19':
						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
						}

						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '7':
					case '28':
						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
						}

						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '31':
						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
						}

						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;
					}
				}

				if (2 <= $_obf_utN1F_H7cX3u) {
					$_obf___CY4VdCRg__ .= ')';
				}

				$_obf_5w__++;

				if ($_obf_5w__ != $_obf_8VWbvil60WRLZQ__) {
					if ($_obf_hK1rtGFfODEDEg__ == '1') {
						$_obf___CY4VdCRg__ .= ' && ';
					}
					else {
						$_obf___CY4VdCRg__ .= ' || ';
					}
				}
			}

			if (2 <= $_obf_8VWbvil60WRLZQ__) {
				$_obf___CY4VdCRg__ .= ')';
			}

			break;
		}

		$_obf_XA__++;

		if ($_obf_XA__ != count($OassListArray[$questionID][$optionID])) {
			if ($_obf_hK1rtGFfODEDEg__ == '1') {
				$_obf___CY4VdCRg__ .= ' && ';
			}
			else {
				$_obf___CY4VdCRg__ .= ' || ';
			}
		}
	}

	return $_obf___CY4VdCRg__;
}

function _getqtnasscond($questionID, $rangeQtnID)
{
	global $DB;
	global $QtnListArray;
	global $QassListArray;
	global $OptionListArray;
	global $RankListArray;

	switch ($QtnListArray[$questionID]['questionType']) {
	case '6':
	case '7':
	case '26':
	case '27':
		$_obf_hK1rtGFfODEDEg__ = $OptionListArray[$questionID][$rangeQtnID]['isLogicAnd'];
		break;

	case '10':
	case '15':
	case '16':
		$_obf_hK1rtGFfODEDEg__ = $RankListArray[$questionID][$rangeQtnID]['isLogicAnd'];
		break;
	}

	$_obf_XA__ = 0;
	$_obf___CY4VdCRg__ = '';

	if (!isset($QassListArray[$questionID][$rangeQtnID])) {
		return $_obf___CY4VdCRg__;
	}

	foreach ($QassListArray[$questionID][$rangeQtnID] as $_obf_iguicuzFU_U_ => $_obf_GF_EUqXCjkab_luaDYnbgQ__) {
		$_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_ = $QtnListArray[$_obf_iguicuzFU_U_]['questionType'];

		switch ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_) {
		case '1':
		case '2':
		case '3':
		case '4':
		case '24':
		case '25':
		case '30':
		case '17':
			$_obf_utN1F_H7cX3u = count($_obf_GF_EUqXCjkab_luaDYnbgQ__[0]);
			$_obf_7w__ = 0;

			if (2 <= $_obf_utN1F_H7cX3u) {
				$_obf___CY4VdCRg__ .= '(';
			}

			foreach ($_obf_GF_EUqXCjkab_luaDYnbgQ__[0] as $_obf_wCoZV9JhZUYDx8qNn_Y_) {
				$_obf_7w__++;

				if ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_ == '4') {
					switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
					case 1:
						$_obf_DEYYWPewU5w_ = '==';
						break;

					case 2:
						$_obf_DEYYWPewU5w_ = '<';
						break;

					case 3:
						$_obf_DEYYWPewU5w_ = '<=';
						break;

					case 4:
						$_obf_DEYYWPewU5w_ = '>';
						break;

					case 5:
						$_obf_DEYYWPewU5w_ = '>=';
						break;

					case 6:
						$_obf_DEYYWPewU5w_ = '!=';
						break;
					}

					if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
					}
					else {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
					}

					$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isNaN(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
				}
				else {
					if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
					}
					else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
					}
					else {
						$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
					}

					switch ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_) {
					case '1':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '2':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
									$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == \'' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . '\')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
								$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '3':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[3] == 2) {
							switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
							case 1:
								$_obf_DEYYWPewU5w_ = '==';
								break;

							case 2:
								$_obf_DEYYWPewU5w_ = '<';
								break;

							case 3:
								$_obf_DEYYWPewU5w_ = '<=';
								break;

							case 4:
								$_obf_DEYYWPewU5w_ = '>';
								break;

							case 5:
								$_obf_DEYYWPewU5w_ = '>=';
								break;

							case 6:
								$_obf_DEYYWPewU5w_ = '!=';
								break;
							}

							if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
							}
							else {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
							}

							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value.split(\',\').length ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
									$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
								$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '17':
						if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
								if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '24':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '25':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[3] == 2) {
							switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
							case 1:
								$_obf_DEYYWPewU5w_ = '==';
								break;

							case 2:
								$_obf_DEYYWPewU5w_ = '<';
								break;

							case 3:
								$_obf_DEYYWPewU5w_ = '<=';
								break;

							case 4:
								$_obf_DEYYWPewU5w_ = '>';
								break;

							case 5:
								$_obf_DEYYWPewU5w_ = '>=';
								break;

							case 6:
								$_obf_DEYYWPewU5w_ = '!=';
								break;
							}

							if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
							}
							else {
								$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
							}

							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value.split(\',\').length ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '30':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[0] == 1) {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) == 1 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) == 0 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[0] == 1) {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) != 1 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) != 0 )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;
					}
				}
			}

			if (2 <= $_obf_utN1F_H7cX3u) {
				$_obf___CY4VdCRg__ .= ')';
			}

			break;

		case '6':
		case '7':
		case '19':
		case '28':
		case '23':
		case '10':
		case '15':
		case '16':
		case '20':
		case '21':
		case '22':
		case '31':
			$_obf_8VWbvil60WRLZQ__ = count($_obf_GF_EUqXCjkab_luaDYnbgQ__);
			$_obf_5w__ = 0;

			if (2 <= $_obf_8VWbvil60WRLZQ__) {
				$_obf___CY4VdCRg__ .= '(';
			}

			foreach ($_obf_GF_EUqXCjkab_luaDYnbgQ__ as $_obf__rwP784_ => $_obf_UAkwn37eaIrRkbJufA__) {
				$_obf_7w__ = 0;
				$_obf_utN1F_H7cX3u = count($_obf_UAkwn37eaIrRkbJufA__);

				if (2 <= $_obf_utN1F_H7cX3u) {
					$_obf___CY4VdCRg__ .= '(';
				}

				foreach ($_obf_UAkwn37eaIrRkbJufA__ as $_obf_wCoZV9JhZUYDx8qNn_Y_) {
					$_obf_7w__++;

					switch ($_obf_BRXupNQN6b_pCLTA8COpMwgu_bD_) {
					case '23':
					case '10':
					case '16':
					case '20':
					case '22':
						switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
						case 1:
							$_obf_DEYYWPewU5w_ = '==';
							break;

						case 2:
							$_obf_DEYYWPewU5w_ = '<';
							break;

						case 3:
							$_obf_DEYYWPewU5w_ = '<=';
							break;

						case 4:
							$_obf_DEYYWPewU5w_ = '>';
							break;

						case 5:
							$_obf_DEYYWPewU5w_ = '>=';
							break;

						case 6:
							$_obf_DEYYWPewU5w_ = '!=';
							break;
						}

						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}

						$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && !isNaN(getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						break;

					case '15':
					case '21':
						switch ($_obf_wCoZV9JhZUYDx8qNn_Y_[1]) {
						case 1:
							$_obf_DEYYWPewU5w_ = '==';
							break;

						case 2:
							$_obf_DEYYWPewU5w_ = '<';
							break;

						case 3:
							$_obf_DEYYWPewU5w_ = '<=';
							break;

						case 4:
							$_obf_DEYYWPewU5w_ = '>';
							break;

						case 5:
							$_obf_DEYYWPewU5w_ = '>=';
							break;

						case 6:
							$_obf_DEYYWPewU5w_ = '!=';
							break;
						}

						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}

						switch ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect']) {
						case '0':
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') != \'\' && getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') != 99 && parseInt(getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') * ' . $QtnListArray[$_obf_iguicuzFU_U_]['weight'] . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != 99 && parseInt(getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value * ' . $QtnListArray[$_obf_iguicuzFU_U_]['weight'] . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}

							break;

						case '1':
						case '2':
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && !isNaN(getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != \'\' && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							break;
						}

						break;

					case '6':
					case '19':
						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
						}

						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '7':
					case '28':
						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
						}

						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '31':
						if ($_obf_7w__ == $_obf_utN1F_H7cX3u) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = '';
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[2] == 1) {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' && ';
						}
						else {
							$_obf_gotbQW_cmpfPqYuUS5HRDQ__ = ' || ';
						}

						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (issamepage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;
					}
				}

				if (2 <= $_obf_utN1F_H7cX3u) {
					$_obf___CY4VdCRg__ .= ')';
				}

				$_obf_5w__++;

				if ($_obf_5w__ != $_obf_8VWbvil60WRLZQ__) {
					if ($_obf_hK1rtGFfODEDEg__ == '1') {
						$_obf___CY4VdCRg__ .= ' && ';
					}
					else {
						$_obf___CY4VdCRg__ .= ' || ';
					}
				}
			}

			if (2 <= $_obf_8VWbvil60WRLZQ__) {
				$_obf___CY4VdCRg__ .= ')';
			}

			break;
		}

		$_obf_XA__++;

		if ($_obf_XA__ != count($QassListArray[$questionID][$rangeQtnID])) {
			if ($_obf_hK1rtGFfODEDEg__ == '1') {
				$_obf___CY4VdCRg__ .= ' && ';
			}
			else {
				$_obf___CY4VdCRg__ .= ' || ';
			}
		}
	}

	return $_obf___CY4VdCRg__;
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
