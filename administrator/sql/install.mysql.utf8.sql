CREATE TABLE IF NOT EXISTS `#__bbb_bastan_meetings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `meeting_id` VARCHAR(255) NOT NULL,
    `attendee_pw` VARCHAR(255) NOT NULL,
    `moderator_pw` VARCHAR(255) NOT NULL,
    `state` TINYINT(3) NOT NULL DEFAULT 1,
    `record_meeting` TINYINT(1) NOT NULL DEFAULT 1,
    `wait_moderator` TINYINT(1) NOT NULL DEFAULT 1,
    `mute_on_start` TINYINT(1) NOT NULL DEFAULT 0,
    `image` VARCHAR(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__bbb_bastan_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `meeting_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `join_time` datetime NOT NULL,
  `leave_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;