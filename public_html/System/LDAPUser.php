<?php
//dezend by http://www.yunlu99.com/
define('ROOT_PATH', '../');
require_once ROOT_PATH . 'Entry/Global.setup.php';
@set_time_limit(0);
_checkroletype('1|2|5');
$SQL = ' SELECT domainControllers,adUsername,accountSuffix,adPassword,baseDN FROM ' . BASESETTING_TABLE . ' ';
$baseRow = $DB->queryFirstRow($SQL);
$baseDN = $baseRow['baseDN'];
$searchName = trim($_GET['Filter']);
$accountSuffix = trim($baseRow['accountSuffix']);
$options['account_suffix'] = trim($baseRow['accountSuffix']);
$domain_controllers = explode(',', trim($baseRow['domainControllers']));
$options['domain_controllers'] = $domain_controllers;
$options['ad_username'] = trim($baseRow['adUsername']);
$options['ad_password'] = trim($baseRow['adPassword']);
$options['base_dn'] = trim($baseRow['baseDN']);
include_once ROOT_PATH . 'Includes/LDAP.class.php';
$LDAP = new LDAPCLASS($options);

if ($searchName != '') {
	$searchName = iconv('gbk', 'UTF-8', $searchName);
	$Groups = $LDAP->all_groups(false, $searchName, true);
	$Users = $LDAP->all_users(false, $searchName, true);
}
else {
	exit('无法Search活动目录，请指定更确定的搜索条件.');
}

echo '<html>' . "\r\n" . '<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">' . "\r\n" . '<meta http-equiv=content-type content="text/html; charset=gbk">' . "\r\n" . '<script>' . "\r\n" . ' function disabletext(e){ return false }' . "\r\n" . ' function reEnable(){ return true }' . "\r\n" . '' . "\r\n" . ' // Disable text selection' . "\r\n" . ' if (window.sidebar) { document.onmousedown=disabletext; document.onclick=reEnable; }' . "\r\n" . ' document.onselectstart=new Function ("return false");' . "\r\n" . '</script>' . "\r\n" . '<style>' . "\r\n" . '.header {border-top: #d6d5d9 1px solid;border-right: #d6d5d9 1px solid; border-bottom: #d6d5d9 1px solid;font-family: Tahoma; font-size: 12px;}' . "\r\n" . 'body {font-family: Tahoma; font-size: 12px;}' . "\r\n" . '</style>' . "\r\n" . '<body bgcolor=#ffffff bottommargin=0 leftmargin=0 rightmargin=0 text=#000000 topmargin=0 vlink=#80b0e0 marginwidth="0" marginheight="0" oncontextmenu="return false">' . "\r\n" . ' <table NOBORDER CELLPADDING=0 CELLSPACING=0 id="dataTable" width=698>' . "\r\n" . ' ';
if ((count($Users) == 0) && (count($Groups) == 0)) {
	echo '	 <font style=\'font-family: Tahoma; font-size: 12px;\'>&nbsp;&nbsp;<img src=\'../Images/empty.gif\' width=9 height=9>&nbsp;没有符合指定检索条件的用户或用户组.</font>' . "\r\n" . ' ';
}
else {
	echo '    <tr>' . "\r\n" . '	  <td width=30% nowrap class="header" style="BORDER-LEFT: #D6D5D9 1px solid;" height=23>&nbsp;显示姓名</td>' . "\r\n" . '	  <td width=15% nowrap class="header">&nbsp;登录名</td>' . "\r\n" . '	  <td width=33% nowrap class="header">&nbsp;Email</td>' . "\r\n" . '	  <td width=22% nowrap class="header">&nbsp;部门</td>' . "\r\n" . '    </tr>' . "\r\n" . '' . "\r\n" . '  ';
}

$i = 0;

