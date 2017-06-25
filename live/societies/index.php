<html>
    <head>
        <?php
        $relPath = "../";

        include_once($relPath . "navbar/navbar.php");

        echo getNavBar();

        $raw = file_get_contents($relPath . "../data/societies.json");
        $societies = json_decode($raw, true);

        echo '<div class="buttonGroup">';
        $i = 0;
        foreach ($societies as $society => $data) {
            echo '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $society . '</button>';
        }
        echo '</div>';
        ?>

        <script> var relPath = "<?= $relPath ?>";</script>
        <script src='<?= $relPath ?>jquery.js'></script>
        <script src="societies.js"></script>

        <meta charset="UTF-8">
        <title>Societies | ECSS</title>
        <base target="_blank">

        <link rel="stylesheet" href="../theme.css">
    </head>
    <body>
        <br>
        <img id="societyImage" width="300"/>
        <table id="societyTable"></table>

        <script>
            $(document).ready(function () {
                showMember("0");
            });
        </script>
    </body>