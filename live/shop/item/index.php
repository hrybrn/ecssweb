<?php
$relPath = "../../";
include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

require_once($relPath . '../config/config.php');

$raw = file_get_contents($relPath . "/shop/item/auth.json");
$auth = json_decode($raw, true);

$societies = [
    'ECSS',
    'Chemistry',
    'SUES'
];

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

//society select
if(in_array('fpStudent', $userInfo['groups'])){
    $society = "ECSS";
}

if(in_array('ebStudent', $userInfo['groups'])){
    $society = "Chemistry";
}

if(in_array('peStudent', $userInfo['groups'])){
    $society = "SUES";
}

if(!isset($society)){
    echo "You are not a member of ECSS, Chemistry or SUES. Please get a friend within these societies to buy your ticket.";
    exit;
}

//check for previous purchases
$limit = [
    "ECSS" => 2,
    "SUES" => 2,
    "Chemistry" => 1
];

$sql = "SELECT COUNT(*) AS count
        FROM purchase
        WHERE username = :username
        AND society = :society
        AND purchased = 1";

$statement = $db->prepare($sql);
$statement->execute([
    ':username' => $userInfo['username'],
    ':society' => $society
]);

$count = $statement->fetchObject();
$count = $count->count;

if($count >= $limit[$society]){
    include_once($relPath . "navbar/navbar.php");
    echo getNavBar();
    echo '<link rel="stylesheet" type="text/css" href="/theme.css" />';
    echo "<h4 style='text-align:center;'>You have already bought the maximum amount of tickets for this event.</h4>";
    exit;
}

//make purchase id
$date = new DateTime();
$date = $date->format(DateTime::COOKIE);

$purchaseID = hash('sha256', $date . $userInfo['username'] . $society);

//log purchase id with info
$sql = "INSERT INTO purchase (purchaseID, username, society, purchased) VALUES (:purchaseID, :username, :society, :purchased);";
$statement = $db->prepare($sql);
$statement->execute([
    ':purchaseID' => $purchaseID,
    ':username' => $userInfo['username'],
    ':society' => $society,
    ':purchased' => 0
]);

//paypal ids for prices
$paypal = [
    "",
    "VVBTZS6SKRHUQ",
    "Z8ZPHKTEU7MRG",
    "WV8C2JGKPECQ8",
    "8WLSYFSRZ5JSY",
    "6FLZWM6KXK7LS",
    "GC3SGSZ333KXE",
    "27SUETDFQ8YZY",
    "D9ZWWB2TCQXWW"
];

//check for open shop and retrieve collection dates
$sql = "SELECT *
        FROM shop AS s
        INNER JOIN collectionDates AS cd
        ON cd.shopID = s.shopID
        WHERE datetime(s.openDate) < datetime('now')
        AND datetime(s.shutDate) > datetime('now');";

$statement = $db->query($sql);
$collectionDates = [];

