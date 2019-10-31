<?php
/**
 * Authentication.class.php
 * 
 * @author  Romain Penchenat <romain.penchenat@icloud.com>
 * @license Apache 2.0
 * @since 1.0
 * 
*/

/**
 * Authentication manager for Candide Admin Interface
 * 
 * @since 1.0
 * No childclasses
 * 
 */
class Authentication {

    const KEY_SESSION_IDENTIFIER = PROJECT_NAME."_logedin";

    /**
     * Return Session identifier if exists
     *
     * @return String|null
     */
    static function getSessionIdentifier():?String{
        if (!key_exists(Authentication::KEY_SESSION_IDENTIFIER,$_SESSION)) {
            return null;
        }
        return $_SESSION[Authentication::KEY_SESSION_IDENTIFIER];
    }

    /**
     * Define session identifier
     *
     * @param Array $user [User informations]
     * @return void
     */
    static function setSessionIdentifier(Array $user){
        $_SESSION[Authentication::KEY_SESSION_IDENTIFIER] = hash("sha256",$user[0]).hash("sha256",$user[1]);
    }

    /**
     * Logout current user
     *
     * @return void
     */
    static function logout() {
        unset($_SESSION[Authentication::KEY_SESSION_IDENTIFIER]);
    }

    /**
     * Check if user is authenticated else redirect to login page
     *
     * @return void
     */
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


    /**
     * Try to login the user
     *
     * @param Array $p [Data posted by the user who try to login]
     * @param AdminTextsManager $texts [AdminTextsManager with errors traduction]
     * @return Array [Possible errors]
     */
    static function login(Array $p, AdminTextsManager $texts):Array {
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