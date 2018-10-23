<?php

if ((version_compare(PHP_VERSION, '5.6') >= 0)) {

    // ako src е извън public директорията на сървъра.
    //$autoloadPath = dirname($_SERVER['DOCUMENT_ROOT']) . '/src/Core/Bootstrap/Bootstrap.php';
    //$autoloadPath =  (dirname(__DIR__) . '/..') . '/src/Core/Bootstrap/Bootstrap.php';

    $autoloadPath = dirname(__DIR__) . '/src/Core/Bootstrap/Bootstrap.php';

    if (file_exists($autoloadPath)) {

        include_once $autoloadPath;

    } else {

        die('{ Bootstrap.php } not Found in path: ' . $autoloadPath);
    }
} else {

    die('Използваната версия на PHP е по-малка от 5.6');
}