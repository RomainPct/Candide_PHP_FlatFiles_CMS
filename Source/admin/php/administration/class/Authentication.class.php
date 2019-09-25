<?php

// Level 0

class Authentication {

    const KEY_SESSION_IDENTIFIER = PROJECT_NAME."_logedin";

    static function getSessionIdentifier():?String{
        if (!key_exists(Authentication::KEY_SESSION_IDENTIFIER,$_SESSION)) {
            return null;
        }
        return $_SESSION[Authentication::KEY_SESSION_IDENTIFIER];
    }

    static function setSessionIdentifier(Array $user){
        $_SESSION[Authentication::KEY_SESSION_IDENTIFIER] = hash("sha256",$user[0]).hash("sha256",$user[1]);
    }

    static function logout() {
        unset($_SESSION[Authentication::KEY_SESSION_IDENTIFIER]);
    }

    static function check() {
        if (basename($_SERVER['PHP_SELF']) == "login.php") {
            return;
        }
        if ( Authentication::getSessionIdentifier() !== null ) {
            foreach (ADMINISTRATORS as $user) {
                if (Authentication::getSessionIdentifier() == hash("sha256",$user[0]).hash("sha256",$user[1])) {
                    return; // leave the function because user is logged in
                }
            }
        }
        Authentication::logout();
        header("Location: login.php");
    }

    static function login($p,$texts):Array {
        if (count($p) == 0) {
            return [];
        }
        foreach (ADMINISTRATORS as $user) {
            if ($user[0] == $p["identifier"]) {
                if (strtoupper($user[1]) == strtoupper(hash("sha256",$p["password"]))) {
                    Authentication::setSessionIdentifier($user);
                    header("Location: ../admin/");
                } else {
                    return [
                        "password" => $texts->get("error_password"),
                        "identifier" => "ok"
                    ];
                }
            }
        }
        return [
            "identifier" => $texts->get("error_identifier")
        ];
    }

}