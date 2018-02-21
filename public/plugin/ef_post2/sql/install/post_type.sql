
CREATE TABLE IF NOT EXISTS `pl_post_type` (
  `post_type_id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_type_id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `title` (`title`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


INSERT INTO `pl_post_type`(`identifier`,`title`,`created_at`) values ('idea','Idea','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_type`(`identifier`,`title`,`created_at`) values ('thought','Thought','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_type`(`identifier`,`title`,`created_at`) values ('poll','Poll','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_type`(`identifier`,`title`,`created_at`) values ('task','Task','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_type`(`identifier`,`title`,`created_at`) values ('form','Open Form','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_type`(`identifier`,`title`,`created_at`) values ('link','Link','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';