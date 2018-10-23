<?php
include_once dirname(__DIR__) . '/Bootstrap/constants.php';

function model($path)
{
    extract($path);

    $dir = APPLICATION_DIR . 'Models' . DIRECTORY_SEPARATOR .$namespace;

    if (!realpath($dir)) {
        mkdir($dir, 0777, true);
    }

    $model = APPLICATION_DIR . 'Models' . DIRECTORY_SEPARATOR . ucfirst($filePath) . '.php';
    $modelNamespace = ($namespace == '') ? 'namespace App\Models;':"namespace App\Models\\$namespace;";
    $f =<<<CONTR
<?php

{$modelNamespace}

use Core\Model;

class $className extends Model
{

    /**
     * BookingModel constructor.
     */
    public function __construct()
    {
        parent::__construct(['table'=>'']);
    }

}
CONTR;

    if (file_put_contents($model, $f)){
        echo 'Model - ' . $model . ' - is created';
    } else {
        die('Model not created');
    }
}

