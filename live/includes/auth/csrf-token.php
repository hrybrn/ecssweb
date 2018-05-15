<?php

function set_csrf_token() {
    session_start();
    if (empty($_SESSION['csrftoken'])) {
        $_SESSION['csrftoken'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrftoken'];
}

function check_post_csrf_token() {
    session_start();
    return isset($_SESSION['csrftoken']) && isset($_POST['csrftoken']) && hash_equals($_SESSION['csrftoken'], $_POST['csrftoken']);
}
