USE theled_dev_db;

CREATE TABLE inventory_update_flag (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, 
    table_name VARCHAR(200) NOT NULL,
    last_update DATETIME NOT NULL
);

CREATE TABLE sites_hivestack (
`site ID` VARCHAR(12) PRIMARY KEY NOT NULL UNIQUE,
`name` VARCHAR(150) NOT NULL,
`internal notes` VARCHAR(120) NOT NULL,
`latitude` INT NOT NULL,
`longitude` INT NOT NULL,
`active?` ENUM('true','false') NOT NULL,
`active screens` TINYINT NOT NULL,
`inactive screens` TINYINT NOT NULL DEFAULT 0,
`last modified on - UTC` DATETIME
);


CREATE TABLE screens_hivestack (
`screen ID` VARCHAR(60) PRIMARY KEY NOT NULL UNIQUE,
`screen name` VARCHAR(130) NOT NULL,
`unit ID` INT NOT NULL,
`ad request API UUID` VARCHAR(36),
`OpenRTB UUID` VARCHAR(36),
`internal notes` VARCHAR(60) NOT NULL,
`active` ENUM('true','false') NOT NULL,
`site ID` VARCHAR(12) NOT NULL,
`site name` VARCHAR(150) NOT NULL,
`ad requested in the last hour` ENUM('true','false') NOT NULL,
`floor CPM` INT NOT NULL,
`Multiplier vendor` VARCHAR(60) NULL,
`Multiplier vendor screen ID` VARCHAR(60) NULL,
`has impression data` ENUM('true','false') NOT NULL,
`screen width (px)` SMALLINT NOT NULL,
`screen height (px)` SMALLINT NOT NULL,
`latitude` INT NOT NULL,
`longitude` INT NOT NULL,
`zip code` VARCHAR(9) NOT NULL,
`venue type` VARCHAR(30) NOT NULL,
`network` VARCHAR(50) NOT NULL,
`country` VARCHAR(50) NOT NULL,
`region` VARCHAR(50) NOT NULL,
`city` VARCHAR(50) NOT NULL,
`tags` VARCHAR(50),
`created on - UTC` DATETIME NOT NULL,
`last modified on - UTC` DATETIME NOT NULL,
`available for ad server` ENUM('true','false') NOT NULL,
`available for deals` ENUM('true','false') NOT NULL,
`available for open exchange` ENUM('true','false') NOT NULL,
`allow HTML` ENUM('true','false') NOT NULL,
`allow images` ENUM('true','false') NOT NULL,
`allow videos` ENUM('true','false') NOT NULL,
`allow zip` ENUM('true','false') NOT NULL,
`allow audio-only files` ENUM('true','false') NOT NULL,
`facing direction` VARCHAR(50),
`languages` VARCHAR(20) NOT NULL,
`physical screen width` SMALLINT,
`physical screen height` SMALLINT,
`time zone` VARCHAR(20) NOT NULL,
`default ad duration` VARCHAR(20) NOT NULL,
`blocked advertisers` VARCHAR(200),
`blocked categories` VARCHAR(200),
`strict categories blocking` ENUM('true','false') NOT NULL,
`category frequency cap` VARCHAR(20) NOT NULL,
`strict frequency capping`  ENUM('No','Yes') NOT NULL,
`advertiser frequency cap` SMALLINT DEFAULT 0,
`last ad request - UTC` DATETIME,
`last played on - UTC` DATETIME,
FOREIGN KEY (`site ID`) REFERENCES sites_hivestack(`site ID`)
);