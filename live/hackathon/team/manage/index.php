<?php
$relPath = "../../../";
include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

require_once($relPath . '../config/config.php');

if (DEBUG) {
    //debug version
    $attributes = [
    	"http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname" => array("hb15g16"),
    	"http://schemas.xmlsoap.org/claims/Group" => array(
    		"Domain Users",
    		"allStudent",
    		"fpStudent",
    		"jfNISSync",
    		"fpappvmatlab2009b",
    		"AllStudentsMassEmail",
    		"f7_All_Faculty_Student",
    		"ebXRDDataSharedResourceRead",
    		"isiMUSI2015Users",
    		"jfSW_Deploy_OpenChoiceDesktop_2.2_SCCM"
    		),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress" => array("hb15g16@ecs.soton.ac.uk"),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname" => array("Harry"),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname" => array("Brown")
    ];
} else {
    //live version
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth();
    $attributes = $as->getAttributes();
}

// csrf token
session_name('csrf_protection');
session_start();
if (empty($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = bin2hex(random_bytes(32));
}
$csrftoken = $_SESSION['csrftoken'];

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

$sql = "SELECT *
FROM hackathonEvent AS he
WHERE datetime(he.hackathonApplicationStartDate) < datetime('now')
AND datetime(he.hackathonApplicationEndDate) > datetime('now')";

$statement = $db->query($sql);

$hackathonEvent = $statement->fetchObject();

if($hackathonEvent == null){
    header('Location: /');
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= $hackathonEvent->hackathonEventName ?> Team Management | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" type="text/css" href="/hackathon/signup.css" />
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

$sql = "SELECT *
        FROM hackathonPerson AS hp
        LEFT JOIN hackathonTeam AS ht
        ON hp.hackathonTeamID = ht.hackathonTeamID
        WHERE hp.hackathonPersonEmail = '" . $userInfo['email'] . "';";

$statement = $db->query($sql);
$person = $statement->fetchObject();
?>
<script src="/jquery.js"></script>

<?php if($person->hackathonTeamID && $person->hackathonTeamLeaderID == $person->hackathonPersonID): ?>
<script>
    var previous = <?= json_encode($person) ?>;
</script>
<script src='/hackathon/team/manage/manage.js'></script>
<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Management Page</h3>
    <p><?= $hackathonEvent->hackathonEventInfo ?></p>
    <p>
        ~Team Sign Up Info~
    </p>
    <table>
        <tr>
            <td>
                <label>Team Name</label>
            </td>
            <td>
                <input type='text' id='name' value='<?= $person->hackathonName ?>'></input>
            </td>
        </tr>
        <tr>
            <td colspan='2'>
            <input type='checkbox' id='matchmaking'>Allow matchmaking with other unfinished groups</input>
            </td>
        </tr>
        <tr>
            <td id='title' colspan='2'>Current Team Members</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>Email</td>
            <td>Kick</td>
        </tr>
        <?php
        $sql = "SELECT hp2.hackathonPersonName AS pname, hp2.hackathonPersonEmail AS email
                FROM hackathonPerson AS hp2
                INNER JOIN (hackathonTeam AS ht
                    INNER JOIN hackathonPerson AS hp1
                    ON ht.hackathonTeamID = hp1.hackathonTeamID)
                ON hp2.hackathonTeamID = ht.hackathonTeamID
                WHERE hp1.hackathonPersonEmail = :email
                AND ht.hackathonEventID = :eventID;";

        $statement = $db->prepare($sql);
        $statement->execute([':email' => $userInfo['email'], ':eventID' => $hackathonEvent->hackathonEventID]);

        $out = "";
        while($row = $statement->fetchObject()){
            $out .= "<tr><td>" . $row->pname . "</td>";
            $out .= "<td>" . $row->email . "</td>";
            $out .= "<td><button onclick='kick(\"" . $row->email . "\")'>Kick</button></td></tr>";
        }
        echo $out;?>

        <tr>
            <td id='title' colspan='2'>
                <label>Team Joining Tokens</label>
                <table>
                    <tr>
                        <td>Time Generated</td>
                        <td>Link</td>
                        <td>Token</td>
                        <td>Expired</td>
                        <td></td>
                    </tr>

        <?php
        $sql = "SELECT *
                FROM hackathonHash AS hh
                INNER JOIN (hackathonTeam AS ht
                    INNER JOIN hackathonPerson AS hp
                    ON ht.hackathonTeamID = hp.hackathonTeamID)
                ON hh.hackathonTeamID = ht.hackathonTeamID
                WHERE hp.hackathonPersonEmail = :email
                AND ht.hackathonEventID = :eventID
                ORDER BY hh.hackathonHashID;";
        
        $statement = $db->prepare($sql);
        $statement->execute([':email' => $userInfo['email'], ':eventID' => $hackathonEvent->hackathonEventID]);

        $out = "";
        while($row = $statement->fetchObject()){
            if($row->hackathonHashExpired){
                $hashExpired = "Yes";
            } else {
                $hashExpired = "No";
            }

            $out .= "<tr><td>" . $row->hackathonHashDate . "</td>";
            $out .= "<td><button onclick=\"copyLink('/hackathon/team/join?token=" . $row->hackathonHash . "')\">Copy Link</button></td>";
            $out .= "<td><textarea cols=10>" . $row->hackathonHash . "</textarea></td>";
            $out .= "<td>" . $hashExpired . "</td>";
            if(!$row->hackathonHashExpired){
                $out .= "<td><button onclick='expire(\"" . $row->hackathonHashID . "\")'>Expire</button></td></tr>";
            } else {
                $out .= "<td></td></tr>";
            }
        }

        echo $out;
        ?>  
                </table>
             </td>
        </tr>
        <tr>
            <td id='submit' colspan='2'>
                <button onclick='update()'>Update</button>
                <button onclick='genToken()'>Generate Token</button>
                <button onclick='disbandTeam()'>Disband Team</button>
            </td>
        </tr>
    </table>
</div>

<?php exit(); endif; if($person): ?>
<script src='/hackathon/team/manage/manage.js'></script>
<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Management Page</h3>
    <p><?= $hackathonEvent->hackathonEventInfo ?></p>
    <p>
        You are not the team leader but here you can see info on your team.
    </p>
    <table>
        <tr>
            <td>
                <label>Team Name</label>
            </td>
            <td>
                <label><?= $person->hackathonName ?></label>
            </td>
        </tr>
        <tr>
            <td id='title' colspan='2'>Current Team Members</td>
        </tr>
        <tr>
            <td>Name</td>
            <td>Email</td>
        </tr>
        <?php
        $sql = "SELECT hp2.hackathonPersonName AS pname, hp2.hackathonPersonEmail AS email
                FROM hackathonPerson AS hp2
                INNER JOIN (hackathonTeam AS ht
                    INNER JOIN hackathonPerson AS hp1
                    ON ht.hackathonTeamID = hp1.hackathonTeamID)
                ON hp2.hackathonTeamID = ht.hackathonTeamID
                WHERE hp1.hackathonPersonEmail = :email
                AND ht.hackathonEventID = :eventID;";

        $statement = $db->prepare($sql);
        $statement->execute([':email' => $userInfo['email'], ':eventID' => $hackathonEvent->hackathonEventID]);

        $out = "";
        while($row = $statement->fetchObject()){
            $out .= "<tr><td>" . $row->pname . "</td><td>" . $row->email . "</td></tr>";
        }
        echo $out;?>
        <tr>
            <td>
                <button onclick='leaveTeam()'>Leave Team</button>
            </td>
        </tr>
    </table>
</div>

<?php else: ?>

<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Management Page</h3>
    <p>You need to be part of a team to see this page</p>
    <button onclick='window.location.href="/hackathon/team/join"'>Join Existing Team</button>
    <button onclick='window.location.href="/hackathon/team/signup"'>Team Sign Up</button>
</div>

<?php endif;