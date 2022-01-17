<?php
include_once dirname(__DIR__) . '/Bootstrap/constants.php';

function controller($path)
{
    /*$filePath = ucfirst($filePath);
    $namespace = ucfirst(substr($filePath, 0, strrpos($filePath, '/')));
    $namespace = str_replace('/', '\\', $namespace);
    $className = ucfirst(substr($filePath,strrpos($filePath, '/')+1));
    $dir = APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR .$namespace;*/
    extract($path);

    $dir = APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR .$namespace;

    if (!realpath($dir)) {
        mkdir($dir, 0777, true);
    }

    $fileName = APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR . ucfirst($filePath) . '.php';

    $controllerNamespace = ($namespace == '') ? 'namespace App\Controllers;':"namespace App\Controllers\\$namespace;";

    $f =<<<CONTR
<?php

{$controllerNamespace}

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Controller;
use Core\Bootstrap\DiContainer;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;
use Core\Libs\Csrf;

class $className extends Controller
{
    public function __construct()
    {
        parent::__construct();


    }

}
CONTR;


    if (file_put_contents($fileName, $f)){
        echo 'Controller - ' . $fileName . ' - is created';
    } else {
        die('Controller not created');
    }
}

