CREATE TABLE Users
(
UserId int PRIMARY KEY AUTO_INCREMENT,
Username varchar(255) UNIQUE,
Password varchar(255),
FirstName varchar(255),
LastName varchar(255),
Email varchar(255),
AddressId int,
AccountStatusId int,
FOREIGN KEY (AddressId) REFERENCES Addresses(AddressId),
FOREIGN KEY (AccountStatusId) REFERENCES AccountStatus(AccountStatusId)
)