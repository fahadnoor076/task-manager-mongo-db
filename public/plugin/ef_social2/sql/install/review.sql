
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}_review` (
  `{wildcard_identifier}_review_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `{wildcard_pk}` bigint(20) unsigned NOT NULL,
  `{target_pk}` bigint(20) unsigned NOT NULL,
  `review` TEXT NULL DEFAULT NULL,
  `reference_data` TEXT NULL DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_review_id`),
  KEY `{wildcard_pk}` (`{wildcard_pk}`),
  KEY `{target_pk}` (`{target_pk}`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;