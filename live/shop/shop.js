$(document).ready(function(){
		search("");

		$('#searchButton').click(function(){
			search($('#searchBox').val());
		});

		$("#searchBox").keydown(function (e) {
  			if (e.keyCode == 13) {
    			search($('#searchBox').val());
  			}
		});
});

function search(search){
	$.ajax({
		url: "/shop/search.php",
		type: 'get',
		data: {'search': search},
		dataType: 'json',
		success: function(result){
			if(result.status){
				$('.item').remove();

				var slideshowID = "";

				$.each(result.data, function(){
					var colours = [];

					slideshowID = "slideshow" + this[0].itemID;

					$.each(this, function(){
						colours.push("../" + this.itemColourImage);
					});

					var item = "<div id='item" + this[0].itemID + "' class='item' onclick='window.location = \"/shop/item?itemID=" + this[0].itemID + "\";'>";
					item += "<h3 class='itemName'>" + this[0].itemName + "</h3>";
					item += "<div id='" + slideshowID + "'></div>";
					item += "</div>";

					$('#itemDiv').append(item);

					new Slideshow(document.getElementById(slideshowID), colours, 2000);
					$('.slideshowButton').remove();
				});
			}
		}
	});
}