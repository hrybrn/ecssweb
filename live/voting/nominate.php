<?php
$relPath = "../";
include_once($relPath . "/voting/votingChecker.php");
include_once($relPath . "db/dbConnect.php");

if(!$mode = check("nominate")){
    echo "Nominations have been closed.";
    exit;
}

$sql = "
        SELECT p.id, p.name
        FROM position AS p
        WHERE p.type = '$mode';";

$result = ex($sql);

$options = "";

foreach($result as $index => $data){
    $options .= '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
}
?>

<table>
    <tr>
        <td>
            <label>Name</label>
        </td>
        <td>
            <input type="text" id="name">
        </td>
        <td class="error">
    </tr>
    <tr>
        <td>
            <label>Email</label>
        </td>
        <td>
            <input type="email" id="email">
        </td>
        <td class="error">
    </tr>
    <tr>
        <td>
            <label>Position</label>
        </td>
        <td>
            <select><?= $options ?></select>
        </td>
    </tr>
    <tr>
        <td>    
            <label>Manifesto</label>    
        </td>
        <td>
            <textarea rows="3" cols="50">
        </td>
    </tr>
</table>
