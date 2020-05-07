<?php
/**
 * Archivo de rutas.
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/routes.php';

if (isset($_GET['p']) && ! empty($_GET['p'])) {

    /**
     * Conexión a la DB.
     */
    require_once CLASS_DB;

    /**
     * Clase de herramientas.
     */
    require_once CLASS_TOOLS;

    /**
     * Carga el proceso
     */
    $file = preg_replace('/[^A-Za-zñÑ\_]/', '', trim($_GET['p']));
    define('FILE', $file);
    if (file_exists(PATH_MODULES . "/$file.php")) {
        require_once PATH_MODULES . "/$file.php";
    } else {
        echo "Error...";
    }

} else {
    echo "Error...";
}

