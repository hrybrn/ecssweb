#!/bin/bash
cd "/var/www"
#may as well keep up to date
#git pull origin master

#remove previous database
cd db
rm ecss.db

sqlite3 ecss.db ".read table/jumpstart.sql"
sqlite3 ecss.db ".read table/admin.sql"
sqlite3 ecss.db ".read realData/tasks.sql"

chmod 777 ecss.db