CREATE TABLE IF NOT EXISTS slideshow (
  slideshowID INTEGER PRIMARY KEY AUTOINCREMENT,
  slideshowName TEXT,
  slideshowLocation TEXT
);

CREATE TABLE IF NOT EXISTS slideshowImage (
  slideshowImageID INTEGER PRIMARY KEY AUTOINCREMENT,
  slideshowImageName TEXT,
  slideshowID INTEGER,
  activated BOOLEAN,
  FOREIGN KEY (slideshowID) REFERENCES slideshow(slideshowID)
);

--INSERT INTO slideshow (slideshowName, slideshowLocation) VALUES ('sponsors page', 'sponsors/slideshow');
