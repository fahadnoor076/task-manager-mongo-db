
CREATE TABLE IF NOT EXISTS `pl_tag` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `title` (`title`),
  KEY `created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('high','High','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('medium','Medium','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('low','Low','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('very_low','Very Low','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('crash','Crash','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('functionality_missing','Functionality Missing','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('malfunction','Malfunction','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('cosmetic','Cosmetic','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('sow','SOW / Requirement','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('designs','Designs','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('beta-1','Beta-1','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('beta-2','Beta-2','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('uat','UAT','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('soft_launch','Soft-Launch (Public Beta)','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag`(`identifier`,`title`,`created_at`) values ('full_launch','Full-Launch (Mass Release)','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';



CREATE TABLE IF NOT EXISTS `pl_tag_type` (
  `tag_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tag_type_id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `title` (`title`),
  KEY `created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `pl_tag_type`(`identifier`,`title`,`created_at`) values
('Priority','priority','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type`(`identifier`,`title`,`created_at`) values
('bug','Bug','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type`(`identifier`,`title`,`created_at`) values
('phase','Phase','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';


CREATE TABLE IF NOT EXISTS `pl_tag_type_map` (
  `post_tag_type_map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_type_id` tinyint(3) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_tag_type_map_id`),
  UNIQUE KEY `unqiue` (`tag_id`,`tag_type_id`),
  KEY `created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(1,1,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(1,2,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(1,3,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(1,4,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(2,5,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(2,6,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(2,7,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(2,8,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,9,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,10,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,11,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,12,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,13,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,14,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_tag_type_map`(`tag_type_id`,`tag_id`,`created_at`) values
(3,15,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';