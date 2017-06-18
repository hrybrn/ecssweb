<?php
include_once("navbar.php");

$relPath = "";
?>
    <link rel="stylesheet" href="theme.css">

    <head>
        <meta charset="UTF-8">
        <title>ECSS</title>
        <?=
        getNavBar();
        ?>
    <div class="logo">
        <!-- <div> -->
            <img src="images/ecss-logo.png" width="200"/>
        </div>
        <div class="intro">
            <h1>Welcome to ECSS!</h1>

            <!-- <h2> -->
                <p>Welcome to the Electronics and Computer Science (ECS) Society website. </p>
                <p>We are a student-run society for ECS at the University of Southampton. </p>
                <p>On this website you will find information about the society such as who we are, what we aim to do, as well as what events we are currently organising! </p>
                <p>Still reading? You can even find out more about us.</p>
                <p>To keep up with the latest events, join the <a href="https://www.facebook.com/groups/ecss.soton/">ECSS Facebook group</a> and follow us on Twitter, <a href="http://twitter.com/ecs_society">@ECS_Society.</a></p>
            <!-- </h2> -->
       <!-- </div> -->
    </div>
    
    <table>
        <tr>
            <td>
    <div id="facebookEmbed">
    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fecss.soton%2F&tabs=timeline&width=500&height=500&small_header=false&adapt_container_width=true&hide_cover=true&show_facepile=false&appId" width="500" height="400" padding="15px" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
    </div>
            </td>
    
            <td width="370">
    <div id="twitterEmbed">
        <a class="twitter-timeline" data-width="340" data-height="400" data-dnt="true" data-theme="light" data-link-color="#E81C4F" href="https://twitter.com/ecs_society">Tweets by ecs_society</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
    </div>
            </td>
            
            <td>
                <iframe src="https://calendar.google.com/calendar/embed?title=ECSS%20Events%202017-2018&amp;mode=AGENDA&amp;height=600&amp;wkst=2&amp;bgcolor=%23FFFFFF&amp;src=0s8rbd2g07uf6uqil2fir8decg%40group.calendar.google.com&amp;color=%235229A3&amp;src=gvo3td8eik1aclq6hvj554c848%40group.calendar.google.com&amp;color=%2329527A&amp;src=7oblk0qa2213h9trodr69jikmk%40group.calendar.google.com&amp;color=%23B1440E&amp;ctz=Europe%2FLondon" style="border:solid 1px #777" width="500" height="400" frameborder="0" scrolling="no"></iframe>
            </td>
        </tr>
    </table>
</head>
