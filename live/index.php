<!doctype html>
<html>
    
    <head>
        <?php
        $relPath = "";

        include_once($relPath . "navbar/navbar.php");
        echo getNavBar();

        setTextDomain('title');
        
        ?>
        <meta charset="UTF-8">
        <title><?= _('Home') ?> | ECSS</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="theme.css" />
        <link rel="stylesheet" type="text/css" href="home.css" />
        <script src="home.js"></script>
        <base target="_blank">
    </head>

<body>
<?php setTextDomain('home'); ?>
<div class="pageContainer">
    <div id="intro">
        <img id="logo" src="images/ecss-logo.png" alt="ECSS logo" />
        <section>
            <h1><?= _('Welcome to ECSS!') ?></h1>
            <p><?= _('Welcome to the Electronics and Computer Science (ECS) Society website.') ?></p>
            <p><?= _('We are a student-run society for ECS at the University of Southampton.') ?></p>
            <p><?= _('On this website you will find information about the society such as who we are, what we aim to do, as well as what events we are currently organising!') ?></p>
            <p><?= _('Still reading? You can even find out more <a href="about/" target="_self">about us</a>.') ?></p>
            <p><?= _('To keep up with the latest events, join the <a href="https://www.facebook.com/groups/ecss.soton/">ECSS Facebook group</a> and follow us on Twitter, <a href="http://twitter.com/ecs_society">@ECS_Society</a>.') ?></p>

            <br><br>
            <p style="text-align: center;">~~~ If you are a new to ECSS and you have just been accepted then well done! We look forward to introducing you to the society and hope you have a great time with us! ~~~
            </p>
            <br><br>
        </section>
    </div>
    <div id="socialWidgetsDiv">
        <div id="socialTabs">
            <ul>
                <li onclick="showWidget(0)" class="activeTab">Twitter</li>
                <li onclick="showWidget(1)">Facebook</li>
                <li onclick="showWidget(2)"><?= _('Events') ?></li>
            </ul>
        </div>
        <div id="socialWidgets">
            <div id="twitterEmbed" class="activeWidget">
                <a class="twitter-timeline" data-width="280" data-height="400" data-dnt="true" data-theme="light" data-link-color="#E81C4F" href="https://twitter.com/ecs_society">Tweets by ecs_society</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
            <div id="facebookPageEmbed" class="inactiveWidget">
                <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fecss.soton&tabs=timeline&width=280&height=400&small_header=true&adapt_container_width=true&hide_cover=true&show_facepile=false&appId=1002687123208525" width="280" height="400" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
            </div>
            <div id="facebookEventsEmbed" class="inactiveWidget">
                <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fecss.soton&tabs=events&width=280&height=400&small_header=true&adapt_container_width=true&hide_cover=true&show_facepile=false&appId=1002687123208525" width="280" height="400" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
            </div>
        </div>
    </div>
</div>
</body>
</html>