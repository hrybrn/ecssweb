CREATE TABLE hackathonEvent(
    hackathonEventID INTEGER PRIMARY KEY AUTOINCREMENT,
    hackathonEventName TEXT,
    hackathonEventInfo TEXT,
    hackathonEventLogo TEXT,
    hackathonApplicationStartDate TEXT,
    hackathonApplicationEndDate TEXT
);

CREATE TABLE hackathonTeam(
    hackathonTeamID INTEGER PRIMARY KEY AUTOINCREMENT,
    hackathonEventID INTEGER,
    hackathonName TEXT,
    hackathonTeamLeaderID INTEGER,
    hackathonMatchmaking INTEGER,
    FOREIGN KEY(hackathonTeamLeaderID) REFERENCES hackathonPerson(hackathonPersonID),
    FOREIGN KEY(hackathonEventID) REFERENCES hackathonEvent(hackathonEventID)
);

CREATE TABLE hackathonPerson(
    hackathonPersonID INTEGER PRIMARY KEY AUTOINCREMENT,
    hackathonPersonName TEXT,
    hackathonPersonEmail TEXT,
    hackathonPersonCourse TEXT,
    hackathonPersonGraduation INTEGER,
    hackathonPersonTShirtSize TEXT,
    hackathonPersonDietComments TEXT,
    hackathonTeamID INTEGER,
    FOREIGN KEY(hackathonTeamID) REFERENCES hackathonTeam(hackathonTeamID)
);

CREATE TABLE hackathonHash(
    hackathonHashID INTEGER PRIMARY KEY AUTOINCREMENT,
    hackathonHash TEXT,
    hackathonTeamID INTEGER,
    hackathonHashExpired INTEGER,
    hackathonHashDate TEXT,
    FOREIGN KEY(hackathonTeamID) REFERENCES hackathonTeam(hackathonTeamID)
);