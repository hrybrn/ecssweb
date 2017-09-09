CREATE TABLE jumpstart (
	memberID INTEGER PRIMARY KEY AUTOINCREMENT,
	name VARCHAR(255),
	groupID INTEGER,
	helper BOOLEAN
);

CREATE INDEX groupIndex ON jumpstart(groupID);

CREATE TABLE helper (
	memberID INTEGER,
	image VARCHAR(255),
	FOREIGN KEY (memberID) REFERENCES jumpstart(memberID)
);