
CREATE TABLE IF NOT EXISTS `pl_post_feedback_type` (
  `post_feedback_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `identifier` varchar(50) NOT NULL,
  `icon_src` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`post_feedback_type_id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `title` (`title`),
  KEY `created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


INSERT INTO `pl_post_feedback_type`(`identifier`,`title`,`icon_src`,`created_at`) values ('like','Like / Dislike',NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_feedback_type`(`identifier`,`title`,`icon_src`,`created_at`) values ('vote','Upvote / Downvote',NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_feedback_type`(`identifier`,`title`,`icon_src`,`created_at`) values
('rating_3','Likert Rating (3)',NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_feedback_type`(`identifier`,`title`,`icon_src`,`created_at`) values
('rating_5','Likert Rating (5)',NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_feedback_type`(`identifier`,`title`,`icon_src`,`created_at`) values
('rating_7','Likert Rating (7)',NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `pl_post_feedback_type`(`identifier`,`title`,`icon_src`,`created_at`) values ('csv','User Input Basec (CSV)',NULL,'{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';