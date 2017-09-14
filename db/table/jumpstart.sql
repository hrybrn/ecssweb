CREATE TABLE jumpstartGroup (
	groupID INTEGER PRIMARY KEY AUTOINCREMENT,
	groupName VARCHAR(255)
);

CREATE TABLE jumpstart (
	memberID INTEGER PRIMARY KEY AUTOINCREMENT,
	memberName VARCHAR(255),
	groupID INTEGER,
	helper BOOLEAN,
	FOREIGN KEY (groupID) REFERENCES jumpstartGroup(groupID)
);

CREATE INDEX jumpstartGroupIndex ON jumpstart(groupID);

CREATE TABLE helper (
	helperID INTEGER PRIMARY KEY AUTOINCREMENT,
	memberID INTEGER,
	image VARCHAR(255),
	username VARCHAR(255),
	admin BOOLEAN,
	FOREIGN KEY (memberID) REFERENCES jumpstart(memberID)
);

CREATE TABLE task (
	taskID INTEGER PRIMARY KEY AUTOINCREMENT,
	taskName VARCHAR(255),
	file BOOLEAN,
	description VARCHAR(255)
);

CREATE TABLE taskEntry (
	taskEntryID INTEGER PRIMARY KEY AUTOINCREMENT,
	groupID INTEGER,
	taskID INTEGER,
	entry VARCHAR(1000),
	latest BOOLEAN,
	entryTime TEXT,
	FOREIGN KEY (groupID) REFERENCES jumpstartGroup(groupID),
	FOREIGN KEY (taskID) REFERENCES task(taskID)
);

CREATE INDEX taskEntryGroupIndex ON taskEntry(groupID);

CREATE TABLE uploadHash (
	hashID INTEGER PRIMARY KEY AUTOINCREMENT,
	groupID INTEGER,
	hash VARCHAR(255),
	expiry TEXT,
	FOREIGN KEY (groupID) REFERENCES jumpstartGroup(groupID)
);