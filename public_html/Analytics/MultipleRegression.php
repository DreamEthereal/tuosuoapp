<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
include_once ROOT_PATH . 'Lang/CN/Lang.mgt.inc.php';
include_once ROOT_PATH . 'Functions/Functions.utilities.inc.php';
include_once ROOT_PATH . 'Functions/Functions.dir.inc.php';
include_once ROOT_PATH . 'Functions/Functions.common.inc.php';
include_once ROOT_PATH . 'Functions/Functions.mgt.inc.php';
$_GET['surveyID'] = (int) $_GET['surveyID'];
$EnableQCoreClass->replace('surveyID', $_GET['surveyID']);
$EnableQCoreClass->replace('surveyTitle', $_GET['surveyTitle']);
$EnableQCoreClass->replace('survey_URLTitle', urlencode($_GET['surveyTitle']));

if ($_POST['Action'] == 'LinearRegressionSubmit') {
	@set_time_limit(0);
	header('Content-Type:text/html; charset=gbk');
	$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_POST['surveyID'] . '\' ';
	$Row = $DB->queryFirstRow($SQL);
	$theSID = $Row['surveyID'];
	if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
		require ROOT_PATH . 'Includes/MakeCache.php';
	}

	require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';
	$dataSource = getdatasourcesql($_POST['dataSource'], $_POST['surveyID']);

	if (isset($_POST['dataSource'])) {
		$_SESSION['dataSource' . $_POST['surveyID']] = $_POST['dataSource'];
	}

	$theXQuestionIDArray = explode(',', $_POST['questionID1']);
	$theXFields = $theMissingXFields = $theXQuestionIDListArray = $theXQtnTypeList = array();
	$temp = 0;

	foreach ($theXQuestionIDArray as $theXQuestionIDList) {
		$theXQuestionIDItem = explode('*', $theXQuestionIDList);
		$theXQuestionID = $theXQuestionIDItem[0];
		$theXQuestionIDListArray[$temp] = $theXQuestionID;
		$theXOptionID = $theXQuestionIDItem[1];
		$theXLabelID = $theXQuestionIDItem[2];
		$theXQtnType = $QtnListArray[$theXQuestionID]['questionType'];
		$theXQtnTypeList[$temp] = $theXQtnType;

		switch ($theXQtnType) {
		case '2':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			if (($QtnListArray[$theXQuestionID]['isSelect'] != '1') && ($QtnListArray[$theXQuestionID]['isHaveOther'] == '1')) {
				if ($QtnListArray[$theXQuestionID]['isUnkown'] == 1) {
					$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
				}
				else {
					$theMissingXFieldsTemp = ' ( option_' . $theXQuestionID . ' != 0  OR (option_' . $theXQuestionID . ' = 0 AND TextOtherValue_' . $theXQuestionID . ' != \'\')) ';
				}
			}
			else {
				$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
			}

			$isUnkown = array();

			foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_radioID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '24':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != 0 ';
			$isUnkown = array();

			foreach ($RadioListArray[$theXQuestionID] as $question_radioID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_radioID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '4':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFields[$temp] = ' option_' . $theXQuestionID . ' != \'\' ';
			break;

		case '18':
			$theXFields[$temp] = 'option_' . $theXQuestionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . ' != \'\' ';
			$isUnkown = array();

			foreach ($YesNoListArray[$theXQuestionID] as $question_yesnoID => $theQuestionArray) {
				if ($theQuestionArray['isUnkown'] == 1) {
					$isUnkown[] = $question_yesnoID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '23':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'\' ';
			break;

		case '6':
		case '19':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
			$isUnkown = array();

			foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
				if ($theAnswerArray['isUnkown'] == 1) {
					$isUnkown[] = $question_range_answerID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . '_' . $theXOptionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;

		case '10':
		case '20':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
			break;

		case '16':
		case '22':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';
			$theMissingXFields[$temp] = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'0.00\') ';
			break;

		case '15':
		case '21':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' ';

			switch ($QtnListArray[$theXQuestionID]['isSelect']) {
			case '0':
				$theMissingXFields[$temp] = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != 99) ';
				break;

			case '1':
				$theMissingXFields[$temp] = ' ( option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 AND option_' . $theXQuestionID . '_' . $theXOptionID . ' != \'0.00\') ';
				break;

			case '2':
				$theMissingXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . ' != 0 ';
				break;
			}

			break;

		case '26':
			$theXFields[$temp] = ' option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' ';
			$theMissingXFieldsTemp = ' option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' != 0 ';
			$isUnkown = array();

			foreach ($AnswerListArray[$theXQuestionID] as $question_range_answerID => $theAnswerArray) {
				if ($theAnswerArray['isUnkown'] == 1) {
					$isUnkown[] = $question_range_answerID;
				}
			}

			if (count($isUnkown) != 0) {
				$theMissingXFieldsTemp .= ' and option_' . $theXQuestionID . '_' . $theXOptionID . '_' . $theXLabelID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
			}

			unset($isUnkown);
			$theMissingXFields[$temp] = $theMissingXFieldsTemp;
			break;
		}

		$temp++;
	}

	$theYQuestionIDArray = explode('*', $_POST['questionID2']);
	$theYQuestionID = $theYQuestionIDArray[0];
	$theYOptionID = $theYQuestionIDArray[1];
	$theYLabelID = $theYQuestionIDArray[2];
	$theYQtnType = $QtnListArray[$theYQuestionID]['questionType'];

	switch ($theYQtnType) {
	case '2':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		if (($QtnListArray[$theYQuestionID]['isSelect'] != '1') && ($QtnListArray[$theYQuestionID]['isHaveOther'] == '1')) {
			if ($QtnListArray[$theYQuestionID]['isUnkown'] == 1) {
				$theMissingYFields = ' option_' . $theYQuestionID . ' != 0 ';
			}
			else {
				$theMissingYFields = ' ( option_' . $theYQuestionID . ' != 0  OR (option_' . $theYQuestionID . ' = 0 AND TextOtherValue_' . $theYQuestionID . ' != \'\')) ';
			}
		}
		else {
			$theMissingYFields = ' option_' . $theYQuestionID . ' != 0 ';
		}

		$isUnkown = array();

		foreach ($RadioListArray[$theYQuestionID] as $question_radioID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_radioID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '24':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . ' != 0 ';
		$isUnkown = array();

		foreach ($RadioListArray[$theYQuestionID] as $question_radioID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_radioID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '4':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . ' != \'\' ';
		break;

	case '18':
		$theYFields = 'option_' . $theYQuestionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . ' != \'\' ';
		$isUnkown = array();

		foreach ($YesNoListArray[$theYQuestionID] as $question_yesnoID => $theQuestionArray) {
			if ($theQuestionArray['isUnkown'] == 1) {
				$isUnkown[] = $question_yesnoID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '23':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != \'\' ';
		break;

	case '6':
	case '19':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 ';
		$isUnkown = array();

		foreach ($AnswerListArray[$theYQuestionID] as $question_range_answerID => $theAnswerArray) {
			if ($theAnswerArray['isUnkown'] == 1) {
				$isUnkown[] = $question_range_answerID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . '_' . $theYOptionID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;

	case '10':
	case '20':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 ';
		break;

	case '16':
	case '22':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';
		$theMissingYFields = ' ( option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 AND option_' . $theYQuestionID . '_' . $theYOptionID . ' != \'0.00\') ';
		break;

	case '15':
	case '21':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' as theYFields ';

		switch ($QtnListArray[$theYQuestionID]['isSelect']) {
		case '0':
			$theMissingYFields = ' ( option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 AND option_' . $theYQuestionID . '_' . $theYOptionID . ' != 99) ';
			break;

		case '1':
			$theMissingYFields = ' ( option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 AND option_' . $theYQuestionID . '_' . $theYOptionID . ' != \'0.00\') ';
			break;

		case '2':
			$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . ' != 0 ';
			break;
		}

		break;

	case '26':
		$theYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . '_' . $theYLabelID . ' as theYFields ';
		$theMissingYFields = ' option_' . $theYQuestionID . '_' . $theYOptionID . '_' . $theYLabelID . ' != 0 ';
		$isUnkown = array();

		foreach ($AnswerListArray[$theYQuestionID] as $question_range_answerID => $theAnswerArray) {
			if ($theAnswerArray['isUnkown'] == 1) {
				$isUnkown[] = $question_range_answerID;
			}
		}

		if (count($isUnkown) != 0) {
			$theMissingYFields .= ' and option_' . $theYQuestionID . '_' . $theYOptionID . '_' . $theYLabelID . ' NOT IN (' . implode(',', $isUnkown) . ') ';
		}

		unset($isUnkown);
		break;
	}

	$theXValue = array();
	$theYValue = array();
	$theXFieldsList = implode(',', $theXFields);
	$theMissingXFieldsList = implode(' AND ', $theMissingXFields);
	$SQL = ' SELECT ' . $theXFieldsList . ',' . $theYFields . ' FROM ' . $table_prefix . 'response_' . $_POST['surveyID'] . ' b WHERE ' . $theMissingXFieldsList . ' AND ' . $theMissingYFields . ' and ' . $dataSource;
	$Result = $DB->query($SQL);
	$o = 0;

	while ($Row = $DB->queryArray($Result)) {
		$label = 0;

		foreach ($theXQuestionIDListArray as $theXQuestionID) {
			$theXValue[$o][0] = 1;
			$p = $label + 1;
			$theXFieldItem = trim($theXFields[$label]);

			switch ($theXQtnTypeList[$label]) {
			case '2':
				if ($Row[$theXFieldItem] == 0) {
					$theXValue[$o][$p] = $QtnListArray[$theXQuestionID]['otherCode'];
				}
				else {
					$theXValue[$o][$p] = $RadioListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				}

				break;

			case '24':
				$theXValue[$o][$p] = $RadioListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;

			case '18':
				$theXValue[$o][$p] = $YesNoListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;

			case '6':
			case '19':
				$theXValue[$o][$p] = $AnswerListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;

			case '4':
			case '10':
			case '16':
			case '20':
			case '22':
			case '15':
			case '21':
			case '23':
				$theXValue[$o][$p] = $Row[$theXFieldItem];
				break;

			case '26':
				$theXValue[$o][$p] = $AnswerListArray[$theXQuestionID][$Row[$theXFieldItem]]['itemCode'];
				break;
			}

			$label++;
		}

		switch ($theYQtnType) {
		case '2':
			if ($Row['theYFields'] == 0) {
				$theYValue[$o] = $QtnListArray[$theYQuestionID]['otherCode'];
			}
			else {
				$theYValue[$o] = $RadioListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			}

			break;

		case '24':
			$theYValue[$o] = $RadioListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;

		case '18':
			$theYValue[$o] = $YesNoListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;

		case '6':
		case '19':
			$theYValue[$o] = $AnswerListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;

		case '4':
		case '10':
		case '16':
		case '20':
		case '22':
		case '15':
		case '21':
		case '23':
			$theYValue[$o] = $Row['theYFields'];
			break;

		case '26':
			$theYValue[$o] = $AnswerListArray[$theYQuestionID][$Row['theYFields']]['itemCode'];
			break;
		}

		$o++;
	}

	unset($theXFields);
	unset($theMissingXFields);
	unset($theXQuestionIDListArray);
	require_once '../JAMA/Matrix.php';
	require_once '../PDL/FDistribution.php';
	require_once '../PDL/TDistribution.php';
	$X = new Matrix($theXValue);
	$nRows = count($theXValue);
	$nCols = count($theXValue[0]);
	$Y = new Matrix($theYValue, $nRows);
	$Xt = $X->transpose();
	$XtX = $Xt->times($X);
	$XtY = $Xt->times($Y);
	$XtXi = $XtX->inverse();
	$b = $XtXi->times($XtY);
	$Yp = $X->times($b);
	$e = $Yp->minus($Y);
	$Yt = $Y->transpose();
	$Ytn = $Yt->times(1 / $nRows);
	$YtY = $Yt->times($Y);
	$J = new Matrix(array_fill(0, $nRows, array_fill(0, $nRows, 1)));
	$YtnJ = $Ytn->times($J);
	$YtnJY = $YtnJ->times($Y);
	$SSTO = $YtnJY->minus($YtY);
	$bt = $b->transpose();
	$btXt = $bt->times($Xt);
	$btXtY = $btXt->times($Y);
	$SSE = $btXtY->minus($YtY);
	$SSR = $SSE->minus($SSTO);
	$dfR = $nCols - 1;
	$dfE = $nRows - $nCols;
	$dfTO = $nRows - 1;
	$MSR = $SSR->A[0][0] / $dfR;
	$MSE = $SSE->A[0][0] / $dfE;
	$SSB = $XtXi->times($MSE);
	$FObs = $MSR / $MSE;
	$FCrit = 1 - ($_POST['confidence'] / 100);
	$F = new FDistribution($dfR, $dfE);
	$FProb = 1 - $F->CDF($FObs);

	if ($FProb == 0) {
		$FStat = 'p < .0000*';
	}
	else if ($TProb[$i] < 1.0E-5) {
		$FStat = 'p < .0000*';
	}
	else if ($FProb < $FCrit) {
		$FStat = 'p = ' . sprintf('%.4f', $FProb) . '*';
	}
	else {
		$FStat = 'p = ' . sprintf('%.4f', $FProb);
	}

	$TCrit = 0.05;
	$T = new TDistribution($dfE);
	$i = 0;

	for (; $i < $nCols; $i++) {
		$TObs[$i] = $b->A[$i][0] / sqrt($SSB->A[$i][$i]);
		$TProb[$i] = 2 * (1 - $T->CDF($TObs[$i]));

		if ($TProb[$i] == 0) {
			$TStat[$i] = 'p < .0000*';
		}
		else if ($TProb[$i] < 1.0E-5) {
			$TStat[$i] = 'p < .0000*';
		}
		else if ($TProb[$i] < $TCrit) {
			$TStat[$i] = 'p = ' . sprintf('%.4f', $TProb[$i]) . '*';
		}
		else {
			$TStat[$i] = 'p = ' . sprintf('%.4f', $TProb[$i]);
		}
	}

	echo '' . "\r\n" . '    <table style="LINE-HEIGHT: 150%;border-collapse:collapse;" width=610 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '    <tr bgcolor=#e5e5e5>' . "\r\n" . '        <td colspan=\'6\' align=center style="font-size:14px"><b>ANOVA(方差分析表)</b></td>' . "\r\n" . '    </tr>' . "\r\n" . '	<tr>' . "\r\n" . '        <td align=\'center\'><b>变异来源</b></td>' . "\r\n" . '        <td align=\'center\'><b>平方和</b></td>' . "\r\n" . '        <td align=\'center\'><b>自由度</b></td>' . "\r\n" . '        <td align=\'center\'><b>均方</b></td>' . "\r\n" . '        <td align=\'center\'><b>F</b></td>' . "\r\n" . '        <td align=\'center\'><b>F Stat</b></td>' . "\r\n" . '	</tr>' . "\r\n" . '	<tr>' . "\r\n" . '	  <td>回归</td>' . "\r\n" . '	  <td>SSR = ';
	printf('%.2f', $SSR->A[0][0]);
	echo '</td>' . "\r\n" . '	  <td>dfR = ';
	echo $dfR;
	echo '</td>' . "\r\n" . '	  <td>MSR = ';
	printf('%.2f', $MSR);
	echo '</td>' . "\r\n" . '	  <td>MSR/MSE = ';
	printf('%.2f', $FObs);
	echo '</td>  ' . "\r\n" . '	  <td align=\'center\'>';
	echo $FStat;
	echo '</td>    ' . "\r\n" . '	</tr>' . "\r\n" . '	<tr>' . "\r\n" . '	  <td>误差</td>' . "\r\n" . '	  <td>SSE = ';
	printf('%.2f', $SSE->A[0][0]);
	echo '</td>' . "\r\n" . '	  <td>dfE = ';
	echo $dfE;
	echo '</td>' . "\r\n" . '	  <td>MSE = ';
	printf('%.2f', $MSE);
	echo '</td>' . "\r\n" . '	  <td>&nbsp;</td>  ' . "\r\n" . '	  <td>&nbsp;</td>    ' . "\r\n" . '	</tr>' . "\r\n" . '	<tr>' . "\r\n" . '	  <td>合计</td>' . "\r\n" . '	  <td>SSTO = ';
	printf('%.2f', $SSTO->A[0][0]);
	echo '</td>' . "\r\n" . '	  <td>dfTO = ';
	echo $dfTO;
	echo '</td>' . "\r\n" . '	  <td>&nbsp;</td>' . "\r\n" . '	  <td>&nbsp;</td>  ' . "\r\n" . '	  <td>&nbsp;</td>    ' . "\r\n" . '	</tr>' . "\r\n" . '	<tr><td colspan=6>注：*指显著</td></tr> ' . "\r\n" . '	</table>' . "\r\n" . '' . "\r\n" . '   <table style="LINE-HEIGHT: 150%;border-collapse:collapse;margin-top:5px;" width=610 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '    <tr bgcolor=#e5e5e5>' . "\r\n" . '        <td colspan=\'5\' align=center style="font-size:14px"><b>回归系数</b></td>' . "\r\n" . '    </tr>' . "\r\n" . '	<tr>' . "\r\n" . '        <td align=\'center\'><b>参数</b></td>' . "\r\n" . '        <td align=\'center\'><b>预测值</b></td>' . "\r\n" . '        <td align=\'center\'><b>标准误差</b></td>' . "\r\n" . '        <td align=\'center\'><b>T</b></td>' . "\r\n" . '        <td align=\'center\'><b>T Stat</b></td>' . "\r\n" . '	</tr>' . "\r\n" . '	';
	$k = 0;

	for (; $k < count($b->A); $k++) {
		echo '	<tr>' . "\r\n" . '	  ';

		if ($k == 0) {
			echo '<td align=center><b>常数</b></td>';
		}
		else {
			echo '<td align=center><b>X<sub>' . $k . '</sub></b></td>';
		}

		echo '	  <td>b<sub>';
		echo $k;
		echo '</sub>=';
		printf('%.4f', $b->A[$k][0]);
		echo '</td>' . "\r\n" . '	  <td>s{b<sub>';
		echo $k;
		echo '</sub>}=';
		printf('%.4f', sqrt($SSB->A[$k][$k]));
		echo '</td>' . "\r\n" . '	  <td>t<sub>';
		echo $k;
		echo '</sub>=';
		printf('%.2f', $TObs[$k]);
		echo '</td>  ' . "\r\n" . '	  <td align=\'center\'>';
		echo $TStat[$k];
		echo '</td>' . "\r\n" . '	</tr>' . "\r\n" . '	';
	}

	echo '	<tr><td colspan=5>注：*指显著</td></tr> ' . "\r\n" . '	</table>' . "\r\n" . '    <table style="LINE-HEIGHT: 150%;border-collapse:collapse;margin-top:5px;" width=610 cellSpacing=0 cellPadding=0 borderColor=#d5d5d5 border=1>' . "\r\n" . '    <tr bgcolor=#e5e5e5>' . "\r\n" . '        <td colspan=\'4\' align=center style="font-size:14px"><b>回代检验</b></td>' . "\r\n" . '    </tr>' . "\r\n" . '	<tr>' . "\r\n" . '        <td align=\'center\'><b>观察</b></td>' . "\r\n" . '        <td align=\'center\'><b>观察值 Y<sub>i</sub></b></td>' . "\r\n" . '        <td align=\'center\'><b>预测值 Y<sub>i</sub></b></td>' . "\r\n" . '        <td align=\'center\'><b>剩余 e<sub>i</sub></b></td>' . "\r\n" . '	</tr>' . "\r\n" . '	';
	$i = 0;

	for (; $i < $nRows; $i++) {
		echo '	  <tr>' . "\r\n" . '		<td align=\'center\'>';
		echo $i + 1;
		echo '</td>' . "\r\n" . '		<td align=\'center\'>';
		printf('%.2f', $Y->A[$i][0]);
		echo '</td>' . "\r\n" . '		<td align=\'center\'>';
		printf('%.2f', $Yp->A[$i][0]);
		echo '</td>' . "\r\n" . '		<td align=\'center\'>';
		printf('%.2f', $e->A[$i][0]);
		echo '</td>' . "\r\n" . '	  </tr>            ' . "\r\n" . '	  ';
	}

	echo '	</table>' . "\r\n" . '	';
	unset($theXValue);
	unset($theYValue);
	exit();
}

$SQL = ' SELECT surveyTitle,isCache,surveyID FROM ' . SURVEY_TABLE . ' WHERE surveyID = \'' . $_GET['surveyID'] . '\' ';
$Row = $DB->queryFirstRow($SQL);
$theSID = $Row['surveyID'];
if (($Row['isCache'] == 1) || !file_exists(ROOT_PATH . $Config['cacheDirectory'] . '/' . $Row['surveyID'] . '/' . md5('Qtn' . $Row['surveyID']) . '.php')) {
	require ROOT_PATH . 'Includes/MakeCache.php';
}

require ROOT_PATH . $Config['cacheDirectory'] . '/' . $theSID . '/' . md5('Qtn' . $theSID) . '.php';

if (!isset($_SESSION['haveCheckValidate'])) {
	require_once ROOT_PATH . 'System/AjaxCheckValidate.php';

	if ($RE29199C6C5DC97F47564201E7E599AC9 != 1) {
		_showerror($lang['RF29199C6C5DC97F47564201E7F579BC5'], $lang['R82353783517DA1951018F2CE07568E40']);
	}

	$_SESSION['haveCheckValidate'] = true;
}

_checkpassport('1|2|3|5|7', $_GET['surveyID']);
$EnableQCoreClass->setTemplateFile('LinearRegressionFile', 'MultipleRegression.html');
$questionList = '';

foreach ($QtnListArray as $questionID => $theQtnArray) {
	switch ($theQtnArray['questionType']) {
	case '2':
	case '24':
		$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		break;

	case '4':
		if ($QtnListArray[$questionID]['isCheckType'] == '4') {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '23':
		foreach ($YesNoListArray[$questionID] as $question_yesnoID => $theQuestionArray) {
			if ($theQuestionArray['isCheckType'] == '4') {
				$questionList .= '<option value=' . $questionID . '*' . $question_yesnoID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;

	case '6':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_range_optionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '10':
	case '15':
	case '16':
		foreach ($RankListArray[$questionID] as $question_rankID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_rankID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '18':
		if ($QtnListArray[$questionID]['isSelect'] != 1) {
			$questionList .= '<option value=' . $questionID . '>' . qnospecialchar($theQtnArray['questionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '19':
	case '20':
	case '21':
	case '22':
		$theBaseID = $QtnListArray[$questionID]['baseID'];
		$theBaseQtnArray = $QtnListArray[$theBaseID];
		$theCheckBoxListArray = $CheckBoxListArray[$theBaseQtnArray['questionID']];

		foreach ($theCheckBoxListArray as $question_checkboxID => $theQuestionArray) {
			$questionList .= '<option value=' . $questionID . '*' . $question_checkboxID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		if ($theBaseQtnArray['isHaveOther'] == '1') {
			$questionList .= '<option value=' . $questionID . '*0>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theBaseQtnArray['otherText']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
		}

		break;

	case '26':
		foreach ($OptionListArray[$questionID] as $question_range_optionID => $theQuestionArray) {
			foreach ($LabelListArray[$questionID] as $question_range_labelID => $theLabelArray) {
				$questionList .= '<option value=' . $questionID . '*' . $question_range_optionID . '*' . $question_range_labelID . '>' . qnospecialchar($theQtnArray['questionName']) . ' - ' . qnospecialchar($theQuestionArray['optionName']) . ' - ' . qnospecialchar($theLabelArray['optionLabel']) . ' (' . $lang['question_type_' . $theQtnArray['questionType']] . ') </option>';
			}
		}

		break;
	}
}

$EnableQCoreClass->replace('questionList1', $questionList);
$EnableQCoreClass->replace('questionList2', $questionList);
$LinearRegressionHTML = $EnableQCoreClass->parse('LinearRegression', 'LinearRegressionFile');
echo $LinearRegressionHTML;
exit();

?>
