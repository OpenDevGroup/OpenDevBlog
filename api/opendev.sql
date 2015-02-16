CREATE TABLE `opendev`.`categories`(
	`id` VARCHAR(15) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `opendev`.`authors`(
	`id` VARCHAR(15) NOT NULL,
	`display_name` VARCHAR(50) NOT NULL,
	`first_name` VARCHAR(50) NOT NULL,
	`last_name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `opendev`.`authors_extended`(
	`id` VARCHAR(15) NOT NULL,
	`age` INT(10),
	`job_position` VARCHAR(50),
	`bio` MEDIUMTEXT,
	`social_links` MEDIUMTEXT,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `opendev`.`post_meta`(
	`id` VARCHAR(15) NOT NULL,
	`title` VARCHAR(50) NOT NULL,
	`categories` INT(30) NOT NULL,
	`published` INT(10) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `opendev`.`post_content`(
	`id` VARCHAR(15) NOT NULL,
	`content` MEDIUMTEXT NOT NULL,
	`media_id` VARCHAR(15),
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `opendev`.`media_library`(
	`id` VARCHAR(15) NOT NULL,
	`content` VARCHAR(50) NOT NULL,
	`title` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `id` (`id`))
ENGINE=InnoDB DEFAULT CHARSET=utf8;