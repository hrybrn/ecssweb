<?php
$current_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI];
?>
<div class="alert alert-info">Login required</div>
<form action="/auth/login.php">
    <input type="hidden" name="return-to" value="<?= $current_url ?>">
    <button class="btn btn-primary" name="login" value="uos-saml">Login with university account</button>
</form>