CREATE TABLE Images
(
ImageId int PRIMARY KEY AUTO_INCREMENT,
Location varchar(255),
Description varchar(255),
AdvertId int,
FOREIGN KEY (AdvertId) REFERENCES Adverts(AdvertId)
)