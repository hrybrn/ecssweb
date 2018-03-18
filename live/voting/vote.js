var mobile = window.location.href.includes("/mobile");

if(window.innerWidth < 800 & !window.location.href.includes("/mobile")){
	window.location = "/voting/mobile";
}


$(document).ready(function () {
	showPosition(first);
});


var manifestoVisible = {};
var manifestoText = {};

var entryData = {};

var positionID = 0;

var readyToVote = true;

function setBestWorstPositions(){
	var container = document.getElementById("nominationDiv");

	if($('#nominationDiv').children().first().prop('id') !== "best"){
		var best = document.getElementById("best");
		container.insertBefore(best,container.firstChild);
	}

	if($('#nominationDiv').children().last().prop('id') !== "worst"){
		var worst = document.getElementById("worst");
		container.appendChild(worst);
	}
}

function showPosition(buttonid) {
	$('#message').remove();
	positionID = $('#' + buttonid).data('positionid');

	$.ajax({
		url: "/voting/getNominations.php",
		type: 'get',
		dataType: 'json',
		data: { 'positionID': positionID },
		success: function (nominations) {
			readyToVote = true;

			//setup youtube playlist
			$('#playlist').attr('src', 'https://www.youtube.com/embed/videoseries?list=' + youtube[nominations.data[0].positionID]);


			$('#bigDiv').remove();

			var bigDiv = "<div id='bigDiv'>";
	        bigDiv += "    <div id='mediumDiv'>";
	        bigDiv += "        <div>";
			bigDiv += "		       <h3 style='text-align: center;'>Voting Order</h3>";
			bigDiv += "		       <h4 style='text-align: center;'>Please put your most preferred candidate on the left, and you least preferred on the right.</h4>";
			bigDiv += "        </div>";
	        bigDiv += "        <div id='nominationDiv'>";
	        bigDiv += "            <button disabled='disabled' id='best'>Most Preferred</button>";
	        bigDiv += "            <button disabled='disabled' id='worst'>Least Preferred</button>";
	        bigDiv += "        </div>";
	        bigDiv += "    </div>";
			bigDiv += "    <div><h3 style='text-align: center;'>Candidates</h3>";
			bigDiv += "		       <h4 style='text-align: center;'>Click and drag candidates into the voting order, between most and least preferred.</h4>";
			bigDiv += "    </div><div id='available'></div>";
	        bigDiv += "</div>";

			$('body').append(bigDiv);

			$(".portrait").remove();

			$('#nominationDiv').data('positionid', positionID);

			if (!nominations.status) {
				return false;
			}

			$('#submit').text('Submit vote for ' + nominations.data[0].positionName);

			$.each(nominations.data, function () {
				if(this.image == null){
					this.image = "https://society.ecs.soton.ac.uk/images/new-logo-black.png";
				}
				//image convert for testing
				this.image = this.image.replace("..", "https://society.ecs.soton.ac.uk");

				//formatting manifesto
				this.manifesto = this.manifesto.replace(/\r?\n|\r/g, "</p><p style='text-align: center;'>");

				manifestoText[this.nominationID] = "<p style='text-align: center;'>" + this.manifesto + "</p>";
				var div = "<div class='portrait' id='" + this.nominationID + "'><h5>" + this.nominationName + "</h5><div class='info' id='name" + this.nominationID + "' title=''></div><img class='unselectable' src='" + this.image + "'></div>";
				$('#available').append(div);
			});

			$("#nominationDiv").sortable({
				revert: 200,
				axis: "x",
				stop: function(){
					setBestWorstPositions();
				},
				change: function(){
					setBestWorstPositions();
				},
				deactivate: function(event,ui){
					//move element back into available
					var lastElem = $('#nominationDiv').children().last();
					if(lastElem.prop('id') !== 'worst'){
						$('#available').append(lastElem);
					}
				},
				containment: "#bigDiv"
			});

			$('#available').droppable();

			$('.portrait').draggable({
				containment: "#bigDiv",
				connectToSortable: "#nominationDiv",
				revert: "invalid"
			});

			$('.info').tooltip({
				content: function(){
					var nomID = $(this).prop("id").replace("name","");
					return manifestoText[nomID];
				},
				track: true
			});
		}
	});
}

function submit() {
	if(!readyToVote){
		window.alert("Select another position to continue voting.");
		return;
	}
	var idsinOrder = $("#nominationDiv").sortable("toArray");

	if(idsinOrder.length === 2){
		window.alert("Your vote cannot be empty!");
		return;
	}

	var intsInOrder = [];
	idsinOrder[0] = -1;
	idsinOrder[idsinOrder.length - 1] = -1;

	$.each(idsinOrder, function(){
		if(this != -1){
			intsInOrder.push(parseInt(this));
		}
	});

	// get value of csrf token
	var csrftoken = $('meta[name=csrftoken]').attr('content');

	$.ajax({
		url: "/voting/submitVote.php",
		type: 'post',
		dataType: 'json',
		data: { 'entryData': intsInOrder, 'positionID': positionID, 'csrftoken': csrftoken },
		success: function (result) {
			if(result.status){
				$("#button" + positionID).remove();
				$("#bigDiv").remove();
			}
			$('#submit').text(result.message);
			readyToVote = false;
		}
	});
}


function toggleManifesto(nominationID){
	if(typeof manifestoVisible[nominationID] == undefined){
		manifestoVisible[nominationID] = false;
	}
	var id = "#manifesto" + nominationID;

	if(manifestoVisible[nominationID]){
		manifestoVisible[nominationID] = false;
		$(id).css("visibility", "hidden");
	} else {
		manifestoVisible[nominationID] = true;
		$(id).css("visibility", "visible");
	}
}
