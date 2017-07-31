<!doctype html>
<html>
    <head>
        <?php
        $relPath = "../";        
        include_once($relPath . "navbar/navbar.php");

        echo getNavBar();

        $raw = file_get_contents($relPath . "../data/" . $lang . "/societies.json");
        $societies = json_decode($raw, true);

        $buttons = '<div class="buttonGroup">';
        $i = 0;
        foreach ($societies as $society => $data) {
            $buttons .= '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $society . '</button>';
        }
        $buttons .= '</div>';
        ?>

        <script> var relPath = "<?= $relPath ?>";</script>
        <script src='<?= $relPath ?>jquery.js'></script>
        <script src="societies.js"></script>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= _("Societies") ?> | ECSS</title>
        <base target="_blank">

        <link rel="stylesheet" href="../theme.css">
    </head>
<body>
    <div id="societiesPageContainer" class="pageContainer">
        <div id="societyImageContainer"><img id="societyImage" /></div>
        <?= $buttons ?>
        <table id="societyTable" class="pageTable"></table>
    </div>
</body>
</html>
