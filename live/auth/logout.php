<?php
$relPath = "../";
require_once($relPath . '../config/config.php');

// logout
// clear csrf token
include_once($relPath . 'includes/destorySession.php');
destorySession('csrf_protection');
// logout from logon.soton.ac.uk
if (!DEBUG) {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    // logout if logged in
    if ($as->isAuthenticated()) {
        //$as->logout('https://society.ecs.soton.ac.uk/auth/logout.php'); // this does not work for now
        /*
        work around. actually sign out from logon.soton.ac.uk and society.ecs.soton.ac.uk SimpleSamlPhp,
        but still show 'error occurred' sometimes
        */
        header('Location: https://logon.soton.ac.uk/adfs/ls/?wa=wsignout1.0');
        exit();
    }
}

// show page after logged out
include_once ($relPath . 'includes/setLang.php');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _('Logged Out') ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="5; url=/index.php">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<div class="pageContainer">
    <p>You have logged out.</p>
    <p>You will be redirected to <a href="/index.php">homepage</a> in <span id="countdown">5</span> seconds...</p>
</div>
<script>
    // redirect countdown
    var countdownTime = 5;
    var countdownDisplay = document.getElementById('countdown');
    function countdown() {
        if (countdownTime > 2) {
            countdownTime--;
            countdownDisplay.textContent = countdownTime;
        }
    }
    setInterval(function(){countdown()}, 1000);
</script>
</body>
</html>
