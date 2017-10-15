<?php
$relPath = "../../";
include_once ($relPath . 'includes/setLang.php');

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title>Voting | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
    <link rel="stylesheet" type="text/css" href="/voting/results/results.css" />
</head>
<body>
<script src="/jquery.js" type="text/javascript"></script>

<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>

<h3>Final Results of the Charity Election</h3>
<div id='overallDiv'>
    <div id='currentRound'></div>
    <div id='buttonDiv'>
        <button id='previous'>Previous Round</button>
        <button id='next'>See The Rundown</button>
        <button id='final'>Final Results</button>
    </div>
</div>

<script type="text/javascript">
    var results = [];

    results[0] = "<p>Number of votes cast: 90<br />Candidates were:</p><p>You're It Southampton<br />Get Well Gamers<br />Solent Mind<br />S.C.R.A.T.C.H<br />Special Effect<br />Autism Hampshire<br />Mencap<br />Rowans Hospice<br />Practical Action</p>";

    results[1] = "<p>-----<br />ROUND 1<br />-----<br />You're It Southampton : 17<br />Solent Mind : 13<br />Autism Hampshire : 11<br />Get Well Gamers : 10<br />S.C.R.A.T.C.H : 9<br />Rowans Hospice : 9<br />Practical Action : 8<br />Special Effect : 7<br />Mencap : 6</p><p>Mencap has been eliminated</p>";

    results[2] = "<p>-----<br />ROUND 2<br />-----<br />You're It Southampton : 17<br />Solent Mind : 13<br />S.C.R.A.T.C.H : 12<br />Autism Hampshire : 12<br />Get Well Gamers : 11<br />Rowans Hospice : 9<br />Special Effect : 8<br />Practical Action : 8</p><p>Special Effect has been eliminated</p>";

    results[3] = "<p>-----<br />ROUND 3<br />-----<br />You're It Southampton : 19<br />Solent Mind : 16<br />S.C.R.A.T.C.H : 13<br />Autism Hampshire : 13<br />Get Well Gamers : 11<br />Rowans Hospice : 9<br />Practical Action : 9</p><p>Practical Action has been eliminated</p>";

    results[4] = "<p>-----<br />ROUND 4<br />-----<br />You're It Southampton : 19<br />Solent Mind : 19<br />S.C.R.A.T.C.H : 16<br />Get Well Gamers : 13<br />Autism Hampshire : 13<br />Rowans Hospice : 10</p><p>Rowans Hospice has been eliminated</p>";

    results[5] = "<p>-----<br />ROUND 5<br />-----<br />You're It Southampton : 24<br />Solent Mind : 21<br />S.C.R.A.T.C.H : 17<br />Autism Hampshire : 15<br />Get Well Gamers : 13</p><p>Get Well Gamers has been eliminated</p>";

    results[6] = "<p>-----<br />ROUND 6<br />-----<br />You're It Southampton : 28<br />Solent Mind : 24<br />S.C.R.A.T.C.H : 20<br />Autism Hampshire : 18</p><p>Autism Hampshire has been eliminated</p>";

    results[7] = "<p>-----<br />ROUND 7<br />-----<br />You're It Southampton : 35<br />Solent Mind : 31<br />S.C.R.A.T.C.H : 24</p><p>S.C.R.A.T.C.H has been eliminated</p>";

    results[8] = "<p>-----<br />ROUND 8<br />-----<br />You're It Southampton : 48<br />Solent Mind : 42</p><p>Solent Mind has been eliminated</p><p>-----<br />WINNER IS: You're It Southampton<br />-----</p>";

    var final = 8;
    var current = -1;


    $('#currentRound').html(results[8]);

    $('#previous').hide();
    $('#final').hide();

    $(document).ready(function(){
        $('#previous').click(function(){
            changeRound(-1);
        });

        $('#next').click(function(){
            changeRound(1);
            $('#previous').show();
            $('#final').show();
            $('#next').html("Next Round");
        });

        $('#final').click(function(){
            setRound(final);
            current = final;
        })
    });

    function changeRound(increment){
        var next = current + increment;

        if(next >= 0 && next <= final){
            current = next;
            setRound(current);
        }
    }

    function setRound(roundNumber){
        if(roundNumber >= 0 && roundNumber <= final){
            $('#currentRound').html(results[roundNumber]);
        }
    }
</script>