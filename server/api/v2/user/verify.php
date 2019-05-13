<?php

require __DIR__ . "/../libs/errors.php";
require __DIR__ . "/../libs/database.php";
header("Content-type: application/json");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        if (Database::connect()) {
            try {
                if (isset($_GET["email"]) && isset($_GET["hash"])) {
                    $user = Database::$query->query("SELECT `user_id`, `email_verified` from `users` where `email`='{$_GET["email"]}'")->fetchColumn();
                    if ($user["email_verified"] !== "Y") {
                        if ($_GET["hash"] === $user["email_verified"]) {
                            Database::$query->exec("CREATE TABLE IF NOT EXISTS `user_$userID` (
								`comment_id` VARCHAR(128) NOT NULL,
								`domain_id` VARCHAR(40) NOT NULL,
								`url_id` VARCHAR(64) NOT NULL,
								PRIMARY KEY (`comment_id`),
								UNIQUE KEY `comment_id` (`comment_id`),
							) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
                            Database::$query->prepare("UPDATE `users` SET `email_verified`='Y' WHERE `email`='{$_GET["email"]}'")->execute();
                            echo json_encode(array(
                                "ok" => true,
                            ));
                            exit;
                        } else {
                            echo Errors::incorrectHash();
                            exit;
                        }
                    } else {
                        echo Errors::emailVerified();
                        exit;
                    }
                } else {
                    echo Errors::incompleteParams(["email", "hash"], []);
                    exit;
                }
            } catch (PDOException $e) {
                echo Errors::PDOException($e);
                exit;
            }
        }
    default:
        echo Errors::requestMethod(["GET"], $_SERVER["REQUEST_METHOD"]);
        exit;
}
