
CREATE TABLE IF NOT EXISTS `history` (
  `history_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL DEFAULT '',
  `notification_type` enum('none','email','push') NOT NULL DEFAULT 'none',
  `notify_user` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `notify_target_user` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_user_viewable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  UNIQUE KEY `uk_identifier` (`identifier`),
  KEY `is_user_viewable` (`is_user_viewable`),
  KEY `notification_type` (`notification_type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-comment_add','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-comment_edit','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-comment_delete','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-review_add','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-review_edit','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-review_delete','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-like_add','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-like_edit','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-like_delete','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-share','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{target_identifier}-follower_add','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history`(`identifier`,`created_at`) values ('{target_identifier}-follower_delete','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';


CREATE TABLE IF NOT EXISTS `history_notification` (
  `history_notification_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `history_identifier` varchar(50) NOT NULL,
  `type` enum('push','email') NOT NULL DEFAULT 'push',
  `for` enum('to_user','to_target_user') NOT NULL DEFAULT 'to_user',
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` longtext,
  `key_code` varchar(10) NOT NULL,
  `hint` varchar(255) NOT NULL DEFAULT '',
  `wildcards` varchar(255) NOT NULL DEFAULT '',
  `replacers` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`history_notification_id`),
  UNIQUE KEY `key_code` (`key_code`),
  KEY `type` (`type`),
  KEY `for` (`for`),
  KEY `history_identifier` (`history_identifier`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `history_notification`(`history_identifier`,`type`,`for`,`title`,`body`,`wildcards`,`replacers`,`hint`,`key_code`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-comment_add','push','to_target_user','[APP_NAME]','[SENDER_NAME] commented on your {wildcard_identifier}.','[SENDER_NAME],[APP_NAME]','{$user->name},{$conf->site_name}','Notification when a {target_identifier} got a comment on {wildcard_identifier}.','111','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history_notification`(`history_identifier`,`type`,`for`,`title`,`body`,`wildcards`,`replacers`,`hint`,`key_code`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-review_add','push','to_target_user','[APP_NAME]','[SENDER_NAME] commented on your {wildcard_identifier}.','[SENDER_NAME],[APP_NAME]','{$user->name},{$conf->site_name}','Notification when a {target_identifier} got a review on {wildcard_identifier}.','111','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history_notification`(`history_identifier`,`type`,`for`,`title`,`body`,`wildcards`,`replacers`,`hint`,`key_code`,`created_at`) values ('{wildcard_identifier}_{target_identifier}-like_add','push','to_target_user','[APP_NAME]','[SENDER_NAME] likes your {wildcard_identifier}.','[SENDER_NAME],[APP_NAME]','{$user->name},{$conf->site_name}','Notification when a {target_identifier} got like on {wildcard_identifier}.','112','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';
INSERT INTO `history_notification`(`history_identifier`,`type`,`for`,`title`,`body`,`wildcards`,`replacers`,`hint`,`key_code`,`created_at`) values ('{target_identifier}-follower_add','push','to_target_user','[APP_NAME]','[SENDER_NAME] is now following you.','[SENDER_NAME],[APP_NAME]','{$user->name},{$conf->site_name}','Notification when a {target_identifier} followes other.','112','{wildcard_datetime}') ON DUPLICATE KEY UPDATE updated_at = '{wildcard_datetime}';


CREATE TABLE IF NOT EXISTS `entity_history` (
  `entity_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `history_id` int(10) unsigned NOT NULL,
  `plugin_identifier` varchar(50) NOT NULL DEFAULT '',
  `actor_entity` varchar(50) NOT NULL DEFAULT '{target_identifier}',
  `actor_id` bigint(20) unsigned NOT NULL,
  `reference_module` varchar(50) NOT NULL,
  `reference_id` bigint(20) unsigned NOT NULL,
  `against` varchar(50) DEFAULT NULL,
  `against_id` bigint(20) unsigned NOT NULL,
  `tracking_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `navigation_type` varchar(50) DEFAULT '',
  `navigation_item_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `is_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_push_notified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`entity_history_id`),
  KEY `actor_entity` (`actor_entity`),
  KEY `actor_id` (`actor_id`),
  KEY `history_id` (`history_id`),
  KEY `is_read` (`is_read`),
  KEY `created_at` (`created_at`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
