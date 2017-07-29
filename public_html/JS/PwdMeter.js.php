<?php
//dezend by http://www.yunlu99.com/
if ((ob_get_length() === false) && !ini_get('zlib.output_compression') && (ini_get('output_handler') != 'ob_gzhandler') && (ini_get('output_handler') != 'mb_output_handler')) {
	ob_start('ob_gzhandler');
}

header('Cache-Control: public');
header('Pragma: cache');
$offset = 2592000;
$ExpStr = 'Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
$LmStr = 'Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT';
header($ExpStr);
header($LmStr);
header('Content-Type: text/javascrīpt; charset: UTF-8');
echo '' . "\r\n" . 'String.prototype.strReverse = function() {' . "\r\n" . '	var newstring = "";' . "\r\n" . '	for (var s=0; s < this.length; s++) {' . "\r\n" . '		newstring = this.charAt(s) + newstring;' . "\r\n" . '	}' . "\r\n" . '	return newstring;' . "\r\n" . '};' . "\r\n" . '' . "\r\n" . 'function chkPass(pwd) {' . "\r\n" . '	var oScorebar = document.getElementById("scorebar");' . "\r\n" . '	var oScore = document.getElementById("score");' . "\r\n" . '	var oComplexity = document.getElementById("complexity");' . "\r\n" . '	var nScore = 0;' . "\r\n" . '	var nLength = 0;' . "\r\n" . '	var nAlphaUC = 0;' . "\r\n" . '	var nAlphaLC = 0;' . "\r\n" . '	var nNumber = 0;' . "\r\n" . '	var nSymbol = 0;' . "\r\n" . '	var nMidChar = 0;' . "\r\n" . '	var nRequirements = 0;' . "\r\n" . '	var nAlphasOnly = 0;' . "\r\n" . '	var nNumbersOnly = 0;' . "\r\n" . '	var nRepChar = 0;' . "\r\n" . '	var nConsecAlphaUC = 0;' . "\r\n" . '	var nConsecAlphaLC = 0;' . "\r\n" . '	var nConsecNumber = 0;' . "\r\n" . '	var nConsecSymbol = 0;' . "\r\n" . '	var nConsecCharType = 0;' . "\r\n" . '	var nSeqAlpha = 0;' . "\r\n" . '	var nSeqNumber = 0;' . "\r\n" . '	var nSeqChar = 0;' . "\r\n" . '	var nReqChar = 0;' . "\r\n" . '	var nReqCharType = 3;' . "\r\n" . '	var nMultLength = 4;' . "\r\n" . '	var nMultAlphaUC = 3;' . "\r\n" . '	var nMultAlphaLC = 3;' . "\r\n" . '	var nMultNumber = 4;' . "\r\n" . '	var nMultSymbol = 6;' . "\r\n" . '	var nMultMidChar = 2;' . "\r\n" . '	var nMultRequirements = 2;' . "\r\n" . '	var nMultRepChar = 1;' . "\r\n" . '	var nMultConsecAlphaUC = 2;' . "\r\n" . '	var nMultConsecAlphaLC = 2;' . "\r\n" . '	var nMultConsecNumber = 2;' . "\r\n" . '	var nMultConsecSymbol = 1;' . "\r\n" . '	var nMultConsecCharType = 0;' . "\r\n" . '	var nMultSeqAlpha = 3;' . "\r\n" . '	var nMultSeqNumber = 3;' . "\r\n" . '	var nTmpAlphaUC = "";' . "\r\n" . '	var nTmpAlphaLC = "";' . "\r\n" . '	var nTmpNumber = "";' . "\r\n" . '	var nTmpSymbol = "";' . "\r\n" . '	var sAlphaUC = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sAlphaLC = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sNumber = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sSymbol = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sMidChar = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sRequirements = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sAlphasOnly = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sNumbersOnly = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sRepChar = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sConsecAlphaUC = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sConsecAlphaLC = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sConsecNumber = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sSeqAlpha = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sSeqNumber = "&nbsp;&nbsp;&nbsp;&nbsp;0";' . "\r\n" . '	var sAlphas = "abcdefghijklmnopqrstuvwxyz";' . "\r\n" . '	var sNumerics = "01234567890";' . "\r\n" . '	var sComplexity = "太短";' . "\r\n" . '	var sStandards = "Below";' . "\r\n" . '	var nMinPwdLen = 8;' . "\r\n" . '	if (document.all) { var nd = 0; } else { var nd = 1; }' . "\r\n" . '	if (pwd) {' . "\r\n" . '		document.getElementById("scorebarBorder").style.paddingLeft = "0px";' . "\r\n" . '		nScore = parseInt(pwd.length * nMultLength);' . "\r\n" . '		nLength = pwd.length;' . "\r\n" . '		var arrPwd = pwd.replace (/\\s+/g,"").split(/\\s*/);' . "\r\n" . '		var arrPwdLen = arrPwd.length;' . "\r\n" . '		' . "\r\n" . '		/* Loop through password to check for Symbol, Numeric, Lowercase and Uppercase pattern matches */' . "\r\n" . '		for (var a=0; a < arrPwdLen; a++) {' . "\r\n" . '			if (arrPwd[a].match(new RegExp(/[A-Z]/g))) {' . "\r\n" . '				if (nTmpAlphaUC !== "") { if ((nTmpAlphaUC + 1) == a) { nConsecAlphaUC++; nConsecCharType++; } }' . "\r\n" . '				nTmpAlphaUC = a;' . "\r\n" . '				nAlphaUC++;' . "\r\n" . '			}' . "\r\n" . '			else if (arrPwd[a].match(new RegExp(/[a-z]/g))) { ' . "\r\n" . '				if (nTmpAlphaLC !== "") { if ((nTmpAlphaLC + 1) == a) { nConsecAlphaLC++; nConsecCharType++; } }' . "\r\n" . '				nTmpAlphaLC = a;' . "\r\n" . '				nAlphaLC++;' . "\r\n" . '			}' . "\r\n" . '			else if (arrPwd[a].match(new RegExp(/[0-9]/g))) { ' . "\r\n" . '				if (a > 0 && a < (arrPwdLen - 1)) { nMidChar++; }' . "\r\n" . '				if (nTmpNumber !== "") { if ((nTmpNumber + 1) == a) { nConsecNumber++; nConsecCharType++; } }' . "\r\n" . '				nTmpNumber = a;' . "\r\n" . '				nNumber++;' . "\r\n" . '			}' . "\r\n" . '			else if (arrPwd[a].match(new RegExp(/[^a-zA-Z0-9_]/g))) { ' . "\r\n" . '				if (a > 0 && a < (arrPwdLen - 1)) { nMidChar++; }' . "\r\n" . '				if (nTmpSymbol !== "") { if ((nTmpSymbol + 1) == a) { nConsecSymbol++; nConsecCharType++; } }' . "\r\n" . '				nTmpSymbol = a;' . "\r\n" . '				nSymbol++;' . "\r\n" . '			}' . "\r\n" . '			/* Internal loop through password to check for repeated characters */' . "\r\n" . '			for (var b=0; b < arrPwdLen; b++) {' . "\r\n" . '				if (arrPwd[a].toLowerCase() == arrPwd[b].toLowerCase() && a != b) { nRepChar++; }' . "\r\n" . '			}' . "\r\n" . '		}' . "\r\n" . '		' . "\r\n" . '		/* Check for sequential alpha string patterns (forward and reverse) */' . "\r\n" . '		for (var s=0; s < 23; s++) {' . "\r\n" . '			var sFwd = sAlphas.substring(s,parseInt(s+3));' . "\r\n" . '			var sRev = sFwd.strReverse();' . "\r\n" . '			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { nSeqAlpha++; nSeqChar++;}' . "\r\n" . '		}' . "\r\n" . '		' . "\r\n" . '		/* Check for sequential numeric string patterns (forward and reverse) */' . "\r\n" . '		for (var s=0; s < 8; s++) {' . "\r\n" . '			var sFwd = sNumerics.substring(s,parseInt(s+3));' . "\r\n" . '			var sRev = sFwd.strReverse();' . "\r\n" . '			if (pwd.toLowerCase().indexOf(sFwd) != -1 || pwd.toLowerCase().indexOf(sRev) != -1) { nSeqNumber++; nSeqChar++;}' . "\r\n" . '		}' . "\r\n" . '		' . "\r\n" . '	/* Modify overall score value based on usage vs requirements */' . "\r\n" . '' . "\r\n" . '		/* General point assignment */' . "\r\n" . '		if (nAlphaUC > 0 && nAlphaUC < nLength) {	' . "\r\n" . '			nScore = parseInt(nScore + ((nLength - nAlphaUC) * 2));' . "\r\n" . '			sAlphaUC = "+ " + parseInt((nLength - nAlphaUC) * 2); ' . "\r\n" . '		}' . "\r\n" . '		if (nAlphaLC > 0 && nAlphaLC < nLength) {	' . "\r\n" . '			nScore = parseInt(nScore + ((nLength - nAlphaLC) * 2)); ' . "\r\n" . '			sAlphaLC = "+ " + parseInt((nLength - nAlphaLC) * 2);' . "\r\n" . '		}' . "\r\n" . '		if (nNumber > 0 && nNumber < nLength) {	' . "\r\n" . '			nScore = parseInt(nScore + (nNumber * nMultNumber));' . "\r\n" . '			sNumber = "+ " + parseInt(nNumber * nMultNumber);' . "\r\n" . '		}' . "\r\n" . '		if (nSymbol > 0) {	' . "\r\n" . '			nScore = parseInt(nScore + (nSymbol * nMultSymbol));' . "\r\n" . '			sSymbol = "+ " + parseInt(nSymbol * nMultSymbol);' . "\r\n" . '		}' . "\r\n" . '		if (nMidChar > 0) {	' . "\r\n" . '			nScore = parseInt(nScore + (nMidChar * nMultMidChar));' . "\r\n" . '			sMidChar = "+ " + parseInt(nMidChar * nMultMidChar);' . "\r\n" . '		}' . "\r\n" . '		' . "\r\n" . '		/* Point deductions for poor practices */' . "\r\n" . '		if ((nAlphaLC > 0 || nAlphaUC > 0) && nSymbol === 0 && nNumber === 0) {  // Only Letters' . "\r\n" . '			nScore = parseInt(nScore - nLength);' . "\r\n" . '			nAlphasOnly = nLength;' . "\r\n" . '			sAlphasOnly = "- " + nLength;' . "\r\n" . '		}' . "\r\n" . '		if (nAlphaLC === 0 && nAlphaUC === 0 && nSymbol === 0 && nNumber > 0) {  // Only Numbers' . "\r\n" . '			nScore = parseInt(nScore - nLength); ' . "\r\n" . '			nNumbersOnly = nLength;' . "\r\n" . '			sNumbersOnly = "- " + nLength;' . "\r\n" . '		}' . "\r\n" . '		if (nRepChar > 0) {  // Same character exists more than once' . "\r\n" . '			nScore = parseInt(nScore - (nRepChar * nRepChar));' . "\r\n" . '			sRepChar = "- " + nRepChar;' . "\r\n" . '		}' . "\r\n" . '		if (nConsecAlphaUC > 0) {  // Consecutive Uppercase Letters exist' . "\r\n" . '			nScore = parseInt(nScore - (nConsecAlphaUC * nMultConsecAlphaUC)); ' . "\r\n" . '			sConsecAlphaUC = "- " + parseInt(nConsecAlphaUC * nMultConsecAlphaUC);' . "\r\n" . '		}' . "\r\n" . '		if (nConsecAlphaLC > 0) {  // Consecutive Lowercase Letters exist' . "\r\n" . '			nScore = parseInt(nScore - (nConsecAlphaLC * nMultConsecAlphaLC)); ' . "\r\n" . '			sConsecAlphaLC = "- " + parseInt(nConsecAlphaLC * nMultConsecAlphaLC);' . "\r\n" . '		}' . "\r\n" . '		if (nConsecNumber > 0) {  // Consecutive Numbers exist' . "\r\n" . '			nScore = parseInt(nScore - (nConsecNumber * nMultConsecNumber));  ' . "\r\n" . '			sConsecNumber = "- " + parseInt(nConsecNumber * nMultConsecNumber);' . "\r\n" . '		}' . "\r\n" . '		if (nSeqAlpha > 0) {  // Sequential alpha strings exist (3 characters or more)' . "\r\n" . '			nScore = parseInt(nScore - (nSeqAlpha * nMultSeqAlpha)); ' . "\r\n" . '			sSeqAlpha = "- " + parseInt(nSeqAlpha * nMultSeqAlpha);' . "\r\n" . '		}' . "\r\n" . '		if (nSeqNumber > 0) {  // Sequential numeric strings exist (3 characters or more)' . "\r\n" . '			nScore = parseInt(nScore - (nSeqNumber * nMultSeqNumber)); ' . "\r\n" . '			sSeqNumber = "- " + parseInt(nSeqNumber * nMultSeqNumber);' . "\r\n" . '		}' . "\r\n" . '' . "\r\n" . '		/* Determine if mandatory requirements have been met and set image indicators accordingly */' . "\r\n" . '		nRequirements = nReqChar;' . "\r\n" . '		if (pwd.length >= nMinPwdLen) { var nMinReqChars = 3; } else { var nMinReqChars = 4; }' . "\r\n" . '		if (nRequirements > nMinReqChars) {  // One or more required characters exist' . "\r\n" . '			nScore = parseInt(nScore + (nRequirements * 2)); ' . "\r\n" . '			sRequirements = "+ " + parseInt(nRequirements * 2);' . "\r\n" . '		}' . "\r\n" . '' . "\r\n" . '		/* Determine complexity based on overall score */' . "\r\n" . '		if (nScore > 100) { nScore = 100; } else if (nScore < 0) { nScore = 0; }' . "\r\n" . '		if (nScore >= 0 && nScore < 20) { sComplexity = "极弱"; }' . "\r\n" . '		else if (nScore >= 20 && nScore < 40) { sComplexity = "弱"; }' . "\r\n" . '		else if (nScore >= 40 && nScore < 60) { sComplexity = "一般"; }' . "\r\n" . '		else if (nScore >= 60 && nScore < 75) { sComplexity = "好"; }' . "\r\n" . '		else if (nScore >= 75 && nScore < 90) { sComplexity = "很好"; }' . "\r\n" . '		else if (nScore >= 90 && nScore <= 100) { sComplexity = "极佳"; }' . "\r\n" . '		' . "\r\n" . '		/* Display updated score criteria to client */' . "\r\n" . '		oScorebar.style.backgroundPosition = "-" + parseInt(nScore * 4) + "px";' . "\r\n" . '		oScore.innerHTML = nScore + "%";' . "\r\n" . '		oComplexity.innerHTML = sComplexity;' . "\r\n" . '	}' . "\r\n" . '	else {' . "\r\n" . '		initPwdChk();' . "\r\n" . '		oScore.innerHTML = nScore + "%";' . "\r\n" . '		oComplexity.innerHTML = sComplexity;' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function initPwdChk() {' . "\r\n" . '	document.getElementById("scorebar").style.backgroundPosition = "0";' . "\r\n" . '	document.getElementById("passWord").className = "text_inp";' . "\r\n" . '}';

?>
