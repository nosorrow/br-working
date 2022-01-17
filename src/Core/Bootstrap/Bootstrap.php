<?php

use Core\Libs\Config;
use Core\Bootstrap\FrontController;
use Core\Bootstrap\DiContainer;
use Core\Bootstrap\ExceptionHandler;
use Core\Bootstrap\ParameterResolver;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

include_once dirname(__DIR__) . '/Bootstrap/constants.php';

include_once ROOT_DIR . 'vendor/autoload.php';

/*
 * Monolog logger и ExceptionHandler
 */
$formatter = new \Monolog\Formatter\HtmlFormatter();
$logger = new Logger('logger');
$stream = new StreamHandler(APPLICATION_DIR . 'Logs/system.html', Logger::DEBUG);
$stream->setFormatter($formatter);
$logger->pushHandler($stream);

$exceptionHandler = new ExceptionHandler($logger);
$exceptionHandler->run();

/*
 *  --- Define environment ---
 */
date_default_timezone_set(Config::getConfigFromFile('timezone'));

if (Config::getConfigFromFile('environment') == 'production') {

    ini_set('display_errors', 0);

    error_reporting(0);

} elseif (Config::getConfigFromFile('environment') == 'development') {

    ini_set('display_errors', 1);

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

} elseif (Config::getConfigFromFile('environment') == 'whoops') {

    ini_set('display_errors', 1);

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

    $run = new Whoops\Run;
    $exceptionHandler = new Whoops\Handler\PrettyPageHandler;
    $JsonHandler = new Whoops\Handler\JsonResponseHandler;

    $exceptionHandler->setPageTitle('Oп ! нещо се обърка');
    $run->pushHandler($exceptionHandler);
    $run->register();

} else {
    die(' $conf[\'environment\'] не е конфигуриран ! ');
}

$dic = new DiContainer();

$app = $dic->get(FrontController::class);
$resolver = $dic->get(ParameterResolver::class);

$app->uriFrontControllerDispatcher();
$class = $app->getFullControllerClassName();
$method = $app->getMethod();

// Взима DI и dafault параметрите от метода на контролера
//връща подредения масив със всички параметри на метода
$resolver->setParametersFromUri($app->getParamsFromUri())
    ->injectedMethodParameters($class, $method)
    ->resolve()
    ->invoke();
