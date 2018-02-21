<?php
// include common
require __DIR__."/../common.php";

$plugin_identifier = "ef_post2"; // identifier name (routes,module class_name)
$plugin_name = "Entity Post";
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
	$plugin_identifier."/types" => "Post : Post Types",
	$plugin_identifier."/attachment/types" => "Post : Post Attachment Types",
	$plugin_identifier."/feedback/types" => "Post : Feedback Types",
	$plugin_identifier."/schedule/types" => "Post : Schedule Types",
	$plugin_identifier."/tag/types" => "Post : Post Tag Types",
	$plugin_identifier."/tags" => "Post : Post Tags",
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
/app/Http/Routes/PLPost2{file_ucword}{file_target_ucword}Api.php
/app/Http/Controllers/Api/PLPost2{file_ucword}{file_target_ucword}GeneralController.php
/app/Http/Models/PLAttachment.php
/app/Http/Models/PLAttachmentType.php
/app/Http/Models/PLPost.php
/app/Http/Models/PLPostAttachmentMap.php
/app/Http/Models/PLPostFeedbackType.php
/app/Http/Models/PLPostScheduleMap.php
/app/Http/Models/PLPostScheduleType.php
/app/Http/Models/PLTag.php
/app/Http/Models/PLTagMap.php
/app/Http/Models/PLTagType.php
/app/Http/Models/PLPostType.php
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
