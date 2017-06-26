<html>
    
    <head>
        <?php
        $relPath = "";

        include_once($relPath . "navbar/navbar.php");
        echo getNavBar();
        
        ?>
        <meta charset="UTF-8">
        <title>Home | ECSS</title>
        <base target="_blank">
        <link rel="stylesheet" href="theme.css">
    </head>

    <body>


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

        <table width="100%">
            <tr>    
                <td width="30%" align="left">
                    <div id="twitterEmbed">
                        <a class="twitter-timeline" data-width="340" data-height="400" data-dnt="true" data-theme="light" data-link-color="#E81C4F" href="https://twitter.com/ecs_society">Tweets by ecs_society</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                    </div>
                </td>

                <td width="40%" align="center">
                    <div id="facebookEmbed">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fecss.soton%2F&tabs=timeline&width=500&height=500&small_header=false&adapt_container_width=true&hide_cover=true&show_facepile=false&appId" height="400" width="175%"padding="15px" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                    </div>
                </td>
                <td width="30%" align="right">
                    <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fecss.soton%2F&tabs=events&width=340&height=244&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=1002687123208525" height="400" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                </td>
            </tr>
        </table>
    </body>
</html>