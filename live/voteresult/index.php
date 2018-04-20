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
    <title>Voting Results | ECSS</title>
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

<div style='width: 90%; margin: 5%;'>
  <h2>ECSS Election 2017: Results</h2>
  <br>
  <div class="tile">
    <pre>President: Brad Elvy is elected</pre>
    <div id="President1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Vice-President Engagement: Christian Clarke is elected</pre>
    <div id="VPE1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Vice-President Operations: Harry Brown is elected</pre>
    <div id="VPO1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Secretary: Jacob Smith is elected</pre>
    <div id="Secretary1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Industry Officer: Constantin Tiron is elected</pre>
    <div id="AcademicOfficer1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Social Secretary: Emily Wayland is elected</pre>
    <div id="SocialSecretary1"style="width: 500px; height: 300px;"></div>
  </div>
    <div class="tile">
    <pre>Web Officer: Jinxuan (Allen) Cui is elected</pre>
    <div id="WebOfficer1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Events Officer: Rhett Mitchell is elected</pre>
    <div id="GamesOfficer1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Treasurer: Angus Brown is elected</pre>
    <div id="Treasurer1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Marketing Officer: Rayna Bozhkova is elected</pre>
    <div id="MarketingOfficer1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Sports Officer: Ben Gesoff is elected
(as Bradley Elvy withdrew his nomination)</pre>
    <div id="SportsOfficer1"style="width: 500px; height: 300px;"></div>
  </div>
  <div class="tile">
    <pre>Welfare Officer: Alicja (Ala) Bochnacka is elected</pre>
    <div id="WelfareOfficer1"style="width: 500px; height: 300px;"></div>
  </div>
</div>



<script type="text/javascript">

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawCharts);

var chart, data, options;

  function drawCharts() {
  	        

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Bradley Elvy', 139],['Felix De Neve', 24],['RON', 10],] );

    options = {
      title: 'President - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('President1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Christian Clarke', 100],['Felix De Neve', 24],['RON', 15],] );

    options = {
      title: 'Vice-President Engagement - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('VPE1'));

    chart.draw(data, options);

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Harry Brown', 121],['RON', 22],] );

    options = {
      title: 'Vice-President Operations - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('VPO1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Jacob Smith', 74],['Will MacLeod', 54],['Felix De Neve', 10],['RON', 7],['Maciej Dudziak', 4],] );

    options = {
      title: 'Secretary - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('Secretary1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Costin Tiron', 89],['Wing Yin Michelle Wong', 23],['Kacper Kubara', 22],['Felix De Neve', 14],['Maciej Dudziak', 4],['RON', 4],] );

    options = {
      title: 'Industry Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('AcademicOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Emily Wayland', 98],['Nathan Bharkda', 39],['Brendan Elmes', 11],['Felix De Neve', 10],['RON', 2],] );

    options = {
      title: 'Social Secretary - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('SocialSecretary1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Jinxuan (Allen) Cui', 72],['Harry Brown', 57],['Scott Williams', 11],['RON', 6],['Felix De Neve', 4],] );

    options = {
      title: 'Web Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('WebOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Rhett Mitchell', 68],['Spas Zahriev', 39],['Felix De Neve', 13],['Corin Holloway', 12],['RON', 4],] );

    options = {
      title: 'Events Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('GamesOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Angus Brown', 101],['Kareem Anbar', 19],['Felix De Neve', 12],['RON', 3],] );

    options = {
      title: 'Treasurer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('Treasurer1'));

    chart.draw(data, options);



    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Rayna Bozhkova', 95],['Felix De Neve', 10],['Corin Holloway', 12],['RON', 11],] );

    options = {
      title: 'Marketing Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('MarketingOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Bradley Elvy', 67],['Ben Gesoff', 37],['Felix De Neve', 12],['RON', 8],['Callum Marshall', 8]] );

    options = {
      title: 'Sports Officer - Round 1'
    };

    chart = new google.visualization.PieChart(document.getElementById('SportsOfficer1'));

    chart.draw(data, options);

                

    data = google.visualization.arrayToDataTable( [['Person', 'Votes'],['Alicja (Ala) Bochnacka', 123],['Felix De Neve', 12],['RON', 4],['Corin Holloway', 4],] );

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

