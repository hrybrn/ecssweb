CREATE TABLE item (
    itemID INTEGER PRIMARY KEY AUTOINCREMENT,
    itemName varchar(255),
    itemPrice varchar(255),
    itemDesc varchar(255)
);

CREATE TABLE itemColour (
	itemColourID INTEGER PRIMARY KEY AUTOINCREMENT,
	itemID INTEGER,
	itemColourName varchar(255),
	itemColourImage varchar(255),
	FOREIGN KEY (itemID) REFERENCES item(itemID)
);

CREATE TABLE size (
	sizeID INTEGER PRIMARY KEY AUTOINCREMENT,
	itemID INTEGER,
	sizeName varchar(255),
	FOREIGN KEY (itemID) REFERENCES item(itemID)
);


INSERT INTO item (itemName, itemPrice, itemDesc) VALUES ('ECSS Helper', '£7.50', 'Get your very own ECSS Helper!');

INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Allen', "images/allen.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Harry', "images/committee/awesomewebmaster.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Rayna', "images/rayna.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Felix', "images/helpers/fdn.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Charis', "images/helpers/charis.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Pier', "images/helpers/ppi.jpg");

INSERT INTO size (itemID, sizeName) VALUES (1, "S");
INSERT INTO size (itemID, sizeName) VALUES (1, "M");
INSERT INTO size (itemID, sizeName) VALUES (1, "L");
INSERT INTO size (itemID, sizeName) VALUES (1, "XL");