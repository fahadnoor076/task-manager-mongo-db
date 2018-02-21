<?php
$plugin_identifier = "ef_social"; // identifier name (routes,module class_name)
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
	"social/like/get_all" => "Social : Get All Likes",
	"social/comment/add" => "Social : Add Comment",
	"social/comment/edit" => "Social : Edit Comment",
	"social/comment/delete" => "Social : Delete Comment",
	"social/comment/get_all" => "Social : Get All Comments",
	"social/comment/get" => "Social : Get Comment",
	"social/review/add" => "Social : Add Review",
	"social/review/edit" => "Social : Edit Review",
	"social/review/delete" => "Social : Delete Review",
	"social/review/get_all" => "Social : Get All Reviews",
	"social/review/get" => "Social : Get Review",
	"social/follow" => "Social : Follow",
	"social/unfollow" => "Social : Unfollow",	
	"social/friend/add" => "Social : Add Friend",
	"social/friend/delete" => "Social : Delete Friend",
	"social/friend/accept_ignore_request" => "Social : Accept/Ignore Friend",
	"social/friend/get_all" => "Social : Get All Friends",
/*	"social/post/add" => "Social : Add Post",	
	"social/post/edit" => "Social : Edit Post",	
	"social/post/delete" => "Social : Delete Post",
	"social/post/get" => "Social : Get Post",	
	"social/post/get_all" => "Social : Get All Post",*/	
	"social/share" => "Social : Share",	
	"social/tag/add" => "Social : Add Tag",	
	"social/tag/delete" => "Social : Delete Tag",	
);

// configurations
$config = array(
	"identifier" => $plugin_identifier,
	"name" => $plugin_name,
	"description" => $plugin_description,
	"version" => $version,
	"features" => $features,
	"webservices" => $webservices,
	"has_target_entity" => 1,
	"target_entity" => array(
		"identifier" => "user",
		"ucword" => "User",
		"table" => "user",
		"pk" => "user_id"
	)
);

//exit(json_encode($config));

?>
<?php // installation SQL ?>
<?php ob_start(); ?>

