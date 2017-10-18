CREATE TABLE item (
    itemID INTEGER PRIMARY KEY,
    itemName varchar(255),
    itemPrice varchar(255),
    itemDesc varchar(255)
);

CREATE TABLE itemColour (
	itemColourID INTEGER PRIMARY KEY,
	itemID INTEGER,
	itemColourName varchar(255),
	itemColourImage varchar(255),
	FOREIGN KEY (itemID) REFERENCES item(itemID)
);


INSERT INTO item (itemName, itemPrice, itemDesc) VALUES ('ECSS Helper', '£7.50', 'Get your very own ECSS Helper!');

INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Allen', "images/allen.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Harry', "images/committee/awesomewebmaster.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Rayna', "images/rayna.jpg");
INSERT INTO itemColour (itemID, itemColourName, itemColourImage) VALUES (1, 'Felix', "images/helpers/fdn.jpg");
