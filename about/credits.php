<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<html>
    <link rel="stylesheet" href="../theme.css">
    
    <meta charset="UTF-8">
    <title>Credits | ECSS</title>

    <table align="center">
        <tr><td colspan="3"><h3 align="center">Production Credits</h3></td></tr>
        <tr>
            <td class="creditImage"><img src="<?= $relPath ?>images/harry.jpg" width="400" ></td>
            <td class="creditImage"><img src="<?= $relPath ?>images/rayna.jpg" height="400"></td>
            <td class="creditImage"><img src="<?= $relPath ?>images/chris.jpg" height="400"></td>
        </tr>
        <tr>
            <td>Harry wrote the pages and made other people format them.</td>
            <td>Rayna wrote most of the CSS and made this website pretty af.</td>
            <td>Chris has yet to make a commit, he will one day once he learns PHP.</td>
        </tr>
    </table>
</html>
