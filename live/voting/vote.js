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

			$.each(nominations.data, function () {
				//formatting manifesto
				this.manifesto = this.manifesto.replace(/\r?\n|\r/g, "<br>");
				var div = "<div id='" + this.nominationID + "' class='group'><h3 class='unselectable' style='text-align: center;'>" + this.nominationName + "<div class='dragme'></h3>";
				div += "<div class='content' data-nominationid='" + this.nominationID + "'><p style='text-align: center;'>" + this.manifesto + "</p>";

				if(this.image != null){
					div += "<image class='unselectable' src='/nominations/" + this.image + "'</div></div>";
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
