<?php
$plugin_identifier = "ef_social_new"; // identifier name (routes,module class_name)
$plugin_name = "Entity Social";
$plugin_description = "Lorem ipsum dolar sit amit demo text";
// version
$version = "1.0";
// array vars
$features = $webservices = array();
// string vars
$install_sql = $uninstall_sql = $upgrade_sql = $install_files = $uninstall_files = $update_note = "";

//features
$features = array();
// webservices ( uri => name )
$webservices = array(
	"social/like/post" => "Social : Like",
	"social/like/get_all" => "Social : Get All",
	"social/comment/add" => "Social : Add Comment",
	"social/comment/edit" => "Social : Edit Comment",
	"social/comment/delete" => "Social : Delete Comment",
	"social/comment/get_all" => "Social : Get All",
	"social/review/add" => "Social : Add Review",
	"social/review/edit" => "Social : Edit Review",
	"social/review/delete" => "Social : Delete Review",
	"social/review/get_all" => "Social : Get All",
	"social/follow" => "Social : Follow",
	"social/unfollow" => "Social : Unfollow"
);

// configurations
$config = array(
	"identifier" => $plugin_identifier,
	"name" => $plugin_name,
	"description" => $plugin_description,
	"version" => $version,
	"features" => $features,
	"webservices" => $webservices
);

//exit(json_encode($config));

?>
<?php // installation SQL ?>
<?php ob_start(); ?>

