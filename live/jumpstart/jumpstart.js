var tableVisible = 0;

function load(){
	var boxes = {};

	var highest = 0;

	$(groups).each(function(){
		var box = "<a onclick='openGroup(" + this.groupID + ")'><div id='group" + this.groupID + "' class=\"overviewSquare\">";
		box += "<image src='../images/" + this.image + "'></image>";
                box += "<h3>Group " + this.groupID + "</h3>";
		box += "<p><marquee>" + this.name + "</marquee></p>";
		box += "</div></a>";

		boxes[this.groupID] = box;

		if(this.groupID > highest)
			highest = this.groupID;
	});

	for(var i=0;i<=highest;i++)
		if(boxes[i] != "")
			$('body').append(boxes[i]);
}

function openGroup(groupID){

	$('.groupTable').remove();
	if(tableVisible == groupID){
		tableVisible = 0;
	} else {
		tableVisible = groupID;
		$.ajax({
			url: 'getGroup.php',
	        type: 'get',
	        data: {'groupID': groupID},
	        dataType: 'json',
	        success: function (group) {
	        	var html = "<div class='groupTable'><table><tr><th>Name</th><th>Role</th></tr>";

	        	$(group.helpers).each(function(){
	        		html += "<tr><td>" + this.name + "</td><td>Helper</td></tr>";
	        	});

	        	$(group.freshers).each(function(){
	        		html += "<tr><td>" + this.name + "</td><td>Fresher</td></tr>";
	        	});

	        	html += "</table></div>";

	        	$('#group' + groupID).append(html);
	        	tableVisible[groupID] = true;
	        }
	    });
	}
}