<?php
if (isset($_GET['login']) && $_GET['login'] === 'uos-saml') {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth(array(
        'ReturnTo' => $_GET['return-to']
    ));
    exit();
}
include('../includes/error_pages/404.php');
