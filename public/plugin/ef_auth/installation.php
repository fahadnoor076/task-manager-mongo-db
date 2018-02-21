<?php
$plugin_identifier = "ef_auth"; // identifier name (routes,module class_name)
$plugin_name = "Entity Authentication";
$plugin_description = "Lorem ipsum dolar sit amit demo text";
// version
$version = "1.0";
// array vars
$features = $webservices = $directories = array();
// string vars
$install_sql = $uninstall_sql = $upgrade_sql = $install_files = $uninstall_files = $update_note = "";

//features
$features = array(
	"login" => "Login",
	"remember_login" => "Remember Login",
	"register" => "Register",
	"forgot_password" => "Forgot Password",
	"change_password" => "Change Password",
	"edit_profile" => "Edit Profile",
);
// webservices ( uri => name )
$webservices = array(
	"user/register" => "Register",
	"user/mobile_login" => "Mobile Login",
	"user/social_login" => "Social Login",
	"user/custom_login" => "Custom Login",
	"user/is_social_registered" => "Is Social Registered",
	"user/get" => "Get",
	"user/logout" => "Logout",
);

// directories to create (provide each node) - no trailing slash
$directories[] = "/public/files/{wildcard_identifier}_img";
$directories[] = "/resources/views/{wildcard_identifier}_panel";
$directories[] = "/resources/views/{wildcard_identifier}_panel/dashboard";
$directories[] = "/resources/views/{wildcard_identifier}_panel/setting";
$directories[] = "/app/Http/Controllers/{wildcard_ucword}_Panel";

// configurations
$config = array(
	"identifier" => $plugin_identifier,
	"name" => $plugin_name,
	"description" => $plugin_description,
	"version" => $version,
	"features" => $features,
	"webservices" => $webservices,
	"directories" => $directories
);

//exit(json_encode($config));

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


insert into `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`order`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values ('{wildcard_ucword} : Register','{wildcard_identifier}/register','API call for user registeration','{plugin_identifier}_{wildcard_identifier}',15,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} : Mobile Login','{wildcard_identifier}/mobile_login','API call for user mobile_login','{plugin_identifier}_{wildcard_identifier}',16,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} : Social Login','{wildcard_identifier}/social_login','API call for user social login','{plugin_identifier}_{wildcard_identifier}',17,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} : Custom Login','{wildcard_identifier}/custom_login','API call for user custom login','{plugin_identifier}_{wildcard_identifier}',18,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} : Is Social Registered','{wildcard_identifier}/is_social_registered','API call for is social registered','{plugin_identifier}_{wildcard_identifier}',19,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} : Get','{wildcard_identifier}/get','API call to get user record','{plugin_identifier}_{wildcard_identifier}',20,1,'{wildcard_datetime}',NULL,NULL);

insert into `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`is_active`,`order`,`created_at`,`updated_at`,`deleted_at`) values
('{wildcard_identifier}/register','required','text','name','Name',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','optional','text','first_name','First Name',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','optional','text','last_name','Last Name',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','required','text','email','Email',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','required','text','password','Password',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','optional','text','gender','Gender',1,6,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','optional','text','image','Image(Base64 encoded)',1,7,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','optional','text','device_udid','Device UDID',1,10,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','required','text','device_type','Device Type (android,ios)',1,11,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/register','optional','text','device_token','Device Token',1,12,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/mobile_login','required','int','device_udid','Device UDID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/mobile_login','required','text','device_type','Device Type (android, ios)',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/custom_login','required','text','email','Email',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/custom_login','required','text','password','Password',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/custom_login','required','text','platform_type','Platform Type (custom, facebook, gplus, twitter)',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/custom_login','required','text','device_type','Device Type (android, ios)',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','required','text','platform_type','Platform Type (custom, facebook, gplus, twitter)',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','required','text','platform_id','Platform ID',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','required','text','device_type','Device Type (android, ios)',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','optional','text','device_token','Device Token',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','required','text','name','Name',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','optional','text','first_name','First Name',1,6,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','optional','text','last_name','Last Name',1,7,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','optional','text','gender','Gender',1,8,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','optional','text','dob','DOB',1,9,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/social_login','optional','text','image_url','Image URL',1,10,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/is_social_registered','required','text','platform_type','Platform Type (custom, facebook, gplus, twitter)',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/is_social_registered','required','text','platform_id','Platform ID',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/is_social_registered','required','text','device_type','Device Type (android, ios)',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/is_social_registered','optional','text','device_token','Device Token',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}/get','required','int','user_id','User ID',1,4,'{wildcard_datetime}',NULL,NULL);

