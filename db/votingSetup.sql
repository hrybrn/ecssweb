create table electionType (
    electionTypeID INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    description VARCHAR(255)
);

create table voter (
    voterID INTEGER PRIMARY KEY AUTOINCREMENT,
    email VARCHAR(255)
);

create table nominee (
    nomineeID INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255),
    email VARCHAR(255)
);

create table position (
    positionID INTEGER PRIMARY KEY AUTOINCREMENT,
    electionTypeID INTEGER,
    name VARCHAR(255),
    description VARCHAR(255),
    FOREIGN KEY(electionTypeID) REFERENCES electionType(electionTypeID)
);

create table election (
    electionID INTEGER PRIMARY KEY AUTOINCREMENT,
    electionTypeID INTEGER,
    nominationStartDate TEXT,
    nominationEndDate TEXT,
    votingStartDate TEXT,
    votingEndDate TEXT,
    FOREIGN KEY(electionTypeID) REFERENCES electionType(electionTypeID)
);

create table nomination (
    nominationID INTEGER PRIMARY KEY AUTOINCREMENT,
    positionID INTEGER,
    electionID INTEGER,
    nomineeID INTEGER,
    manifesto VARCHAR(1000),
    FOREIGN KEY(positionID) REFERENCES position(positionID),
    FOREIGN KEY(electionID) REFERENCES election(electionID),
    FOREIGN KEY(nomineeID) REFERENCES nominee(nomineeID)
);

create table vote (
    voteID INTEGER PRIMARY KEY AUTOINCREMENT,
    nominationID INTEGER,
    voterID INTEGER,
    ranking INTEGER,
    FOREIGN KEY(nominationID) REFERENCES nomination(nominationID),
    FOREIGN KEY(voterID) REFERENCES voter(voterID)
);