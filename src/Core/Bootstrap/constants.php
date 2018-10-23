<?php
/**
 * Define Path Constant
 *
 */
define('VERSION', '1.1');
define('APPLICATION_DIR', realpath(dirname(__DIR__) . '/..') .DIRECTORY_SEPARATOR .'App'.DIRECTORY_SEPARATOR);
define('VIEW_DIR', APPLICATION_DIR . 'Views' . DIRECTORY_SEPARATOR);
define('SYSTEM_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(dirname(__DIR__) . '/..') .DIRECTORY_SEPARATOR);
define('VENDOR_DIR', ROOT_DIR . 'vendor'. DIRECTORY_SEPARATOR);
define('SYSTEM_PATH', basename(dirname(__DIR__)) . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', dirname($_SERVER['SCRIPT_FILENAME']) . DIRECTORY_SEPARATOR);

