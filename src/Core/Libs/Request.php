<?php

namespace Core\Libs;

use Core\Libs\Files\Upload;

/**
 * Class Request
 * @package Libs
 */
class Request
{
    private static $instance = null;

    public $get = array();

    public $post = array();

    public $put = array();

    public $delete = array();

    public $cookie = array();

    public $input;

    public $method;

    public $session;

    public $file;

    private function __construct()
    {
        $this->session = Session::getInstance();

        $this->post = !empty($_POST) ? $_POST : null;

        $this->get = !empty($_GET) ? $_GET : null;

        $this->cookie = !empty($_COOKIE) ? $_COOKIE : null;

        $this->file = new Upload();

        $this->httpMethodInit();

        $this->put = $this->setPut();

    }

    public function input($name, $normalize = null)
    {
        $post = $this->post($name, $normalize);
        $get = $this->get($name, $normalize);

        if (null !== $post && "" !== $post) {
            $this->input[$name] = $this->post($name, $normalize);
            //  return $this->post($name, $normalize);

        } elseif (null !== $get && "" !== $get) {
            $this->input[$name] = $this->get($name, $normalize);
            // return $this->get($name, $normalize);

        } else {

            $this->input[$name] = null;
        }

        return $this->input[$name];
    }

    /**
     * post
     *
     * @param $index
     * @return mixed
     */
    public function post($index = null, $normalize = null)
    {
        if (is_array($this->post[$index])) {

            foreach ($this->post[$index] as $key => $value) {
                $post[$key] = trim(htmlspecialchars(XssSecure::xss_clean($value)));
                if ($normalize != null) {
                    $post[$key] = self::filter($post[$key], $normalize);
                }
            }

        } else {
            $post = trim(htmlspecialchars(XssSecure::xss_clean($this->post[$index])));

            if ($normalize != null) {

                $post = self::filter($post, $normalize);
            }
        }

        return $post;
    }

    public function postAll($normalize = null)
    {
        foreach ($this->post as $key => $value) {
            $post[$key] = trim(htmlspecialchars(XssSecure::xss_clean($value)));
            if ($normalize != null) {
                $post[$key] = self::filter($post[$key], $normalize);
            }
        }

        return $post;
    }

    /**
     * пълни Get -> параметрите на
     * методите на контролерите
     * @param array $data
     */
    public function setGet(array $data)
    {
        if (isset($this->get)) {
            $this->get = array_merge($data, $this->get);

        } else {
            $this->get = $data;
        }
    }

    /**
     * $_GET
     * @param null $index
     * @param null $normalize
     * @return array|bool|float|int|null|string
     */
    public function get($index = null, $normalize = null)
    {
        if (isset($this->get[$index])) {
            $get = trim(htmlspecialchars(XssSecure::xss_clean($this->get[$index])));

            if ($normalize !== null) {
                $get = self::filter($get, $normalize);
            }

        }
        return $get;
    }

    public function getAll($normalize = null)
    {
        foreach ($this->get as $key => $value) {
            $get[$key] = trim(htmlspecialchars(XssSecure::xss_clean($value)));
            if ($normalize != null) {
                $get[$key] = self::filter($get[$key], $normalize);
            }
        }
        return $get;
    }

    /**
     * @param null $index
     * @param null $normalize
     * @return bool|float|int|string
     */
    public function put($index = null, $normalize = null)
    {
        $put = trim(htmlspecialchars(XssSecure::xss_clean($this->put[$index])));

        if ($normalize !== null) {
            $put = self::filter($put, $normalize);
        }

        return $put;
    }

    /**
     * @return array|null
     */
    public function setPut()
    {
        if ($this->post('_method') && strtoupper($this->post('_method')) == 'PUT') {
            $put = $this->post;
            unset($this->post);

        } elseif ($this->get('_method') && strtoupper($this->get('_method')) == 'PUT') {
            $put = $this->get;
            unset($this->get);
        }

        if (!empty($put)) {
            $this->method = "PUT";
        }

        return $put;

    }

    /**
     * cookie
     *
     * @param $data
     * @return mixed
     */
    public function cookie($data)
    {
        $cookie = trim(htmlspecialchars(XssSecure::xss_clean($this->cookie[$data])));

        return $cookie;
    }

    public function file()
    {
        return $this->file;
    }

    /**
     * @param $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool|false $secure
     * @param bool|true $httponly
     */
    public function set_cookie($name, $value = '', $expire = 3600, $path = '/', $domain = '',
                               $secure = false, $httponly = true)
    {
        /*
        * $cookie = array(
            'name'   => 'The Cookie Name',
            'value'  => 'The Value',
            'expire' => '86500',
            'domain' => '.some-domain.com',
            'path'   => '/',
            'prefix' => 'myprefix_',
            'secure' => TRUE
            );
        */
        if (is_array($name)) {

            extract($name);
        }

        $value = XssSecure::xss_clean($value);

        setcookie($name, $value, time() + $expire, $path, $domain, $secure, $httponly);

    }

    /**
     * filter data
     *
     * @param $data
     * @param $types
     * @return bool|float|int|string
     */
    public static function filter($data, $types)
    {
        $types = explode('|', $types);

        if (is_array($types)) {

            foreach ($types as $value) {
                switch ($value) {
                    case 'int':
                        $data = (int)$data;
                        break;
                    case 'float':
                        $data = (float)$data;
                        break;
                    case 'double':
                        $data = (double)$data;
                        break;
                    case 'bool':
                        $data = (bool)$data;
                        break;
                    case 'string':
                        $data = (string)$data;
                        break;
                    case 'addslashes':
                        $data = addslashes($data);
                        break;
                    case 'htmlentities':
                        $data = htmlentities($data, ENT_NOQUOTES, "UTF-8");
                        break;
                    case 'strip_tags':
                        $data = strip_tags($data);
                        break;
                    case 'strip_interval':
                        $data = trim(preg_replace('/\s\s+/', ' ', $data));
                        break;
                    case 'html_entity_decode':
                        $data = html_entity_decode($data);
                        break;
                    case 'trim':
                        $data = trim($data);
                        break;
                    case 'urlencode':
                        $data = urlencode($data);
                        break;
                }
            }
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function method()
    {
        return strtoupper($this->method);
    }

    /**
     * @param $httpMethod
     */
    public function setMethod($httpMethod)
    {
        $this->method = $httpMethod;
    }

    /**
     *  за PUT PATCH DELETE
     * методи които не се поддържат от браузъра се използва:
     * <input type="hidden" name="_method" value="DELETE">
     */
    private function httpMethodInit()
    {
        if ($this->post('_method')) {

            $this->method = strtoupper($this->post('_method'));

        } else {

            $this->method = $_SERVER['REQUEST_METHOD'];

        }

    }

    /**
     * за PUT PATCH DELETE
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $method = $this->method();
        if ('DELETE' === $method || 'PATCH' === $method ) {

            return $this->post($arguments[0], $arguments[1]);
        }

    }

    /**
     * @return Request|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

}