<?php

require __DIR__ . "/../libs/errors.php";
require __DIR__ . "/../libs/database.php";
require __DIR__ . "/../libs/auth.php";
require __DIR__ . "/../libs/hash.php";
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                if (isset($_SERVER["HTTP_REFERER"])) {
                    if (isset($_POST["comment"])) {
                        $user = Auth::verify($_SERVER["HTTP_AUTHORIZATION"]);
                        if ($user) {
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
                                    $commentID = Hash::generate(128);
                                    $dbThreads = Database::$query->query("SELECT thread FROM `comment_$urlID`")->fetchAll();
                                    $thread = array_map('intval', explode(".", $dbThreads[sizeof($dbThreads) - 1]["thread"]))[0] + 1 . ".1";
                                    Database::$query->prepare("INSERT into `comment_$urlID`(`comment_id`, `user_id`, `thread`, `comment`) VALUES ('{$commentID}', '{$user["user_id"]}', '{$thread}', '{$_POST["comment"]}')")->execute();
                                    Database::$query->prepare("INSERT into `user_{$user["user_id"]}`(`comment_id`, `domain_id`, `url_id`) VALUES ('{$commentID}', '{$domainInfo["domain_id"]}', '{$urlID}')")->execute();
                                    echo json_encode(array(
                                        "ok" => true,
                                    ));
                                    exit;
                                } else {
                                    $urlID = Hash::generate(56);
                                    $commentID = Hash::generate(128);
                                    Database::$query->exec("CREATE TABLE IF NOT EXISTS `comment_$urlID` (
										`time` timestamp DEFAULT CURRENT_TIMESTAMP,
										`comment_id` VARCHAR(128) NOT NULL,
										`user_id` VARCHAR(40) NOT NULL,
										`thread` VARCHAR(1024) NOT NULL,
										`comment` TEXT NOT NULL,
										PRIMARY KEY (`time`),
										UNIQUE KEY `comment_id` (`comment_id`),
										UNIQUE KEY `thread` (`thread`)
									) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
                                    Database::$query->prepare("INSERT into `comment_$urlID`(`comment_id`, `user_id`, `thread`, `comment`) VALUES ('{$commentID}', '{$user["user_id"]}', '1.1', '{$_POST["comment"]}')")->execute();
                                    Database::$query->prepare("INSERT into `domain_{$domainInfo["domain_id"]}`(`url_id`, `url`, `name`) VALUES ('{$urlID}', '{$domain["path"]}', '')")->execute();
                                    Database::$query->prepare("INSERT into `user_{$user["user_id"]}`(`comment_id`, `domain_id`, `url_id`) VALUES ('{$commentID}', '{$domainInfo["domain_id"]}', '{$urlID}')")->execute();
                                    echo json_encode(array(
                                        "ok" => true,
                                    ));
                                    exit;
                                }
                            } else {
                                echo Errors::domainDoesNotExist($domain["host"]);
                                exit;
                            }
                            echo Errors::unableToAddComment();
                            exit;
                        }
                    } else {
                        echo Errors::incompleteBody(["comment"], []);
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
