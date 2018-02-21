CREATE TABLE IF NOT EXISTS `pl_{wildcard_identifier}` (
  `{wildcard_pk}` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{wildcard_identifier}_type_id` tinyint(3) unsigned NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `search_term` varchar(150) DEFAULT NULL,
  `content` longtext,
  `data_packet` longtext,
  `location` varchar(100) DEFAULT NULL,
  `latitude` varchar(15) DEFAULT NULL,
  `longitude` varchar(15) DEFAULT NULL,
  `count_like` bigint(20) NOT NULL DEFAULT '0',
  `count_share` bigint(20) NOT NULL DEFAULT '0',
  `count_comment` bigint(20) NOT NULL DEFAULT '0',
  `count_view` bigint(20) NOT NULL DEFAULT '0',
  `status` enum('inactive','active','baned') NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_pk}`),
  KEY `title` (`title`),
  KEY `search_term` (`search_term`),
  KEY `{wildcard_identifier}_type_id` (`{wildcard_identifier}_type_id`),
  KEY `created_at` (`created_at`),
  KEY `location` (`location`),
  KEY `latitude` (`latitude`),
  KEY `longitude` (`longitude`),
  KEY `count_like` (`count_like`),
  KEY `count_comment` (`count_comment`),
  KEY `count_view` (`count_view`),
  KEY `count_share` (`count_share`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pl_{wildcard_identifier}_type` (
  `{wildcard_identifier}_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_type_id`),
  KEY `title` (`title`),
  UNIQUE KEY `unique` (`identifier`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `pl_{wildcard_identifier}_type`(`identifier`,`title`,`created_at`) values ('image','Image','{wildcard_datetime}') ON DUPLICATE KEY UPDATE `identifier` = 'image', title = 'Image';

INSERT INTO `pl_{wildcard_identifier}_type`(`identifier`,`title`,`created_at`) values ('audio','Audio','{wildcard_datetime}') ON DUPLICATE KEY UPDATE `identifier` = 'audio', title = 'Audio';

INSERT INTO `pl_{wildcard_identifier}_type`(`identifier`,`title`,`created_at`) values ('video','Video','{wildcard_datetime}') ON DUPLICATE KEY UPDATE `identifier` = 'video', title = 'Video';

INSERT INTO `pl_{wildcard_identifier}_type`(`identifier`,`title`,`created_at`) values ('text','Text','{wildcard_datetime}') ON DUPLICATE KEY UPDATE `identifier` = 'text', title = 'Text';

INSERT INTO `pl_{wildcard_identifier}_type`(`identifier`,`title`,`created_at`) values ('location','Location','{wildcard_datetime}') ON DUPLICATE KEY UPDATE `identifier` = 'location', title = 'Location';

INSERT INTO `pl_{wildcard_identifier}_type`(`identifier`,`title`,`created_at`) values ('link','Link','{wildcard_datetime}') ON DUPLICATE KEY UPDATE `identifier` = 'link', title = 'Link';