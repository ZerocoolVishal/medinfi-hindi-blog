CREATE TABLE IF NOT EXISTS `user_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lon` float(10,6) DEFAULT NULL,
  `gps_status` tinyint(1) DEFAULT NULL,
  `location_detected` tinyint(1) DEFAULT NULL,
  `identified_location` varchar(50) DEFAULT NULL,
  `identified_city` varchar(50) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `user_uniqueId` varchar(100) DEFAULT NULL,
  `user_type` varchar(100) DEFAULT NULL,
  `device_type` varchar(100) DEFAULT NULL,
  `os_details` varchar(100) DEFAULT NULL,
  `permission_allow_web` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `user_session` ADD INDEX(`user_uniqueId`);

CREATE TABLE IF NOT EXISTS `user_session_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_session_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `page2_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `start_time` timestamp NOT NULL,
  `end_time` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_type` (`event_type`)
);

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE `user_session_page` ADD INDEX(`event_type`);
ALTER TABLE `user_session_page` ADD INDEX(`page_id`);

CREATE TABLE user_session_archive LIKE user_session;

CREATE TABLE user_session_page_archive LIKE user_session_page;

CREATE TABLE IF NOT EXISTS `user_selected_location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned DEFAULT NULL,
  `usersession_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `selected_location` varchar(50) DEFAULT NULL,
  `selected_city` varchar(50) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);