<?php // History table ?>
CREATE TABLE IF NOT EXISTS `{target_identifier}_history` (
  `{target_identifier}_history_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `history_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `{target_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `reference_module` varchar(50) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `against` varchar(50) DEFAULT NULL,
  `against_id` bigint(20) unsigned DEFAULT NULL,
  `tracking_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{target_identifier}_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

<?php // Social table : Comment  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_comment`;
CREATE TABLE `{wildcard_identifier}_{target_identifier}_comment` (
  `{wildcard_identifier}_{target_identifier}_comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{target_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `{wildcard_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment` text,
  `parent_id` bigint(20) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_{target_identifier}_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_comment_add');
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_comment_update');

<?php // Social table : Like  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_like`;
CREATE TABLE `{wildcard_identifier}_{target_identifier}_like` (
  `{wildcard_identifier}_{target_identifier}_like_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{target_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `{wildcard_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `type` enum('thumb','smile','happy','angry','love','sad','cool') NOT NULL DEFAULT 'thumb',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_{target_identifier}_like_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_like_add');
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_like_update');

<?php // Social table : Follow  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_follow`;
CREATE TABLE `{wildcard_identifier}_{target_identifier}_follow` (
  `{wildcard_identifier}_{target_identifier}_follow_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{target_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',  
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_{target_identifier}_follow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_follow_add');
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_follow_update');



<?php // Social table : Review  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_review`;
CREATE TABLE `{wildcard_identifier}_{target_identifier}_review` (
  `{wildcard_identifier}_{target_identifier}_review_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{target_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `{wildcard_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `review` text,
  `rating` int(2) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_{target_identifier}_review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_review_add');
INSERT IGNORE INTO `history` (`identifier`) VALUES ('{wildcard_identifier}_{target_identifier}_review_update');


<?php // Social table : Log  ?>
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}_{target_identifier}_log` (
  `{wildcard_identifier}_{target_identifier}_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `{target_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `{wildcard_pk}` bigint(20) unsigned NOT NULL DEFAULT '0',
  `activity_type` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_{target_identifier}_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


<?php // API method entries ?>
insert into `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`,`updated_at`,`deleted_at`) values ('Social : {wildcard_ucword} > {target_ucword} : Like','social/{wildcard_identifier}/{target_identifier}/like/post','API call for Liking anything','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2001,'{wildcard_datetime}',NULL,NULL),
('Social : {wildcard_ucword} > {target_ucword} : Add Comment','social/{wildcard_identifier}/{target_identifier}/comment/add','API call for commenting on user','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2002,'{wildcard_datetime}',NULL,NULL),
('Social : {wildcard_ucword} > {target_ucword} : Edit Comment','social/{wildcard_identifier}/{target_identifier}/comment/edit','API call for editing user comment','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2003,'{wildcard_datetime}',NULL,NULL),
('Social : {wildcard_ucword} > {target_ucword} : Add Review','social/{wildcard_identifier}/{target_identifier}/review/add','API call for adding {wildcard_identifier} review','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2004,'{wildcard_datetime}',NULL,NULL),
('Social : {wildcard_ucword} > {target_ucword} : Edit Review','social/{wildcard_identifier}/{target_identifier}/review/edit','API call for editing {wildcard_identifier} review','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2005,'{wildcard_datetime}',NULL,NULL),
('Social : {wildcard_ucword} > {target_ucword} : Follow','social/{wildcard_identifier}/{target_identifier}/follow','API call for {wildcard_identifier} follow','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2006,'{wildcard_datetime}',NULL,NULL),
('Social : {wildcard_ucword} > {target_ucword} : UnFollow','social/{wildcard_identifier}/{target_identifier}/unfollow','API call for {wildcard_identifier} unfollow','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,2007,'{wildcard_datetime}',NULL,NULL);

<?php // API method fields entries ?>
insert into `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`is_active`,`order`,`created_at`,`updated_at`,`deleted_at`) values
('social/{wildcard_identifier}/{target_identifier}/like/post','required','int','{target_pk}','{target_ucword} for liking',1,2001.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/like/post','required','int','{wildcard_pk}','{wildcard_ucword} ID for liking',1,2001.3,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/add','required','int','{target_pk}','{target_ucword} for commenting',1,2002.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/add','required','int','{wildcard_pk}','{wildcard_ucword} ID for commenting',1,2002.3,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/add','required','text','comment','Comment text',1,2002.4,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/add','optional','int','parent_id','add parent_id for the comment Add',1,2002.5,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','int','user_comment_id','ID of a comment to edit',1,2003.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','int','{target_pk}','{target_ucword} for Edit Comment',1,2003.2,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','int','{wildcard_pk}','{wildcard_ucword} ID for editing comment',1,2003.4,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','text','comment','Comment text',1,2003.5,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','optional','int','parent_id','add parent_id for the comment Edit',1,2003.6,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/add','required','int','{target_pk}','{target_ucword} for adding review',1,2004.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/add','required','int','{wildcard_pk}','{wildcard_ucword} ID for adding review',1,2004.3,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/add','optional','text','review','Review text',1,2004.4,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/add','optional','int','rating','add rating for current review',1,2004.5,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/edit','required','int','{wildcard_identifier}_{target_identifier}_review_id','ID for review to edit',1,2005.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/edit','required','int','{target_pk}','{target_ucword} for editing review',1,2005.2,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/edit','required','int','{wildcard_pk}','{wildcard_ucword} ID for editing review',1,2005.4,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/edit','optional','text','review','Review text',1,2005.5,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/review/edit','optional','int','rating','edit rating for current review',1,2005.6,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/follow','required','int','{target_pk}','{target_ucword} for following',1,2006.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/follow','required','int','{wildcard_pk}','{wildcard_ucword} ID for following',1,2006.2,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/unfollow','required','int','{target_pk}','Follow id for unfollowing',1,2007.1,'{wildcard_datetime}',NULL,NULL),
('social/{wildcard_identifier}/{target_identifier}/unfollow','required','int','{target_pk}','Follow id for unfollowing',1,2007.2,'{wildcard_datetime}',NULL,NULL);

<?php $install_sql = ob_get_clean(); ?>

<?php // un-installation Files ?>
<?php ob_start(); ?>
<?php // uninstall API method fields ?>
DELETE FROM `api_method_field` WHERE `method_uri` IN (SELECT `uri` FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}_{target_identifier}');
<?php // uninstall API methods ?>
DELETE FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}_{target_identifier}';
<?php // drop tables ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_like`;
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_comment`;
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_follow`;
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_review`;
DROP TABLE IF EXISTS `{wildcard_identifier}_{target_identifier}_log`;
<?php $uninstall_sql = ob_get_clean(); ?>

<?php // installation Files ?>
<?php ob_start(); ?>
/config/pl_{file_identifier}.php
/app/Http/Routes/{file_ucword}{file_target_ucword}SocialApi.php
/app/Http/Controllers/Api/{file_ucword}{file_target_ucword}SocialController.php
/app/Http/Models/{file_ucword}{file_target_ucword}Comment.php
/app/Http/Models/{file_ucword}{file_target_ucword}Follow.php
/app/Http/Models/{file_ucword}{file_target_ucword}Like.php
/app/Http/Models/{file_ucword}{file_target_ucword}Log.php
/app/Http/Models/{file_ucword}{file_target_ucword}Review.php
<?php $install_files = $uninstall_files = ob_get_clean(); ?>
<?php
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
{target_identifier}
{target_ucword}
{target_pk}
{target_entity_id}
{file_target_identifier}
{file_target_ucword}
*/

return array(
	"config" => $config,
	"update_note" => trim($update_note),
	"install_sql" => trim($install_sql),
	"uninstall_sql" => trim($uninstall_sql),
	"install_files" => trim($install_files),
	"uninstall_files" => trim($uninstall_files)
);