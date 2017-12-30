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


    <p><span style="font-size: 13pt;"><strong>ECSS is a student run society within the School of Electronics and Computer Science at the University of Southampton. We are a society for the students. That means we are here to help you.</strong></span></p>
    <p><strong><span style="font-size: 13pt;">A Brief History:</span></strong></p>
    <p>ECSS was founded in 2005 when two pre-existing societies merged, the Software Engineering and Computer Science Society (SECSS) and the Electronic Electrical Electromechanical and Computer Engineering Society (EEECES). Officially, SECSS was renamed to the Electronics and Computer Science Society to become a departmental society. EEECES had never been affiliated with the Union and had been rejected in both 2004 and 2005.</p>
    <p><strong><span style="font-size: 13pt;">Present Day:</span></strong></p>
    <p>The aim of the society is to run a range of events, activities and opportunities, as well as to support the groups and societies which run within the School of Electronics and Computer Science.</p>
    <p>Through the year that we (the current committee) are in power, we will provide you with a range of events and activities. These range from club nights and pub crawls, to sports events and games nights.</p>
    <p>We also provide academic and job related activities such as talks from external companies, including those that sponsor ECSS, as well as ECS alumni.</p>
    <p>We are always open however, to new and fresh ideas of events that you think we should run. If you have an ideas, send it our way and we'll see what we can do! Our most recent <a href="https://society.ecs.soton.ac.uk/files/Constitution-2015.pdf">Constitution</a>, <a href="https://www.susu.org/groups/ecss">SUSU Society Page</a>, our <a href="/voteresult">latest Election Results</a> and the subsequent pages on this website are available for your immediate browsing. If at any point you require any more information, or are interested in sponsoring our society, please do not hesitate to contact us.</p>

</section>
<script>
    var files = ["..\/images\/people.jpg", "..\/images\/everyone.png", "..\/images\/leaflet\/games\/GamesSoc\/VideoGames\/IMG_2378.JPG", "..\/images\/leaflet\/running\/parkrun.jpg", "..\/images\/leaflet\/games\/GamesSoc\/BoardGames\/IMG_3138.JPG"];
    $(document).ready(function() {
        var slideshow = new Slideshow(document.getElementById("slideshow"), files);
    });
</script>
</body>
</html>
