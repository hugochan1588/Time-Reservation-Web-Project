use 19071028d;

CREATE TABLE choices (
studentid varchar(255) NOT NULL,
code varchar(255) NOT NULL,
selected_time longtext NOT NULL,
submission_time datetime NOT NULL,
CONSTRAINT nodupe PRIMARY KEY (studentid, code)
);

CREATE TABLE data (
username varchar(255) NOT NULL,
password varchar(255) NOT NULL,
type varchar(255) NOT NULL,
PRIMARY KEY (username)
);

CREATE TABLE meeting(
id int(11) NOT NULL AUTO_INCREMENT,
uuid varchar(255) NOT NULL,
title varchar(255) NOT NULL,
subject varchar(255) NOT NULL,
teacher varchar(255) NOT NULL,
duration int(11) NOT NULL,
deadline datetime NOT NULL,
timeslots longtext NOT NULL,
studentid longtext NOT NULL,
password varchar(255) NOT NULL,
numbers longtext NOT NULL,
days longtext NOT NULL,
PRIMARY KEY (id)
);

CREATE TABLE result(
uuid varchar(255) NOT NULL,
result longtext NOT NULL,
PRIMARY KEY (uuid)
);

INSERT INTO data (username, password, type)
VALUES
('teacher','teacher','teacher');

commit;