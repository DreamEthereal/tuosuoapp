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
header('Content-Type: text/javascr��pt; charset: UTF-8');
echo '' . "\n" . '' . "\n" . 'var ttAbove       = false;       ' . "\n" . 'var ttBgColor     = "#e6ecff";' . "\n" . 'var ttBgImg       = "";           // path to background image;' . "\n" . 'var ttBorderColor = "#003399";' . "\n" . 'var ttBorderWidth = 1;' . "\n" . 'var ttDelay       = 500;          // time span until tooltip shows up [milliseconds]' . "\n" . 'var ttFontColor   = "#000066";' . "\n" . 'var ttFontFace    = "arial,helvetica,sans-serif";' . "\n" . 'var ttFontSize    = "12px";' . "\n" . 'var ttFontWeight  = "normal";     // alternative: "bold";' . "\n" . 'var ttLeft        = false;        // tooltip on the left of the mouse? Alternative: true' . "\n" . 'var ttOffsetX     = 12;           // horizontal offset of left-top corner from mousepointer' . "\n" . 'var ttOffsetY     = 15;           // vertical offset                   "' . "\n" . 'var ttOpacity     = 100;          // opacity of tooltip in percent (must be integer between 0 and 100)' . "\n" . 'var ttPadding     = 1;            // spacing between border and content' . "\n" . 'var ttShadowColor = "";' . "\n" . 'var ttShadowWidth = 0;' . "\n" . 'var ttStatic      = false;        // tooltip NOT move with the mouse? Alternative: true' . "\n" . 'var ttSticky      = false;        // do NOT hide tooltip on mouseout? Alternative: true' . "\n" . 'var ttTemp        = 0;            // time span after which the tooltip disappears; 0 (zero) means "infinite timespan"' . "\n" . 'var ttTextAlign   = "left";' . "\n" . 'var ttTitleColor  = "#ffffff";    // color of caption text' . "\n" . 'var ttWidth       = 300;' . "\n" . 'var tt_tags = new Array("a","area","b","big","caption","center","code","dd","div","dl","dt","em","h1","h2","h3","h4","h5","h6","i","img","input","li","map","ol","p","pre","s", "select", "small","span","strike","strong","sub","sup","table","td","textarea","th","tr","tt","u","var","ul","layer");' . "\n" . '' . "\n" . 'var tt_obj = null,         // current tooltip' . "\n" . 'tt_ifrm = null,            // iframe to cover windowed controls in IE' . "\n" . 'tt_objW = 0, tt_objH = 0,  // width and height of tt_obj' . "\n" . 'tt_objX = 0, tt_objY = 0,' . "\n" . 'tt_offX = 0, tt_offY = 0,' . "\n" . 'xlim = 0, ylim = 0,        // right and bottom borders of visible client area' . "\n" . 'tt_sup = false,            // true if T_ABOVE cmd' . "\n" . 'tt_sticky = false,         // tt_obj sticky?' . "\n" . 'tt_wait = false,' . "\n" . 'tt_act = false,            // tooltip visibility flag' . "\n" . 'tt_sub = false,            // true while tooltip below mousepointer' . "\n" . 'tt_u = "undefined",' . "\n" . 'tt_mf = null,              // stores previous mousemove evthandler' . "\n" . '// Opera: disable href when hovering <a>' . "\n" . 'tt_tag = null;             // stores hovered dom node, href and previous statusbar txt' . "\n" . '' . "\n" . '' . "\n" . 'var tt_db = (document.compatMode && document.compatMode != "BackCompat")? document.documentElement : document.body? document.body : null,' . "\n" . 'tt_n = navigator.userAgent.toLowerCase(),' . "\n" . 'tt_nv = navigator.appVersion;' . "\n" . '// Browser flags' . "\n" . 'var tt_op = !!(window.opera && document.getElementById),' . "\n" . 'tt_op6 = tt_op && !document.defaultView,' . "\n" . 'tt_op7 = tt_op && !tt_op6,' . "\n" . 'tt_ie = tt_n.indexOf("msie") != -1 && document.all && tt_db && !tt_op,' . "\n" . 'tt_ie7 = tt_ie && typeof document.body.style.maxHeight != tt_u,' . "\n" . 'tt_ie6 = tt_ie && !tt_ie7 && parseFloat(tt_nv.substring(tt_nv.indexOf("MSIE")+5)) >= 5.5,' . "\n" . 'tt_n4 = (document.layers && typeof document.classes != tt_u),' . "\n" . 'tt_n6 = (!tt_op && document.defaultView && typeof document.defaultView.getComputedStyle != tt_u),' . "\n" . 'tt_w3c = !tt_ie && !tt_n6 && !tt_op && document.getElementById;' . "\n" . '' . "\n" . 'function tt_Int(t_x)' . "\n" . '{' . "\n" . '	var t_y;' . "\n" . '	return isNaN(t_y = parseInt(t_x))? 0 : t_y;' . "\n" . '}' . "\n" . 'function wzReplace(t_x, t_y)' . "\n" . '{' . "\n" . '	var t_ret = "",' . "\n" . '	t_str = this,' . "\n" . '	t_xI;' . "\n" . '	while((t_xI = t_str.indexOf(t_x)) != -1)' . "\n" . '	{' . "\n" . '		t_ret += t_str.substring(0, t_xI) + t_y;' . "\n" . '		t_str = t_str.substring(t_xI + t_x.length);' . "\n" . '	}' . "\n" . '	return t_ret+t_str;' . "\n" . '}' . "\n" . 'String.prototype.wzReplace = wzReplace;' . "\n" . 'function tt_N4Tags(tagtyp, t_d, t_y)' . "\n" . '{' . "\n" . '	t_d = t_d || document;' . "\n" . '	t_y = t_y || new Array();' . "\n" . '	var t_x = (tagtyp=="a")? t_d.links : t_d.layers;' . "\n" . '	for(var z = t_x.length; z--;) t_y[t_y.length] = t_x[z];' . "\n" . '	for(z = t_d.layers.length; z--;) t_y = tt_N4Tags(tagtyp, t_d.layers[z].document, t_y);' . "\n" . '	return t_y;' . "\n" . '}' . "\n" . 'function tt_Htm(tt, t_id, txt)' . "\n" . '{' . "\n" . '	var t_bgc = (typeof tt.T_BGCOLOR != tt_u)? tt.T_BGCOLOR : ttBgColor,' . "\n" . '	t_bgimg   = (typeof tt.T_BGIMG != tt_u)? tt.T_BGIMG : ttBgImg,' . "\n" . '	t_bc      = (typeof tt.T_BORDERCOLOR != tt_u)? tt.T_BORDERCOLOR : ttBorderColor,' . "\n" . '	t_bw      = (typeof tt.T_BORDERWIDTH != tt_u)? tt.T_BORDERWIDTH : ttBorderWidth,' . "\n" . '	t_ff      = (typeof tt.T_FONTFACE != tt_u)? tt.T_FONTFACE : ttFontFace,' . "\n" . '	t_fc      = (typeof tt.T_FONTCOLOR != tt_u)? tt.T_FONTCOLOR : ttFontColor,' . "\n" . '	t_fsz     = (typeof tt.T_FONTSIZE != tt_u)? tt.T_FONTSIZE : ttFontSize,' . "\n" . '	t_fwght   = (typeof tt.T_FONTWEIGHT != tt_u)? tt.T_FONTWEIGHT : ttFontWeight,' . "\n" . '	t_opa     = (typeof tt.T_OPACITY != tt_u)? tt.T_OPACITY : ttOpacity,' . "\n" . '	t_padd    = (typeof tt.T_PADDING != tt_u)? tt.T_PADDING : ttPadding,' . "\n" . '	t_shc     = (typeof tt.T_SHADOWCOLOR != tt_u)? tt.T_SHADOWCOLOR : (ttShadowColor || 0),' . "\n" . '	t_shw     = (typeof tt.T_SHADOWWIDTH != tt_u)? tt.T_SHADOWWIDTH : (ttShadowWidth || 0),' . "\n" . '	t_algn    = (typeof tt.T_TEXTALIGN != tt_u)? tt.T_TEXTALIGN : ttTextAlign,' . "\n" . '	t_tit     = (typeof tt.T_TITLE != tt_u)? tt.T_TITLE : "",' . "\n" . '	t_titc    = (typeof tt.T_TITLECOLOR != tt_u)? tt.T_TITLECOLOR : ttTitleColor,' . "\n" . '	t_w       = (typeof tt.T_WIDTH != tt_u)? tt.T_WIDTH  : ttWidth;' . "\n" . '	if(t_shc || t_shw)' . "\n" . '	{' . "\n" . '		t_shc = t_shc || "#cccccc";' . "\n" . '		t_shw = t_shw || 5;' . "\n" . '	}' . "\n" . '	if(tt_n4 && (t_fsz == "10px" || t_fsz == "11px")) t_fsz = "12px";' . "\n" . '' . "\n" . '	var t_optx = (tt_n4? \'\' : tt_n6? (\'-moz-opacity:\'+(t_opa/100.0)) : tt_ie? (\'filter:Alpha(opacity=\'+t_opa+\')\') : (\'opacity:\'+(t_opa/100.0))) + \';\';' . "\n" . '	var t_y = \'<div id="\'+t_id+\'" style="position:absolute;z-index:1010;\';' . "\n" . '	t_y += \'left:0px;top:0px;width:\'+(t_w+t_shw)+\'px;visibility:\'+(tt_n4? \'hide\' : \'hidden\')+\';\'+t_optx+\'">\' +' . "\n" . '		\'<table border="0" cellpadding="0" cellspacing="0"\'+(t_bc? (\' bgcolor="\'+t_bc+\'" style="background:\'+t_bc+\';"\') : \'\')+\' width="\'+t_w+\'">\';' . "\n" . '	if(t_tit)' . "\n" . '	{' . "\n" . '		t_y += \'<tr><td style="padding-left:3px;padding-right:3px;" align="\'+t_algn+\'"><font color="\'+t_titc+\'" face="\'+t_ff+\'" \' +' . "\n" . '			\'style="color:\'+t_titc+\';font-family:\'+t_ff+\';font-size:\'+t_fsz+\';"><b>\' +' . "\n" . '			(tt_n4? \'&nbsp;\' : \'\')+t_tit+\'</b></font></td></tr>\';' . "\n" . '	}' . "\n" . '	t_y += \'<tr><td style="padding:2px;"><table border="0" cellpadding="\'+t_padd+\'" cellspacing="\'+t_bw+\'" width="100%">\' +' . "\n" . '		\'<tr><td\'+(t_bgc? (\' bgcolor="\'+t_bgc+\'"\') : \'\')+(t_bgimg? \' background="\'+t_bgimg+\'"\' : \'\')+\' style="text-align:\'+t_algn+\';\';' . "\n" . '	if(tt_n6) t_y += \'padding:\'+t_padd+\'px;\';' . "\n" . '	t_y += \'" align="\'+t_algn+\'"><font color="\'+t_fc+\'" face="\'+t_ff+\'"\' +' . "\n" . '		\' style="color:\'+t_fc+\';font-family:\'+t_ff+\';font-size:\'+t_fsz+\';font-weight:\'+t_fwght+\';">\';' . "\n" . '	if(t_fwght == \'bold\') t_y += \'<b>\';' . "\n" . '	t_y += txt;' . "\n" . '	if(t_fwght == \'bold\') t_y += \'</b>\';' . "\n" . '	t_y += \'</font></td></tr></table></td></tr></table>\';' . "\n" . '	if(t_shw)' . "\n" . '	{' . "\n" . '		var t_spct = Math.round(t_shw*1.3);' . "\n" . '		if(tt_n4)' . "\n" . '		{' . "\n" . '			t_y += \'<layer bgcolor="\'+t_shc+\'" left="\'+t_w+\'" top="\'+t_spct+\'" width="\'+t_shw+\'" height="0"></layer>\' +' . "\n" . '				\'<layer bgcolor="\'+t_shc+\'" left="\'+t_spct+\'" align="bottom" width="\'+(t_w-t_spct)+\'" height="\'+t_shw+\'"></layer>\';' . "\n" . '		}' . "\n" . '		else' . "\n" . '		{' . "\n" . '			t_optx = tt_n6? \'-moz-opacity:0.85;\' : tt_ie? \'filter:Alpha(opacity=85);\' : \'opacity:0.85;\';' . "\n" . '			t_y += \'<div id="\'+t_id+\'R" style="position:absolute;background:\'+t_shc+\';left:\'+t_w+\'px;top:\'+t_spct+\'px;width:\'+t_shw+\'px;height:1px;overflow:hidden;\'+t_optx+\'"></div>\' +' . "\n" . '				\'<div style="position:relative;background:\'+t_shc+\';left:\'+t_spct+\'px;top:0px;width:\'+(t_w-t_spct)+\'px;height:\'+t_shw+\'px;overflow:hidden;\'+t_optx+\'"></div>\';' . "\n" . '		}' . "\n" . '	}' . "\n" . '	return(t_y+\'</div>\');' . "\n" . '}' . "\n" . 'function tt_EvX(t_e)' . "\n" . '{' . "\n" . '	var t_y = tt_Int(t_e.pageX || t_e.clientX || 0) +' . "\n" . '		tt_Int(tt_ie? tt_db.scrollLeft : 0) +' . "\n" . '		tt_offX;' . "\n" . '	if(t_y > xlim) t_y = xlim;' . "\n" . '	var t_scr = tt_Int(window.pageXOffset || (tt_db? tt_db.scrollLeft : 0) || 0);' . "\n" . '	if(t_y < t_scr) t_y = t_scr;' . "\n" . '	return t_y;' . "\n" . '}' . "\n" . 'function tt_EvY(t_e)' . "\n" . '{' . "\n" . '	var t_y2;' . "\n" . '' . "\n" . '	var t_y = tt_Int(t_e.pageY || t_e.clientY || 0) +' . "\n" . '		tt_Int(tt_ie? tt_db.scrollTop : 0);' . "\n" . '	if(tt_sup && (t_y2 = t_y - (tt_objH + tt_offY - 15)) >= tt_Int(window.pageYOffset || (tt_db? tt_db.scrollTop : 0) || 0))' . "\n" . '		t_y -= (tt_objH + tt_offY - 15);' . "\n" . '	else if(t_y > ylim || !tt_sub && t_y > ylim-24)' . "\n" . '	{' . "\n" . '		t_y -= (tt_objH + 5);' . "\n" . '		tt_sub = false;' . "\n" . '	}' . "\n" . '	else' . "\n" . '	{' . "\n" . '		t_y += tt_offY;' . "\n" . '		tt_sub = true;' . "\n" . '	}' . "\n" . '	return t_y;' . "\n" . '}' . "\n" . 'function tt_ReleasMov()' . "\n" . '{' . "\n" . '	if(document.onmousemove == tt_Move)' . "\n" . '	{' . "\n" . '		if(!tt_mf && document.releaseEvents) document.releaseEvents(Event.MOUSEMOVE);' . "\n" . '		document.onmousemove = tt_mf;' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_ShowIfrm(t_x)' . "\n" . '{' . "\n" . '	if(!tt_obj || !tt_ifrm) return;' . "\n" . '	if(t_x)' . "\n" . '	{' . "\n" . '		tt_ifrm.style.width = tt_objW+\'px\';' . "\n" . '		tt_ifrm.style.height = tt_objH+\'px\';' . "\n" . '		tt_ifrm.style.display = "block";' . "\n" . '	}' . "\n" . '	else tt_ifrm.style.display = "none";' . "\n" . '}' . "\n" . 'function tt_GetDiv(t_id)' . "\n" . '{' . "\n" . '	return(' . "\n" . '		tt_n4? (document.layers[t_id] || null)' . "\n" . '		: tt_ie? (document.all[t_id] || null)' . "\n" . '		: (document.getElementById(t_id) || null)' . "\n" . '	);' . "\n" . '}' . "\n" . 'function tt_GetDivW()' . "\n" . '{' . "\n" . '	return tt_Int(' . "\n" . '		tt_n4? tt_obj.clip.width' . "\n" . '		: (tt_obj.style.pixelWidth || tt_obj.offsetWidth)' . "\n" . '	);' . "\n" . '}' . "\n" . 'function tt_GetDivH()' . "\n" . '{' . "\n" . '	return tt_Int(' . "\n" . '		tt_n4? tt_obj.clip.height' . "\n" . '		: (tt_obj.style.pixelHeight || tt_obj.offsetHeight)' . "\n" . '	);' . "\n" . '}' . "\n" . '' . "\n" . '// Compat with DragDrop Lib: Ensure that z-index of tooltip is lifted beyond toplevel dragdrop element' . "\n" . 'function tt_SetDivZ()' . "\n" . '{' . "\n" . '	var t_i = tt_obj.style || tt_obj;' . "\n" . '	if(t_i)' . "\n" . '	{' . "\n" . '		if(window.dd && dd.z)' . "\n" . '			t_i.zIndex = Math.max(dd.z+1, t_i.zIndex);' . "\n" . '		if(tt_ifrm) tt_ifrm.style.zIndex = t_i.zIndex-1;' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_SetDivPos(t_x, t_y)' . "\n" . '{' . "\n" . '	var t_i = tt_obj.style || tt_obj;' . "\n" . '	var t_px = (tt_op6 || tt_n4)? \'\' : \'px\';' . "\n" . '	t_i.left = (tt_objX = t_x) + t_px;' . "\n" . '	t_i.top = (tt_objY = t_y) + t_px;' . "\n" . '	//  window... to work around the FireFox Alzheimer Bug' . "\n" . '	if(window.tt_ifrm)' . "\n" . '	{' . "\n" . '		tt_ifrm.style.left = t_i.left;' . "\n" . '		tt_ifrm.style.top = t_i.top;' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_ShowDiv(t_x)' . "\n" . '{' . "\n" . '	tt_ShowIfrm(t_x);' . "\n" . '	if(tt_n4) tt_obj.visibility = t_x? \'show\' : \'hide\';' . "\n" . '	else tt_obj.style.visibility = t_x? \'visible\' : \'hidden\';' . "\n" . '	tt_act = t_x;' . "\n" . '}' . "\n" . 'function tt_OpDeHref(t_e)' . "\n" . '{' . "\n" . '	var t_tag;' . "\n" . '	if(t_e)' . "\n" . '	{' . "\n" . '		t_tag = t_e.target;' . "\n" . '		while(t_tag)' . "\n" . '		{' . "\n" . '			if(t_tag.hasAttribute("href"))' . "\n" . '			{' . "\n" . '				tt_tag = t_tag' . "\n" . '				tt_tag.t_href = tt_tag.getAttribute("href");' . "\n" . '				tt_tag.removeAttribute("href");' . "\n" . '				tt_tag.style.cursor = "hand";' . "\n" . '				tt_tag.onmousedown = tt_OpReHref;' . "\n" . '				tt_tag.stats = window.status;' . "\n" . '				window.status = tt_tag.t_href;' . "\n" . '				break;' . "\n" . '			}' . "\n" . '			t_tag = t_tag.parentElement;' . "\n" . '		}' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_OpReHref()' . "\n" . '{' . "\n" . '	if(tt_tag)' . "\n" . '	{' . "\n" . '		tt_tag.setAttribute("href", tt_tag.t_href);' . "\n" . '		window.status = tt_tag.stats;' . "\n" . '		tt_tag = null;' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_Show(t_e, t_id, t_sup, t_delay, t_fix, t_left, t_offx, t_offy, t_static, t_sticky, t_temp)' . "\n" . '{' . "\n" . '	if(tt_obj) tt_Hide();' . "\n" . '	tt_mf = document.onmousemove || null;' . "\n" . '	if(window.dd && (window.DRAG && tt_mf == DRAG || window.RESIZE && tt_mf == RESIZE)) return;' . "\n" . '	var t_sh, t_h;' . "\n" . '' . "\n" . '	tt_obj = tt_GetDiv(t_id);' . "\n" . '	if(tt_obj)' . "\n" . '	{' . "\n" . '		t_e = t_e || window.event;' . "\n" . '		tt_sub = !(tt_sup = t_sup);' . "\n" . '		tt_sticky = t_sticky;' . "\n" . '		tt_objW = tt_GetDivW();' . "\n" . '		tt_objH = tt_GetDivH();' . "\n" . '		tt_offX = t_left? -(tt_objW+t_offx) : t_offx;' . "\n" . '		tt_offY = t_offy;' . "\n" . '		if(tt_op7) tt_OpDeHref(t_e);' . "\n" . '		if(tt_n4)' . "\n" . '		{' . "\n" . '			if(tt_obj.document.layers.length)' . "\n" . '			{' . "\n" . '				t_sh = tt_obj.document.layers[0];' . "\n" . '				t_sh.clip.height = tt_objH - Math.round(t_sh.clip.width*1.3);' . "\n" . '			}' . "\n" . '		}' . "\n" . '		else' . "\n" . '		{' . "\n" . '			t_sh = tt_GetDiv(t_id+\'R\');' . "\n" . '			if(t_sh)' . "\n" . '			{' . "\n" . '				t_h = tt_objH - tt_Int(t_sh.style.pixelTop || t_sh.style.top || 0);' . "\n" . '				if(typeof t_sh.style.pixelHeight != tt_u) t_sh.style.pixelHeight = t_h;' . "\n" . '				else t_sh.style.height = t_h+\'px\';' . "\n" . '			}' . "\n" . '		}' . "\n" . '' . "\n" . '		xlim = tt_Int((tt_db && tt_db.clientWidth)? tt_db.clientWidth : window.innerWidth) +' . "\n" . '			tt_Int(window.pageXOffset || (tt_db? tt_db.scrollLeft : 0) || 0) -' . "\n" . '			tt_objW -' . "\n" . '			(tt_n4? 21 : 0);' . "\n" . '		ylim = tt_Int(window.innerHeight || tt_db.clientHeight) +' . "\n" . '			tt_Int(window.pageYOffset || (tt_db? tt_db.scrollTop : 0) || 0) -' . "\n" . '			tt_objH - tt_offY;' . "\n" . '' . "\n" . '		tt_SetDivZ();' . "\n" . '		if(t_fix) tt_SetDivPos(tt_Int((t_fix = t_fix.split(\',\'))[0]), tt_Int(t_fix[1]));' . "\n" . '		else tt_SetDivPos(tt_EvX(t_e), tt_EvY(t_e));' . "\n" . '' . "\n" . '		var t_txt = \'tt_ShowDiv(\\\'true\\\');\';' . "\n" . '		if(t_sticky) t_txt += \'{\'+' . "\n" . '				\'tt_ReleasMov();\'+' . "\n" . '				\'window.tt_upFunc = document.onmouseup || null;\'+' . "\n" . '				\'if(document.captureEvents) document.captureEvents(Event.MOUSEUP);\'+' . "\n" . '				\'document.onmouseup = new Function("window.setTimeout(\\\'tt_Hide();\\\', 10);");\'+' . "\n" . '			\'}\';' . "\n" . '		else if(t_static) t_txt += \'tt_ReleasMov();\';' . "\n" . '		if(t_temp > 0) t_txt += \'window.tt_rtm = window.setTimeout(\\\'tt_sticky = false; tt_Hide();\\\',\'+t_temp+\');\';' . "\n" . '		window.tt_rdl = window.setTimeout(t_txt, t_delay);' . "\n" . '' . "\n" . '		if(!t_fix)' . "\n" . '		{' . "\n" . '			if(document.captureEvents) document.captureEvents(Event.MOUSEMOVE);' . "\n" . '			document.onmousemove = tt_Move;' . "\n" . '		}' . "\n" . '	}' . "\n" . '}' . "\n" . 'var tt_area = false;' . "\n" . 'function tt_Move(t_ev)' . "\n" . '{' . "\n" . '	if(!tt_obj) return;' . "\n" . '	if(tt_n6 || tt_w3c)' . "\n" . '	{' . "\n" . '		if(tt_wait) return;' . "\n" . '		tt_wait = true;' . "\n" . '		setTimeout(\'tt_wait = false;\', 5);' . "\n" . '	}' . "\n" . '	var t_e = t_ev || window.event;' . "\n" . '	tt_SetDivPos(tt_EvX(t_e), tt_EvY(t_e));' . "\n" . '	if(tt_op6)' . "\n" . '	{' . "\n" . '		if(tt_area && t_e.target.tagName != \'AREA\') tt_Hide();' . "\n" . '		else if(t_e.target.tagName == \'AREA\') tt_area = true;' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_Hide()' . "\n" . '{' . "\n" . '	if(window.tt_obj)' . "\n" . '	{' . "\n" . '		if(window.tt_rdl) window.clearTimeout(tt_rdl);' . "\n" . '		if(!tt_sticky || !tt_act)' . "\n" . '		{' . "\n" . '			if(window.tt_rtm) window.clearTimeout(tt_rtm);' . "\n" . '			tt_ShowDiv(false);' . "\n" . '			tt_SetDivPos(-tt_objW, -tt_objH);' . "\n" . '			tt_obj = null;' . "\n" . '			if(typeof window.tt_upFunc != tt_u) document.onmouseup = window.tt_upFunc;' . "\n" . '		}' . "\n" . '		tt_sticky = false;' . "\n" . '		if(tt_op6 && tt_area) tt_area = false;' . "\n" . '		tt_ReleasMov();' . "\n" . '		if(tt_op7) tt_OpReHref();' . "\n" . '	}' . "\n" . '}' . "\n" . 'function tt_Init()' . "\n" . '{' . "\n" . '	if(!(tt_op || tt_n4 || tt_n6 || tt_ie || tt_w3c)) return;' . "\n" . '' . "\n" . '	var htm = tt_n4? \'<div style="position:absolute;"></div>\' : \'\',' . "\n" . '	tags,' . "\n" . '	t_tj,' . "\n" . '	over,' . "\n" . '	esc = \'return escape(\';' . "\n" . '	var i = tt_tags.length; while(i--)' . "\n" . '	{' . "\n" . '		tags = tt_ie? (document.all.tags(tt_tags[i]) || 1)' . "\n" . '			: document.getElementsByTagName? (document.getElementsByTagName(tt_tags[i]) || 1)' . "\n" . '			: (!tt_n4 && tt_tags[i]=="a")? document.links' . "\n" . '			: 1;' . "\n" . '		if(tt_n4 && (tt_tags[i] == "a" || tt_tags[i] == "layer")) tags = tt_N4Tags(tt_tags[i]);' . "\n" . '		var j = tags.length; while(j--)' . "\n" . '		{' . "\n" . '			if(typeof (t_tj = tags[j]).onmouseover == "function" && t_tj.onmouseover.toString().indexOf(esc) != -1 && !tt_n6 || tt_n6 && (over = t_tj.getAttribute("onmouseover")) && over.indexOf(esc) != -1)' . "\n" . '			{' . "\n" . '				if(over) t_tj.onmouseover = new Function(over);' . "\n" . '				var txt = unescape(t_tj.onmouseover());' . "\n" . '				htm += tt_Htm(' . "\n" . '					t_tj,' . "\n" . '					"tOoLtIp"+i+""+j,' . "\n" . '					txt.wzReplace("& ","&")' . "\n" . '				);' . "\n" . '                // window... to work around the FF Alzheimer Bug' . "\n" . '				t_tj.onmouseover = new Function(\'e\',' . "\n" . '					\'if(window.tt_Show && tt_Show) tt_Show(e,\'+' . "\n" . '					\'"tOoLtIp\' +i+\'\'+j+ \'",\'+' . "\n" . '					((typeof t_tj.T_ABOVE != tt_u)? t_tj.T_ABOVE : ttAbove)+\',\'+' . "\n" . '					((typeof t_tj.T_DELAY != tt_u)? t_tj.T_DELAY : ttDelay)+\',\'+' . "\n" . '					((typeof t_tj.T_FIX != tt_u)? \'"\'+t_tj.T_FIX+\'"\' : \'""\')+\',\'+' . "\n" . '					((typeof t_tj.T_LEFT != tt_u)? t_tj.T_LEFT : ttLeft)+\',\'+' . "\n" . '					((typeof t_tj.T_OFFSETX != tt_u)? t_tj.T_OFFSETX : ttOffsetX)+\',\'+' . "\n" . '					((typeof t_tj.T_OFFSETY != tt_u)? t_tj.T_OFFSETY : ttOffsetY)+\',\'+' . "\n" . '					((typeof t_tj.T_STATIC != tt_u)? t_tj.T_STATIC : ttStatic)+\',\'+' . "\n" . '					((typeof t_tj.T_STICKY != tt_u)? t_tj.T_STICKY : ttSticky)+\',\'+' . "\n" . '					((typeof t_tj.T_TEMP != tt_u)? t_tj.T_TEMP : ttTemp)+' . "\n" . '					\');\'' . "\n" . '				);' . "\n" . '				t_tj.onmouseout = tt_Hide;' . "\n" . '				if(t_tj.alt) t_tj.alt = "";' . "\n" . '				if(t_tj.title) t_tj.title = "";' . "\n" . '			}' . "\n" . '		}' . "\n" . '	}' . "\n" . '	if(tt_ie6) htm += \'<iframe id="TTiEiFrM" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>\';' . "\n" . '	document.write(htm);' . "\n" . '	if(document.getElementById) tt_ifrm = document.getElementById("TTiEiFrM");' . "\n" . '}' . "\n" . 'tt_Init();';

?>