for (; $i <= count($Groups) - 1; $i++) {
	$infoValue = $Groups[$i];
	$splittedArray = split('#', $infoValue);
	$Account = $splittedArray[0];
	$canonName = $splittedArray[1];
	$flagPicture = '../Images/group.gif';
	$displayFields = $Account;
	$AddValue = 'group/' . $displayFields;
	echo '    <tr id="gid_';
	echo $i;
	echo '" style="cursor:hand;" onDblClick="AddValue(\'';
	echo $AddValue;
	echo '\');" onClick="TRselected(this);">' . "\r\n" . '	  <td height=23><div style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;<IMG SRC=\'';
	echo $flagPicture;
	echo '\' width=16 height=16 align=center>&nbsp;';
	echo $Account;
	echo '&nbsp;</div></td>' . "\r\n" . '	  <td id="hidden_';
	echo $i;
	echo '" style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\' style="display:none">';
	echo $AddValue;
	echo '</td>' . "\r\n" . '	  <script>' . "\r\n" . '		  var obj = eval("document.getElementById(\'hidden_\' + ';
	echo $i;
	echo ')");' . "\r\n" . '		  obj.style.display = "none";' . "\r\n" . '	  </script>' . "\r\n" . '	  <td style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;&nbsp;</td>' . "\r\n" . '	  <td><div style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;&nbsp;</div></td>' . "\r\n" . '	  <td><div style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;';
	echo $canonName;
	echo '&nbsp;</div></td>' . "\r\n" . '    </tr>' . "\r\n" . ' ';
}

$i = 0;

for (; $i <= count($Users) - 1; $i++) {
	$infoValue = $Users[$i];
	$splittedArray = split('#', $infoValue);
	$LastName = $splittedArray[0];
	$FirstName = $splittedArray[1];
	$Account = trim($splittedArray[2]);
	$Mail = $splittedArray[3];
	$Department = $splittedArray[4];
	$State = $splittedArray[5];

	if ($State == 1) {
		$flagPicture = '../Images/deactive.gif';
	}
	else {
		$flagPicture = '../Images/active.gif';
	}

	$displayFields = $Account;
	echo '    <tr id="uid_';
	echo $i;
	echo '" style="cursor:hand;" onDblClick="AddValue(\'';
	echo $displayFields;
	echo '\');" onClick="TRselected(this);">' . "\r\n" . '	  <td height=23><div style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;<IMG SRC=\'';
	echo $flagPicture;
	echo '\' width=16 height=16 align=center>&nbsp;';
	echo $LastName . $FirstName;
	echo '&nbsp;</div></td>' . "\r\n" . '	  <td style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;';
	echo $Account;
	echo '&nbsp;</td>' . "\r\n" . '	  <td><div style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;';
	echo $Mail;
	echo '&nbsp;</div></td>' . "\r\n" . '	  <td><div style=\'height: 16px; overflow: hidden; font-family: Tahoma; font-size: 12px;\'>&nbsp;';
	echo $Department;
	echo '&nbsp;</div></td>' . "\r\n" . '    </tr>' . "\r\n" . ' ';
}

