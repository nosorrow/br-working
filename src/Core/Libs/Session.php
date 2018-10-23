<?php

namespace Core\Libs;

use Core\Libs\Utils\Arrays;

/*
 * $session = new Session();
 *
 * $session->store('name', 'value');
 *
 */

/**
 * Class Session
 * @package Core\Libs
 */
class Session
{

    private static $instance = null;

    public $session_save_path;

    public $config;

    protected $session_bag;

    /**
     * Session constructor.
     *
     */
    protected function __construct()
    {
        $driver = Config::getConfigFromFile('session_handler');
        if ($driver == 'database') {
            include_once SYSTEM_DIR . 'Libs\Session\DataBaseSessionHandler.php';
        }
        $name = Config::getConfigFromFile('session_name');
        $lifetime = Config::getConfigFromFile('session_cookie_lifetime');
        $session_save_path = Config::getConfigFromFile('session_save_path');
        $domain = null;
        $secure = Config::getConfigFromFile('session_secure');

        $this->session_save_path = $session_save_path;
        if (!realpath($session_save_path)) {
            mkdir($session_save_path, 0777, true);
        }
        session_name($name);
        session_save_path($session_save_path);
        session_set_cookie_params($lifetime, '/', $domain, $secure, true);
        session_start();

        $this->session_bag = !empty($_SESSION) ? $_SESSION :null;
    }

    public function session_regenerate($_bool, $_time = 300)
    {
        // Времето на първата сесия
        if (!isset($_SESSION['last_regen'])) {
            $_SESSION['last_regen'] = time();
        }

        $session_regen_time = $_time;

        // Only regenerate session id if last_regen is older than the given regen time.
        if ($_SESSION['last_regen'] + $session_regen_time < time()) {
            $_SESSION['last_regen'] = time();
            $bool = (bool)$_bool;
            session_regenerate_id($bool);
        }

    }

    /**
     * @param $data
     * @return mixed
     */
    public function getConfig($data)
    {
        return $this->config[$data];
    }

    /**
     * @param mixed $config
     * @param $data
     */
    public function setConfig($config, $data)
    {
        $this->config[$config] = $data;
    }


    /**
     * @param $name
     * @param null $data
     */
    public function store($name, $data = null)
    {
        if (!is_array($name)) {
            $name = [$name => $data];
        }

        foreach ($name as $key => $value) {
            Arrays::set($_SESSION, $key, $value);

        }
    }

    /**
     * Искам на изхода сесията да ми е чиста;
     * @param array $array
     * @return array
     */
    protected function recursiveCleanSession(array $array)
    {
        array_walk_recursive($array, function (&$value) {
            $value = XssSecure::xss_clean($value);
        });

        return $array;
    }

    /**
     * @return array
     */
    public function get_all()
    {
        $session = $this->recursiveCleanSession($_SESSION);

        return $session;
    }

    /**
     * @param $name
     * @return null
     */
    public function getData($name)
    {
        $session = $this->recursiveCleanSession($_SESSION);

        return Arrays::get($session, $name, []);
    }

    /**
     * @param $name
     * @return bool
     */

    public function has($name)
    {
        $array = (Arrays::get($_SESSION, $name, []));

        return (bool)($array);

    }

    /**
     * @param $name
     * @param $value
     */
    public function push($name, $value)
    {
        $array = (array)(Arrays::get($_SESSION, $name, []));

        $array[] = $value;

        $this->store($name, $array);
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function pull($name, $default = null)
    {
        return Arrays::pull($_SESSION, $name, $default);

    }

    /**
     * @param $name
     * @param $value
     */
    public function setFlash($name, $value)
    {
        $_SESSION['flash'][$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getFlash($name)
    {
        $flash = array();

        if (isset($_SESSION['flash'][$name])) {

            $flash[$name] = $_SESSION['flash'][$name];

            unset ($_SESSION['flash'][$name]);
        }
        return ($flash[$name]);
    }

    /**
     * destroy
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * delete
     * @param $name
     *
     */
    public function delete($name)
    {
        Arrays::forget($_SESSION, $name);
    }

    /**
     * gc
     */
    public function gc()
    {
        foreach (glob($this->session_save_path . "/sess_*") as $filename) {
            if (filemtime($filename) + session_get_cookie_params()['lifetime'] < time()) {
                @unlink($filename);
            }
        }
    }

    /**
     * @return Session|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

}