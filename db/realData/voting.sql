INSERT INTO electionType(electionName, electionDescription) VALUES('Postgrad and Masters Election', 'This is an election for Postgrad and Masters positions only.');
INSERT INTO electionType(electionName, electionDescription) VALUES('General Election', 'This is an election for most of the committee positions.');

INSERT INTO election(electionTypeID, nominationStartDate, nominationEndDate, votingStartDate, votingEndDate) VALUES(1, '2017-09-25', '2017-09-31', '2017-10-01', '2017-10-10');

INSERT INTO position(electionTypeID, positionName, description) VALUES(1, 'Postgraduate Representative', 'The PhD rep liases with students on ECS doctoral courses, helping the ECSS committee who are often in their undergraduate degrees to cater for the PhD audience.');
INSERT INTO position(electionTypeID, positionName, description) VALUES(1, 'Masters Representative', 'The masters rep liases with students on ECS masters courses, helping the ECSS committee who are often in their undergraduate degrees to cater for the masters audience.');