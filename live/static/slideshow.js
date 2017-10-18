function Slideshow(container, imagesPaths, interval) {
    var slideshow = this;

    if(typeof interval == "undefined"){
        interval = 5000;
    }

    this.updateTime(interval);

    this.container = container;
    this.imagesPaths = imagesPaths;

    this.currentIndex = 0;
    this.isPlaying = false;

    this.container.classList.add("slideshow"); // add class to container

    // image container
    this.imageContainer = document.createElement("div");
    this.imageContainer.classList.add("slideshowImageContainer");
    // image
    this.image = document.createElement("img");
    this.updateImage(this.imagesPaths[this.currentIndex]);
    this.imageContainer.appendChild(this.image);
    // add image container
    this.container.appendChild(this.imageContainer);

    // control
    this.controlContainer = document.createElement("div");
    this.controlContainer.classList.add("slideshowControl");
    // previous button
    this.previousButton = document.createElement("button");
    this.previousButton.textContent = '<';
    this.previousButton.classList.add("slideshowButton");
    this.previousButton.addEventListener("click", function(){slideshow.nextPhoto(-1)});
    this.controlContainer.appendChild(this.previousButton);
    // pause button
    this.pauseButton = document.createElement("button");
    this.pauseButton.textContent = '||';
    this.pauseButton.classList.add("slideshowButton");
    this.pauseButton.addEventListener("click", function(){slideshow.pausePlay()});
    this.controlContainer.appendChild(this.pauseButton);
    // next button
    this.nextButton = document.createElement("button");
    this.nextButton.textContent = '>';
    this.nextButton.classList.add("slideshowButton");
    this.nextButton.addEventListener("click", function(){slideshow.nextPhoto(1)});
    this.controlContainer.appendChild(this.nextButton);
    // add control
    this.container.appendChild(this.controlContainer);

    // start
    this.startSlideshow();
}

Slideshow.prototype.updateImage = function(imagePath) {
    this.image.src = imagePath;
    this.image.alt = "";
}

Slideshow.prototype.startSlideshow = function() {
    this.isPlaying = true;
    var slideshow = this;
    this.autoSlideshow = setInterval(function() {
        slideshow.nextPhoto(1);
    }, Slideshow.prototype.interval);
}

Slideshow.prototype.interval = 5000;

Slideshow.prototype.updateTime = function(newTime){
    Slideshow.prototype.interval = newTime;
}

Slideshow.prototype.nextPhoto = function(skip) {
    this.currentIndex += skip;

    if(this.currentIndex < 0){
        this.currentIndex = this.imagesPaths.length + this.currentIndex;
    }

    if(this.currentIndex >= this.imagesPaths.length){
        this.currentIndex = this.currentIndex - this.imagesPaths.length;
    }

    this.updateImage(this.imagesPaths[this.currentIndex]);

    if (this.isPlaying) {
        // reset timer
        clearInterval(this.autoSlideshow);
        this.startSlideshow();
    }
}

Slideshow.prototype.pausePlay = function(button) {
    if (this.isPlaying) {
        clearInterval(this.autoSlideshow);
        this.isPlaying = false;
        this.pauseButton.classList.add("slideshowButtonToggled");
    } else {
        clearInterval(this.autoSlideshow);
        this.startSlideshow();
        this.isPlaying = true;
        this.pauseButton.classList.remove("slideshowButtonToggled");
    }
}