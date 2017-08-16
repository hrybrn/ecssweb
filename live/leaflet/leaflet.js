function nextPhoto(skip){
	currentIndex += skip;

	if(currentIndex < 0){
		currentIndex = files.length + currentIndex;
	}

	if(currentIndex >= files.length){
		currentIndex = currentIndex - files.length;
	} 


	console.log(currentIndex);

	var currentFile = files[currentIndex];
	$('.slideshow').css("background-image", "url(\"" + currentFile + "\")");
}

var currentIndex = 0;