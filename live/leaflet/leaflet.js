function nextPhoto(skip){
	currentIndex += skip;
	var currentFile = files[currentIndex];
	$('.slideshow').css("background-image", "url(\"" + currentFile + "\"")
}

var currentIndex = 0;