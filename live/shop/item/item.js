$('#sizeSelect').change(function(){
	var size = $('#sizeSelect').find(':selected').val();

	$('#size').val(size);
});

$('#colourSelect').change(function(){
	var colour = $('#colourSelect').find(':selected').val();
	$('#colour').val(colour);
});