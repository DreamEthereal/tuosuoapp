<?php
//dezend by http://www.yunlu99.com/
function isSamePage($baseQtnID, $conQtnID)
{
	return true;
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
							$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '2':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
								$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
							$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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

							$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
								$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == '1') {
							$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '17':
						if ($QtnListArray[$_obf_iguicuzFU_U_]['isSelect'] == 1) {
							if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
								$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
							else {
								$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
							}
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}

						break;

					case '24':
						if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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

							$_obf___CY4VdCRg__ .= '(getCheckBoxSelNum(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else if ($_obf_wCoZV9JhZUYDx8qNn_Y_[1] == 1) {
							$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
							$_obf___CY4VdCRg__ .= '( getID(\'option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . '\') != null && getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') != \'\' && getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') != 99 && parseInt(getRadioCheckBoxValue(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ') * ' . $QtnListArray[$_obf_iguicuzFU_U_]['weight'] . ') ' . $_obf_DEYYWPewU5w_ . ' ' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ')' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
							$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
							$_obf___CY4VdCRg__ .= '( IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInCheckBox(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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
							$_obf___CY4VdCRg__ .= '( IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
						}
						else {
							$_obf___CY4VdCRg__ .= '( !IsInSelectedList(document.Survey_Form.option_' . $_obf_iguicuzFU_U_ . '_' . $_obf__rwP784_ . ',' . $_obf_wCoZV9JhZUYDx8qNn_Y_[0] . ') )' . $_obf_gotbQW_cmpfPqYuUS5HRDQ__;
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

function isradioselect($checkID, $questionID)
{
	if (($_SESSION['option_' . $questionID] != '') && ($_SESSION['option_' . $questionID] == $checkID)) {
		return 1;
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
				return true;
			}
			else {
				return false;
			}
		}
		else {
			$_obf_HeZlwr2X7nQH7K10bA__ = explode(',', $_SESSION['option_' . $questionID]);

			if (in_array($checkID, $_obf_HeZlwr2X7nQH7K10bA__)) {
				return true;
			}
			else {
				return false;
			}
		}
	}
	else {
		return false;
	}
}

