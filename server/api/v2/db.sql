-- List of all users
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` VARCHAR(40) NOT NULL,
    `email` VARCHAR(320) NOT NULL,
    `name` TEXT NOT NULL,
    `password` TEXT NOT NULL,
    `email_verified` TEXT NOT NULL,
	`profile_picture` TEXT NOT NULL,
	`settings` JSON NOT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- Who owns which domain
CREATE TABLE IF NOT EXISTS `domains` (
	`time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`domain_id` VARCHAR(40) NOT NULL,
	`user_id` VARCHAR(40) NOT NULL,
	`domain` TEXT NOT NULL,
	`name` TEXT NOT NULL,
	`domain_verified` TEXT NOT NULL,
	`permissions` TEXT NOT NULL,
	`settings` JSON NOT NULL,
    PRIMARY KEY (`time`),
    UNIQUE KEY `domain_id` (`domain_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- Unverified domain
CREATE TABLE IF NOT EXISTS `domains_unverified` (
	`time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`domain_id` VARCHAR(40) NOT NULL,
	`user_id` VARCHAR(40) NOT NULL,
	`domain` TEXT NOT NULL,
	`name` TEXT NOT NULL,
	`domain_verified` TEXT NOT NULL,
	`permissions` TEXT NOT NULL,
	`settings` JSON NOT NULL,
    PRIMARY KEY (`time`),
    UNIQUE KEY `domain_id` (`domain_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

-- -- Comments user has made
-- CREATE TABLE IF NOT EXISTS `user_$userID` (
--     `comment_id` VARCHAR(128) NOT NULL,
--     `domain_id` VARCHAR(40) NOT NULL,
--     `url_id` VARCHAR(64) NOT NULL,
--     PRIMARY KEY (`comment_id`),
--     UNIQUE KEY `comment_id` (`comment_id`),
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -- Comments in an URL
-- CREATE TABLE IF NOT EXISTS `comment_$urlID` (
--     `time` timestamp DEFAULT CURRENT_TIMESTAMP,
--     `comment_id` VARCHAR(128) NOT NULL,
-- 	`user_id` VARCHAR(40) NOT NULL,
--     `thread` VARCHAR(1024) NOT NULL,
--     `comment` TEXT NOT NULL,
--     PRIMARY KEY (`time`),
--     UNIQUE KEY `comment_id` (`comment_id`),
--     UNIQUE KEY `thread` (`thread`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -- List of URLs in a domain
-- CREATE TABLE IF NOT EXISTS `domain_$domainID` (
-- 	`url_id` VARCHAR(64) NOT NULL,
-- 	`url` VARCHAR(1024) NOT NULL,
-- 	`name` TEXT,
-- 	PRIMARY KEY (`url_id`),
-- 	UNIQUE KEY `url_id` (`url_id`),
-- 	UNIQUE KEY `url` (`url`)
-- ) ENGINE = InnoDB DEFAULT CHARSET=utf8;