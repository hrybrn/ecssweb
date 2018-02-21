CREATE TABLE support(
    supportID INTEGER PRIMARY KEY AUTOINCREMENT,
    nominationID INTEGER,
    supportUsername TEXT,
    FOREIGN KEY (nominationID) REFERENCES nomination(nominationID)
);