CREATE TABLE IF NOT EXISTS `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `pass` longtext NOT NULL,
    `name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `domains` (
    `id` int NOT NULL AUTO_INCREMENT,
    `domain` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `deleted@domain.tld` (
    `domain` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `status` int(255) NOT NULL,
    PRIMARY KEY (`domain`),
    UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `deleted@domain.tld`(`domain`, `name`, `status`) VALUES ('deleted@domain.tld', 'Deleted Comment', 2);
INSERT INTO `users`(`email`, `pass`, `name`) VALUES ('deleted@domain.tld', 'Deleted', 'Deleted Comment');

-- CREATE TABLE IF NOT EXISTS `user_$id` (
--     `comment_id` int NOT NULL AUTO_INCREMENT,
--     `domain_id` varchar(255) NOT NULL,
--     `url_id` varchar(255) NOT NULL,
--     `thread` varchar(255) NOT NULL,
--     PRIMARY KEY (`id`),
--     UNIQUE KEY `id` (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- CREATE TABLE IF NOT EXISTS `domain_id` (
--     `url_id` int NOT NULL AUTO_INCREMENT,
--     `date` timestamp DEFAULT CURRENT_TIMESTAMP,
--     `thread` varchar(255) NOT NULL,
--     `email` varchar(255) NOT NULL,
--     `name` varchar(255) NOT NULL,
--     `url` longtext NOT NULL,
--     `comment` longtext NOT NULL,
--     PRIMARY KEY (`id`),
--     UNIQUE KEY `id` (`id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8;