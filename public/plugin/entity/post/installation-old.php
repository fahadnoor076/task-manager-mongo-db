<?php
// string vars
$install_sql = $uninstall_sql = $install_files = $uninstall_files = "";
?>

<?php // installation SQL ?>
<?php ob_start(); ?>
CREATE TABLE IF NOT EXISTS `{wildcard_identifier}` (
  `{wildcard_pk}` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `content_type` enum('text','image','audio','video','file') NOT NULL DEFAULT 'text',
  `title` varchar(150) DEFAULT NULL,
  `content` longtext,
  `data_packet` longtext DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `latitude` varchar(15) DEFAULT NULL,
  `longitude` varchar(15) DEFAULT NULL,
  `count_like` bigint(20) NOT NULL DEFAULT '0',
  `count_share` bigint(20) NOT NULL DEFAULT '0',
  `count_comment` bigint(20) NOT NULL DEFAULT '0',
  `count_view` bigint(20) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`{wildcard_pk}`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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