<?php

function redirect() {
    header('HTTP/1.1 303 See Other');
    header('Location: ' . (isset($_SERVER['HTTPS']) ? "https" : "http") . '://' . $_SERVER[HTTP_HOST] . '/shop/boat-ball-tickets-refund-request.php');
}

function send_email_notification($username, $num, $comment) {
    $to = 'ECSS Committee <hb15g16@soton.ac.uk>';
    $subject = 'Boat Ball Tickets Refund Request from ' . $username;
    $message = "$username has requested refund for $num boat ball ticket(s).";
    if ($comment !== '') {
        $comment = htmlspecialchars($comment);
        $message .= "\n\n$username also left a comment:\n$comment";
    }
    $headers = "From: ecssweb <no-reply@ecssweb.ecs.soton.ac.uk>\r\nReply-To: ECSS Webmaster <jc14g16@soton.ac.uk>";
    mail($to, $subject, $message, $headers);
}

function send_email_receipt($username, $num) {
    $to = "$username <$username@soton.ac.uk>";
    $subject = 'Boat Ball Tickets Refund Request Received';
    $message = "Hello,\nWe have received your request to refund $num boat ball ticket(s), we will be processing your request as soon as we can. At the meanwhile, if you have any questions please contact us at society@ecs.soton.ac.uk\n\nElectronics and Computer Science Society (ECSS)
\nsociety@ecs.soton.ac.uk\nhttps://society.ecs.soton.ac.uk\n\n\nThis is an automated email sent to $username@soton.ac.uk";
    $headers = "From: ECS Society <no-reply@ecssweb.ecs.soton.ac.uk>\r\nReply-To: ECS Society <society@ecs.soton.ac.uk>";
    mail($to, $subject, $message, $headers);
}

/**
 * Accept POST request only
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include('../includes/error_pages/404.php');
    exit();
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
if ($num_of_tickets <= 0) {
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Number of tickets to refund should be at least 1.'
    );
    redirect();
    exit();
}

/**
 * Check database
 */
$db_path = '../../db/boat-ball/boat-ball.sqlite3';
$db = new PDO('sqlite:' . $db_path);

// check if requested before
$sql = 'SELECT * FROM refund_requests WHERE username=:username';
$statement = $db->prepare($sql);
$db_success = $statement->execute(array(
    ':username' => $user_info['username']
));
if ($db_success) {
    if ($statement->fetchObject()) {
        $_SESSION['temp-data'] = array(
            'status' => 'error',
            'message' => 'You have already requested boat ball tickets refund. If you want to change your request, please <a href="/about/contact.php">contact us.</a>',
        );
        redirect();
        exit();
    }
} else { // database error
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Unknown error, please <a href="/about/contact.php">contact us.</a>',
    );
    redirect();
    exit();
}


// write into database
$sql = 'INSERT INTO refund_requests(username, num_tickets, comments)
        VALUES (:username, :num_tickets, :comments)';

$statement = $db->prepare($sql);
$db_success = $statement->execute(array(
    ':username' => $user_info['username'],
    ':num_tickets' => $num_of_tickets,
    ':comments' => $_POST['comments']
));

if ($db_success) { // success
    $_SESSION['temp-data'] = array(
        'status' => 'success',
        'message' => 'Successfully submitted request to refund ' . $num_of_tickets . ' boat ball ticket(s) for ' . $user_info['username'] . '.'
    );
    // send email notification
    send_email_notification($user_info['username'], $num_of_tickets, $_POST['comments']);
    // send email receipt
    send_email_receipt($user_info['username'], $num_of_tickets);
} else { // database error
    $_SESSION['temp-data'] = array(
        'status' => 'error',
        'message' => 'Unknown error, please <a href="/about/contact.php">contact us.</a>',
    );
}
redirect();
exit();
