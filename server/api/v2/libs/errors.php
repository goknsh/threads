<?php

class Errors
{
    public static function PDOException($e)
    {
        http_response_code(500);
        return json_encode(array(
            "ok" => false,
            "response" => "serverError",
            "error" => array(
                "type" => "database",
                "message" => $e->getMessage(),
                "code" => $e->getCode(),
            ),
            "trace" => array(
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ),
        ));
    }
    public static function unsendableEmail($email)
    {
        http_response_code(500);
        return json_encode(array(
            "ok" => false,
            "response" => "serverError",
            "error" => array(
                "type" => "emailError",
                "message" => "We could not send an email to {$email}",
            ),
        ));
    }
    public static function emailVerified()
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "emailError",
                "message" => "Your account has already been verified",
            ),
        ));
    }
    public static function incorrectHash()
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "emailError",
                "message" => "The given hash does not match the required hash",
            ),
        ));
    }
    public static function invalidImage()
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "invalidImage",
                "message" => "The uploaded image is invalid",
            ),
        ));
    }
    public static function uploadFailed()
    {
        http_response_code(500);
        return json_encode(array(
            "ok" => false,
            "response" => "serverError",
            "error" => array(
                "type" => "uploadFailed",
                "message" => "Image upload failed; try again later",
            ),
        ));
    }
    public static function incompleteBody($required, $optional)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "incompleteBody",
                "message" => "The required body is not present",
                "headers" => array(
                    "required" => $required,
                    "optional" => $optional,
                ),
            ),
        ));
    }
    public static function incompleteHeader($required, $optional)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "incompleteHeader",
                "message" => "The required headers are not present",
                "headers" => array(
                    "required" => $required,
                    "optional" => $optional,
                ),
            ),
        ));
    }
    public static function incompleteParams($required, $optional)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "incompleteParams",
                "message" => "The required params are not present",
                "params" => array(
                    "required" => $required,
                    "optional" => $optional,
                ),
            ),
        ));
    }
    public static function requestMethod($accepted, $unaccepted)
    {
        http_response_code(405);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "requestMethod",
                "message" => "The {$unaccepted} method is an invalid method of accessing this page",
                "acceptedMethods" => $accepted,
            ),
        ));
    }
    public static function invalidVerificationMethod($accepted, $unaccepted)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "invalidVerificationMethod",
                "message" => "The {$unaccepted} method is an invalid method of verification",
                "acceptedMethods" => $accepted,
            ),
        ));
    }
    public static function dnsRecordNotFound($type, $domain, $value)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "dnsRecordNotFound",
                "message" => "$type record at $domain with value of \"$value\" was not found",
            ),
        ));
    }
    public static function fileNotFound($file, $value)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "fileNotFound",
                "message" => "File with contents of \"$value\" was not found",
                "link" => $file,
            ),
        ));
    }
    public static function invalidURL($url)
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "invalidURL",
                "message" => "$url is an invalid URL",
            ),
        ));
    }
    public static function JWT($e)
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "tokenError",
                "message" => $e->getMessage(),
                "code" => $e->getCode(),
            ),
            "trace" => array(
                "file" => $e->getFile(),
                "line" => $e->getLine(),
            ),
        ));
    }
    public static function noAuthHeader()
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "tokenError",
                "message" => "No Authorization token was supplied",
            ),
        ));
    }
    public static function userExists($email)
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "userExists",
                "message" => "The user with an email of {$email} exists",
            ),
        ));
    }
    public static function domainExists($domain)
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "domainExists",
                "message" => "The domain {$domain} already exists",
            ),
        ));
    }
    public static function domainAlreadyVerified($domain)
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "domainAlreadyVerified",
                "message" => "The domain {$domain} has already been verified",
            ),
        ));
    }
    public static function domainDoesNotExist($domain)
    {

        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "domainDoesNotExist",
                "message" => "The domain {$domain} does not exist in our database",
            ),
        ));
    }
    public static function unverifiedEmail()
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "unverifiedEmail",
                "message" => "This account's email address has not been verified",
            ),
        ));
    }
    public static function incorrectPassword()
    {
        http_response_code(401);
        return json_encode(array(
            "ok" => false,
            "response" => "userError",
            "error" => array(
                "type" => "incorrectPassword",
                "message" => "The given password is incorrect",
            ),
        ));
    }
    public static function unableToAddComment()
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "unableToAddComment",
                "message" => "We are unable to add this comment",
            ),
        ));
    }
    public static function unableToAddReply()
    {
        http_response_code(400);
        return json_encode(array(
            "ok" => false,
            "response" => "clientError",
            "error" => array(
                "type" => "unableToAddReply",
                "message" => "We are unable to add this reply",
            ),
        ));
    }
}
