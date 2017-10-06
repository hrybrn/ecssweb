$(document).ready(function(){
	$("#roleSelect").change(function(){
		showInfo($("#roleSelect").find(":selected").val());
	});

	showInfo(first);
});

function showInfo(positionID){
	$.ajax({
		url: "/voting/getPositionInfo.php",
		type: 'get',
		data: {'positionID': positionID},
		dataType: 'json',
		success: function(result){
			if(!result.status){
				return false;
			}

			//build the form
			var table = "<table id='table'><tr><th colspan=2>Nomination Form</th></tr>";
			table += "<tr><td>Role Description</td><td>" + result.description + "</td></tr>";
			
			table += "<tr><td>Name</td><td><input id='name' type='text' value='" + userInfo.firstName + " " + userInfo.lastName + "'></td></tr>"
			table += "<tr><td>Manifesto</td><td><textarea id='manifesto' rows=3></textarea></td></tr>";
			table += "<tr><td>Image</td><td><input type='file' id='imageUpload' name='imageUpload'><progress id='prog' value='0' min='0' max='100'></progress></td></tr>";
			table += "<tr><td><p id='returnMessage'></p></td></tr>";

			table += "</table>";
			$('#table').remove();
			$('body').append(table);
		}
	});
}

function submit(){
	var positionID = $('#roleSelect').find(':selected').val();

	var name = $('#name').val();
	var manifesto = $('#manifesto').val();

	$.ajax({
		url: "/voting/submitNomination.php",
		type: 'get',
		data: {'positionID': positionID, 'name': name, 'manifesto': manifesto},
		dataType: 'json',
		success: function(result){
			var data = {};
			data.nominationID = result.nominationID;
			if(result.status){
				$("#imageUpload").upload(
					"/voting/imageUpload.php",
					data,
					function(success){
						$('#table').remove();
						$('#roleSelect').remove();
						$('#submitButton').remove();
			
						$('body').append(success.message);
					},
					$("#prog"));
			} else {
				$('#table').remove();
				$('#roleSelect').remove();
				$('#submitButton').remove();
	
				$('body').append(result.message);
			}			
		}
	});
}