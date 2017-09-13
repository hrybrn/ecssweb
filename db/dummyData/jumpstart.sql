insert into jumpstartGroup(groupName) values ('Group 1');
insert into jumpstartGroup(groupName) values ('Group 2');
insert into jumpstartGroup(groupName) values ('Group 3');

insert into jumpstart(memberName, groupID, helper) values ('Harry Brown', 1, 1);
insert into jumpstart(memberName, groupID, helper) values ('Christopher Coles', 1, 0);
insert into jumpstart(memberName, groupID, helper) values ('Josh Wright', 1, 0);
insert into jumpstart(memberName, groupID, helper) values ('Simon Brooks', 1, 0);

insert into jumpstart(memberName, groupID, helper) values ('Charis King', 2, 1);
insert into jumpstart(memberName, groupID, helper) values ('Christian Clarke', 2, 0);
insert into jumpstart(memberName, groupID, helper) values ('Luke Wooley', 2, 0);
insert into jumpstart(memberName, groupID, helper) values ('Hope Shaw', 2, 0);

insert into jumpstart(memberName, groupID, helper) values ('Rayna Marinova Bozhkova', 3, 1);
insert into jumpstart(memberName, groupID, helper) values ('Desislava Danielova Stamenova', 3, 0);
insert into jumpstart(memberName, groupID, helper) values ('Stanimir Tashev', 3, 0);
insert into jumpstart(memberName, groupID, helper) values ('Yoan-Daniel Malinov', 3, 0);

insert into helper(memberID, image, username, admin) values (1, "helpers/harry.jpg", 'hb15g16', 0);
insert into helper(memberID, image, username, admin) values (5, "helpers/charis.jpg", 'ck15g16', 1);
insert into helper(memberID, image, username, admin) values (9, "helpers/rayna.jpg", 'rmb15g16', 0);

insert into task(taskName, file, description) values ('testChallenge', 0, 'This is a test challenge');
insert into task(taskName, file, description) values ('testFile', 1, 'This is a test on file uploads');

insert into taskEntry(groupID, taskID, entry, latest, entryTime) values (1, 1, 'Test Entry', 1, 'Tue, 12 Sep 17 16:44:47 +0100');