<?php
//dezend by http://www.yunlu99.com/
echo '' . "\r\n" . 'function readGpsSimInfo()' . "\r\n" . '{' . "\r\n" . '   //读取设备和SIM信息' . "\r\n" . '   var currentCity = ( rexseePreference.exists(\'city\') ) ? rexseePreference.get(\'city\') : "";' . "\r\n" . '   if( rexseeTelephony.isSimReady() )' . "\r\n" . '   {' . "\r\n" . '	  document.getElementById(\'SimInfo\').value = currentCity+\'$$$\'+rexseeBuild.getBrand()+\'$$$\'+rexseeBuild.getModel()+\'$$$\'+rexseeTelephony.getDeviceId()+\'$$$\'+rexseeTelephony.getSimOperatorName()+\'$$$\'+rexseeTelephony.getSimSerialNumber()+\'$$$\'+rexseeTelephony.getLine1Number();' . "\r\n" . '   }' . "\r\n" . '   else' . "\r\n" . '   {' . "\r\n" . '	  document.getElementById(\'SimInfo\').value = currentCity+\'$$$\'+rexseeBuild.getBrand()+\'$$$\'+rexseeBuild.getModel()+\'$$$\'+rexseeTelephony.getDeviceId();' . "\r\n" . '   }' . "\r\n" . '}' . "\r\n" . 'function onGpsLocationChanged(time,accuracy,longitude,latitude,speed,bearing,altitude){' . "\r\n" . '	rexseeProgressDialog.hide();' . "\r\n" . '	' . "\r\n" . '	//将GPS插入对应的数据表' . "\r\n" . '	var gps_time = (typeof(time) == \'undefined\') ? \'\' :time;' . "\r\n" . '	var gps_accuracy = (typeof(accuracy) == \'undefined\') ? \'\' :accuracy;' . "\r\n" . '	var gps_longitude = (typeof(longitude) == \'undefined\') ? \'\' :longitude;' . "\r\n" . '	var gps_latitude = (typeof(latitude) == \'undefined\') ? \'\' :latitude;' . "\r\n" . '	var gps_speed = (typeof(speed) == \'undefined\') ? \'\' :speed;' . "\r\n" . '	var gps_bearing = (typeof(bearing) == \'undefined\') ? \'\' :bearing;' . "\r\n" . '	var gps_altitude = (typeof(altitude) == \'undefined\') ? \'\' :altitude;' . "\r\n" . '	' . "\r\n" . '	if( typeof(isLocalGps) != \'undefined\' && isLocalGps == 1 )' . "\r\n" . '	{' . "\r\n" . '		var SQL = " INSERT INTO eq_gps_trace ( surveyID,gpsTime,accuracy,longitude,latitude,speed,bearing,altitude,flag,isCell ) VALUES ( \'"+qid+"\',";' . "\r\n" . '		SQL += "\'"+gps_time+"\',\'"+gps_accuracy+"\',\'"+gps_longitude+"\',\'"+gps_latitude+"\',\'"+gps_speed+"\',\'"+gps_bearing+"\',\'"+gps_altitude+"\',\'0\',\'0\');";' . "\r\n" . '		rexseeDatabase.exec(SQL,\'rexsee:enableq.db\');' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	if( typeof(getNewLoctURL) != \'undefined\' && getNewLoctURL != \'\' )' . "\r\n" . '	{' . "\r\n" . '		var the_gps_time = gps_time/1000;' . "\r\n" . '		var postData = eval(\'({brand:"\'+rexseeBuild.getBrand()+\'",model:"\'+rexseeBuild.getModel()+\'",deviceId: "\'+rexseeTelephony.getDeviceId()+\'", gpsTime: "\'+the_gps_time+\'",accuracy:"\'+gps_accuracy+\'",longitude:"\'+gps_longitude+\'",latitude:"\'+gps_latitude+\'",altitude:"\'+gps_altitude+\'",nickUserName:"\'+nickUserName+\'",isCell:"0"})\');' . "\r\n" . '		if( getServerVersion() == -1 ) {}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			$.ajaxSetup({async:false});' . "\r\n" . '			$.post(getNewLoctURL, postData, ' . "\r\n" . '					function(data) { ' . "\r\n" . '					rtnMessage = data;' . "\r\n" . '			});' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . '' . "\r\n" . 'function onCellLocationChanged()' . "\r\n" . '{' . "\r\n" . '	var location = eval(\'(\'+rexseeCellLocation.getLastKnownLocation()+\')\');' . "\r\n" . '	var type = location.type.toLowerCase();' . "\r\n" . '	var mcc = parseInt(location.operator.substring(0,3));' . "\r\n" . '	var mnc = (type == "gsm") ? parseInt(location.operator.substring(3)) : location.systemId;' . "\r\n" . '	var cid = (type == "gsm") ? location.cid : location.baseStationId;' . "\r\n" . '	var lac = (type == "gsm") ? location.lac : location.networkId;' . "\r\n" . '		' . "\r\n" . '	if( typeof(isLocalGps) != \'undefined\' && isLocalGps == 1 )' . "\r\n" . '	{' . "\r\n" . '		var SQL = " INSERT INTO eq_gps_trace ( surveyID,gpsTime,accuracy,longitude,latitude,altitude,flag,isCell ) VALUES ( \'"+qid+"\',";' . "\r\n" . '		SQL += "\'"+time()+"\',\'"+mcc+"\',\'"+cid+"\',\'"+mnc+"\',\'"+lac+"\',\'0\',\'1\');";' . "\r\n" . '		rexseeDatabase.exec(SQL,\'rexsee:enableq.db\');' . "\r\n" . '	}' . "\r\n" . '	if( typeof(getNewLoctURL) != \'undefined\' && getNewLoctURL != \'\' )' . "\r\n" . '	{' . "\r\n" . '		var postData = eval(\'({brand:"\'+rexseeBuild.getBrand()+\'",model:"\'+rexseeBuild.getModel()+\'",deviceId: "\'+rexseeTelephony.getDeviceId()+\'", gpsTime: "\'+time()+\'",accuracy:"\'+mcc+\'",longitude:"\'+cid+\'",latitude:"\'+mnc+\'",altitude:"\'+lac+\'",nickUserName:"\'+nickUserName+\'",isCell:"1"})\');' . "\r\n" . '' . "\r\n" . '		if( getServerVersion() == -1 ) {}' . "\r\n" . '		else' . "\r\n" . '		{' . "\r\n" . '			$.ajaxSetup({async:false});' . "\r\n" . '			$.post(getNewLoctURL, postData, ' . "\r\n" . '					function(data) { ' . "\r\n" . '					rtnMessage = data;' . "\r\n" . '			});' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function getGpsLastKnownLocation(qtnID)' . "\r\n" . '{' . "\r\n" . '	if( isCell == 1 || isCell == 3 )  //GPS定位或者混合定位' . "\r\n" . '	{' . "\r\n" . '		var gpsRtn = rexseeGps.getLastKnownLocation();' . "\r\n" . '		if( gpsRtn != \'\' )' . "\r\n" . '		{' . "\r\n" . '			var gpsJson = eval(\'(\'+gpsRtn+\')\');' . "\r\n" . '			var gps_time = gpsJson.time;' . "\r\n" . '			var gps_accuracy = gpsJson.accuracy;' . "\r\n" . '			var gps_longitude = gpsJson.longitude;' . "\r\n" . '			var gps_latitude = gpsJson.latitude;' . "\r\n" . '			var gps_speed = gpsJson.speed;' . "\r\n" . '			var gps_bearing = gpsJson.bearing;' . "\r\n" . '			var gps_altitude = gpsJson.altitude;' . "\r\n" . '' . "\r\n" . '			var SQL = " INSERT INTO eq_gps_trace ( surveyID,gpsTime,accuracy,longitude,latitude,speed,bearing,altitude,flag,isCell ) VALUES ( \'"+qid+"\',";' . "\r\n" . '			SQL += "\'"+gps_time+"\',\'"+gps_accuracy+"\',\'"+gps_longitude+"\',\'"+gps_latitude+"\',\'"+gps_speed+"\',\'"+gps_bearing+"\',\'"+gps_altitude+"\',\'0\',\'0\' );";' . "\r\n" . '			rexseeDatabase.exec(SQL,\'rexsee:enableq.db\');' . "\r\n" . '			var SQL = " INSERT INTO eq_gps_trace_upload ( surveyID,qtnID,gpsTime,accuracy,longitude,latitude,speed,bearing,altitude,flag,isCell ) VALUES ( \'"+qid+"\',\'"+qtnID+"\',";' . "\r\n" . '			SQL += "\'"+gps_time+"\',\'"+gps_accuracy+"\',\'"+gps_longitude+"\',\'"+gps_latitude+"\',\'"+gps_speed+"\',\'"+gps_bearing+"\',\'"+gps_altitude+"\',\'0\',\'0\' );";' . "\r\n" . '			rexseeDatabase.exec(SQL,\'rexsee:enableq.db\');' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '' . "\r\n" . '	if( isCell == 2 || isCell == 3 )  //基站定位或者混合定位' . "\r\n" . '	{' . "\r\n" . '		var locRtn = rexseeCellLocation.getLastKnownLocation();' . "\r\n" . '		if( locRtn != \'\' )' . "\r\n" . '		{' . "\r\n" . '			var location = eval(\'(\'+locRtn+\')\');' . "\r\n" . '			var type = location.type.toLowerCase();' . "\r\n" . '			var mcc = parseInt(location.operator.substring(0,3));' . "\r\n" . '			var mnc = (type == "gsm") ? parseInt(location.operator.substring(3)) : location.systemId;' . "\r\n" . '			var cid = (type == "gsm") ? location.cid : location.baseStationId;' . "\r\n" . '			var lac = (type == "gsm") ? location.lac : location.networkId;' . "\r\n" . '				' . "\r\n" . '			var SQL = " INSERT INTO eq_gps_trace ( surveyID,gpsTime,accuracy,longitude,latitude,altitude,flag,isCell ) VALUES ( \'"+qid+"\',";' . "\r\n" . '			SQL += "\'"+time()+"\',\'"+mcc+"\',\'"+cid+"\',\'"+mnc+"\',\'"+lac+"\',\'0\',\'1\');";' . "\r\n" . '			rexseeDatabase.exec(SQL,\'rexsee:enableq.db\');' . "\r\n" . '			var SQL = " INSERT INTO eq_gps_trace_upload ( surveyID,qtnID,gpsTime,accuracy,longitude,latitude,altitude,flag,isCell ) VALUES ( \'"+qid+"\',\'"+qtnID+"\',";' . "\r\n" . '			SQL += "\'"+time()+"\',\'"+mcc+"\',\'"+cid+"\',\'"+mnc+"\',\'"+lac+"\',\'0\',\'1\');";' . "\r\n" . '			rexseeDatabase.exec(SQL,\'rexsee:enableq.db\');' . "\r\n" . '		}' . "\r\n" . '	}' . "\r\n" . '}' . "\r\n" . 'function onGpsEnabled()' . "\r\n" . '{' . "\r\n" . '	rexseeDialog.toast("启用GPS...");' . "\r\n" . '}' . "\r\n" . 'function onGpsDisabled()' . "\r\n" . '{' . "\r\n" . '	rexseeDialog.toast("GPS被禁用,请启用GPS...");' . "\r\n" . '	rexseeGps.openSettings();' . "\r\n" . '}' . "\r\n" . 'function onGpsSettingFailed()' . "\r\n" . '{' . "\r\n" . '	rexseeDialog.toast("打开GPS配置失败,请启用GPS...");' . "\r\n" . '	rexseeGps.openSettings();' . "\r\n" . '}' . "\r\n" . 'function getServerVersion()' . "\r\n" . '{' . "\r\n" . '	var theHomeURL = (rexseeApplication.getHome() == \'\' ) ? rexseeApplication.getDeveloperHome() : rexseeApplication.getHome();' . "\r\n" . '	var remoteXMLURL = str_replace(\'default.html\',\'bulidClient.xml\',theHomeURL);' . "\r\n" . '	var ser_version = rexseeClient.getLatestVersion(remoteXMLURL);' . "\r\n" . '	return ser_version;' . "\r\n" . '}';

?>
