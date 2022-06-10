<?php
session_start();

class DataBaseConnect
{
    /**
     * DB接続
     *
     * @return mixed $db_info
     */
    public function connectDataBase()
    {
        $db_name = 'pgsql:dbname=board_database; host=db; port=5555;';
        $user = 'root';
        $bd_password = 'password';
        $db_info = new PDO($db_name, $user, $bd_password);
        return $db_info;
    }
}