<?php // Social table : Comment  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_comment`;
CREATE TABLE `{wildcard_identifier}_comment` (
  `{wildcard_identifier}_comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `actor_type` varchar(50) DEFAULT NULL,
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `target_type` varchar(50) DEFAULT NULL,
  `comment` text,
  `parent_id` bigint(20) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

<?php // Social table : Like  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_like`;
CREATE TABLE `{wildcard_identifier}_like` (
  `{wildcard_identifier}_like_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `actor_type` varchar(50) DEFAULT NULL,
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_like_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


<?php // Social table : Follow  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_follow`;
CREATE TABLE `{wildcard_identifier}_follow` (
  `{wildcard_identifier}_follow_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',  
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_follow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


<?php // Social table : Review  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_review`;
CREATE TABLE `{wildcard_identifier}_review` (
  `{wildcard_identifier}_review_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `actor_type` varchar(50) DEFAULT NULL,
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `review` text,
  `rating` int(2) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

<?php // Social table : Log  ?>
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}_log` (
  `{wildcard_identifier}_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `actor_type` varchar(50) DEFAULT NULL,
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `target_type` varchar(50) DEFAULT NULL,
  `activity_type` varchar(200) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

<?php // Social table : Friend  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_friend`;
CREATE TABLE `{wildcard_identifier}_friend` (
  `{wildcard_identifier}_friend_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',  
  `actor_type` varchar(50) DEFAULT NULL,
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_friend_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

<?php // Social table : Share  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_share`;
CREATE TABLE `{wildcard_identifier}_share` (
  `{wildcard_identifier}_share_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',  
  `actor_type` varchar(50) DEFAULT NULL,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `referral_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_share_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;


<?php // Social table : Tag  ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_tag`;
CREATE TABLE `{wildcard_identifier}_tag` (
  `{wildcard_identifier}_tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `actor_id` bigint(20) unsigned NOT NULL DEFAULT '0',  
  `actor_type` varchar(50) DEFAULT NULL,
  `target_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `target_type` varchar(50) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_identifier}_tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

<?php // API method entries ?>
insert into `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`order`,`is_active`,`created_at`,`updated_at`,`deleted_at`) values ('{wildcard_ucword} Social : Like','{wildcard_identifier}_social/like/post','API call for Liking anything','{plugin_identifier}',15,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Get All Like','{wildcard_identifier}_social/like/get_all','API call for get all likes','{plugin_identifier}',16,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Get Comment','{wildcard_identifier}_social/comment/get','API call to get comment','{plugin_identifier}',17,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Get All Comments','{wildcard_identifier}_social/comment/get_all','API call to get all user comments','{plugin_identifier}',18,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Add Comment','{wildcard_identifier}_social/comment/add','API call for commenting on user','{plugin_identifier}',19,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Edit Comment','{wildcard_identifier}_social/comment/edit','API call for editing user comment','{plugin_identifier}',20,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Delete Comment','{wildcard_identifier}_social/comment/delete','API call for delete user comment','{plugin_identifier}',21,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Get Review','{wildcard_identifier}_social/review/get','API call to get {wildcard_identifier} review','{plugin_identifier}',22,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Get All Reviews','{wildcard_identifier}_social/review/get_all','API call to get all {wildcard_identifier} review','{plugin_identifier}',23,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Add Review','{wildcard_identifier}_social/review/add','API call for adding {wildcard_identifier} review','{plugin_identifier}',24,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Edit Review','{wildcard_identifier}_social/review/edit','API call for editing {wildcard_identifier} review','{plugin_identifier}',25,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Delete Review','{wildcard_identifier}_social/review/delete','API call for delete a review','{plugin_identifier}',26,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Follow','{wildcard_identifier}_social/follow','API call for {wildcard_identifier} follow','{plugin_identifier}',27,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : UnFollow','{wildcard_identifier}_social/unfollow','API call for {wildcard_identifier} unfollow','{plugin_identifier}',28,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Request/Add Friend','{wildcard_identifier}_social/friend/add','API call for {wildcard_identifier} add friend','{plugin_identifier}',29,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Delete Friend','{wildcard_identifier}_social/friend/delete','API call to {wildcard_identifier} delete a friend','{plugin_identifier}',30,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Accept/Ignore Friend Request','{wildcard_identifier}_social/friend/accept_ignore_request','API call for {wildcard_identifier} accept or ignore friend request','{plugin_identifier}',31,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Get All Friends','{wildcard_identifier}_social/friend/get_all','API call for {wildcard_identifier} get all friends','{plugin_identifier}',32,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Share','{wildcard_identifier}_social/share/post','API call for {wildcard_identifier} share','{plugin_identifier}',33,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Delete Share','{wildcard_identifier}_social/share/delete','API call for {wildcard_identifier} Delete share','{plugin_identifier}',34,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Add Tag','{wildcard_identifier}_social/tag/add','API call for {wildcard_identifier} add Tag','{plugin_identifier}',35,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_ucword} Social : Delete Tag','{wildcard_identifier}_social/tag/delete','API call for {wildcard_identifier} Delete tag','{plugin_identifier}',36,1,'{wildcard_datetime}',NULL,NULL);


<?php // API method fields entries ?>
insert into `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`is_active`,`order`,`created_at`,`updated_at`,`deleted_at`) values
('{wildcard_identifier}_social/like/post','required','int','actor_id','Actor id for liking',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/like/post','required','text','actor_type','Actor type for liking',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/like/post','required','int','target_id','Target id for liking',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/like/get_all','required','int','actor_id','Actor id for get list of liking',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/get','required','text','{wildcard_identifier}_comment_id','{wildcard_identifier} Comment ID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/get_all','required','text','actor_id','Actor ID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/add','required','int','actor_id','Actor id for commenting',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/add','required','text','actor_type','Actor type for commenting',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/add','required','int','target_id','Target id for commenting',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/add','required','text','comment','Comment text',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/add','optional','int','parent_id','add parent_id for the comment Add',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/edit','required','int','user_comment_id','ID of a comment to edit',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/edit','required','int','actor_id','Actor id for Edit Comment',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/edit','required','text','actor_type','Actor type for editing comment',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/edit','required','int','target_id','Target id for editing comment',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/edit','required','text','comment','Comment text',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/edit','optional','int','parent_id','add parent_id for the comment Edit',1,6,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/comment/delete','optional','int','{wildcard_identifier}_comment_id','Comment id to delete a comment',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/get','required','int','{wildcard_identifier}_review_id','{wildcard_identifier} Review ID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/get_all','required','int','actor_id','Actor id',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/add','required','int','actor_id','Actor id for adding review',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/add','required','text','actor_type','Actor type for adding review',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/add','required','int','target_id','Target id for adding review',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/add','optional','text','review','Review text',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/add','optional','int','rating','add rating for current review',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/edit','required','int','{wildcard_identifier}_review_id','ID for review to edit',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/edit','required','int','actor_id','Actor id for editing review',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/edit','required','text','actor_type','Actor type for editing review',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/edit','required','int','target_id','Target id for editing review',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/edit','optional','text','review','Review text',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/edit','optional','int','rating','edit rating for current review',1,6,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/review/delete','optional','int','{wildcard_identifier}_review_id','Review id to delete a review',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/follow','required','int','actor_id','Actor id for following',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/follow','required','text','actor_type','Actor type for following',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/follow','required','int','target_id','Target id for following',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/unfollow','required','int','{wildcard_identifier}_follow_id','Follow id for unfollowing',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/add','required','int','actor_id','Actor ID for connection',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/add','required','text','actor_type','Actor Type for connection',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/add','required','int','target_id','Target ID for connection',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/delete','required','int','{wildcard_identifier}_friend_id','Friend id for delete a connection',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/accept_ignore_request','required','int','{wildcard_identifier}_friend_id','Friend id for accept/ignore request',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/accept_ignore_request','required','int','status','Status for accept/ignore request',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/get_all','required','int','actor_id','Actor id to get all active friends list',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/friend/pending_list','required','int','actor_id','Actor id to get all pending list',1,1,'{wildcard_datetime}',NULL,NULL),

('{wildcard_identifier}_social/share/post','required','int','actor_id','Actor ID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/share/post','required','text','actor_type','Actor Type',1,2,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/share/post','required','int','post_id','Post ID',1,3,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/share/post','optional','text','title','Content',1,4,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/share/post','optional','int','referral_id','Referral ID',1,5,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/share/delete','required','int','{wildcard_identifier}_share_id','{wildcard_identifier} Share ID',1,1,'{wildcard_datetime}',NULL,NULL),

('{wildcard_identifier}_social/tag/add','required','int','actor_id','Actor ID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/tag/add','required','text','actor_type','Actor Type',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/tag/add','required','int','target_id','Target ID',1,1,'{wildcard_datetime}',NULL,NULL),
('{wildcard_identifier}_social/tag/add','required','text','target_type','Target Type',1,1,'{wildcard_datetime}',NULL,NULL),

('{wildcard_identifier}_social/tag/delete','required','int','{wildcard_identifier}_tag_id','{wildcard_identifier} Tag ID',1,1,'{wildcard_datetime}',NULL,NULL);


<?php $install_sql = ob_get_clean(); ?>

<?php // un-installation Files ?>
<?php ob_start(); ?>
<?php // uninstall API method fields ?>
DELETE FROM `api_method_field` WHERE `method_uri` IN (SELECT `uri` FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}');
<?php // uninstall API methods ?>
DELETE FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}';
<?php // drop tables ?>
DROP TABLE IF EXISTS `{wildcard_identifier}_like`;
DROP TABLE IF EXISTS `{wildcard_identifier}_comment`;
DROP TABLE IF EXISTS `{wildcard_identifier}_follow`;
DROP TABLE IF EXISTS `{wildcard_identifier}_review`;
DROP TABLE IF EXISTS `{wildcard_identifier}_friend`;
DROP TABLE IF EXISTS `{wildcard_identifier}_log`;
DROP TABLE IF EXISTS `{wildcard_identifier}_share`;
DROP TABLE IF EXISTS `{wildcard_identifier}_tag`;
<?php $uninstall_sql = ob_get_clean(); ?>

<?php // installation Files ?>
<?php ob_start(); ?>
/app/Http/Routes/{file_ucword}SocialApi.php
/app/Http/Controllers/Api/{file_ucword}SocialController.php
/app/Http/Models/{file_ucword}Comment.php
/app/Http/Models/{file_ucword}Follow.php
/app/Http/Models/{file_ucword}Like.php
/app/Http/Models/{file_ucword}Friend.php
/app/Http/Models/{file_ucword}Log.php
/app/Http/Models/{file_ucword}Review.php
/app/Http/Models/{file_ucword}Share.php
/app/Http/Models/{file_ucword}Tag.php
<?php $install_files = $uninstall_files = ob_get_clean(); ?>
<?php
return array(
	"config" => $config,
	"update_note" => trim($update_note),
	"install_sql" => trim($install_sql),
	"uninstall_sql" => trim($uninstall_sql),
	"install_files" => trim($install_files),
	"uninstall_files" => trim($uninstall_files)
);