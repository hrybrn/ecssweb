var groupID = 0;

function showGroup(id){
	$.ajax({
		url: '/jumpstart/committee/getGroup.php',
		type: 'get',
        data: {'groupID': id, 'lang': lang},
        dataType: 'json',
        success: function (tasks) {
        	groupID = id;
        	$('#scores').remove();
        	$('body').append(tasks.data);
        }
	});
}

function load(){
	$('#groupSelect').change(function(){
		showGroup($('#groupSelect').find(':selected').val());
	});
}

function save(){
	saveData = {};

	$('.score').each(function(){
		if(this.val() != ""){
			saveData[this.data('taskScoreID')] = this.val();
		}
	});

	$.ajax({
		url: '/jumpstart/committee/saveScores.php',
		type: 'get',
        data: {'groupID': groupID, 'scores': saveData},
        dataType: 'json'
	});
}