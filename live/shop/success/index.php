<?php
$relPath = "../../";
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

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);


if(!(in_array("fpStudent", $userInfo['groups']) || in_array("fpStaff", $userInfo['groups']) || in_array("pjStudent", $userInfo['groups']) || in_array("pjStaff", $userInfo['groups']))){
	echo json_encode(['status' => false, 'message' => "You're not a member of ECS"]);
	exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title>Shop | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" type="text/css" href="/shop/item/item.css" />
</head>
<body>
<script type="text/javascript" src='/jquery.js'></script>
<script type="text/javascript" src='/shop/item/item.js'></script>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

?>
<div style='text-align: center;'>
    <h3>Thank you for your purchase!</h3>
    <p>Please come collect your merch on the day you selected.</p>
    <p>Paypal sends you the order details in the email. If any of these details are innacurate please email <a href='mailto:hb15g16@soton.ac.uk'>Harry Brown</a> to correct this.</p>
    <p>If you have any feedback on the amazing shopping experience you just had, you can submit it anonymously <a href='/comment'>here</a>.</p>
</div>