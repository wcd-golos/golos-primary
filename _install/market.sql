CREATE DATABASE `market` CHARACTER SET utf8;
USE `market`;

/*Table structure for table `market` */

DROP TABLE IF EXISTS `market`;

CREATE TABLE `market` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Desc` text,
  `Price` decimal(10,2) DEFAULT NULL,
  `Alias` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `market` */

insert  into `market`(`ID`,`Name`,`Image`,`Desc`,`Price`,`Alias`) values (1,'Платье, Barcelonica','model7.jpg','Изящное платье-футляр облегающего силуэта с прозрачными вставкам черного цвета. Такое платье прекрасно подойдет для ужина в ресторане или вечеринки.','47','lace-dress'),(2,'Пальто, Nevis','model2.jpg','Изящное пальто облегающего силуэта.','99','coat'),(3,'Платье','model6.jpg','Изящное платье облегающего силуэта с прозрачными вставкам. Такое платье прекрасно подойдет для ужина в ресторане или вечеринки.','55','dress'),(4,'Платье, Mini','model1.jpg','Изящное короткое платье облегающего силуэта с прозрачными вставкам. Такое платье прекрасно подойдет для ужина в ресторане или вечеринки.','35','short-dress'),(5,'Шорты','model5.jpg','Элегантные шорты. Такие шорты прекрасно подойдет для пляжа или вечеринки.','22','shorts');

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Product` varchar(255) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `Permlink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `reviews` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `ID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `LastName` varchar(255) NOT NULL DEFAULT '',
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) NOT NULL DEFAULT '',
  `Email` varchar(255) NOT NULL DEFAULT '',
  `Status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `RoleID` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`ID`,`Name`,`LastName`,`Username`,`Password`,`Email`,`Status`,`RoleID`) values (1,'Admin','Admin','administrator','5ebe2294ecd0e0f08eab7690d2a6ee69','admin@yourmarket.com',1,1),(2,'Guest','Guest',NULL,'fa375de6e5680065bf07b3fb32616f3a','',0,2);

/*Table structure for table `users_resources` */

DROP TABLE IF EXISTS `users_resources`;

CREATE TABLE `users_resources` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `ParentID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `users_resources` */

insert  into `users_resources`(`ID`,`Name`,`ParentID`) values (1,'admin',0),(2,'frontend',0),(3,'frontend-default',2),(4,'frontend-default-index',2),(5,'frontend-default-index-index',2),(6,'frontend-default-index-product',2),(7,'frontend-default-index-post',2);

/*Table structure for table `users_roles` */

DROP TABLE IF EXISTS `users_roles`;

CREATE TABLE `users_roles` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL DEFAULT '',
  `SiteSection` varchar(255) DEFAULT NULL,
  `ParentID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `users_roles` */

insert  into `users_roles`(`ID`,`Name`,`SiteSection`,`ParentID`) values (1,'Admin','admin',0),(2,'Guest','frontend',0);

/*Table structure for table `users_rules` */

DROP TABLE IF EXISTS `users_rules`;

CREATE TABLE `users_rules` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `User_Role_ID` int(10) unsigned NOT NULL DEFAULT '0',
  `User_Resource_ID` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `users_rules` */

insert  into `users_rules`(`ID`,`User_Role_ID`,`User_Resource_ID`) values (1,1,0),(2,2,2);
