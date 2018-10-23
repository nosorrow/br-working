<?php

namespace App\Controllers;

use Core\Controller;
use Core\Libs\Headers;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

class ErrorPage extends Controller
{
    public $logger;

    public function __construct()
    {
        parent::__construct();

        $logger = new Logger('logger');
        $stream = new StreamHandler(APPLICATION_DIR . 'Logs/system.log', Logger::DEBUG);
        $logger->pushHandler($stream);
        $this->logger = $logger;
    }

    public function show($code)
    {
        Headers::setHeaderWithCode($code);
        $file = APPLICATION_DIR . 'Views/Errors/' . $code . '.php';
        if(!file_exists($file)){
            $this->view->render('Errors/404');

            $this->logger->warning("Няма страница ".$code . ".php");

        } else {
            $this->view->render('Errors/' . $code);
        }

    }
}