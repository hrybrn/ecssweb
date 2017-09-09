function load(){
	var boxes = {};

	var highest = 0;

	$(groups).each(function(){
		var box = "<div>";
		box += "<h3>Group " + this.groupID + "</h3>";
		box += "<image width=400 src='../images/" + this.image + "'></image>";
		box += "<p>" + this.name + "</p>";
		box += "</div>";

		boxes[this.groupID] = box;

		if(this.groupID > highest)
			highest = this.groupID;
	});

	for(var i=0;i<=highest;i++)
		if(boxes[i] != "")
			$('body').append(boxes[i]);
}