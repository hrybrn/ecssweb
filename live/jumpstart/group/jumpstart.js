var tableVisible = 0;

function load(){
	var boxes = {};

	var highest = 0;

	$(groups).each(function(){
		boxes[this.groupID] = makeBox(this);

		if(parseInt(this.groupID) > highest)
			highest = this.groupID;
	});

	for(var i=0;i<=highest;i++)
		if(boxes[i] != "")
			$('body').append(boxes[i]);
}

function makeBox(group){
	var box = "<a onclick='openGroup(" + group.groupID + ")'><div id='group" + group.groupID + "' class=\"overviewSquare\">";
	box += "<image src='/images/" + group.image + "'></image>";
    box += "<h3>" + group.groupName + "</h3>";
	box += "<p><marquee>Helper: " + group.memberName + "</marquee></p>";
	box += "</div></a>";

	return box;
}

function openGroup(groupID){

	$('.groupTable').remove();
	if(tableVisible == groupID){
		tableVisible = 0;
	} else {
		tableVisible = groupID;
		$.ajax({
			url: '/jumpstart/group/getGroup.php',
	        type: 'get',
	        data: {'groupID': groupID},
	        dataType: 'json',
	        success: function (group) {
	        	var html = "<div class='groupTable'>";
                        html += "<p class=\"groupTableHeader\">Members:</p>";

	        	$(group.freshers).each(function(){
	        		html += "<p class=\"fresher\">" + this.memberName + "<p>";
	        	});

	        	//html += "</table></div>";

	        	$('#group' + groupID).append(html);
	        }
	    });
	}
}

function search(){
	var search = $('#searchInput').val();

	$.ajax({
		url: '/jumpstart/group/search.php',
		type: 'get',
		data: {'search': search},
		dataType: 'json',
		success: function (searchResults) {
			$('.overviewSquare').remove();

			groups = searchResults;
			load();
		}
	});
}