<?php
$relPath = "../";
include_once ($relPath . 'includes/setLang.php');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _("About") ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $relPath ?>theme.css">
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$orderID = $_GET['orderID'];

$sql = "INSERT INTO orderEntry(orderID, orderTime) values(:orderID, :orderTime);";
$statement = $db->prepare($sql);

$time = new DateTime();
$time = $time->format(DateTime::ATOM);

$statement->execute([
    ':orderID' => $orderID,
    ':orderTime' => $time 
]);
?>
<p style='text-align: center;'>
Thank you for ordering merch from ECSS!
</p>