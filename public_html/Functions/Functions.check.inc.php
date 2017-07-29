<?php
//dezend by http://www.yunlu99.com/
function checkvaluerelation($surveyID, $questionID, $questionType, $qtnID, $optionID, $labelID, $type = 1)
{
	global $DB;

	switch ($questionType) {
	case '3':
	case '25':
	case '15':
	case '17':
		$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND optionID =\'' . $optionID . '\' ';
		$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
		$_obf_4NdPKJ07tY4Qytes = array();

		while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
			$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
		}

		if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
			$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
			$DB->query($_obf_xCnI);
			$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
			$DB->query($_obf_xCnI);
		}

		$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND optionID =\'' . $optionID . '\' ';
		$DB->query($_obf_xCnI);

		switch ($questionType) {
		case '3':
		case '25':
			$_obf_eSXFZ1ZNB82CCa5d7P8t_WgExGleTRY_ = _getbaseitrelqtnrange($questionID);

			if ($_obf_eSXFZ1ZNB82CCa5d7P8t_WgExGleTRY_ != false) {
				$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID IN (' . $_obf_eSXFZ1ZNB82CCa5d7P8t_WgExGleTRY_ . ') AND qtnID =\'' . $optionID . '\' ';
				$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
				$_obf_4NdPKJ07tY4Qytes = array();

				while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
					$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
				}

				if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
					$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
					$DB->query($_obf_xCnI);
					$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
					$DB->query($_obf_xCnI);
				}

				$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID IN (' . $_obf_eSXFZ1ZNB82CCa5d7P8t_WgExGleTRY_ . ') AND qtnID =\'' . $optionID . '\' ';
				$DB->query($_obf_xCnI);
			}

			$_obf_lvYPJoQHjxpYUcGeNH6gP6v6Jg2WAA__ = _getbaseitrelqtnauto($questionID);

			if ($_obf_lvYPJoQHjxpYUcGeNH6gP6v6Jg2WAA__ != false) {
				$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID IN (' . $_obf_lvYPJoQHjxpYUcGeNH6gP6v6Jg2WAA__ . ') AND optionID =\'' . $optionID . '\' ';
				$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
				$_obf_4NdPKJ07tY4Qytes = array();

				while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
					$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
				}

				if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
					$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
					$DB->query($_obf_xCnI);
					$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
					$DB->query($_obf_xCnI);
				}

				$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID IN (' . $_obf_lvYPJoQHjxpYUcGeNH6gP6v6Jg2WAA__ . ') AND optionID =\'' . $optionID . '\' ';
				$DB->query($_obf_xCnI);
			}

			break;
		}

		break;

	case '6':
		$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND qtnID =\'' . $qtnID . '\' ';
		$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
		$_obf_4NdPKJ07tY4Qytes = array();

		while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
			$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
		}

		if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
			$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
			$DB->query($_obf_xCnI);
			$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
			$DB->query($_obf_xCnI);
		}

		$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND qtnID =\'' . $qtnID . '\' ';
		$DB->query($_obf_xCnI);
		break;

	case '7':
	case '26':
	case '28':
		switch ($type) {
		case '1':
			$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND qtnID =\'' . $qtnID . '\' ';
			$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
			$_obf_4NdPKJ07tY4Qytes = array();

			while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
				$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
			}

			if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
				$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
				$DB->query($_obf_xCnI);
				$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
				$DB->query($_obf_xCnI);
			}

			$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND qtnID =\'' . $qtnID . '\' ';
			$DB->query($_obf_xCnI);
			break;

		case '2':
			$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND optionID =\'' . $optionID . '\' ';
			$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
			$_obf_4NdPKJ07tY4Qytes = array();

			while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
				$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
			}

			if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
				$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
				$DB->query($_obf_xCnI);
				$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
				$DB->query($_obf_xCnI);
			}

			$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $questionID . '\' AND optionID =\'' . $optionID . '\' ';
			$DB->query($_obf_xCnI);
			break;
		}

		break;

	case '18':
		$_obf_OWpxVw__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE questionID=\'' . $questionID . '\' ';
		$_obf_Rb6y1B2rPw__ = $DB->query($_obf_OWpxVw__);
		$_obf_4NdPKJ07tY4Qytes = array();

		while ($_obf__aTmJQ__ = $DB->queryArray($_obf_Rb6y1B2rPw__)) {
			$_obf_4NdPKJ07tY4Qytes[] = $_obf__aTmJQ__['relationID'];
		}

		if (count($_obf_4NdPKJ07tY4Qytes) != 0) {
			$_obf_xCnI = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
			$DB->query($_obf_xCnI);
			$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE relationID IN (' . implode(',', $_obf_4NdPKJ07tY4Qytes) . ') ';
			$DB->query($_obf_xCnI);
		}

		$_obf_xCnI = ' DELETE FROM ' . RELATION_LIST_TABLE . ' WHERE questionID=\'' . $questionID . '\' ';
		$DB->query($_obf_xCnI);
		break;
	}

	$_obf_9wzLMQ__ = ' SELECT relationID FROM ' . RELATION_TABLE . ' WHERE surveyID = \'' . $surveyID . '\' ORDER BY relationID ASC ';
	$_obf_U4oSnJ8L9w__ = $DB->query($_obf_9wzLMQ__);

	while ($_obf_DaEyRg__ = $DB->queryArray($_obf_U4oSnJ8L9w__)) {
		$_obf_vEZqsUc_ = ' SELECT listID FROM ' . RELATION_LIST_TABLE . ' WHERE relationID = \'' . $_obf_DaEyRg__['relationID'] . '\' LIMIT 1 ';
		$_obf_tB2vqEA_ = $DB->queryFirstRow($_obf_vEZqsUc_);

		if (!$_obf_tB2vqEA_) {
			$_obf_5bmY6w__ = ' DELETE FROM ' . RELATION_TABLE . ' WHERE relationID = \'' . $_obf_DaEyRg__['relationID'] . '\' ';
			$DB->query($_obf_5bmY6w__);
		}
	}
}

