<?php
$relPath = "../../";

require_once($relPath . '../config/config.php');

include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

include_once($relPath . "navbar/navbar.php");

if (DEBUG) {
    //debug version
    $username = "hb15g16";
} else {
    //live version
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth();
    $attributes = $as->getAttributes();
    $username = $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0];
}

$sql = "SELECT j.groupID, g.groupName
		FROM (helper AS h
		INNER JOIN jumpstart AS j ON h.memberID = j.memberID)
		INNER JOIN jumpstartGroup AS g ON g.groupID = j.groupID
		WHERE h.username = :username;"; 

$statement = $db->prepare($sql);
$statement->execute(array(':username' => $username));

if(!$helper = $statement->fetchObject()){
	header('Location: /jumpstart/committee');
	exit;
}

//generate time 30 minutes from now
$time = new DateTime();
$time->add(new DateInterval('PT30M')); // valid for 30 minutes
$expiry = $time->format(DateTime::RFC1036);

//generate authentication hash
$hash = hash("sha256", $expiry . $helper->groupID);

$sql = "INSERT INTO uploadHash (groupID, hash, expiry) values (:groupID, :hash, :expiry);";
$statement = $db->prepare($sql);
$statement->execute(array(
	':groupID' => $helper->groupID,
	':hash' => $hash,
	':expiry' => $expiry,
));

$sql = "SELECT *
		FROM task AS t
		LEFT JOIN (
			SELECT *
			FROM taskEntry
			WHERE latest = 1
			AND groupID = :groupID
		)
		AS te
		ON t.taskID = te.taskID;";

$statement = $db->prepare($sql);
$statement->execute(array(':groupID' => $helper->groupID));

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
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" href="/jumpstart/admin/admin.css" />
    <script src="/jumpstart/admin/admin.js"></script>

    

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
			<input type="text" id="name" placeholder="<?= $helper->groupName ?>">
		</td>
	</tr>
<?php
	$previousEntries = array();

	foreach($tasks as $task){
		$html = "<tr><td><p>" . $task->taskName . "</p><p>" . $task->description . "</p></td>";

		if(!isset($entry)){
			$value = "";
		} else {
			$value = $entry->entry;
		}

		if($task->file){
			$html .= "<td><input type='file' id='task" . $task->taskID . "' name='task" . $task->taskID . "'><progress id='prog" . $task->taskID . "' value='0' min='0' max='100'></progress>";

			if(isset($entry)){
				$time = new DateTime($entry->entryTime);
				$html .= "<p id='para" . $task->taskID . "'>Submitted " . $time->format('d/m/Y, H:i:s') . "</p><img class='taskimg' id='img" . $task->taskID . "' src='" . $entry->entry . "'>";
			}

			$html .= "</td></tr>";
		}

		else {
			

			$html .= "<td><textarea id='task" . $task->taskID . "' rows=5>" . $value . "</textarea>";

			if(isset($entry)){
				$time = new DateTime($entry->entryTime);
				$html .= "<p id='para" . $task->taskID . "'>Submitted " . $time->format('d/m/Y, H:i:s') . "</p>";
			}

			$html .= "</td></tr>";

			$previousEntries[$task->taskID] = $value;
		}

		
		echo $html;
	}
	echo
		"<script>
			var previousEntries = " . json_encode($previousEntries) . ";
			var groupID = " . $helper->groupID . ";
			var hash = '" . $hash . "';
		</script>";
?>
	<tr>
		<td></td>
		<td>
			<button onclick='save();'>Save</button>
		</td>
	</tr>
</table>

<script src="/jquery.js"></script>
<script src="/ajaxfileupload.js"></script>
