<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

echo getNavBar();
?>
<html>
    <table align="center">
        <tr><td colspan="2"><h3 align="center">Production Credits</h3></td></tr>
        <tr>
            <td align="center"><img src="<?= $relPath ?>images/harry.jpg" width="400" ></td>
            <td align="center"><img src="<?= $relPath ?>images/rayna.jpg" height="400"></td>
        </tr>
        <tr>
            <td>Harry wrote most of the website and set up the system.</td>
            <td>Rayna wrote most of the CSS and made this website pretty af.</td>
        </tr>
    </table>
</html>
