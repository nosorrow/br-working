<?php
/*
 * Create Controller
 * use terminal command: php cli.php create controller:ControllerName
 *
 * Create Model
 * use terminal command: php cli.php create model:ModelName
 *
 */
include_once 'Core/CLI/create.php';

foreach ($argv as $i=>$arg )
{
    if ( $arg == "create" )
    {
        create($argv[2]);
    }
}