CREATE TABLE items (
    itemID INTEGER PRIMARY KEY,
    itemName varchar(255),
    itemPrice integer,
    itemImage varchar(255),
    itemDesc varchar(255)
);


INSERT INTO items (itemName, itemPrice, itemImage, itemDesc) VALUES ('Test Item', 15, 'images/allen.jpg', 'This is a test item for testing purposes');
