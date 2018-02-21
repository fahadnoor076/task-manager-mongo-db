<?php
$test = include_once 'installation.php';

echo "<pre>";
print_r($test);
echo "</pre>";

$files = explode("\n",$test["install_files"]);
echo "<pre>";
print_r($files);
echo "</pre>";