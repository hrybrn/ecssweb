$(document).ready(function () {
	showPosition(first);
	init();
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
				var div = "<div id='" + this.nominationID + "' class='group'><h3>" + this.nominationName + "</h3>";
				div += "<div data-nominationid='" + this.nominationID + "'><p>" + this.manifesto + "</p>";
				div += "<image src='/nominations/" + this.image + "'</div></div>";

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
				autoheight: false
			}).sortable({
				axis: "y",
				handle: "h3",
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

	$.ajax({
		url: "/voting/submitVote.php",
		type: 'post',
		dataType: 'json',
		data: { 'entryData': intsInOrder, 'positionID': positionID },
		success: function (result) {
			if(result.status){
				$("#button" + positionID).remove();
				$(".group").remove();

				$("#nominationDiv").append("<span id='message'><p>" + result.message + "</p></span>");
			}
		}
	});
}

function touchHandler(event) {
    var touch = event.changedTouches[0];

    var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent({
        touchstart: "mousedown",
        touchmove: "mousemove",
        touchend: "mouseup"
    }[event.type], true, true, window, 1,
        touch.screenX, touch.screenY,
        touch.clientX, touch.clientY, false,
        false, false, false, 0, null);

    touch.target.dispatchEvent(simulatedEvent);
    event.preventDefault();
}

function init() {
    document.addEventListener("touchstart", touchHandler, true);
    document.addEventListener("touchmove", touchHandler, true);
    document.addEventListener("touchend", touchHandler, true);
    document.addEventListener("touchcancel", touchHandler, true);
}