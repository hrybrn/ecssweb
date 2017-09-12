CREATE TABLE jumpstartGroup (
	groupID INTEGER PRIMARY KEY AUTOINCREMENT,
	name VARCHAR(255)
);

CREATE TABLE jumpstart (
	memberID INTEGER PRIMARY KEY AUTOINCREMENT,
	name VARCHAR(255),
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
	name VARCHAR(255),
	file BOOLEAN,
	description VARCHAR(255)
);

CREATE TABLE taskEntry (
	taskEntryID INTEGER PRIMARY KEY AUTOINCREMENT,
	groupID INTEGER,
	taskID INTEGER,
	entry VARCHAR(1000),
	FOREIGN KEY (groupID) REFERENCES jumpstartGroup(groupID),
	FOREIGN KEY (taskID) REFERENCES task(taskID)
);

CREATE INDEX taskEntryGroupIndex ON taskEntry(groupID);