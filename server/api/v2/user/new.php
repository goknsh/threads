<?php

require __DIR__ . "/../libs/errors.php";
require __DIR__ . "/../libs/database.php";
require __DIR__ . "/../libs/mail.php";
require __DIR__ . "/../libs/hash.php";
header("Content-Type: application/json");
$_POST = json_decode(file_get_contents("php://input"), true);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        if (Database::connect()) {
            try {
                if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["name"]) && isset($_FILES["profPic"])) {
                    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
                    $email_hash = Hash::generate(128);
                    $user_id = Hash::generate(40);
                    if (!Database::$query->query("SELECT `email` from `users` where `email`='{$_POST["email"]}'")->fetchColumn()) {
                        if (getimagesize($_FILES["profPic"]["tmp_name"])) {
                            $image_name = Hash::generate(64) . "." . strtolower(pathinfo($_FILES["profPic"]["name"], PATHINFO_EXTENSION));
                            if (move_uploaded_file($_FILES["profPic"]["tmp_name"], __DIR__ . "/images/profile-pictures/" . $image_name)) {
                                if (Mail::verifyUser($_POST["email"], $_POST["name"], $email_hash)) {
                                    Database::$query->prepare("INSERT into `users`(`user_id`, `email`, `name`, `password`, `email_verified`, `profile_picture`) VALUES ('$user_id', '{$_POST["email"]}', '{$_POST["name"]}', '$password', '$email_hash', '$image_name')")->execute();
                                    echo json_encode(array(
                                        "ok" => true,
                                    ));
                                    exit;
                                } else {
                                    echo Errors::unsendableEmail($_POST["email"]);
                                    exit;
                                }
                            } else {
                                echo Errors::uploadFailed();
                                exit;
                            }
                        } else {
                            echo Errors::invalidImage();
                            exit;
                        }
                    } else {
                        echo Errors::userExists($_POST["email"]);
                        exit;
                    }
                } else {
                    echo Errors::incompleteBody(["email", "password", "name"], ["profPic"]);
                    exit;
                }
            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    echo Errors::userExists($_POST["email"]);
                    exit;
                } else {
                    echo Errors::PDOException($e);
                    exit;
                }
            }
        }
    default:
        echo Errors::requestMethod(["POST"], $_SERVER["REQUEST_METHOD"]);
        exit;
}
