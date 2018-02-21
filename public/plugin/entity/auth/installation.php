<?php
// string vars
$install_sql = $uninstall_sql = $install_files = $uninstall_files = "";
?>

<?php // installation SQL ?>
<?php ob_start(); ?>
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}` (
  `{wildcard_pk}` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `user_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `image` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(500) NOT NULL DEFAULT '',
  `country_id` int(10) DEFAULT NULL,
  `state_id` int(10) DEFAULT NULL,
  `city_id` int(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `location` varchar(100) DEFAULT '',
  `latitude` varchar(20) DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `platform_type` enum('custom','facebook','twitter','gplus','device') NOT NULL DEFAULT 'custom',
  `platform_id` text,
  `device_udid` varchar(50) NOT NULL DEFAULT '',
  `device_type` enum('ios','android') NOT NULL DEFAULT 'android',
  `device_token` varchar(500) NOT NULL DEFAULT '',
  `is_verified` tinyint(1) DEFAULT '0',
  `verification_token` varchar(100) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `is_mobile_verified` tinyint(1) DEFAULT '0',
  `mobile_verification_code` varchar(100) DEFAULT NULL,
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `is_guest` tinyint(1) DEFAULT '0',
  `remember_login_token` varchar(100) DEFAULT NULL,
  `remember_login_token_created_at` timestamp NULL DEFAULT NULL,
  `forgot_password_hash` varchar(100) DEFAULT NULL,
  `forgot_password_hash_created_at` timestamp NULL DEFAULT NULL,
  `other_data` text,
  `additional_note` text,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_pk}`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

insert  into `{wildcard_identifier}`(`name`,`email`,`password`,`gender`,`image`,`status`,`platform_type`,`device_udid`,`device_type`,`device_token`,`is_verified`,`verified_at`,`is_guest`,`additional_note`,`last_login_at`,`created_at`) values 
('{wildcard_ucword}','{wildcard_identifier}@{wildcard_identifier}.com','Xi@#_xw-Ooaf04694f7996493f9f6a5ef42b8904ce','male','image_574ed3d7ea6ee.jpg?v=1466674753',1,'custom','','android','',1,'{wildcard_datetime}',0,'My {wildcard_ucword}','{wildcard_datetime}','{wildcard_datetime}');
<?php $install_sql = ob_get_clean(); ?>

<?php // un-installation SQL ?>
<?php ob_start(); ?>
DROP TABLE IF EXISTS `{wildcard_identifier}`;
<?php $uninstall_sql = ob_get_clean(); ?>

<?php // installation Files ?>
<?php ob_start(); ?>
/app/Http/Models/{file_ucword}.php
<?php $install_files = $uninstall_files = ob_get_clean(); ?>


<?php

// Wildcards dictionary starts
/*
{wildcard_identifier}
{wildcard_title}
{wildcard_plural_title}
{wildcard_ucword}
{wildcard_pk}
{wildcard_datetime}
{plugin_identifier}
{wildcard_ucword}
{wildcard_identifier}
{file_identifier}
{file_ucword}
{base_entity_id}
*/
// Wildcards dictionary ends

return array(
	"install_sql" => trim($install_sql),
	"uninstall_sql" => trim($uninstall_sql),
	"install_files" => trim($install_files),
	"uninstall_files" => trim($uninstall_files)
);