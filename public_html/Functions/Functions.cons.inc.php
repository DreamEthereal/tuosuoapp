<?php
//dezend by http://www.yunlu99.com/
function isSamePage($baseQtnID, $conQtnID)
{
	global $thisPageStep;
	global $pageQtnList;
	if (empty($pageQtnList) || empty($pageQtnList[$thisPageStep]) || ($pageQtnList[$thisPageStep] == '')) {
		return false;
	}

	if (in_array($baseQtnID, $pageQtnList[$thisPageStep]) && in_array($conQtnID, $pageQtnList[$thisPageStep])) {
		return true;
	}
	else {
		return false;
	}
}

function _getquestioncond($questionID, $surveyID)
{
	global $DB;
	global $QtnListArray;
	global $CondListArray;
	$_obf_XA__ = 0;
	$_obf___CY4VdCRg__ = '';

	if (!isset($CondListArray[$questionID])) {
		return $_obf___CY4VdCRg__;
	}

	$_obf_QsqwTu3kcHVx1Fm_7_DLz0PPt2jw = count($CondListArray[$questionID]);

	foreach ($CondListArray[$questionID] as $_obf_iguicuzFU_U_ => $_obf_GF_EUqXCjkab_luaDYnbgQ__) {
		if ($_obf_iguicuzFU_U_ == 0) {
			$_obf_QsqwTu3kcHVx1Fm_7_DLz0PPt2jw--;
			continue;
		}

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
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '2':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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

							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value.split(\',\').length ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
								if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
									$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
								else {
									$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
								}
							}
							else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value != ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && !isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '24':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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

							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value.split(\',\').length ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && isInValueList(' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ',getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value) )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
							if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
								$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\').value == ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if (isSamePage($questionID, $_obf_iguicuzFU_U_)) {
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
					if ($QtnListArray[$questionID]['isLogicAnd'] == '1') {
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

		if ($_obf_XA__ != $_obf_QsqwTu3kcHVx1Fm_7_DLz0PPt2jw) {
			if ($QtnListArray[$questionID]['isLogicAnd'] == '1') {
				$_obf___CY4VdCRg__ .= ' && ';
			}
			else {
				$_obf___CY4VdCRg__ .= ' || ';
			}
		}
	}

	return $_obf___CY4VdCRg__;
}

function isSkipPage($pageTag, $arrow = 1)
{
	global $pageQtnList;
	global $DB;
	global $pageBreak;
	global $surveyID;
	global $QtnListArray;

	if ($arrow == 1) {
		if ($pageTag == count($pageBreak) - 1) {
			return $pageTag;
		}
	}
	else if ($pageTag <= 0) {
		return 0;
	}

	if (empty($pageQtnList[$pageTag]) || ($pageQtnList[$pageTag] == '')) {
		if ($arrow == 1) {
			return isskippage($pageTag + 1, 1);
		}
		else {
			return isskippage($pageTag - 1, 2);
		}
	}
	else {
		$pageQtnIDList = implode(',', $pageQtnList[$pageTag]);
		$SQL = ' SELECT DISTINCT questionID FROM ' . CONDITIONS_TABLE . ' WHERE questionID IN (' . $pageQtnIDList . ') ORDER BY questionID ASC ';
		$Result = $DB->query($SQL);
		$haveConQtnIDArray = array();

		while ($Row = $DB->queryArray($Result)) {
			$haveConQtnIDArray[] = $Row['questionID'];
		}

		$theMaskQtnList = array();

		foreach ($pageQtnList[$pageTag] as $theQtnID) {
			if (!in_array($QtnListArray[$theQtnID]['questionType'], array('17', '19', '20', '21', '22', '28', '29'))) {
				continue;
			}

			if ($QtnListArray[$theQtnID]['isNeg'] == 1) {
				continue;
			}

			$thisBaseID = $QtnListArray[$theQtnID]['baseID'];

			if ($QtnListArray[$thisBaseID]['questionType'] != '3') {
				continue;
			}

			if (!in_array('99999', $_SESSION['option_' . $thisBaseID])) {
				continue;
			}

			$theMaskQtnList[] = $theQtnID;
			clrsessionforcon($theQtnID);
		}

		if (empty($haveConQtnIDArray) || ($haveConQtnIDArray == '')) {
			$haveNoMaskQtn = arraydiff($pageQtnList[$pageTag], $theMaskQtnList);
			if (!empty($haveNoMaskQtn) && ($haveNoMaskQtn != '')) {
				return $pageTag;
			}
			else if ($arrow == 1) {
				return isskippage($pageTag + 1, 1);
			}
			else {
				return isskippage($pageTag - 1, 2);
			}
		}
		else {
			$haveNoCondQtn = arraydiff($pageQtnList[$pageTag], $haveConQtnIDArray);
			$isMaskSkip = true;
			$haveNoMaskQtn = arraydiff($haveNoCondQtn, $theMaskQtnList);
			if (!empty($haveNoMaskQtn) && ($haveNoMaskQtn != '')) {
				$isMaskSkip = false;
			}

			$isSkipPage = true;

			foreach ($haveConQtnIDArray as $haveConQtnID) {
				$ConSuccFlag = getqtnconsucc($haveConQtnID);

				if (runcode($ConSuccFlag)) {
					$isSkipPage = false;
					break;
				}
				else {
					clrsessionforcon($haveConQtnID);
				}
			}

			if (($isSkipPage == true) && ($isMaskSkip == true)) {
				if ($arrow == 1) {
					return isskippage($pageTag + 1, 1);
				}
				else {
					return isskippage($pageTag - 1, 2);
				}
			}
			else {
				return $pageTag;
			}
		}
	}
}

function getqtnconsucc($theQtnID)
{
	global $DB;
	global $surveyID;
	global $QtnListArray;
	global $CondListArray;
	$_obf_XA__ = 0;
	$_obf___CY4VdCRg__ = '';

	if (!isset($CondListArray[$theQtnID])) {
		return $_obf___CY4VdCRg__;
	}

	foreach ($CondListArray[$theQtnID] as $_obf_iguicuzFU_U_ => $_obf_GF_EUqXCjkab_luaDYnbgQ__) {
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

					$_obf___CY4VdCRg__ .= isnumbertextlogictrue('option_' . $_obf_iguicuzFU_U_, $_obf_DEYYWPewU5w_, $_obf_wCoZV9JhZUYDx8qNn_Y_[0], 1, 0, 1) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
					case '2':
					case '24':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							$_obf___CY4VdCRg__ .= isradioselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= isradiounselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '3':
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

							$_obf___CY4VdCRg__ .= getcheckboxselnum('option_' . $_obf_iguicuzFU_U_, $_obf_DEYYWPewU5w_, $_obf_wCoZV9JhZUYDx8qNn_Y_[0]) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							$_obf___CY4VdCRg__ .= ischeckboxselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= ischeckboxunselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '17':
						if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
								$_obf___CY4VdCRg__ .= isradioselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= isradiounselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							$_obf___CY4VdCRg__ .= ischeckboxselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= ischeckboxunselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '30':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[0] == 1) {
								$_obf___CY4VdCRg__ .= isradioselect('1', $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= isradioselect('0', $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[0] == 1) {
							$_obf___CY4VdCRg__ .= isradiounselect('1', $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= isradiounselect('0', $_obf_iguicuzFU_U_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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

						$_obf___CY4VdCRg__ .= isnumbertextlogictrue('option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_, $_obf_DEYYWPewU5w_, $_obf_wCoZV9JhZUYDx8qNn_Y_[0], 1, 0, 1) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						break;

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

						$_obf___CY4VdCRg__ .= isnumbertextlogictrue('option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_, $_obf_DEYYWPewU5w_, $_obf_wCoZV9JhZUYDx8qNn_Y_[0], 1, 0, 2) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
							$_obf___CY4VdCRg__ .= isnumbertextlogictrue('option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_, $_obf_DEYYWPewU5w_, $_obf_wCoZV9JhZUYDx8qNn_Y_[0], $QtnListArray[$_obf_iguicuzFU_U_]['weight'], 1, 2) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							break;

						case '1':
						case '2':
							$_obf___CY4VdCRg__ .= isnumbertextlogictrue('option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_, $_obf_DEYYWPewU5w_, $_obf_wCoZV9JhZUYDx8qNn_Y_[0], 1, 0, 2) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							break;
						}

						break;

					case '6':
					case '19':
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
							$_obf___CY4VdCRg__ .= isradioselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= isradiounselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
							$_obf___CY4VdCRg__ .= ischeckboxselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= ischeckboxunselect($_obf_wCoZV9JhZUYDx8qNn_Y_[0], $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_) . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;
					}
				}

				if (2 <= $_obf_utN1F_H7cX3u) {
					$_obf___CY4VdCRg__ .= ')';
				}

				$_obf_5w__++;

				if ($_obf_5w__ != $_obf_8VWbvil60WRLZQ__) {
					if ($QtnListArray[$theQtnID]['isLogicAnd'] == '1') {
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

		if ($_obf_XA__ != count($CondListArray[$theQtnID])) {
			if ($QtnListArray[$theQtnID]['isLogicAnd'] == '1') {
				$_obf___CY4VdCRg__ .= ' && ';
			}
			else {
				$_obf___CY4VdCRg__ .= ' || ';
			}
		}
	}

	return $_obf___CY4VdCRg__;
}

function clrsessionforcon($qtnID)
{
	global $DB;
	global $surveyID;
	global $Module;
	global $QtnListArray;
	global $YesNoListArray;
	global $RadioListArray;
	global $CheckBoxListArray;
	global $AnswerListArray;
	global $OptionListArray;
	global $LabelListArray;
	global $RankListArray;
	$this_fields_list = '';
	$this_fileds_type = '';
	$questionID = $qtnID;
	$theQuestionType = $QtnListArray[$questionID]['questionType'];
	$theQtnArray = $QtnListArray[$questionID];

	if (!in_array($theQuestionType, array(8, 9))) {
		require ROOT_PATH . 'PlugIn/' . $Module[$theQuestionType] . '/Admin/' . $Module[$theQuestionType] . '.fields.inc.php';
	}

	$this_fields_list = substr($this_fields_list, 0, -1);
	$survey_fields_name = explode('|', $this_fields_list);
	$i = 0;

	for (; $i < count($survey_fields_name); $i++) {
		$_SESSION[$survey_fields_name[$i]] = '';
		unset($_SESSION[$survey_fields_name[$i]]);
	}
}

function isradioselect($checkID, $questionID)
{
	if (($_SESSION['option_' . $questionID] != '') && ($_SESSION['option_' . $questionID] == $checkID)) {
		return 1;
	}
	else {
		return 0;
	}
}

function isradiounselect($checkID, $questionID)
{
	if (isset($_SESSION['option_' . $questionID])) {
		if ($_SESSION['option_' . $questionID] != $checkID) {
			return 1;
		}
		else {
			return 0;
		}
	}
	else {
		return 0;
	}
}

function ischeckboxselect($checkID, $questionID)
{
	if ($_SESSION['option_' . $questionID] != '') {
		if (is_array($_SESSION['option_' . $questionID])) {
			if (in_array($checkID, $_SESSION['option_' . $questionID])) {
				return 1;
			}
			else {
				return 0;
			}
		}
		else {
			$_obf_HeZlwr2X7nQH7K10bA__ = explode(',', $_SESSION['option_' . $questionID]);

			if (in_array($checkID, $_obf_HeZlwr2X7nQH7K10bA__)) {
				return 1;
			}
			else {
				return 0;
			}
		}
	}
	else {
		return 0;
	}
}

function ischeckboxunselect($checkID, $questionID)
{
	if (isset($_SESSION['option_' . $questionID])) {
		if (is_array($_SESSION['option_' . $questionID])) {
			if (in_array($checkID, $_SESSION['option_' . $questionID])) {
				return 0;
			}
			else {
				return 1;
			}
		}
		else {
			$_obf_HeZlwr2X7nQH7K10bA__ = explode(',', $_SESSION['option_' . $questionID]);

			if (in_array($checkID, $_obf_HeZlwr2X7nQH7K10bA__)) {
				return 0;
			}
			else {
				return 1;
			}
		}
	}
	else {
		return 0;
	}
}

function getcheckboxselnum($filedName, $opertion, $checkID)
{
	$_obf_fVE9owv_EIg_ = count($_SESSION[$filedName]);

	if (runcode($_obf_fVE9owv_EIg_ . $opertion . $checkID)) {
		return 1;
	}
	else {
		return 0;
	}
}

function isnumbertextlogictrue($filedName, $opertion, $checkID, $weight = 1, $isSkipUnkown = 0, $nullType = 1)
{
	if ($nullType == 1) {
		if (!isset($_SESSION[$filedName]) || ($_SESSION[$filedName] == '')) {
			return 0;
		}
	}
	else {
		if (!isset($_SESSION[$filedName]) || ($_SESSION[$filedName] == '') || ($_SESSION[$filedName] == '0')) {
			return 0;
		}
	}

	if (($isSkipUnkown == 1) && ($_SESSION[$filedName] == '99')) {
		return 0;
	}

	$_obf_fVE9owv_EIg_ = $_SESSION[$filedName] * $weight;

	if (runcode($_obf_fVE9owv_EIg_ . $opertion . $checkID)) {
		return 1;
	}
	else {
		return 0;
	}
}

function runcode($code)
{
	if ($code) {
		ob_start();
		eval ('echo ' . $code . ';');
		$contents = ob_get_contents();
		ob_end_clean();
	}

	return $contents;
}

function qshowqtnname($qtnName, $questionID, $inType = 1)
{
	global $check_survey_conditions_list;
	global $QtnListArray;
	global $RadioListArray;
	global $YesNoListArray;
	global $CheckBoxListArray;
	global $OptionListArray;

	if (preg_match_all('/\\[Answer_[^\\]]*\\]/si', $qtnName, $Matches, PREG_SET_ORDER)) {
		$theQtnName = $qtnName;

		foreach ($Matches as $Value) {
			$theBaseString = str_replace('[', '', str_replace(']', '', $Value[0]));
			$theQtnArray = explode('_', $theBaseString);
			$theQtnID = $theQtnArray[1];

			if (!in_array($QtnListArray[$theQtnID]['questionType'], array(2, 3, 4, 17, 18, 23, 24, 25))) {
				$theQtnName = str_replace($Value[0], '', $theQtnName);
			}
			else {
				switch ($inType) {
				case 1:
					$nodeName = 'node_';
					break;

				case 2:
					$nodeName = 'tips_';
					break;
				}

				if (isSamePage($theQtnID, $questionID)) {
					switch ($QtnListArray[$theQtnID]['questionType']) {
					case '2':
						if (count($theQtnArray) == 2) {
							if ($QtnListArray[$theQtnID]['isSelect'] == 1) {
								$check_survey_conditions_list .= '	getSelectOptionText(\'option_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
							}
							else {
								$check_survey_conditions_list .= '	getRadioOptionText(\'option_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',' . $QtnListArray[$theQtnID]['questionType'] . ',' . $inType . ');' . "\n" . '';
							}

							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '></span>', $theQtnName);
						}
						else if ($theQtnArray[2] != 0) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',0' . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '_0></span>', $theQtnName);
						}

						break;

					case '3':
						if (count($theQtnArray) != 3) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else if ($theQtnArray[2] != 0) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',0' . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '_0></span>', $theQtnName);
						}

						break;

					case '24':
						if (count($theQtnArray) == 2) {
							$check_survey_conditions_list .= '	getRadioOptionText(\'option_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',' . $QtnListArray[$theQtnID]['questionType'] . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '></span>', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $theQtnID . '_' . $theQtnArray[2] . '\',' . $theQtnID . ',' . $questionID . ',' . $theQtnArray[2] . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '_' . $theQtnArray[2] . '></span>', $theQtnName);
						}

						break;

					case '23':
						if (count($theQtnArray) != 3) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getRangeInputTextValue(\'option_' . $theQtnID . '_' . $theQtnArray[2] . '\',' . $theQtnID . ',' . $questionID . ',' . $theQtnArray[2] . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '_' . $theQtnArray[2] . '></span>', $theQtnName);
						}

						break;

					case '25':
						if (count($theQtnArray) != 3) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $theQtnID . '_' . $theQtnArray[2] . '\',' . $theQtnID . ',' . $questionID . ',' . $theQtnArray[2] . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '_' . $theQtnArray[2] . '></span>', $theQtnName);
						}

						break;

					case '17':
						if ($QtnListArray[$theQtnID]['isSelect'] != 1) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getAutoOptionText(\'option_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '></span>', $theQtnName);
						}

						break;

					case '18':
						if ($QtnListArray[$theQtnID]['isSelect'] == 1) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$check_survey_conditions_list .= '	getSelectOptionText(\'option_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
							$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '></span>', $theQtnName);
						}

						break;

					case '4':
						$check_survey_conditions_list .= '	getInputTextValue(\'option_' . $theQtnID . '\',' . $theQtnID . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
						$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '></span>', $theQtnName);
						break;
					}
				}
				else {
					switch ($QtnListArray[$theQtnID]['questionType']) {
					case '2':
						if (count($theQtnArray) == 2) {
							if (!isset($_SESSION['option_' . $theQtnID]) || ($_SESSION['option_' . $theQtnID] == '')) {
								$theQtnName = str_replace($Value[0], '', $theQtnName);
							}
							else {
								if (($_SESSION['option_' . $theQtnID] == '0') && ($_SESSION['TextOtherValue_' . $theQtnID] != '')) {
									$theQtnName = str_replace($Value[0], qshowquotechar($QtnListArray[$theQtnID]['otherText']), $theQtnName);
								}
								else {
									$theQtnName = str_replace($Value[0], qshowquotechar($RadioListArray[$theQtnID][$_SESSION['option_' . $theQtnID]]['optionName']), $theQtnName);
								}
							}
						}
						else if ($theQtnArray[2] != 0) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							if (($_SESSION['option_' . $theQtnID] == '0') && ($_SESSION['TextOtherValue_' . $theQtnID] != '')) {
								$theQtnName = str_replace($Value[0], qhtmlspecialchars($_SESSION['TextOtherValue_' . $theQtnID]), $theQtnName);
							}
							else {
								$theQtnName = str_replace($Value[0], '', $theQtnName);
							}
						}

						break;

					case '3':
						if (count($theQtnArray) != 3) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else if ($theQtnArray[2] != 0) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$theQtnName = str_replace($Value[0], qhtmlspecialchars($_SESSION['TextOtherValue_' . $theQtnID]), $theQtnName);
						}

						break;

					case '23':
						if (count($theQtnArray) != 3) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$theQtnName = str_replace($Value[0], qhtmlspecialchars($_SESSION['option_' . $theQtnID . '_' . $theQtnArray[2]]), $theQtnName);
						}

						break;

					case '4':
						$theQtnName = str_replace($Value[0], qhtmlspecialchars($_SESSION['option_' . $theQtnID]), $theQtnName);
						break;

					case '24':
						if (count($theQtnArray) == 2) {
							if (!isset($_SESSION['option_' . $theQtnID]) || ($_SESSION['option_' . $theQtnID] == '')) {
								$theQtnName = str_replace($Value[0], '', $theQtnName);
							}
							else {
								$theQtnName = str_replace($Value[0], qshowquotechar($RadioListArray[$theQtnID][$_SESSION['option_' . $theQtnID]]['optionName']), $theQtnName);
							}
						}
						else {
							$theQtnName = str_replace($Value[0], qhtmlspecialchars($_SESSION['TextOtherValue_' . $theQtnID . '_' . $theQtnArray[2]]), $theQtnName);
						}

						break;

					case '25':
						if (count($theQtnArray) != 3) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$theQtnName = str_replace($Value[0], qhtmlspecialchars($_SESSION['TextOtherValue_' . $theQtnID . '_' . $theQtnArray[2]]), $theQtnName);
						}

						break;

					case '17':
						if ($QtnListArray[$theQtnID]['isSelect'] != 1) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							$theBaseID = $QtnListArray[$theQtnID]['baseID'];
							$theBaseQtnArray = $QtnListArray[$theBaseID];
							$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];
							if (!isset($_SESSION['option_' . $theQtnID]) || ($_SESSION['option_' . $theQtnID] == '')) {
								$theQtnName = str_replace($Value[0], '', $theQtnName);
							}
							else if ($_SESSION['option_' . $theQtnID] == '0') {
								$theQtnName = str_replace($Value[0], qshowquotechar($QtnListArray[$theBaseID]['otherText']), $theQtnName);
							}
							else {
								$theQtnName = str_replace($Value[0], qshowquotechar($theCheckBoxListArray[$_SESSION['option_' . $theQtnID]]['optionName']), $theQtnName);
							}
						}

						break;

					case '18':
						if ($QtnListArray[$theQtnID]['isSelect'] == 1) {
							$theQtnName = str_replace($Value[0], '', $theQtnName);
						}
						else {
							if (!isset($_SESSION['option_' . $theQtnID]) || ($_SESSION['option_' . $theQtnID] == '')) {
								$theQtnName = str_replace($Value[0], '', $theQtnName);
							}
							else {
								$theQtnName = str_replace($Value[0], qshowquotechar($YesNoListArray[$theQtnID][$_SESSION['option_' . $theQtnID]]['optionName']), $theQtnName);
							}
						}

						break;
					}
				}
			}
		}

		return $theQtnName;
	}
	else if (preg_match_all('/\\[Kish_[^\\]]*\\]/si', $qtnName, $Matches, PREG_SET_ORDER)) {
		$theQtnName = $qtnName;

		foreach ($Matches as $Value) {
			$theBaseString = str_replace('[', '', str_replace(']', '', $Value[0]));
			$theQtnArray = explode('_', $theBaseString);
			$theQtnID = $theQtnArray[1];

			if (!in_array($QtnListArray[$theQtnID]['questionType'], array(23, 27))) {
				$theQtnName = str_replace($Value[0], '', $theQtnName);
			}
			else if (isSamePage($theQtnID, $questionID)) {
				switch ($inType) {
				case 1:
					$nodeName = 'knode_';
					break;

				case 2:
					$nodeName = 'ktips_';
					break;
				}

				switch ($QtnListArray[$theQtnID]['questionType']) {
				case '23':
					$objField = '';

					foreach ($YesNoListArray[$theQtnID] as $question_yesnoID => $theQuestionArray) {
						$objField .= $question_yesnoID . ',';
					}

					$check_survey_conditions_list .= '	getCombTextRandText(\'' . substr($objField, 0, -1) . '\',' . $theQtnID . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
					$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '></span>', $theQtnName);
					break;

				case '27':
					$objField = '';

					foreach ($OptionListArray[$theQtnID] as $question_range_optionID => $theQuestionArray) {
						$objField .= $question_range_optionID . ',';
					}

					$check_survey_conditions_list .= '	getMultipleTextRandText(\'' . substr($objField, 0, -1) . '\',' . $theQtnID . ',' . $questionID . ',' . $theQtnArray[2] . ',' . $inType . ');' . "\n" . '';
					$theQtnName = str_replace($Value[0], '<span class=' . $nodeName . $questionID . '_' . $theQtnID . '_' . $theQtnArray[2] . '></span>', $theQtnName);
					break;
				}
			}
			else {
				switch ($QtnListArray[$theQtnID]['questionType']) {
				case '23':
					$theExistValueArray = array();

					foreach ($YesNoListArray[$theQtnID] as $question_yesnoID => $theQuestionArray) {
						$theOptionID = 'option_' . $theQtnID . '_' . $question_yesnoID;
						if (isset($_SESSION[$theOptionID]) && ($_SESSION[$theOptionID] != '')) {
							$theExistValueArray[] = $_SESSION[$theOptionID];
						}
					}

					if (count($theExistValueArray) == 0) {
						$theQtnName = str_replace($Value[0], '', $theQtnName);
					}
					else {
						$theAfterRandArray = php_array_rand($theExistValueArray);
						$theQtnName = str_replace($Value[0], qshowquotechar($theExistValueArray[$theAfterRandArray[0]]), $theQtnName);
					}

					unset($theExistValueArray);
					break;

				case '27':
					$theExistValueArray = array();

					if (count($theQtnArray) != 3) {
						$theQtnName = str_replace($Value[0], '', $theQtnName);
					}
					else {
						foreach ($OptionListArray[$theQtnID] as $question_range_optionID => $theQuestionArray) {
							$theOptionID = 'option_' . $theQtnID . '_' . $question_range_optionID . '_' . $theQtnArray[2];
							if (isset($_SESSION[$theOptionID]) && ($_SESSION[$theOptionID] != '')) {
								$theExistValueArray[] = $_SESSION[$theOptionID];
							}
						}
					}

					if (count($theExistValueArray) == 0) {
						$theQtnName = str_replace($Value[0], '', $theQtnName);
					}
					else {
						$theAfterRandArray = php_array_rand($theExistValueArray);
						$theQtnName = str_replace($Value[0], qshowquotechar($theExistValueArray[$theAfterRandArray[0]]), $theQtnName);
					}

					unset($theExistValueArray);
					break;
				}
			}
		}

		return $theQtnName;
	}
	else {
		return $qtnName;
	}
}

function php_array_rand($array, $arrayNum = 0)
{
	$_obf_f517kg__ = array_keys($array);
	shuffle($_obf_f517kg__);

	if ($arrayNum != 0) {
		$_obf_XDUrKDvrl_Y_ = array_slice($_obf_f517kg__, 0, $arrayNum);
		return $_obf_XDUrKDvrl_Y_;
	}
	else {
		return $_obf_f517kg__;
	}
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

include_once ROOT_PATH . 'Functions/Functions.string.inc.php';
include_once ROOT_PATH . 'Functions/Functions.array.inc.php';
include_once ROOT_PATH . 'Functions/Functions.ass.inc.php';

?>
