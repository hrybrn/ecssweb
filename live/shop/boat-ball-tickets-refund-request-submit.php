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
if (is_numeric($num_of_tickets)) {
    $num_of_tickets = intval($num_of_tickets);
} else {
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Check your input for number of tickets to refund.'
    );
    redirect();
    exit();
}
if (!$num_of_tickets > 0) {
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Number of tickets to refund should be at least 1.'
    );
    redirect();
    exit();
}

/**
 * Write to database
 */
$db_path = '../../db/boat-ball/boat-ball.sqlite3';
$db = new PDO('sqlite:' . $db_path);

$sql = 'INSERT INTO refund_requests(username, num_tickets, comments)
        VALUES (:username, :num_tickets, :comments)';

$statement = $db->prepare($sql);
$db_success = $statement->execute(array(
    ':username' => $user_info['username'],
    ':num_tickets' => $num_of_tickets,
    ':comments' => $_POST['comments']
));

if ($db_success) {
    $_SESSION['temp-data'] = array(
        'status' => 'success',
        'message' => 'Successfully submitted request to refund ' . $num_of_tickets . ' boat ball ticket(s) for ' . $user_info['username'] . '.'
    );
} else {
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Unknown error, please <a href="/about/contact.php">contact us.</a>',
    );
}
redirect();
exit();
