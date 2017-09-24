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

$groupSelect = "<select id='groupSelect'>";
foreach($groups as $id => $name){
    $groupSelect .= "<option value='" . $id . "'>" . $name . "</option>";
}

$groupSelect .= "</select>";

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
    <script src="/jumpstart/committee/committee.js"></script>

    

</head>
<body>
<?= getNavBar(); ?>
<script src="/jquery.js"></script>
<script src="/ajaxfileupload.js"></script>
<?= $groupSelect ?>
<button onclick='save()''>Save</button>
<script>
    var lang = '<?= $lang ?>';
    $(document).ready(function(){
        load();
        showGroup(1);
    });
</script>