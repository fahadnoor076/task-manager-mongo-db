
CREATE TABLE IF NOT EXISTS `{target_identifier}_follower` (
  `{target_identifier}_follower_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{target_pk}` bigint(20) unsigned NOT NULL,
  `target_{target_pk}` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{target_identifier}_follower_id`),
  KEY `{target_pk}` (`{target_pk}`),
  KEY `target_{target_pk}` (`target_{target_pk}`),
  KEY `created_at` (`created_at`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;