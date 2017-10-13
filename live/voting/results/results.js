function showTask(id){
	$.ajax({
		url: '/voting/results/getResults.php',
		type: 'get',
        data: {'electionID': id},
        dataType: 'json',
        success: function (results) {
        	if(results.status){
        		var overall = "";
        		$.each(results.standings, function(positionName, standings){
        			var table = "<table><tr><th colspan='2'>" + positionName + "</th></tr>";
	        		table += "<tr><th>Candidate</th><th>Percentage</th></tr>";

	        		for(var i = 0; i < standings.length; i++){
	        			table += "<tr><td>" + standings[i].nominationName + "</td><td>" + standings[i].percentage + "</td></tr>";
	        		}

	        		table += "</table>";
	        		overall += table;
	        	});

        		$('#resultsDiv').html(overall);
        	} else {
        		$('#resultsDiv').html("<p class='errorMessage'>" + results.message + "</p>");
        	}
        }
	});
}

function load(){
	$('#taskSelect').change(function(){
		showTask($('#electionSelect').find(':selected').val());
	});

	showTask($('#electionSelect').find(':selected').val());
}