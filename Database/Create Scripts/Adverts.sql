CREATE TABLE Adverts
(
AdvertId int PRIMARY KEY AUTO_INCREMENT,
Title varchar(255),
Description varchar(255),
InsertedStamp DateTime,
UserId int,
FULLTEXT (Title,Description),
FOREIGN KEY (UserId) REFERENCES Users(UserId)
) ENGINE=MyISAM;