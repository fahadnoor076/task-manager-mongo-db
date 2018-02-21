<?php
// include common
require __DIR__."/../common.php";

$plugin_identifier = "ef_friend"; // identifier name (routes,module class_name)
$plugin_name = "Entity Friendship";
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
	$plugin_identifier."/friend/add" => "Friend : Add",
	$plugin_identifier."/friend/accept" => "Friend : Accept",
	$plugin_identifier."/friend/delete" => "Friend : Delete",
	$plugin_identifier."/friend/cancel" => "Friend : Cancel",
	$plugin_identifier."/friend/reject" => "Friend : Reject",
	$plugin_identifier."/friend/get_all" => "Friend : Get All",
	$plugin_identifier."/friend/get_all_pending" => "Friend : Get All Pending",
	$plugin_identifier."/friend/get_all_sent" => "Friend : Get All Sent",
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
<?php $install_sql .= getFileContents(__DIR__."/sql/install",".sql"); ?>
<?php $install_sql .= getFileContents(__DIR__."/sql/install/api",".sql"); ?>

<?php // un-installation SQL : Base ?>
<?php $uninstall_sql .= getFileContents(__DIR__."/sql/uninstall",".sql"); ?>

<?php // installation Files ?>
<?php ob_start(); ?>
/app/Http/Routes/{file_ucword}FriendApi.php
/app/Http/Controllers/Api/{file_ucword}FriendController.php
/app/Http/Models/{file_ucword}Friend.php
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
