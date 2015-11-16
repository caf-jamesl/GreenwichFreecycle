CREATE TABLE Adverts
(
AdvertId int PRIMARY KEY AUTO_INCREMENT,
Title varchar(255),
Description varchar(255),
UserId int,
FOREIGN KEY (UserId) REFERENCES Users(UserId)
)