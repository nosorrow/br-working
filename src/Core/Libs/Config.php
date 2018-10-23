<?php


namespace Core\Libs;


class Config
{
    public static $config = array();

    public static function getConfigFromFile($name)
    {

        $config = include realpath(APPLICATION_DIR . 'Config/config.php');

        if (!isset($config[$name])) {

            throw new \Exception('Configuration is not defined in config.php', 500);

        }

        self::$config[$name] = $config[$name];

        return self::$config[$name];

    }

    /**
     * @return array
     */
    public static function getConfig($key)
    {
        return self::$config[$key];
    }

    /**
     * @param $key
     * @param $value
     */
    public static function setConfig($key, $value)
    {
        self::$config = array($key => $value);
    }

}