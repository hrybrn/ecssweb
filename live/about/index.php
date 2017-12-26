<?php
$relPath = "../";
include_once ($relPath . 'includes/setLang.php');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _("About") ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $relPath ?>theme.css">
    <link rel="stylesheet" href="about.css">
    <link rel="stylesheet" href="/static/slideshow/slideshow.css">
    <script src="<?= $relPath ?>jquery.js"></script>
    <script src="/static/slideshow/slideshow.js"></script>
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<section class="pageContainer">
    <!--<img id="committeeFam" src="<?= $relPath ?>images/people.jpg">-->

    <div id="slideshow" class="slideshow">
        <noscript>Javascript is required for the slideshow.</noscript>
    </div>

    <h1>About ECSS</h1>

    <h3>

        ECSS is a student run society within the School of Electronics and Computer Science at the University of Southampton. We are a society for the students.
        That means we are here to help you.</h3>
    <p>
        Through the year that we (the current committee) are in power, we
        will try to provide you with a range of events and activities. We have a
        number of plans of different events we are planning on running.
        These range from pirate, golf and cocktail pub crawls to sporting events such as a table tennis tournament.
        We also provide more academic and job related activities such as talks from outside companies, as well as day trips to these companies such as J.P. Morgan and IBM.</p>
    <p>  We are always open however, to new and fresh ideas of events that you think we should run. If you have an ideas, send it our way and we'll see what we can do!
        Our most recent Constitution, SUSU Society Page, our latest Election Results and the subsequent pages on this website are available for your immediate browsing. If you are a UoS Student, you can also log into the portal for current merchandise orders, voting and more. If at any point you require any more information, or are interested in sponsoring our society, please do not hesitate to contact us.
    </p>
</section>
<script>
    var files = ["..\/images\/people.jpg", "..\/images\/everyone.png", "..\/images\/leaflet\/games\/GamesSoc\/VideoGames\/IMG_2378.JPG", "..\/images\/leaflet\/running\/parkrun.jpg", "..\/images\/leaflet\/games\/GamesSoc\/BoardGames\/IMG_3138.JPG"];
    $(document).ready(function() {
        var slideshow = new Slideshow(document.getElementById("slideshow"), files);
    });
</script>
</body>
</html>
