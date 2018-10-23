<?php
/*
 * View се извикват в контролера:
 * $this->load->setLayout('dashboard')->view('file', data);
 * $this->load->view('result');
 */

namespace Core\Libs;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

class View
{
    private static $instance = null;

    private $viewDir = 'Views/';

    private $layout = 'Views/layout/default.php';

    public $_data = array();


    private function __construct()
    {
    }


    /**
     * Използва се в  controller
     * $this->load->setLayout('dashboard')->render('file', data);
     * @param $file
     * @return $this
     */
    public function setLayout($file)
    {
        $this->layout = 'Views/layout/' . $file . '.php';

        return $this;

    }

    public function render($name, $data = [])
    {

        $main = APPLICATION_DIR . $this->viewDir . $name . '.php';

        $view = APPLICATION_DIR . $this->layout;

        if (is_readable($main)) {

            ob_start();

            extract($data);

            if (is_readable($view)) {

                include_once $view;

            } else {

                throw new \Exception('Грешка в : Core\Libs\Load при зареждане на  [' . $view . ' ]', 500);
            }

            echo ob_get_clean();

        } else {

            throw new \Exception('Грешка в : Core\Libs\View при зареждане на [' . $main . ' ]', 500);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

}