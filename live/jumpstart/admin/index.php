<?php

$relPath = "../../";

include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

include_once($relPath . "navbar/navbar.php");

//debug version
$username = "hb15g16";
//live version

$sql = "SELECT j.groupID, g.name
		FROM (helper AS h
		INNER JOIN jumpstart AS j ON h.memberID = j.memberID)
		INNER JOIN jumpstartGroup AS g ON g.groupID = j.groupID
		WHERE h.username = :username;"; 

$statement = $db->prepare($sql);
$statement->execute(array(':username' => $username));

if(!$helper = $statement->fetchObject()){
	exit;
}

$sql = "SELECT *
		FROM task AS t
		LEFT JOIN taskEntry AS te ON t.taskID = te.taskID;";

$statement = $db->query($sql);

$tasks = array();
while($row = $statement->fetchObject()){
	$tasks[] = $row;
}


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
    <link rel="stylesheet" href="<?= $relPath ?>jumpstart/admin/admin.css" />
    <script src="admin.js"></script>
</head>
<body>
<?= getNavBar(); ?>
<table>
	<tr>
		<th>Attribute</th>
		<th>Value</th>
	</tr>
	<tr>
		<td>
			<label>Name</label>
		</td>
		<td>
			<input type="text" id="name" placeholder="<?= $helper->name ?>">
		</td>
	</tr>
<?php
	foreach($tasks as $task){
		$html = "<tr><td><p>" . $task->name . "</p><p>" . $task->description . "</p></td>";

		if($task->file){
			$html .= "<td><input type='file' id='task" . $task->taskID . "'></td></tr>";
		}

		else {

			if(!isset($task->entry)){
				$task->entry = "";
			}

			$html .= "<td><textarea rows=5 placeholder='" . $task->entry . "'></textarea></td></tr>"; 
		}

		echo $html;
	}
?>
	<tr>
		<td></td>
		<td>
			<button onclick='save();'>Save</button>
		</td>
</table>