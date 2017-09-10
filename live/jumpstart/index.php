<?php
$relPath = "../";

include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _('Jumpstart') ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

//build group data
$sql = "SELECT *
		FROM jumpstart AS j
		INNER JOIN helper AS h
		ON j.memberID = h.memberID
		WHERE j.helper = 1;";

$statement = $db->query($sql);
$results = array();

while($rowObject = $statement->fetchObject()){
	$results[] = $rowObject;
}
?>
<script src="jumpstart.js"></script>
<link rel="stylesheet" href="<?= $relPath ?>theme.css">
<script type="text/javascript">
	var groups = <?= json_encode($results) ?>;

	$(document).ready(function(){
		load();
	});
</script>
</body>
</html>
