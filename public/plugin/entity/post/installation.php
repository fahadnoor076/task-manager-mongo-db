<?php
// string vars
$install_sql = $uninstall_sql = $install_files = $uninstall_files = "";
?>

<?php // Install SQL : Base ?>
<?php $install_sql .= trim(file_get_contents("sql/install.sql",true)); ?>

<?php // un-installation SQL : Base ?>
<?php $uninstall_sql .= trim(file_get_contents("sql/uninstall.sql",true)); ?>

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