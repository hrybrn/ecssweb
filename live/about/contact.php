<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "../data/societies.json");
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
<title>Contact Us | ECSS</title>
        
<div style="padding: 15px">
<h1>Contact Us</h1>
<p>
    ECSS welcomes all communications from its society members!<br><br>
    Please feel free to contact us at any of the links below and we will get back to you as soon as possible.
</p>
<p><a href="https://www.facebook.com/ecss.soton/"><img id="linkIcon" src="../images/icons/facebook-circle.png">Facebook Page</a></p>
<p><a href=""><img id="linkIcon" src="../images/icons/facebook-circle.png">Facebook Group</a></p>
<p><a href=""><img id="linkIcon" src="../images/icons/email-circle.png">Email (insert email here so that it's visible</a></p>

<table>
    <tr>
        <?= $links ?>
    </tr>
</table>
</div>