while($row = $statement->fetchObject()){
    $collectionDates[$row->collectionDateID] = $row->collectionDate;
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

if($count == 0){
    $tickets = "0 tickets";
}

if($count == 1){
    $tickets = "1 ticket";
}


$itemID = $_GET['itemID'];

$sql = "SELECT *
        FROM ((((item AS i
        LEFT JOIN itemColour AS ic ON i.itemID = ic.itemID)
        LEFT JOIN itemSize AS isi ON i.itemID = isi.itemID)
        LEFT JOIN itemSlogan AS isl ON i.itemID = isl.itemID)
        LEFT JOIN size AS si ON isi.sizeID = si.sizeID)
        LEFT JOIN slogan AS sl ON isl.sloganID = sl.sloganID
        WHERE i.itemID = :itemID;";

$statement = $db->prepare($sql);
$statement->execute([':itemID' => $itemID]);

$items = [];
while($row = $statement->fetchObject()){
    $items[] = $row;
}

if(empty($items)){
    echo "Invalid itemID";
    exit;
}

$colours = [];
$sizes = [];
$slogans = [];

foreach ($items as $row){
    $colours[$row->colourID] = $row->colourName;
    $sizes[$row->sizeID] = $row->sizeName;
    $slogans[$row->sloganID] = $row->sloganName;
}

unset($slogans[""]);
unset($sizes[""]);
unset($colours[""]);

sort($colours);
sort($slogans);
sort($collectionDates);
?>
<div id='containsEverything'>
<div id='itemSlideshowContainer'>
    <div id='itemColours'>
        <img class='colour' src='<?= $relPath . $items[0]->itemImage ?>'>
    </div>
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

        <?php if($items[0]->itemSizeGuide):?>

        <p>Size Guide:</p>
        <p>
            <?= $items[0]->itemSizeGuide ?>
        </p>

        <?php endif;?>

        <table id='sizeAndColour'>
            <?php if(!empty($colours)): ?>

            <tr>
                <td>
                    Colour
                </td>
                <td>
                    <select id='colourSelect'>
                        <?php
                            foreach($colours as $colourID => $colourName){
                                echo "<option value=" . $colourID . ">" . $colourName . "</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>

            <?php endif; 
            if(!empty($sizes)):?>
            <tr>
                <td>
                    Size
                </td>
                <td>
                    <select id='sizeSelect'>
                        <?php
                            foreach($sizes as $sizeID => $sizeName){
                                echo "<option value=" . $sizeID . ">" . $sizeName . "</option>";
                            }

                            
                        ?>
                    </select>
                </td>
            </tr>
            <?php endif; if(!empty($slogans)):?>
            <tr>
                <td>
                    Slogan
                </td>
                <td>
                    <select id='sloganSelect'>
                        <?php
                            foreach($slogans as $sloganID => $sloganName){
                                echo "<option value=" . $sloganID . ">" . $sloganName . "</option>";
                            }

                            if(empty($slogans)){
                                echo "<option value=0>No Slogan</option>"; 
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <?php endif; 
            if(!empty($collectionDates)):?>
            <tr>
                <td>
                    Collection Date
                </td>
                <td>
                    <select id='collectionSelect'>
                        <?php
                            foreach($collectionDates as $collectionID => $collectionDate){
                                echo "<option value=" . $collectionID . ">" . $collectionDate . "</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <?php endif;
            if(!empty($societies) & false):?>
            <tr>
                <td>
                    Society
                </td>
                <td>
                    <select id='societySelect'>
                    <?php
                    foreach($societies as $society){
                        echo "<option value=" . $society . ">" . $society . "</option>";
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <?php endif; ?>
        </table>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="return" value="https://society.ecs.soton.ac.uk/shop/purchase?id=<?= $purchaseID ?>">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="<?= $auth[$society]['paypal'] //$paypal[$itemID] ?>">

            <input type="hidden" name="item_name" value="<?= $items[0]->itemName ?>">
            <input type="hidden" name="item_number" value="<?= $itemID ?>">

            <input type="hidden" name="quantity" value="1">

            <input type="hidden" name="on5" value="Colour">        
            <input type="hidden" name="os5" value="<?= $colours[0] ?>" id='colour'>

            <input type="hidden" name="on2" value="Size">  
            <input type="hidden" name="os2" value="<?= isset($sizes[0]) ? $sizes[0] : $sizes[1] ?>" id='size'>

            <input type="hidden" name="on0" value="Username">  
            <input type="hidden" name="os0" value="<?= $userInfo['username'] ?>">

            <input type="hidden" name="on1" value="Society">  
            <input type="hidden" name="os1" value="<?= $auth[$society]['society'] ?>" id='society'>

            <input type="hidden" name="on3" value="Slogan">  
            <input type="hidden" name="os3" value="<?= $slogans[0] ?>" id='slogan'>

            <input type="hidden" name="on4" value="CollectionDate">  
            <input type="hidden" name="os4" value="<?= $collectionDates[0] ?>" id='collectionDate'>
            
            <input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
        </form>
        <p>
            Payments authenticated by iSolutions, and paid through Paypal.
        </p>
    </div>
</div>
<script type="text/javascript">
    $('#sizeSelect').change(function(){
        var size = $('#sizeSelect').find(':selected').html();
        $('#size').val(size);
    });

    $('#colourSelect').change(function(){
        var colour = $('#colourSelect').find(':selected').html();
        $('#colour').val(colour);
    });

    $('#sloganSelect').change(function(){
        var colour = $('#sloganSelect').find(':selected').html();
        $('#slogan').val(colour);
    });

    $('#collectionSelect').change(function(){
        var colour = $('#collectionSelect').find(':selected').html();
        $('#collectionDate').val(colour);
    });

    $('#societySelect').change(function(){
        var colour = $('#societySelect').find(':selected').html();
        $('#society').val(colour);
    });

    $(document).ready(function(){
        var size = $('#sizeSelect').find(':selected').html();
        $('#size').val(size);
        var colour = $('#colourSelect').find(':selected').html();
        $('#colour').val(colour);
        var colour = $('#sloganSelect').find(':selected').html();
        $('#slogan').val(colour);
        var colour = $('#collectionSelect').find(':selected').html();
        $('#collectionDate').val(colour);
        //var colour = $('#societySelect').find(':selected').html();
        //$('#society').val(colour);
    });
</script>
</body>
</html>
