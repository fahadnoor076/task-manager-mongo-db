
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}_friend` (
  `{wildcard_identifier}_friend_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{wildcard_pk}` bigint(20) unsigned NOT NULL,
  `target_{wildcard_pk}` bigint(20) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `tracking_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_friend_id`),
  KEY `{wildcard_pk}` (`{wildcard_pk}`),
  KEY `target_{wildcard_pk}` (`target_{wildcard_pk}`),
  KEY `tracking_id` (`tracking_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;