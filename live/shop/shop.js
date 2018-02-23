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

		$("#searchBox").on("keyup search", function () {
			if ($('#searchBox').val() === "") {
                search($('#searchBox').val());
            }
        })
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

				$.each(result.data, function(){
					var item = "<div id='item" + this.itemID + "' class='item' onclick='window.location = \"/shop/item?itemID=" + this.itemID + "\";'>";
					item += "<h3 class='itemName'>" + this.itemName + "</h3>";
					item += "<div><img class='itemImage' src='../" + this.itemImage + "'></div>";
					item += "<p style='text-align: center;'>" + this.itemPrice + "</p>";
					item += "</div>";

					$('#itemDiv').append(item);
				});

				if(empty(result.data)){
					$('#itemDiv').append("<p class='item'>No items are currently for sale, sorry!</p>");
				}
			}
		}
	});
}