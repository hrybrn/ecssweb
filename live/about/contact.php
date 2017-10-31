<!doctype html>
<html>
<head>
<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "../data/en/societies.json");
$societies = json_decode($raw, true);

$ecss = $societies['ECSS'];

$links = "";
foreach($ecss as $key => $val){
    if(strlen(strstr($val,"http")) > 0 || strlen(strstr($val,"@")) > 0){
        $links .= '<td><a href="' . $val . '>' . $key . '</a></td>';
    }
}
?>
<link rel="stylesheet" href="<?= $relPath ?>theme.css">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= _("Contact Us") ?> | ECSS</title>
</head>
<body>
<section id="contactSection" class="pageContainer">
<h1>Contact Us</h1>
<p>
    ECSS welcomes all communications from its society members!<br><br>
    Please feel free to contact us at any of the links below and we will get back to you as soon as possible.
</p>
<p><a href="https://www.facebook.com/ecss.soton/"><span class="facebookIcon linkIcon"></span>ECSS Facebook Page</a></p>
<p><a href="https://www.facebook.com/groups/ecss.soton/"><span class="facebookIcon linkIcon"></span>ECSS Facebook Group</a></p>
<p><a href="mailto:society@ecs.soton.ac.uk"><span class="emailIcon linkIcon"></span>Email: society@ecs.soton.ac.uk</a></p>

</section>
</body>
</html>