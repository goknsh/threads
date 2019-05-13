<?php

require __DIR__ . "/../vendor/autoload.php";
use \Firebase\JWT\JWT;

class Auth
{
    public static function verify($token)
    {
        try {
            if (isset($token)) {
                $env = json_decode(file_get_contents(__DIR__ . "/../.env.json"), true);
                preg_match_all("/[\S]+/", $token, $matches);
                $token = $matches[0][1];
                $user = (array) JWT::decode($token, $env["jwt"]["key"], array("HS256"));
                return (array) $user["data"];
            } else {
                echo Errors::noAuthHeader();
                exit;
            }
        } catch (Exception $e) {
            echo Errors::JWT($e);
            exit;
        }
    }
}
