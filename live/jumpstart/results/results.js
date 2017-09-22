function load(){
	$('#groupSelect').change(function(){
		showEntries($('#groupSelect').find(':selected').val());
	});
}

function showEntries(id){
	$.ajax({
		url: '/jumpstart/results/getResults.php',
		type: 'get',
        data: {'groupID': id, 'lang': lang},
        dataType: 'json',
        success: function (tasks) {
        	$('#tasks').remove();
        	$('body').append(tasks.data);
        }
	});
}