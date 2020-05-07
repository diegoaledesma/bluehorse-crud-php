<?php

#require_once 'access_control.php';
session_start();
require_once CLASS_CORE;

if (isset($_GET['reference']) && ! empty($_GET['reference'])) {
	    define('REFERENCE', preg_replace('/[^0-9]/', '', $_GET['reference']));
}

use BlueHorse\Core as Core;

$foo =& Core\Core::getMode();