echo '</table>' . "\r\n" . '</body>' . "\r\n" . '</html>' . "\r\n" . '' . "\r\n" . '<script>' . "\r\n" . ' function AddValue(theValue)' . "\r\n" . ' {' . "\r\n" . '	 var currentValue = parent.document.getElementById("Users").value;' . "\r\n" . '	 var currentArray = currentValue.split(\';\');' . "\r\n" . '	 var isHaveValue = false;' . "\r\n" . '	 for(var i=0;i<currentArray.length-1;i++)' . "\r\n" . '	 {' . "\r\n" . '		 if(Trim(currentArray[i]).toLowerCase() == Trim(theValue).toLowerCase() ) ' . "\r\n" . '		 {' . "\r\n" . '			 isHaveValue = true;' . "\r\n" . '			 break;' . "\r\n" . '		 }' . "\r\n" . '	 }' . "\r\n" . '	 if ( isHaveValue == false )' . "\r\n" . '	 {' . "\r\n" . '		 if ( currentValue == \'自列表中双击对象进行选择或选择对象后点击添加\' )' . "\r\n" . '		 {' . "\r\n" . '		     parent.document.getElementById("Users").value = "";' . "\r\n" . '		 }' . "\r\n" . '		 parent.document.getElementById("Users").value = parent.document.getElementById("Users").value+theValue+"; ";' . "\r\n" . '	 }' . "\r\n" . '	 window.focus(); ' . "\r\n" . ' }' . "\r\n" . 'function TRselected(obj){' . "\r\n" . '  if ( window.event.ctrlKey ) ' . "\r\n" . '  {' . "\r\n" . '     if ( obj.style.color != \'#000000\' && obj.style.color != \'black\' ) {' . "\r\n" . '        obj.style.backgroundColor = "#FFFFFF";' . "\r\n" . '        obj.style.color = "#000000";' . "\r\n" . '     } ' . "\r\n" . '	 else ' . "\r\n" . '	 {' . "\r\n" . '        obj.style.backgroundColor = "#2663E0";' . "\r\n" . '        obj.style.color = "#FFFFFF";' . "\r\n" . '     }' . "\r\n" . '  } ' . "\r\n" . '  else if ( window.event.shiftKey ) ' . "\r\n" . '  {' . "\r\n" . '     var theTable = obj.parentElement;' . "\r\n" . '     var sIndex=0, lIndex=0;' . "\r\n" . '     for ( var i=1; i<theTable.rows.length; i++ )' . "\r\n" . '	 {' . "\r\n" . '         if ( theTable.rows[i].style.color != \'#000000\' && theTable.rows[i].style.color != \'black\' ) ' . "\r\n" . '		 {' . "\r\n" . '            if ( sIndex == 0 ) sIndex = i;' . "\r\n" . '            if ( i > lIndex ) lIndex = i;' . "\r\n" . '         } ' . "\r\n" . '     }' . "\r\n" . '     sIndex = ( sIndex == 0 ) ? 1 : sIndex;' . "\r\n" . '     lIndex = ( lIndex == 0 ) ? 1 : lIndex;' . "\r\n" . '     if ( sIndex < obj.rowIndex ) lIndex= obj.rowIndex;' . "\r\n" . '     else sIndex = obj.rowIndex;' . "\r\n" . '     for ( var i=1; i<theTable.rows.length; i++ ){' . "\r\n" . '        if ( i>=sIndex && i<=lIndex ) {' . "\r\n" . '            theTable.rows[i].style.backgroundColor = "#2663E0";' . "\r\n" . '            theTable.rows[i].style.color = "#FFFFFF";' . "\r\n" . '        } else {' . "\r\n" . '            theTable.rows[i].style.backgroundColor = "#FFFFFF";' . "\r\n" . '            theTable.rows[i].style.color = "#000000";' . "\r\n" . '        } ' . "\r\n" . '     }' . "\r\n" . '  } else {' . "\r\n" . '     unselectall();' . "\r\n" . '     obj.style.backgroundColor=\'#2663E0\';' . "\r\n" . '     obj.style.color=\'#FFFFFF\';' . "\r\n" . '  }' . "\r\n" . '  //selectedTD = obj.cells(1); ' . "\r\n" . '}' . "\r\n" . ' ' . "\r\n" . 'function unselectall(){' . "\r\n" . ' var rows = document.getElementById("dataTable").rows;' . "\r\n" . ' for ( var i=1; i<rows.length; i++) {' . "\r\n" . '  rows(i).style.backgroundColor=\'#ffffff\';' . "\r\n" . '  rows(i).style.color=\'#000000\';' . "\r\n" . ' }' . "\r\n" . '}' . "\r\n" . ' //去除字符串左端空格' . "\r\n" . 'function LTrim(str) {' . "\r\n" . '  return str.replace(/^\\s*/, \'\');' . "\r\n" . ' }' . "\r\n" . ' //去除字符串右端空格' . "\r\n" . 'function RTrim(str) {' . "\r\n" . '  return str.replace(/\\s*$/, \'\');' . "\r\n" . ' }' . "\r\n" . ' //去除字符串两端空格' . "\r\n" . 'function Trim(str) {' . "\r\n" . '  return LTrim(RTrim(str));' . "\r\n" . '}' . "\r\n" . '</script>' . "\r\n" . '';

?>
