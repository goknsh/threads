<?php

require __DIR__ . "/../libs/errors.php";
require __DIR__ . "/../libs/database.php";
require __DIR__ . "/../vendor/autoload.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
$env = json_decode(file_get_contents(__DIR__ . "/../.env.json"), true);
$_POST = json_decode(file_get_contents("php://input"), true);
use \Firebase\JWT\JWT;

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                if (isset($_POST["email"]) && isset($_POST["password"])) {
                    if (password_verify($_POST["password"], Database::$query->query("SELECT `password` from `users` where `email`='{$_POST["email"]}'")->fetchColumn())) {
                        if (Database::$query->query("SELECT `email_verified` from `users` where `email`='{$_POST["email"]}'")->fetchColumn() === "Y") {
                            $user = Database::$query->query("SELECT `user_id`, `email`, `name`, `profile_picture` from `users` where `email`='{$_POST["email"]}'")->fetch(PDO::FETCH_ASSOC);
                            echo json_encode(array(
                                "ok" => true,
                                "token" => JWT::encode(array(
                                    "iss" => $env["server"]["server"],
                                    "aud" => $env["server"]["client"],
                                    "iat" => time(),
                                    "nbf" => time(),
                                    "exp" => time() + 21600,
                                    "jti" => base64_encode(random_bytes(22)),
                                    "data" => array(
                                        "user_id" => $user["user_id"],
                                        "email" => $user["email"],
                                        "name" => $user["name"],
                                        "profile_picture" => $user["profile_picture"],
                                    ),
                                ), $env["jwt"]["key"]),
                            ));
                            exit;
                        } else {
                            echo Errors::unverifiedEmail();
                            exit;
                        }
                    } else {
                        echo Errors::incorrectPassword();
                        exit;
                    }
                } else {
                    echo Errors::incompleteBody(["email", "password"], []);
                    exit;
                }
            } catch (PDOException $e) {
                echo Errors::PDOException($e);
                exit;
            }
        }
    default:
        echo Errors::requestMethod(["POST"], $_SERVER["REQUEST_METHOD"]);
        exit;
}
