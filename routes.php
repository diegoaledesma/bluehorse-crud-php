<?php

$url        = 'http://diegoledesma.com.ar';
$root       = $_SERVER['DOCUMENT_ROOT'];
$proyect    = '/bh';
$path       = $root . $proyect . '/bluehorse';

define('CLASS_CORE',        $path . '/Core.php');
define('CLASS_BROWSER',     $path . '/Browser.php');
define('CLASS_DETAILER',    $path . '/Editor.php');
define('CLASS_EDITOR',      $path . '/Detailer.php');
define('CLASS_DB',          $path . '/DB.php');

define('VIEW_HEAD',         $path . '/views/view_head.php');
define('VIEW_BROWSER',      $path . '/views/view_browser.php');
define('VIEW_EDITOR',       $path . '/views/view_editor.php');
define('VIEW_DETAILER',     $path . '/views/view_detailer.php');
define('VIEW_ERROR',        $path . '/views/view_error.php');
define('VIEW_MENU',         $path . '/views/view_menu.php');
define('VIEW_PANEL_LATERAL',$path . '/views/view_panel_lateral.php');
define('VIEW_INDEX',        $path . '/views/view_index.php');

define('CLASS_COOKIES',     $path . '/config/Cookies.php');
define('CLASS_CONTROL',     $path . '/config/Control.php');
define('CLASS_TOOLS',       $path . '/config/Tools.php');
define('SETTINGS',          $path . '/config/settings__.php');

define('URL_IMG',           $url . $proyect . '/images');
define('FILE_APP',          $url . $proyect . '/app/app.php');
define('FILE_APPP',         $url . $proyect . '/app/appp.php');
define('FILE_LOGOUT',       $url . $proyect . '/auth/logout.php');
define('FILE_LOGIN',        $url . $proyect . '/auth/login.php');
define('FILE_INDEX',        $url . $proyect . '/app/index.php');
define('FILE_HEADER',       $root . $proyect . '/modules/header.php');
define('PATH_MODULES',      $root . $proyect . '/modules');
define('PATH_SH',           $path . '/sh');
define('URL_AJAX',          $proyect . '/modules/ajax');

define('ERR_400',           $root . '/err/400.php');
define('ERR_401',           $root . '/err/401.php');
define('ERR_403',           $root . '/err/403.php');
define('ERR_404',           $root . '/err/404.php');
define('ERR_500',           $root . '/err/500.php');
define('ERR_503',           $root . '/err/503.php');

