<?php

require __DIR__ . "/../../libs/errors.php";
require __DIR__ . "/../../libs/database.php";
require __DIR__ . "/../../libs/hash.php";
require __DIR__ . "/../../libs/auth.php";
// header("Content-Type: application/json");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                $user = Auth::verify($_SERVER["HTTP_AUTHORIZATION"]);
                if ($user) {
                    $urlIDs = Database::$query->query("SELECT `domain_id`, `url_id` from `user_{$user["user_id"]}`")->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
                    $commentIDs = Database::$query->query("SELECT `url_id`, `comment_id` from `user_{$user["user_id"]}`")->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
                    foreach ($urlIDs as $domainID => $urlID) {
                        $urlIDs[$domainID] = array_unique($urlID);
                        foreach ($urlIDs[$domainID] as $urlID) {
                            $comments[$domainID][$urlID] = $commentIDs[$urlID];
                        }
                    }
                    echo json_encode(array(
                        "ok" => true,
                        "comments" => $comments,
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
