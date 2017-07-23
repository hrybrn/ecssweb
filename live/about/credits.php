<!doctype html>
<html>
<head>
<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../theme.css">
    <link rel="stylesheet" type="text/css" href="credits.css">
    <title>Credits | ECSS</title>
</head>
<body>
<section id="creditsSection" class="pageContainer">
    <h3>Production Credits</h3>
    <section>
        <div class="creditImageContainer"><img src="<?= $relPath ?>images/harry.jpg" /></div>
        <span class="creditCaption">Harry wrote the pages and made other people format them.</span>
    </section>
    <section>
        <div class="creditImageContainer"><img src="<?= $relPath ?>images/rayna.jpg" /></div>
        <span class="creditCaption">Rayna wrote most of the CSS and made this website pretty af.</span>
    </section>
    <section>
        <div class="creditImageContainer"><img src="<?= $relPath ?>images/allen.jpg" /></div>
        <span class="creditCaption">Jinxuan (aka Allen) wrote responsive CSS and made this website looks good on mobile.</span>
    </section>
</section>
</body>
</html>
