function showTask(id){
	$.ajax({
		url: '/voting/results/getResults.php',
		type: 'get',
        data: {'electionID', id},
        dataType: 'json',
        success: function (results) {
        	if(results.status){

        		var table = "<table><tr><th>Candidate</th><th>Percentage</th></tr>";
        		for(var i = 0; i < results.standings.length; i++){
        			table += "<tr><td>" + results.standings[i].nominationName + "</td><td>" + results.standings[i].percentage + "</td></tr>";
        		}

        		table += "</table>";
        		$('#resultsDiv').html(table);
        	} else {
        		$('#resultsDiv').html("<p class='errorMessage'>" + results.message + "</p>");
        	}
        }
	});
}

function load(){
	$('#taskSelect').change(function(){
		showTask($('#taskSelect').find(':selected').val());
	});

	showTask($('#taskSelect').find(':selected').val());
}