function qshowqtnname($qtnName, $questionID, $inType = 1)
{
	global $check_survey_conditions_list;
	global $QtnListArray;
	global $RadioListArray;
	global $YesNoListArray;
	global $CheckBoxListArray;
	global $OptionListArray;

	if (preg_match_all('/\\[Answer_[^\\]]*\\]/si', $qtnName, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_fvhCn51TdKIfNw__ = $qtnName;

		foreach ($_obf_63rLGbiIZg__ as $_obf_PvNuNfs_) {
			$_obf_Jp5fWQAyjE7FkTiO1A__ = str_replace('[', '', str_replace(']', '', $_obf_PvNuNfs_[0]));
			$_obf_uFZJM35XMJTetkA_ = explode('_', $_obf_Jp5fWQAyjE7FkTiO1A__);
			$_obf_3MDEGZq_8RQ_ = $_obf_uFZJM35XMJTetkA_[1];

			if (!in_array($QtnListArray[$_obf_3MDEGZq_8RQ_]['questionType'], array(2, 3, 4, 17, 18, 23, 24, 25))) {
				$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
			}
			else {
				switch ($inType) {
				case 1:
					$_obf_8XOV4EeOyOI_ = 'node_';
					break;

				case 2:
					$_obf_8XOV4EeOyOI_ = 'tips_';
					break;
				}

				switch ($QtnListArray[$_obf_3MDEGZq_8RQ_]['questionType']) {
				case '2':
					if (count($_obf_uFZJM35XMJTetkA_) == 2) {
						if ($QtnListArray[$_obf_3MDEGZq_8RQ_]['isSelect'] == 1) {
							$check_survey_conditions_list .= '	getSelectOptionText(\'option_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
						}
						else {
							$check_survey_conditions_list .= '	getRadioOptionText(\'option_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $QtnListArray[$_obf_3MDEGZq_8RQ_]['questionType'] . ',' . $inType . ');' . "\n" . '';
						}

						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '></span>', $_obf_fvhCn51TdKIfNw__);
					}
					else if ($_obf_uFZJM35XMJTetkA_[2] != 0) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',0' . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '_0></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '3':
					if (count($_obf_uFZJM35XMJTetkA_) != 3) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else if ($_obf_uFZJM35XMJTetkA_[2] != 0) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',0' . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '_0></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '24':
					if (count($_obf_uFZJM35XMJTetkA_) == 2) {
						$check_survey_conditions_list .= '	getRadioOptionText(\'option_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $QtnListArray[$_obf_3MDEGZq_8RQ_]['questionType'] . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '></span>', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $_obf_uFZJM35XMJTetkA_[2] . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '23':
					if (count($_obf_uFZJM35XMJTetkA_) != 3) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getRangeInputTextValue(\'option_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $_obf_uFZJM35XMJTetkA_[2] . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '25':
					if (count($_obf_uFZJM35XMJTetkA_) != 3) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getRangeInputTextValue(\'TextOtherValue_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $_obf_uFZJM35XMJTetkA_[2] . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '17':
					if ($QtnListArray[$_obf_3MDEGZq_8RQ_]['isSelect'] != 1) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getAutoOptionText(\'option_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '18':
					if ($QtnListArray[$_obf_3MDEGZq_8RQ_]['isSelect'] == 1) {
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
					}
					else {
						$check_survey_conditions_list .= '	getSelectOptionText(\'option_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
						$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '></span>', $_obf_fvhCn51TdKIfNw__);
					}

					break;

				case '4':
					$check_survey_conditions_list .= '	getInputTextValue(\'option_' . $_obf_3MDEGZq_8RQ_ . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '></span>', $_obf_fvhCn51TdKIfNw__);
					break;
				}
			}
		}

		return $_obf_fvhCn51TdKIfNw__;
	}
	else if (preg_match_all('/\\[Kish_[^\\]]*\\]/si', $qtnName, $_obf_63rLGbiIZg__, PREG_SET_ORDER)) {
		$_obf_fvhCn51TdKIfNw__ = $qtnName;

		foreach ($_obf_63rLGbiIZg__ as $_obf_PvNuNfs_) {
			$_obf_Jp5fWQAyjE7FkTiO1A__ = str_replace('[', '', str_replace(']', '', $_obf_PvNuNfs_[0]));
			$_obf_uFZJM35XMJTetkA_ = explode('_', $_obf_Jp5fWQAyjE7FkTiO1A__);
			$_obf_3MDEGZq_8RQ_ = $_obf_uFZJM35XMJTetkA_[1];

			if (!in_array($QtnListArray[$_obf_3MDEGZq_8RQ_]['questionType'], array(23, 27))) {
				$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '', $_obf_fvhCn51TdKIfNw__);
			}
			else {
				switch ($inType) {
				case 1:
					$_obf_8XOV4EeOyOI_ = 'knode_';
					break;

				case 2:
					$_obf_8XOV4EeOyOI_ = 'ktips_';
					break;
				}

				switch ($QtnListArray[$_obf_3MDEGZq_8RQ_]['questionType']) {
				case '23':
					$_obf_WD_Y4sTFywQ_ = '';

					foreach ($YesNoListArray[$_obf_3MDEGZq_8RQ_] as $_obf_k8rCMO2BgMIY67hW0IScZg__ => $_obf_HrbCnlTkOe4apA_CPAchEg__) {
						$_obf_WD_Y4sTFywQ_ .= $_obf_k8rCMO2BgMIY67hW0IScZg__ . ',';
					}

					$check_survey_conditions_list .= '	getCombTextRandText(\'' . substr($_obf_WD_Y4sTFywQ_, 0, -1) . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $inType . ');' . "\n" . '';
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '></span>', $_obf_fvhCn51TdKIfNw__);
					break;

				case '27':
					$_obf_WD_Y4sTFywQ_ = '';

					foreach ($OptionListArray[$_obf_3MDEGZq_8RQ_] as $_obf_ufmRWTpc_addZniZv5Poubj4nUUgfNI_ => $_obf_HrbCnlTkOe4apA_CPAchEg__) {
						$_obf_WD_Y4sTFywQ_ .= $_obf_ufmRWTpc_addZniZv5Poubj4nUUgfNI_ . ',';
					}

					$check_survey_conditions_list .= '	getMultipleTextRandText(\'' . substr($_obf_WD_Y4sTFywQ_, 0, -1) . '\',' . $_obf_3MDEGZq_8RQ_ . ',' . $questionID . ',' . $_obf_uFZJM35XMJTetkA_[2] . ',' . $inType . ');' . "\n" . '';
					$_obf_fvhCn51TdKIfNw__ = str_replace($_obf_PvNuNfs_[0], '<span class=' . $_obf_8XOV4EeOyOI_ . $questionID . '_' . $_obf_3MDEGZq_8RQ_ . '_' . $_obf_uFZJM35XMJTetkA_[2] . '></span>', $_obf_fvhCn51TdKIfNw__);
					break;
				}
			}
		}

		return $_obf_fvhCn51TdKIfNw__;
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
include_once ROOT_PATH . 'Functions/Functions.ass.inc.php';

?>
