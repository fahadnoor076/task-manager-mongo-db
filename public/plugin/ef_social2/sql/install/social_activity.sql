
CREATE TABLE IF NOT EXISTS `soc_activity_type` (
  `activity_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` varchar(100) NOT NULL COMMENT 'like, comment, review',
  `title` varchar(100) NOT NULL,
  `identifier` varchar(100) NOT NULL,
  `value` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`activity_type_id`),
  UNIQUE KEY `unique` (`activity`,`identifier`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Thumb','thumb',1,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Smile','smile',2,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Happy','happy',3,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Angry','angry',4,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Love','love',5,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Sad','sad',6,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('like','Cool','cool',7,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('comment','Text','text',1,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `soc_activity_type`(`activity`,`title`,`identifier`,`value`,`created_at`) values ('comment','Sticker','sticker',1,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';