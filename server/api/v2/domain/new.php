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
                if (isset($_POST["domain"]) && isset($_POST["name"])) {
                    $user = Auth::verify($_SERVER["HTTP_AUTHORIZATION"]);
                    if ($user) {
                        if (filter_var($_POST["domain"], FILTER_VALIDATE_URL)) {
                            $domain = parse_url($_POST["domain"]);
                            if (!Database::$query->query("SELECT `domain` from `domains` where `domain`='{$domain["host"]}'")->fetchColumn()) {
                                if (!Database::$query->query("SELECT `domain` from `domains_unverified` where `domain`='{$domain["host"]}' and `user_id`='{$user["user_id"]}'")->fetchColumn()) {
                                    $domain_id = Hash::generate(40);
                                    $domain_hash = Hash::generate(128);
                                    Database::$query->prepare("INSERT into `domains_unverified`(`domain_id`, `user_id`, `domain`, `name`, `domain_verified`, `permissions`, `settings`) VALUES ('$domain_id', '{$user["user_id"]}', '{$domain["host"]}', '{$_POST["name"]}', '$domain_hash', 'owner', '{}')")->execute();
                                    echo json_encode(array(
                                        "ok" => true,
                                        "domain" => $domain["host"],
                                        "hash" => $domain_hash,
                                    ));
                                    exit;
                                } else {
                                    $domainDB = Database::$query->query("SELECT `domain_verified` from `domains_unverified` where `domain`='{$domain["host"]}' and `user_id`='{$user["user_id"]}'")->fetch(PDO::FETCH_ASSOC);
                                    echo json_encode(array(
                                        "ok" => true,
                                        "domain" => "threads-verification.{$domain["host"]}",
                                        "hash" => $domainDB["domain_verified"],
                                    ));
                                    exit;
                                }
                            } else {
                                echo Errors::domainExists($domain["host"]);
                                exit;
                            }
                        } else {
                            echo Errors::invalidURL($_POST["domain"]);
                            exit;
                        }
                    }
                } else {
                    echo Errors::incompleteBody(["domain", "name"], []);
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
