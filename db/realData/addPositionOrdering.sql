ALTER TABLE position
ADD COLUMN ordering INTEGER;

UPDATE position SET ordering = 0 WHERE positionID = 4;
UPDATE position SET ordering = 1 WHERE positionID = 5;
UPDATE position SET ordering = 2 WHERE positionID = 15;
UPDATE position SET ordering = 3 WHERE positionID = 9;
UPDATE position SET ordering = 4 WHERE positionID = 6;
UPDATE position SET ordering = 5 WHERE positionID = 7;
UPDATE position SET ordering = 6 WHERE positionID = 11;
UPDATE position SET ordering = 7 WHERE positionID = 10;
UPDATE position SET ordering = 8 WHERE positionID = 13;
UPDATE position SET ordering = 9 WHERE positionID = 14;
UPDATE position SET ordering = 10 WHERE positionID = 12;
UPDATE position SET ordering = 11 WHERE positionID = 8;
