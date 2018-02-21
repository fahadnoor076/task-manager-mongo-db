
CREATE TABLE IF NOT EXISTS `pl_post_schedule_type` (
  `post_schedule_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_schedule_type_id`),
  UNIQUE KEY `identifier` (`identifier`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO `pl_post_schedule_type`(`identifier`,`title`,`created_at`) values ('datetime','Datetime','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_schedule_type`(`identifier`,`title`,`created_at`) values ('reccuring','Reccuring','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';



CREATE TABLE IF NOT EXISTS `pl_post_schedule_map` (
  `post_schedule_map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_schedule_type_id` tinyint(4) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `publish_at` timestamp NULL DEFAULT NULL COMMENT '// timestamp of scheduled post',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '// on/off switch (def:on)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_schedule_map_id`),
  KEY `post_id` (`post_id`),
  KEY `post_schedule_type_id` (`post_schedule_type_id`),
  KEY `publish_at` (`publish_at`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;