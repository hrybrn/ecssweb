<?php

function redirect() {
    header('HTTP/1.1 303 See Other');
    header('Location: ' . (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER[HTTP_HOST] . '/shop/boat-ball-tickets-refund-request.php');
}

/**
 * Accept POST request only
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include('../includes/error_pages/404.php');
}

/**
 * Check csrf
 */
include_once('../includes/auth/csrf-token.php');
if (!check_post_csrf_token()) { // fail csrf check
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Authentication failed.'
    );
    redirect();
    exit();
}

/**
 * Check login
 */
require_once('../../config/config.php');
if (!DEBUG) {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    if (!$as->isAuthenticated()) {
        $_SESSION['temp-data'] = array(
            'status' => 'error',
            'message' => 'Authentication failed.'
        );
        redirect();
        exit();
    } else {
        $attributes = $as->getAttributes();
        $user_info = array(
            'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0]
        );
    }
} else { // if debug
    $user_info = array(
        'username' => 'exampleusername'
    );
}

/**
 * Check input
 */
$num_of_tickets = $_POST['num-of-tickets'];
if (is_int($num_of_tickets) && $num_of_tickets > 0) {

} else {
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Number of tickets to refund should be at least 1.',
    );
    redirect();
    exit();
}
