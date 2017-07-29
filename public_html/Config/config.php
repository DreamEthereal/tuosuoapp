<?php
/**************************************************************************
 *                                                                        *
 *    EnableQ System                                                      *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *        Copyright: (C) 2012-2018 ItEnable Services,Inc.                 *  
 *        WebSite: itenable.com.cn                                        *
 *                                                                        *
 *        Last Modified: 2013/06/30                                       *
 *        Scriptversion: 8.xx                                             *
 *                                                                        *
 **************************************************************************/


switch ($_SERVER['HTTP_HOST']) {
    case 'eclear.yinglian360.com':
        $Config['siteName'] = 'eclear在线问卷调查引擎';
        $Config['absolutenessPath'] = '/a/domains/eclear.yinglian360.com/public_html/';
        $Config['encrypt'] = 1;
        $DB_name = 'eclear';
        $DB_lang = 'gbk';
        $DB_user = 'eclear';
        $DB_password = 'ZeWnNasbZlWeFqyIyMj';
        $DB_host = '127.0.0.1';
        break;
    case 'toupiao.yinglian360.com':
        $Config['siteName'] = 'eclear在线问卷调查引擎';
        $Config['absolutenessPath'] = '/b/domains/toupiao.yinglian360.com/public_html/';
        $Config['encrypt'] = 1;
        $DB_name = 'toupiao';
        $DB_lang = 'gbk';
        $DB_user = 'eclear';
        $DB_password = 'ZeWnNasbZlWeFqyIyMj';
        $DB_host = '127.0.0.1';
        break;
    case 'yg007.yinglian360.com':
        $Config['siteName'] = 'eclear在线问卷调查引擎';
        $Config['absolutenessPath'] = '/b/domains/eclear.yinglian360.com/public_html/';
        $Config['encrypt'] = 1;
        $DB_name = 'yg007';
        $DB_lang = 'gbk';
        $DB_user = 'root';
        $DB_password ='cemn9avbdlAeO0O0OqO0O0O';
        $DB_host = '47.93.47.143';
    default:
    case 'yg007_app.yinglian360.com':
        $Config['siteName'] = 'eclear在线问卷调查引擎';
        $Config['absolutenessPath'] = '/b/domains/web_yg007_tp/public_html/';
        $Config['encrypt'] = 1;
        $DB_name = 'yg007';
        $DB_lang = 'gbk';
        $DB_user = 'yg007';
        $DB_password ='eeWncawbMlDecqyMDE3';
        $DB_host = '182.92.74.192';
    default:

        break;
}
?>
