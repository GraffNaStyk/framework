CREATE TABLE IF NOT EXISTS `users` ( `id` int (11)  NOT NULL AUTO_INCREMENT, `name` varchar (50)  NOT NULL, `password` varchar (100)  NOT NULL, `phone` int (9)  NOT NULL, `city` varchar (100)  NOT NULL, `street` varchar (100)  NOT NULL, `mail` varchar (100)  NOT NULL, `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP, `updated_at` timestamp    NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,  PRIMARY KEY (`id`),  UNIQUE (`name`) );

CREATE TABLE IF NOT EXISTS `rights` ( `id` int (11)  NOT NULL AUTO_INCREMENT, `user_id` int (11)  NOT NULL, `clients` tinyint (1)  NOT NULL DEFAULT '0',  PRIMARY KEY (`id`),  UNIQUE (`user_id`) );

CREATE TABLE IF NOT EXISTS `files` ( `id` int (11) UNSIGNED   NOT NULL AUTO_INCREMENT, `name` varchar (100)  NOT NULL, `hash` varchar (100)  NOT NULL, `dir` varchar (100)  NOT NULL, `ext` varchar (6)  NOT NULL, `sha1` varchar (100)  NOT NULL, `visible` enum ('y', 'n')  NOT NULL DEFAULT 'y',  PRIMARY KEY (`id`),  INDEX (`name`),  INDEX (`hash`) );

CREATE TABLE IF NOT EXISTS `example` ( `hash` varchar (50)  NOT NULL, `receiver_id` int (11)  NOT NULL );
