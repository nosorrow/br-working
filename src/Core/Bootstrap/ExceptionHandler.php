<?php
/*
 * set_exception_handler
 */
namespace Core\Bootstrap;

use Core\Libs\Config;

use Core\Libs\Headers;

/**
 * Class ExceptionHandler
 * @package Core\Bootstrap
 */
class ExceptionHandler
{
    /**
     * Monolog logger
     * @var
     */
    public $logger;

    /**
     * ExceptionHandler constructor.
     * @param $logger
     */
    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function run()
    {
        set_exception_handler(array($this, '_exception'));
    }

    public function _exception($e)
    {
        if (Config::getConfigFromFile('environment') == 'development') {
            try {
                $msg = Headers::setHeaderWithCode($e->getCode());
                echo '<h2>{ ' . $e->getCode() . ' }</h2>';
                echo '<h4>{ ' . $msg . ' }</h4>';
                echo "<b>Exceptions Message: </b><span style = \"color:#FF4031; font-size: 18px;\">", $e->getMessage() . "</span> <br><strong>Line: </strong>"
                    . $e->getLine() . ' => ' . $e->getFile() . "<br><strong>Trace: </strong>" . $e->getTraceAsString();
                exit;
            } catch (\Exception $e) {
                echo '<h1>' . $e->getCode() . '</h1>';
            }

        } elseif (Config::getConfigFromFile('environment') == 'production') {

            try {
                if (Config::getConfigFromFile('logger') === true) {
                    $remote = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] :'Unknown IP';
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    $log =
                        " Remote host : " . $remote . PHP_EOL
                        . " USER_AGENT: " . $user_agent . PHP_EOL
                        . " Exceptions Message: " . $e->getMessage() . PHP_EOL
                        . " Line: " . $e->getLine() . PHP_EOL
                        . " Trace: " . $e->getTraceAsString();

                    $this->logger->error($log);
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

            redirect(route('error', [404]));

        }
    }
}
