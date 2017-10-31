CREATE TABLE comment (
    commentID INTEGER PRIMARY KEY AUTOINCREMENT,
    commentMessage VARCHAR(1023),
    adminID INTEGER,
    adminResponse VARCHAR(1023)
);

CREATE TABLE commentHash (
    commentHashID INTEGER PRIMARY KEY AUTOINCREMENT,
    commentHash VARCHAR(255),
    commentHashDate TEXT
);