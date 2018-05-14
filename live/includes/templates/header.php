<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

    <link rel="stylesheet" href="/static/styles/base.css">
    <?php
    if (isset($page_style)) {
        echo $page_style;
    }
    ?>
    <title><?= $title ?></title>
</head>
<body>
<header class="bg-black">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-black">
            <a class="navbar-brand" href="/">
                <img src="/images/new-logo-black.png" width="30" height="30" alt="ECSS website navbar brand logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/committee/">Committee</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/societies/">Societies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sponsors/">Sponsors</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarEventsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Events
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarEventsDropdown">
                            <a class="dropdown-item" href="/leaflet/?section=Socials">Socials</a>
                            <a class="dropdown-item" href="/leaflet/?section=Gaming%20Socials">Game Socials</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/leaflet/?section=Welfare">Welfare</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/leaflet/section=Sports" id="navbarSportsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sports
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarSportsDropdown">
                            <a class="dropdown-item" href="/leaflet/?section=Sports"
                            <a class="dropdown-item" href="/leaflet/?section=Football">Football</a>
                            <a class="dropdown-item" href="/leaflet/?section=Netball">Netball</a>
                            <a class="dropdown-item" href="/leaflet/?section=Running">Running</a>
                            <a class="dropdown-item" href="/leaflet/?section=Others">Others</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="/filestore/index.php/s/JeqwjpN2tkVKW5X" id="navbarFilesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Files
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarFilesDropdown">
                            <a class="dropdown-item" href="/filestore/index.php/s/JeqwjpN2tkVKW5X">Meeting Minutes</a>
                            <a class="dropdown-item" href="/filestore/">Committee Files</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarFeedbackDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Feedback
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarFeedbackDropdown">
                            <a class="dropdown-item" href="/comment/">Submit</a>
                            <a class="dropdown-item" href="/comment/responses/">Responses</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarAboutDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            About
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarAboutDropdown">
                            <a class="dropdown-item" href="/about/">About</a>
                            <a class="dropdown-item" href="/filestore/index.php/s/idGPSGlxT15tbHi">Constitution</a>
                            <a class="dropdown-item" href="/voteresult/">Voting Results</a>
                            <a class="dropdown-item" href="/about/contact.php">Contact Us</a>
                            <a class="dropdown-item" href="/about/credits.php">Credits</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>