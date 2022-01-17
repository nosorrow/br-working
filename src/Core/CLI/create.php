<?php
include_once 'controller.php';
include_once 'model.php';

function create($class)
{
    $e = explode(':', $class);
    list($class, $name) = $e;
    $path = _parse($name);

 //   var_dump($path);die;

    switch ($class) {
        case 'controller':
            controller($path);
            break;
        case 'model':
            model($path);
            break;
    }
}

function _parse($path)
{
    $filePath = ucfirst($path);
    $namespace = ucfirst(substr($filePath, 0, strrpos($filePath, '/')));
    $namespace = str_replace('/', '\\', $namespace);
    $namespace = trim($namespace, '\\');

    if (strrpos($filePath, '/')){
        $className = ucfirst(substr($filePath,strrpos($filePath, '/')+1));
    } else {
        $className = ucfirst($filePath);

    }

    return compact('filePath', 'namespace', 'className');

}