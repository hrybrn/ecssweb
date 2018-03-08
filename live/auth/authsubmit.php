<?php
include_once($relPath . "auth/forcelogin.php");

// check csrf token
session_name('csrf_protection');
session_start();
if (!hash_equals($_SESSION['csrftoken'], $_POST['csrftoken'])) {
    echo json_encode(['status' => false, 'message' => "Authorization failed."]);
    exit();
}
