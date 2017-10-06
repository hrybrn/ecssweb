create table electionType (
    electionTypeID INTEGER PRIMARY KEY AUTOINCREMENT,
    electionName VARCHAR(255),
    electionDescription VARCHAR(255)
);

create table position (
    positionID INTEGER PRIMARY KEY AUTOINCREMENT,
    electionTypeID INTEGER,
    positionName VARCHAR(255),
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
    manifesto VARCHAR(1000),
    nominationName VARCHAR(255),
    nominationUsername VARCHAR(255),
    image VARCHAR(255),
    FOREIGN KEY(positionID) REFERENCES position(positionID),
    FOREIGN KEY(electionID) REFERENCES election(electionID)
);

create table vote (
    voteID INTEGER PRIMARY KEY AUTOINCREMENT,
    nominationID INTEGER,
    voteUsername VARCHAR(255),
    ranking INTEGER,
    FOREIGN KEY(nominationID) REFERENCES nomination(nominationID)
);