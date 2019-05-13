<?php

require __DIR__ . "/../../libs/errors.php";
require __DIR__ . "/../../libs/database.php";
require __DIR__ . "/../../libs/hash.php";
require __DIR__ . "/../../libs/auth.php";
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                $user = Auth::verify($_SERVER["HTTP_AUTHORIZATION"]);
                if ($user) {
                    $domainIDs = Database::$query->query("SELECT `domain_id` from `domains` where `user_id`='{$user["user_id"]}'")->fetchAll(PDO::FETCH_COLUMN);
                    $domainsInfo = Database::$query->query("SELECT `domain_id`, `domain_id`, `domain`, `name`, `permissions`, `settings` from `domains` where `user_id`='{$user["user_id"]}'")->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
                    $domainsInfo = array_map("reset", $domainsInfo);
                    $i = 0;
                    foreach ($domainsInfo as $domainInfo) {
                        $domainsInfo[$domainIDs[$i]]["settings"] = json_decode($domainsInfo[$domainIDs[$i]]["settings"]);
                        $i++;
                    }
                    echo json_encode(array(
                        "ok" => true,
                        "ids" => $domainIDs,
                        "domains" => $domainsInfo,
                    ));
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
