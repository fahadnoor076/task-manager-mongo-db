
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}_like` (
  `{wildcard_identifier}_like_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{wildcard_pk}` bigint(20) unsigned NOT NULL,
  `activity_type_id` int(10) unsigned NOT NULL,
  `actor_type` varchar(50) NOT NULL,
  `actor_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_like_id`),
  KEY `{wildcard_pk}` (`{wildcard_pk}`),
  KEY `activity_type_id` (`activity_type_id`),
  KEY `actor_type` (`actor_type`),
  KEY `actor_id` (`actor_id`),
  KEY `created_at` (`created_at`),
  KEY `deleted_at` (`deleted_at`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;