<?php

namespace Core;

defined( 'APPLICATION_DIR' ) OR exit( 'No direct Accesss here !' );

use Core\Libs\Url;
use Core\Libs\View;
use Core\Libs\Request;
use Core\Libs\Database\MySqlDBQuery;

class Controller
{
    use MySqlDBQuery{
        MySqlDBQuery::__construct as private __MySqlDBQueryConstructor;
    }

    protected static $instance = null;

    public $view;

    public $request;

    public $url;

    public function __construct()
    {
        $this->__MySqlDBQueryConstructor();

        $this->view = View::getInstance();

        $this->request = Request::getInstance();

        $this->request->session->session_regenerate(true);

        $this->url = Url::getInstance();
    }

}