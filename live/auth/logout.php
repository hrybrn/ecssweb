<?php
$relPath = "../";
require_once($relPath . '../config/config.php');

// logout
if (!DEBUG) {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    // logout if logged in
    if ($as->isAuthenticated()) {
        $as->logout('https://society.ecs.soton.ac.uk/auth/logout.php');
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
