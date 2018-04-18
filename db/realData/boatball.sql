INSERT INTO item (itemName, itemPrice, itemDesc, itemImage, shopID) VALUES ('STEM Boat Ball Ticket', 'Price TBD', 'Someone who is more elegant with words should write the description.', 'images/merch/boatball.gif', 3);

INSERT INTO shop (openDate, shutDate) VALUES ('2018-04-20', '2018-05-01');

CREATE TABLE purchase (
    purchaseID TEXT,
    username TEXT,
    society TEXT,
    purchased INTEGER
);