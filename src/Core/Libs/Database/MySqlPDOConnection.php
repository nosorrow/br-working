<?php

namespace Core\Libs\Database;


class MySqlPDOConnection
{
    /**
     * @var \PDO
     */
    public $dbh;

    /**
     * @var null
     */
    private static $instance = null;

    private function __construct()
    {
        $driver = 'mysql';
        $database = '';
        $host = '';
        $username = '';
        $password = '';

        $mysql = include realpath(APPLICATION_DIR . "Config/mysql_db_config.php");

        extract($mysql);

        $dsn = $driver . ':dbname=' . $database . ';' . 'host=' . $host;
        try {

            $this->dbh = new \PDO($dsn, $username, $password,
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

            $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {

            die('Грешка при връзка с DB');
        }
    }


    /**
     *
     * @return MySqlPDOConnection|null
     */
    public static function getInstance()
    {
        if (self::$instance === null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return  \PDO
     */
    public function getConnection()
    {
        return $this->dbh;
    }
}