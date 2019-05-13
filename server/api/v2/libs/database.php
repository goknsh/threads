<?php

class Database
{
    public static $query;
    public static function connect()
    {
        try {
            $env = json_decode(file_get_contents(__DIR__ . "/../.env.json"), true);
            $env = $env["database"];
            static::$query = new PDO("mysql:host={$env["host"]};dbname={$env["name"]}", $env["user"], $env["password"]);
            static::$query->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            echo Errors::PDOException($e);
            exit;
        }
    }
}
