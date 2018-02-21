
CREATE TABLE IF NOT EXISTS `pl_attachment` (
  `attachment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attachment_type_id` int(10) unsigned NOT NULL,
  `title` varchar(100) DEFAULT '',
  `content` text,
  `thumb` varchar(100) DEFAULT NULL,
  `data_packet` text,
  `file` varchar(100) DEFAULT NULL,
  `actor_entity` varchar(50) NOT NULL DEFAULT '{target_identifier}',
  `actor_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`attachment_id`),
  KEY `attachment_type_id` (`attachment_type_id`),
  KEY `title` (`title`),
  KEY `actor_entity` (`actor_entity`),
  KEY `actor_id` (`actor_id`),
  KEY `created_at` (`created_at`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `pl_attachment_type` (
  `attachment_type_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `generate_thumb` tinyint(1) DEFAULT '0',
  `allowed_extensions` VARCHAR DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`attachment_type_id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `title` (`title`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('location','Location',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('event','Event',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values
('form_poll','Form > Poll',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values
('form_task','Form > Task',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`created_at`) values
('form_survey','Form > Question / Survery',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('audio','Audio',0,'.mp3','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('video','Video',0,'.mp4','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('image','Image',0,'.jpg,.jpeg,.gif,.png','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('file','File',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('document','Document',0,'.doc,docx','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_attachment_type`(`identifier`,`title`,`generate_thumb`,`allowed_extensions`,`created_at`) values ('link','Link',0,NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';



CREATE TABLE `pl_post_attachment_map` (
  `post_attachment_map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) NOT NULL,
  `attachment_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_attachment_map_id`),
  UNIQUE KEY `unqiue` (`post_id`,`attachment_id`),
  KEY `created_at` (`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;