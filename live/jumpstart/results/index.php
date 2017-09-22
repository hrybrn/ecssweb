<?php
$relPath = "../../";

require_once($relPath . '../config/config.php');

include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

include_once($relPath . "navbar/navbar.php");
echo getNavBar();

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

$sql = "SELECT a.adminID
		FROM admin AS a
		WHERE a.username = :username;";

$statement = $db->prepare($sql);
$statement->execute(array(':username' => $username));

if(!$user = $statement->fetchObject()){
	exit;
}


$sql = "SELECT g.groupID, g.groupName
		FROM jumpstartGroup AS g";

$statement = $db->query($sql);
$groups = array();
while($group = $statement->fetchObject()){
	$groups[$group->groupID] = $group->groupName;
}

echo "<select id='groupSelect'>";
foreach($groups as $id => $name){
	echo "<option value='" . $id . "'>" . $name . "</option>";
}

echo "</select>";
?>

<script src='/jquery.js'></script>
<script src='/jumpstart/results/results.js'></script>
<link rel="stylesheet" type="text/css" href="/theme.css" />
<link rel="stylesheet" type="text/css" href="/jumpstart/results/results.css" />

<script type="text/javascript">
	var lang = '<?= $lang ?>';
	$(document).ready(function(){
		load();
		showEntries(1);
	});
</script>