<?php

require __DIR__ . "/../libs/errors.php";
require __DIR__ . "/../libs/database.php";
require __DIR__ . "/../libs/auth.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                if (isset($_SERVER["HTTP_REFERER"])) {
                    $domain = parse_url($_SERVER["HTTP_REFERER"]);
                    $domainInfo = Database::$query->query("SELECT `domain_id`, `settings` from `domains` where `domain`='{$domain["host"]}'")->fetch(PDO::FETCH_ASSOC);
                    if ($domainInfo) {
                        $domainInfo["settings"] = (array) json_decode($domainInfo["settings"]);
                        if (!(bool) $domainInfo["settings"]["disregardParams"]) {
                            $domain["path"] = "{$domain["path"]}?{$domain["query"]}";
                        }
                        if (!(bool) $domainInfo["settings"]["disregardHash"]) {
                            $domain["path"] = "{$domain["path"]}#{$domain["fragment"]}";
                        }
                        $urlID = Database::$query->query("SELECT `url_id` from `domain_{$domainInfo["domain_id"]}` where `url`='{$domain["path"]}'")->fetchColumn();
                        if ($urlID) {
                            $comments = Database::$query->query("SELECT * from `comment_{$urlID}`")->fetchAll(PDO::FETCH_ASSOC);
                            $userIDs = array();
                            foreach ($comments as $key => $comment) {
                                $userIDs[$key] = "'{$comment["user_id"]}'";
                            }
                            array_unique($userIDs);
                            $userIDs = join(', ', $userIDs);
                            $users = Database::$query->query("SELECT `user_id`, `name`, `profile_picture` from `users` where `user_id` in ($userIDs)")->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
                            $users = array_map("reset", $users);
                            echo json_encode(array(
                                "ok" => true,
                                "comments" => $comments,
                                "users" => $users,
                            ));
                            exit;
                        } else {
                            echo json_encode(array(
                                "ok" => true,
                                "comments" => array(),
                                "users" => array(),
                            ));
                            exit;
                        }
                    } else {
                        echo Errors::domainDoesNotExist($domain["host"]);
                        exit;
                    }
                } else {
                    echo Errors::incompleteHeader(["Referer"], []);
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
