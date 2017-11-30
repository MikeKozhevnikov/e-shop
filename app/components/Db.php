<?php

/**
 * Db Class used for work with MySQL PDO
 */
class Db
{

    /**
     * Create connection to DataBase server
     * @return \PDO <p>PDO Object</p>
     */
    public static function createConnection()
    {
        // Use settings from db config file
        $paramsPath = ROOT . '/app/config/DbConfig.php';
        $params = include($paramsPath);

        // create connection
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['password']);

        // change charset
        $db->exec("set names utf8");

        return $db;
    }

}