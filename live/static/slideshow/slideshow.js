/*
 * JS Image Slideshow
 * https://github.com/allc/JS-Image-Slideshow
 *
 * Copyright (c) 2017 Cui Jinxuan
 *
 * Licensed under the MIT license:
 * https://github.com/allc/JS-Image-Slideshow/blob/master/LICENSE
 */
function Slideshow(container, imagesPaths, options) {
    //'use strict';
    var slideshow = this;

    if(options && options.interval){
        this.interval = options.interval;
    } else {
        this.interval = 5000;
    }

    this.container = container;
    this.imagesPaths = imagesPaths;

    this.currentIndex = 0;
    this.isPlaying = false;

    this.container.classList.add("slideshow"); // add class to container

    // image container
    this.imageContainer = document.createElement("div");
    this.imageContainer.classList.add("slideshowImageContainer");
    // images
    this.images = [];
    for (var i = 0; i < imagesPaths.length; i++) {
        var image = document.createElement("img");
        image.src = this.imagesPaths[i];
        image.classList.add('slideshowHiddenImage');
        this.imageContainer.appendChild(image);
        this.images.push(image);
    }
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
    this.updateImage(this.currentIndex);
    this.startSlideshow();
}

Slideshow.prototype.updateImage = function(nextIndex, lastIndex) {
    if (typeof lastIndex !== 'undefined') {
        this.images[lastIndex].classList.add("slideshowHiddenImage");
    }
    this.images[nextIndex].classList.remove("slideshowHiddenImage");
}

Slideshow.prototype.startSlideshow = function() {
    this.isPlaying = true;
    var slideshow = this;
    this.autoSlideshow = setInterval(function() {
        slideshow.nextPhoto(1);
    }, this.interval);
}

Slideshow.prototype.nextPhoto = function(skip) {
    var lastIndex = this.currentIndex;
    this.currentIndex += skip;

    if(this.currentIndex < 0){
        this.currentIndex = this.imagesPaths.length + this.currentIndex;
    }

    if(this.currentIndex >= this.imagesPaths.length){
        this.currentIndex = this.currentIndex - this.imagesPaths.length;
    }

    this.updateImage(this.currentIndex, lastIndex);

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
