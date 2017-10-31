CREATE TABLE company (
    companyID INTEGER PRIMARY KEY AUTOINCREMENT,
    companyName VARCHAR(255),
    applicationStartDate TEXT,
    applicationEndDate TEXT
);

CREATE TABLE application (
    applicationID INTEGER PRIMARY KEY AUTOINCREMENT,
    companyID INTEGER,
    applicationName VARCHAR(255),
    applicationUsername VARCHAR(255),
    applicationEmail VARCHAR(255),
    applicationCourse VARCHAR(255),
    applicationYear VARCHAR(255),
    applicationCV VARCHAR(255),
    applicationCover VARCHAR(255),
    FOREIGN KEY (companyID) REFERENCES company(companyID)
);

INSERT INTO company(companyName, applicationStartDate, applicationEndDate) VALUES ("Ultra Electronics" , "2017-10-22", "2017-11-21");