insert  into `email_template`(`key`,`subject`,`hint`,`body`,`wildcards`,`created_at`) values 
('{wildcard_identifier}_new_account','Welcome to [APP_NAME]','Template for new {wildcard_ucword} accounts that are created','<p>Hello [ENTITY_NAME],<br />\r\nWelcome to [APP_NAME]. Your new account has been created by admin with following details :<br />\r\nEmail : [USER_EMAIL]<br />\r\nPassword : [ENTITY_PASSWORD]<br />\r\n<br />\r\nYou can now manage your promotions by login here : <a href=\"[ADMIN_LINK]\">[ADMIN_LINK]</a><br />\r\n<br />\r\nThank you,<br />\r\nTeam [APP_NAME]</p>','[APP_NAME],[APP_LINK],[ENTITY_NAME],[ENTITY_EMAIL],[ENTITY_PASSWORD]','{wildcard_datetime}');

insert  into `email_template`(`key`,`subject`,`hint`,`body`,`wildcards`,`created_at`) values 
('{wildcard_identifier}_forgot_password_confirmation','Forgot Password Confirmation', 'Template for confirming for password request','<p>Hello <strong>[ENTITY_NAME],</strong><br />\r\nA request has been made to recover password for your account. Please follow below link to confirm your email and generate new password for your account :<br />\r\n<a href=\"[CONFIRMATION_LINK]\">[CONFIRMATION_LINK]</a><br />\r\n<br />\r\nIncase if you have not requested new password for your account, kindly ignore this email.<br />\r\nYou can always change your password by login here : <a href=\"[APP_LINK]\">[APP_LINK]</a><br />\r\n<br />\r\nThank you,<br />\r\n<strong>[APP_NAME]</strong></p>','[APP_NAME],[APP_LINK],[ENTITY_NAME],[CONFIRMATION_LINK]','{wildcard_datetime}');

insert  into `email_template`(`key`,`subject`,`hint`,`body`,`wildcards`,`created_at`) values 
('{wildcard_identifier}_recover_password_success','Your new Password','Template for sending new password to {wildcard_ucword}','Hello [ENTITY_NAME],\r\n<br />\r\nYour account password has been reset successffully. Your new login details are :<br />\r\nEmail : [ENTITY_EMAIL]<br />\r\nPassword : <strong>[ENTITY_PASSWORD]</strong><br />\r\n<br />\r\nYou can now manage your promotions by login here : <a href=\"[APP_LINK]\">[APP_LINK]</a><br />\r\n<br />\r\nThank you,<br />\r\n[APP_NAME]','[APP_NAME],[APP_LINK],[ENTITY_NAME],[ENTITY_EMAIL],[ENTITY_PASSWORD]','{wildcard_datetime}');


<?php $install_sql = ob_get_clean(); ?>

<?php // un-installation SQL ?>
<?php ob_start(); ?>
DROP TABLE IF EXISTS `{wildcard_identifier}`;

DELETE FROM `email_template` WHERE `key` = '{wildcard_identifier}_new_account';
DELETE FROM `email_template` WHERE `key` = '{wildcard_identifier}_forgot_password_confirmation';
DELETE FROM `email_template` WHERE `key` = '{wildcard_identifier}_recover_password_success';

<?php // uninstall API method fields ?>
DELETE FROM `api_method_field` WHERE `method_uri` IN (SELECT `uri` FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}');
<?php // uninstall API methods ?>
DELETE FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}';
<?php $uninstall_sql = ob_get_clean(); ?>

<?php // installation Files ?>
<?php ob_start(); ?>
/config/pl_{file_identifier}.php
/app/Http/Routes/{file_ucword}.php
/app/Http/Controllers/{file_ucword}_Panel/{file_ucword}Controller.php
/app/Http/Controllers/{file_ucword}_Panel/DashboardController.php
/app/Http/Controllers/{file_ucword}_Panel/IndexController.php
/app/Http/Controllers/{file_ucword}_Panel/SettingController.php
/app/Http/Controllers/Api/{file_ucword}Controller.php
<?php /* /app/Http/Models/{file_ucword}.php */?>
/resources/views/{file_identifier}_panel/sidebar.blade.php
/resources/views/{file_identifier}_panel/change_password.blade.php
/resources/views/{file_identifier}_panel/confirm_forgot.blade.php
/resources/views/{file_identifier}_panel/elfinder.blade.php
/resources/views/{file_identifier}_panel/flash_message.blade.php
/resources/views/{file_identifier}_panel/footer.blade.php
/resources/views/{file_identifier}_panel/forgot_password.blade.php
/resources/views/{file_identifier}_panel/header.blade.php
/resources/views/{file_identifier}_panel/login.blade.php
/resources/views/{file_identifier}_panel/nav_header.blade.php
/resources/views/{file_identifier}_panel/side_overlay.blade.php
/resources/views/{file_identifier}_panel/dashboard/index.blade.php
/resources/views/{file_identifier}_panel/setting/index.blade.php
/resources/views/{file_identifier}_panel/setting/logo_browser.blade.php
/resources/views/{file_identifier}_panel/setting/update.blade.php
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
*/
// Wildcards dictionary ends

return array(
	"config" => $config,
	"update_note" => trim($update_note),
	"install_sql" => trim($install_sql),
	"uninstall_sql" => trim($uninstall_sql),
	"install_files" => trim($install_files),
	"uninstall_files" => trim($uninstall_files)
);