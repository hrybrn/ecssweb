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

$emailParts = explode("@", $userInfo['email']);

if($emailParts[1] != "ecs.soton.ac.uk"){
	echo "You're not a member of ECS";
	exit;
}

$sql = "SELECT a.adminID
FROM admin AS a
WHERE a.username = :username;";

$statement = $db->prepare($sql);
$statement->execute(array(':username' => $userInfo['username']));

if(!$user = $statement->fetchObject()){
    http_response_code(403);
    echo "user " . $userInfo['username'] . " doesn't have permissions for this page";
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

<script type="text/javascript" src='/static/slideshow.js'></script>
<script type="text/javascript" src='/load-image.min.js'></script>
<link rel="stylesheet" href="/shop/item/slideshow.css">
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

$itemID = $_GET['itemID'];

$sql = "SELECT *
		FROM item AS i
        INNER JOIN itemColour AS ic
        ON i.itemID = ic.itemID
		WHERE i.itemID = :itemID";

$statement = $db->prepare($sql);
$statement->execute([':itemID' => $itemID]);

$items = [];

$files = [];
$indexedFiles = [];

while($row = $statement->fetchObject()){
    $items[] = $row;
    $files[] = "../../" . $row->itemColourImage;
    $indexedFiles[$row->itemColourID] = "../../" . $row->itemColourImage;
}

$sql = "SELECT *
        FROM size AS s
        WHERE s.itemID = :itemID";

$statement = $db->prepare($sql);
$statement->execute([':itemID' => $itemID]);

$sizes = [];
while($row = $statement->fetchObject()){
    $sizes[] = $row;
}

?>
<div id='itemSlideshowContainer'>
    <div id='itemSlideshow'></div>
</div>

<div id='itemInfo'>
    <h3>
        <?= $items[0]->itemName ?>
    </h3>

    <p>
        <?= $items[0]->itemPrice ?>
    </p>

    <p>
        <?= $items[0]->itemDesc ?>
    </p>

    <table id='sizeAndColour'>
        <tr>
            <td>
                Colour
            </td>
            <td>
                <select id='colourSelect'>
                    <?php
                        foreach($items as $item){
                            echo "<option value=" . $item->itemColourID . ">" . $item->itemColourName . "</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Size
            </td>
            <td>
                <select id='sizeSelect'>
                    <?php
                        foreach($sizes as $size){
                            echo "<option value=" . $size->sizeID . ">" . $size->sizeName . "</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="VVBTZS6SKRHUQ">

        <input type="hidden" name="item_name" value="<?= $items[0]->itemName ?>">
        <input type="hidden" name="item_number" value="<?= $itemID ?>">

        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="amount" value="<?= preg_replace("/£/", "", $items[0]->itemPrice) ?>">  

        <input type="hidden" name="on0" value="Colour">        
        <input type="hidden" name="os0" value="<?= $items[0]->itemColourName ?>" id='colour'>

        <input type="hidden" name="on1" value="Size">  
        <input type="hidden" name="os1" value="<?= $sizes[0]->sizeName ?>" id='size'>

        <input type="hidden" name="on2" value="Username">  
        <input type="hidden" name="os2" value="<?= $userInfo['username'] ?>">
        
        <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
    </form>

    <p>
        Payments authenticated by iSolutions, and paid through Paypal.
    </p>
</div>

<script type="text/javascript">
    var files = <?= json_encode($files) ?>;
    var indexedFiles = <?= json_encode($indexedFiles) ?>;
    
    var slideshow = new Slideshow(document.getElementById("itemSlideshow"), files, 2000);

    $('#sizeSelect').change(function(){
        var size = $('#sizeSelect').find(':selected').html();

        $('#size').val(size);
    });

    $('#colourSelect').change(function(){
        var colour = $('#colourSelect').find(':selected').html();
        $('#colour').val(colour);
    });
</script>