function _getbaseitrelqtnrange($questionID)
{
	global $DB;
	$_obf_JuYaSU2cf0Xt = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID = \'' . $questionID . '\' AND questionType IN (19,21,22,28) ';
	$_obf_Vb8uRRxvBb7GMtBw = $DB->query($_obf_JuYaSU2cf0Xt);
	$_obf_PbZfMVoIa9XvHv4RWA__ = array();

	while ($_obf_f9JfQvXRnVTA = $DB->queryArray($_obf_Vb8uRRxvBb7GMtBw)) {
		$_obf_PbZfMVoIa9XvHv4RWA__[] = $_obf_f9JfQvXRnVTA['questionID'];
	}

	if (count($_obf_PbZfMVoIa9XvHv4RWA__) == 0) {
		return false;
	}

	return implode(',', $_obf_PbZfMVoIa9XvHv4RWA__);
}

function _getbaseitrelqtnauto($questionID)
{
	global $DB;
	$_obf_JuYaSU2cf0Xt = ' SELECT questionID FROM ' . QUESTION_TABLE . ' WHERE baseID = \'' . $questionID . '\' AND questionType=17 AND isSelect != 1 ';
	$_obf_Vb8uRRxvBb7GMtBw = $DB->query($_obf_JuYaSU2cf0Xt);
	$_obf_PbZfMVoIa9XvHv4RWA__ = array();

	while ($_obf_f9JfQvXRnVTA = $DB->queryArray($_obf_Vb8uRRxvBb7GMtBw)) {
		$_obf_PbZfMVoIa9XvHv4RWA__[] = $_obf_f9JfQvXRnVTA['questionID'];
	}

	if (count($_obf_PbZfMVoIa9XvHv4RWA__) == 0) {
		return false;
	}

	return implode(',', $_obf_PbZfMVoIa9XvHv4RWA__);
}

if (!defined('ROOT_PATH')) {
	exit('EnableQ Security Violation');
}

?>
