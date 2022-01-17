<?php

namespace Core\Bootstrap;

use Core\Libs\Uri;
use Core\Libs\Request;

class FrontController
{
    protected $request;
    /**
     * @var Uri
     */
    protected $uri;
    /**
     * @var
     */
    protected $router;
    /**
     * @var array
     */
    protected $route;
    /**
     * @var
     */
    public $dir;
    /**
     * @var
     */
    protected $routesInit;
    /**
     * @var
     */
    protected $fullControllerClassName;
    /**
     * @var
     */
    protected $method;
    /**
     * @var array
     */
    protected $params_from_uri = array();

    /**
     * FrontController constructor.
     * @param Uri $uri
     * @param Router $router
     */
    public function __construct(Uri $uri, Router $router, Request $request)
    {
        $this->uri = $uri;
        $this->router = $router;
        $this->request = $request;

    }


    /**
     * @return $this
     * @throws \Exception
     */
    public function uriFrontControllerDispatcher()
    {
        $httpMethod = $this->request->method();
        $_uri = $this->uri->uriString();

        if (!$_uri) {

            $_uri = '/';
        }

       /*
       // -- връща масив със сегментите от URL --
        $segments_from_uri = $this->uri->rawSegments();

        // -- връща първите 2 сегмента от масива на URL като стринг -> "route/route1" --
        $__route = rtrim(implode(array_slice($segments_from_uri, 0, 2), '/'), '/');
       */

        // параметрите на методите = масив;
        $this->params_from_uri = array();

        $route = $this->router->dispatch($httpMethod, urldecode($_uri));

        if (!is_string($route['action']) && is_callable($route['action'])) {
            if (($route['params']) == null){
                call_user_func($route['action']);
                exit;

            } else {
                call_user_func_array($route['action'], $route['params']);
                exit;
            }

        }

        if(isset($route['params'])){
            $this->params_from_uri = array_values($route['params']);

        }
        // пълни $_GET
        if (!empty($route['params'])) {
            $this->request->setGet($route['params']);
        }

        // Folder/SubFolder/Rooms@booking
        $action_str = $route['action'];
        if (strpos($action_str, '/') !== false) {
            $this->dir = substr($action_str, 0, strrpos($action_str, '/') + 1);
            $act = substr($action_str, strrpos($action_str, '/') + 1);
        } else {
            $act = $action_str;
			$this->dir = '';
        }

        if (strpos($act, '@') !== false) {
            $controller = substr($act, 0, strpos($act, '@'));
            $this->method = substr($act, strpos($act, '@') + 1);

        } else {
            $controller = $act;
            $this->method = 'index';
        }

        // Има ли такъв файл ?
        $controller_file = (APPLICATION_DIR . 'Controllers' . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $this->dir) . ucfirst($controller) . '.php');

        if (!file_exists($controller_file) && !is_readable($controller_file)) {

            throw new \Exception('Не е намерен файл :  [ ' . $controller_file . ' ] ', 500);
        }

        //Контролерът живее в namespace App\Controllers
        $namespace = 'App\Controllers' . "\\" . str_replace('/', '\\', $this->dir);
        $this->fullControllerClassName = $namespace . ucfirst($controller);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullControllerClassName()
    {
        return $this->fullControllerClassName;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getParamsFromUri()
    {
        return (array)($this->params_from_uri);
    }
}
