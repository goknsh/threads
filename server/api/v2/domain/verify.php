<?php

require __DIR__ . "/../libs/errors.php";
require __DIR__ . "/../libs/database.php";
require __DIR__ . "/../libs/hash.php";
require __DIR__ . "/../libs/auth.php";
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                if (isset($_POST["domain"]) && isset($_POST["verifyMethod"]) && isset($_POST["disregardParams"]) && isset($_POST["disregardHash"])) {
                    $user = Auth::verify($_SERVER["HTTP_AUTHORIZATION"]);
                    if ($user) {
                        if (filter_var($_POST["domain"], FILTER_VALIDATE_URL)) {
                            $_POST["verifyMethod"] = strtolower($_POST["verifyMethod"]);
                            if ($_POST["verifyMethod"] === "dns" || $_POST["verifyMethod"] === "file") {
                                $domain = parse_url($_POST["domain"]);
                                $domainDB = Database::$query->query("SELECT * from `domains_unverified` where `domain`='{$domain["host"]}' and `user_id`='{$user["user_id"]}'")->fetch(PDO::FETCH_ASSOC);
                                if ($domainDB) {
                                    $settings = json_encode(array(
                                        "disregardHash" => (bool) $_POST["disregardHash"],
                                        "disregardParams" => (bool) $_POST["disregardParams"],
                                    ));
                                    if ($_POST["verifyMethod"] === "dns") {
                                        foreach (dns_get_record("threads-verification.{$domain["host"]}", DNS_TXT)[0]["entries"] as $record) {
                                            if ($record === $domainDB["domain_verified"]) {
                                                Database::$query->prepare("INSERT into `domains`(`domain_id`, `user_id`, `domain`, `name`, `domain_verified`, `permissions`, `settings`) VALUES ('{$domainDB["domain_id"]}', '{$domainDB["user_id"]}', '{$domain["host"]}', '{$domainDB["name"]}', 'Y', '{$domainDB["permissions"]}', '{$settings}')")->execute();
                                                Database::$query->exec("DELETE from `domains_unverified` WHERE `domain`='{$domain["host"]}' and `user_id`='{$user["user_id"]}'");
                                                Database::$query->exec("CREATE TABLE IF NOT EXISTS `domain_$domainID` (
													`url_id` VARCHAR(64) NOT NULL,
													`url` VARCHAR(1024) NOT NULL,
													`name` TEXT,
													PRIMARY KEY (`url_id`),
													UNIQUE KEY `url_id` (`url_id`),
													UNIQUE KEY `url` (`url`)
												) ENGINE = InnoDB DEFAULT CHARSET=utf8;");
                                                echo json_encode(array(
                                                    "ok" => true,
                                                ));
                                                exit;
                                            }
                                        }
                                        echo Errors::dnsRecordNotFound("TXT", "threads-verification.{$domain["host"]}", $domainDB["domain_verified"]);
                                        exit;
                                    } else if ($_POST["verifyMethod"] === "file") {
                                        if (file_get_contents("{$domain["scheme"]}://{$domain["host"]}/threads-verification/{$domainDB["domain_verified"]}") === $domainDB["domain_verified"]) {
                                            Database::$query->prepare("INSERT into `domains`(`domain_id`, `user_id`, `domain`, `name`, `domain_verified`, `permissions`, `settings`) VALUES ('{$domainDB["domain_id"]}', '{$domainDB["user_id"]}', '{$domainDB["host"]}', '{$domainDB["name"]}', 'Y', '{$domainDB["permissions"]}', '{$settings}')")->execute();
                                            Database::$query->exec("DELETE from `domains_unverified` WHERE `domain`='{$domain["host"]}' and `user_id`={$user["user_id"]}");
                                            echo json_encode(array(
                                                "ok" => true,
                                            ));
                                            exit;
                                        } else {
                                            echo Errors::fileNotFound("{$domain["scheme"]}://{$domain["host"]}/threads-verification/{$domainDB["domain_verified"]}", $domainDB["domain_verified"]);
                                            exit;
                                        }
                                    }
                                } else {
                                    if (Database::$query->query("SELECT `domain` from `domains` where `domain`='{$domain["host"]}' and `user_id`='{$user["user_id"]}'")->fetch(PDO::FETCH_ASSOC)) {
                                        echo Errors::domainAlreadyVerified($domain["host"]);
                                        exit;
                                    } else {
                                        echo Errors::domainDoesNotExist($domain["host"]);
                                        exit;
                                    }
                                }
                            } else {
                                echo Errors::invalidVerificationMethod(["dns", "file"], $_POST["verifyMethod"]);
                                exit;
                            }
                        } else {
                            echo Errors::invalidURL($_POST["domain"]);
                            exit;
                        }
                    }
                } else {
                    echo Errors::incompleteBody(["domain", "verifyMethod", "disregardParams", "disregardHash"], []);
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
