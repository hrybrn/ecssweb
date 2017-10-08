<?php
/**
 * modified from https://secure.php.net/manual/en/function.session-destroy.php#example-5410
 */

/**
 * @param $sessionName
 */
function destorySession($sessionName = null)
{
    // Initialize the session.
    if (!is_null($sessionName)) {
        session_name($sessionName);
    } else {
        session_name();
    }
    session_start();

    // Unset all of the session variables.
    $_SESSION = array();

    // If it's desired to kill the session, also delete the session cookie.
    // Note: This will destroy the session, and not just the session data!
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finally, destroy the session.
    session_destroy();
}