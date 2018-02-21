<?php
// include common
require __DIR__."/../common.php";

$plugin_identifier = "ef_social2"; // identifier name (routes,module class_name)
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
	$plugin_identifier."/comment/add" => "Social : Comment : Add",
	$plugin_identifier."/comment/edit" => "Social : Comment : Edit",
	$plugin_identifier."/comment/delete" => "Social : Comment : Delete",
	$plugin_identifier."/comment/get" => "Social : Comment : Get",
	$plugin_identifier."/comment/get_all" => "Social : Comment : Get All",
	$plugin_identifier."/like/add" => "Social : Like",
	$plugin_identifier."/like/edit" => "Social : Like : Edit",
	$plugin_identifier."/like/delete" => "Social : UnLike",
	$plugin_identifier."/like/get_all" => "Social : Like : Get All",
	$plugin_identifier."/review/add" => "Social : Review : Add",
	$plugin_identifier."/review/edit" => "Social : Review : Edit",
	$plugin_identifier."/review/delete" => "Social : Review : Delete",
	$plugin_identifier."/review/get" => "Social : Review : Get",
	$plugin_identifier."/review/get_all" => "Social : Review : Get All",
	$plugin_identifier."/follower/add" => "Social : Follow",
	$plugin_identifier."/follower/delete" => "Social : UnFollow",
	$plugin_identifier."/follower/get" => "Social : Follower : Get",
	$plugin_identifier."/follower/get_all_following" => "Social : Follower : Get All Following",
	$plugin_identifier."/follower/get_all_followers" => "Social : Follower : Get All Followers"
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

<?php // Install SQL : Base ?>
<?php //$install_sql .= trim(file_get_contents("sql/install.sql",true)); ?>
<?php $install_sql .= getFileContents(__DIR__."/sql/install",".sql"); ?>
<?php $install_sql .= getFileContents(__DIR__."/sql/install/api",".sql"); ?>

<?php // un-installation SQL : Base ?>
<?php //$uninstall_sql .= trim(file_get_contents("sql/uninstall.sql",true)); ?>
<?php $uninstall_sql .= getFileContents(__DIR__."/sql/uninstall",".sql"); ?>

<?php // installation Files ?>
<?php ob_start(); ?>
/app/Http/Routes/{file_ucword}{file_target_ucword}SocialApi.php
/app/Http/Controllers/Api/{file_ucword}{file_target_ucword}SocialController.php
/app/Http/Models/{file_ucword}Comment.php
/app/Http/Models/{file_ucword}Like.php
/app/Http/Models/{file_ucword}Review.php
/app/Http/Models/{file_target_ucword}Follower.php
/app/Http/Models/SOCActivityType.php
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
{target_identifier}
{target_ucword}
{target_pk}
{target_entity_id}
{file_target_identifier}
{file_target_ucword}
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
?>
