<?php
$relPath = "../";
include_once ($relPath . 'includes/setLang.php');
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _("About") ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $relPath ?>theme.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<style>
.tile {
	display: inline-block;
	padding-right:20px;
	padding-bottom:50px;
}
</style>

<h2>ECSS Election 2017: Results</h2>
<br>
<div class="tile">
<pre>President: Ricki Tura is elected</pre>
<div id="President1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Vice-President: Ayush Katariya is elected</pre>
<div id="Vice-President1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Secretary: Charis King is elected 
(as Ricki Tura withdrew his nomination)</pre>
<div id="Secretary1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Academic Officer: Denisa Prisiceanu is elected</pre>
<div id="AcademicOfficer1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Social Secretary: Luke Woolley is elected</pre>
<div id="SocialSecretary1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Web Officer: Harry Brown is elected
(as Joshua Curry withdrew his nomination)</pre>
<div id="WebOfficer1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Games Officer: Christian Clarke is elected</pre>
<div id="GamesOfficer1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Treasurer: Angus Brown is elected</pre>
<div id="Treasurer1"style="width: 500px; height: 300px;"></div>
<div id="Treasurer2"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Marketing Officer: George Elliott-Hunter is elected</pre>
<div id="MarketingOfficer1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Sports Officer: Bradley Elvy is elected</pre>
<div id="SportsOfficer1"style="width: 500px; height: 300px;"></div>
</div>
<div class="tile">
<pre>Welfare Officer: Hope Shaw is elected</pre>
<div id="WelfareOfficer1"style="width: 500px; height: 300px;"></div>
</div>



<script type="text/javascript">

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);

var chart, data, options;

  function drawCharts() {
  	        

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Ricki Tura', 137],['Oliver Hayes', 108],['RON', 3],] );

    options = {
      title: 'President - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('President1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Ayush Katariya', 172],['Scott Williams', 25],['Joshua Perriman', 41],['RON', 10],] );

    options = {
      title: 'Vice-President - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('Vice-President1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Charis King', 93],['Ricki Tura', 145],['RON', 10],] );

    options = {
      title: 'Secretary - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('Secretary1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Jamie Smith', 85],['Denisa Prisiceanu', 153],['RON', 10],] );

    options = {
      title: 'Academic Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('AcademicOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Luke Woolley', 203],['RON', 45],] );

    options = {
      title: 'Social Secretary - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('SocialSecretary1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Joshua Curry', 196],['Harry Brown', 46],['RON', 6],] );

    options = {
      title: 'Web Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('WebOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Christian Clarke', 208],['RON', 40],] );

    options = {
      title: 'Games Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('GamesOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Angus Brown', 124],['Denisa Prisiceanu', 106],['RON', 18],] );

    options = {
      title: 'Treasurer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('Treasurer1'));

    chart.draw(data, options);

        

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Angus Brown', 131],['Denisa Prisiceanu', 117],] );

    options = {
      title: 'Treasurer - Round 2'
    };

    chart = new google.visualization.PieChart(document.getElementById('Treasurer2'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['George Elliott-Hunter', 220],['RON', 28],] );

    options = {
      title: 'Marketing Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('MarketingOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Felix De Neve', 86],['Bradley Elvy', 151],['RON', 11],] );

    options = {
      title: 'Sports Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('SportsOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Hope Shaw', 180],['Brendan Elmes', 61],['RON', 7],] );

    options = {
      title: 'Welfare Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('WelfareOfficer1'));

    chart.draw(data, options);

        
    }
    </script>

</div>
  </body>
</html>

