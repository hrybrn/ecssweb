<?php
if ($_GET['login'] === 'uos-saml') {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth(array(
        'ReturnTo' => $_GET['return-to']
    ));
}
http_response_code(404);
echo '404';