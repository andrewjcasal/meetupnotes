CREATE TABLE `meetupnotes`.`notes` (
  `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` VARCHAR(255) NULL,
  `user_id` INTEGER UNSIGNED NULL,
  `title` TEXT NULL,
  `description` TEXT NULL,
  `url` TEXT NOT NULL,
  `content` TEXT NULL,
  `created` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;