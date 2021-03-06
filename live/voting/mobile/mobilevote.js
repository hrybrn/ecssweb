var mobile = window.location.href.includes("/mobile");

if((window.innerWidth < 812 | window.innerHeight < 812) & !window.location.href.includes("/mobile")){
	window.location = "/voting/mobile";
}


$(document).ready(function () {
	showPosition(first);
});

var entryData = {};

var positionID = 0;

function showPosition(buttonid) {
	$('#message').remove();
	positionID = $('#' + buttonid).data('positionid');

	$.ajax({
		url: "/voting/getNominations.php",
		type: 'get',
		dataType: 'json',
		data: { 'positionID': positionID },
		success: function (nominations) {
			$(".group").remove();

			$('#nominationDiv').data('positionid', positionID);

			if (!nominations.status) {
				return false;
			}

			//setup youtube playlist
			$('#playlist').attr('src', 'https://www.youtube.com/embed/videoseries?list=' + youtube[nominations.data[0].positionID]);

			$('#submit').text('Submit vote for ' + nominations.data[0].positionName);

			$.each(nominations.data, function () {
				//formatting manifesto
				if(this.manifesto != null){
					this.manifesto = this.manifesto.replace("\"", "\\\"");
					this.manifesto = this.manifesto.replace(/\r?\n|\r/g, "</p><p style='text-align: center;'>");
				}

				if(this.image == null){
					this.image = "https://society.ecs.soton.ac.uk/images/new-logo-black.png";
				}

				this.image = this.image.replace("..", "../..");

				var div = "<div id='" + this.nominationID + "' class='group'><h3 class='unselectable' style='text-align: center;'>" + this.nominationName + "<div class='dragme'></h3>";
				div += "<div class='content' data-nominationid='" + this.nominationID + "'><p style='text-align: center;'>" + this.manifesto + "</p>";

				if(this.image != null){
					div += "<image class='unselectable' src='" + this.image + "'</div></div>";
				}

				$('#nominationDiv').append(div);

				var nomination = this;

				if (typeof entryData[positionID] != 'undefined') {
					if (entryData[positionID] != null) {
						if (typeof entryData[positionID][nomination.nominationID] != 'undefined') {
							$('.rankSelect').each(function () {
								if ($(this).data('nominationid') == nomination.nominationID) {
									$(this).val(entryData[positionID][nomination.nominationID]);
								}
							});
						}
					}
				} else {
					entryData[positionID] = null;
				}
			});
			$("#nominationDiv").accordion({
				collapsible: true,
				header: "> div > h3",
				heightStyle: "content",
				active: false
			}).sortable({
				axis: "y",
				handle: ".dragme",
				stop: function( event, ui ) {
				  ui.item.children( "h3" ).triggerHandler( "focusout" );

				  // Refresh accordion to handle new order
				  $( this ).accordion( "refresh" );
			  	}
			  });

			$("#nominationDiv").accordion("refresh");
			$("#nominationDiv").sortable("enable");
		}
	});
}

function submit() {
	var idsinOrder = $("#nominationDiv").sortable("toArray");
	var intsInOrder = [];

	$.each(idsinOrder, function(){
		intsInOrder.push(parseInt(this));
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
				$(".group").remove();

                $("#nominationDiv").append("<p id='message'>" + result.message + "</p>");
			}
		}
	});
}
