INSERT INTO shop(openDate, shutDate) VALUES('2018-02-06', '2018-02-20');

INSERT INTO collectionDates (shopID, collectionDate) VALUES(2, 'Electronic');

ALTER TABLE item ADD COLUMN shopID INTEGER;
UPDATE item SET shopID = 1;

INSERT INTO item(itemName, itemPrice, itemDesc, itemImage, shopID)
VALUES ('Playzone Pandemonium Ft. Psychosoc & ECSS', '£20', 'Psychosoc and ECSS have come together to create one big epic social!
Alcoholic slushies combined with indoor play set and a ball pit, the evening is set to be crazy fun. 
<br><br>
Playzone is based in Portsmouth so we will be taking two coaches to get there, limited tickets available!
<br><br>
Last year Psychosoc sold out really quick so buy them ASAP to avoid disappointment. The ticket covers the cost for the hire of playzone and a seat on the coach.
<br><br>
Everyone needs to bring valid ID, as this is a requirement for the bar to be open at Playzone.
We will be checking IDs before you get on the coach.', 'images/merch/playzone.jpg', 2);