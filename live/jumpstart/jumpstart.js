function load(){
	var boxes = {};

	var highest = 0;

	$(groups).each(function(){
		var box = "<div class=\"overviewSquare\">";
		box += "<image src='../images/" + this.image + "'></image>";
                box += "<h3>Group " + this.groupID + "</h3>";
		box += "<p><marquee>" + this.name + "</marquee></p>";
		box += "</div>";

		boxes[this.groupID] = box;

		if(this.groupID > highest)
			highest = this.groupID;
	});

	for(var i=0;i<=highest;i++)
		if(boxes[i] != "")
			$('body').append(boxes[i]);
}