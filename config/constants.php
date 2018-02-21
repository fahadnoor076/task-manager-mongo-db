<?php

return [
    'APP_NAME' => APP_NAME,
    'APP_ADMIN_NAME' => APP_NAME.' :: Administrator :: ',
    'IMG_URL' => '',
    'CSS_URL' => '',
    'JS_URL' => 'resources/assets/js/',
	'DESIGNSPECS_URL' => 'resources/assets/design_specs/',
	'PORTFOLIO_URL' => 'resources/assets/design_specs/images/portfolio',
	'PALETTE_URL' => 'resources/assets/design_specs/images/color',
    'FONTS_URL' => '',
    'ADMIN_IMG_URL' => 'resources/assets/'.DIR_ADMIN.'img/',
    'ADMIN_RESOURCE_URL' => 'resources/assets/'.DIR_ADMIN,
    'ADMIN_CSS_URL' => 'resources/assets/'.DIR_ADMIN.'css/',
    'ADMIN_JS_URL' => 'resources/assets/'.DIR_ADMIN.'js/',
    'ADMIN_FONTS_URL' => 'resources/assets/'.DIR_ADMIN.'fonts/',
    'BULK_UPLOAD_SAMPLE' => 'resources/assets/'.DIR_ADMIN.'sample/',
    'PROFILE_IMAGE_URL' => 'public/profile/',
    'PROFILE_IMAGES_PATH' => 'public/profile/',
	'RESOURCES_PATH' => 'resources/',
	'ASSETS_PATH' => 'resources/assets/',
	'LOGO_PATH' => 'public/files/logo/',
	'BANNER_AD_PATH' => 'public/files/banner_ad/',
	'CATEGORIES_PATH' => 'public/files/categories/',
	'VIRTUAL_ITEM_PATH' => 'public/files/virtual_item/',
    'CSV_PATH' => 'public/files/csv/',
    'DIR_ADMIN' => DIR_ADMIN,
	'RAW_PATH' => 'public/files/raw/',
	'MASTER_ADMIN_ROUTES' => array(
		DIR_ADMIN.'admin_group',
		DIR_ADMIN.'admin',
		DIR_ADMIN.'setting',
		DIR_ADMIN.'admin_widget',
	),
	'MOBILE_AD_TYPES' => array(
		'none' => 'None',
		'admob' => 'Admob',
		'custom' => 'Custom',
	),
	'ADMIN_WIDGET_TYPES' => array(
		'chart' => 'Chart',
		'tile' => 'Tile',
		'map_chart' => 'Map Chart',
		'data_grid' => 'Data Grid',
		'bar_chart' => 'Bar Chart',
		'pie_chart' => 'Pie Chart',
		'donut_chart' => 'Donut Chart',
		'flot_chart' => 'Flot Chart',
		'stack_chart' => 'Stack Chart',
		'line_chart' => 'Line Chart',
		'radar_chart' => 'Radar Chart',
	),
	'VIRTUAL_ITEM_TYPES' => array(
		'inapp' => 'In-app',
		'exchange' => 'Exchange',
	),
	'ASSET_MANAGEMENT_PATH' => 'public/files/asset/',
	'ASSET_TYPES' => array(
		'image' => 'Image',
		'audio' => 'Audio',
		'video' => 'Video'
	),
	'ADMIN_STATUSES' => array(
		0 => 'Inactive',
		1 => 'Active',
		2 => 'Ban'
	),
	'LEVEL_TYPES' => array(
		'simulation' => 'Simulation',
		'runner' => 'Runner',
		'qa' => 'Q/A',
	),
	'ACHIEVEMENT_TYPES' => array(
		'simulation' => 'Simulation',
		'runner' => 'Runner',
		'qa' => 'Q/A',
	),
	'PRE_CHECK_TYPES' => array(
		'sql' => 'SQL Query',
		'levels' => 'Levels',
		'achievements' => 'Achievements',
	),
	'POST_CHECK_TYPES' => array(
		'sql' => 'SQL Query',
		'levels' => 'Levels',
		'achievements' => 'Achievements',
	),
	'ASSET_TYPES' => array(
		'image' => 'Image',
		'audio' => 'Audio',
		'video' => 'Video'
	),
	
	'LOBBY_LIB_PATH' => 'app/Libraries/lobby/',
	'PLIST_LIB_PATH' => 'app/Libraries/CFPropertyList/',
	'SE_RESOURCE_TYPES' => array('text','color','constant','font_style', 'custom'),
	'SE_DATA_TYPES' => array('integer','string','float','bool'),
	'SE_PLATFORM_TYPES' => array('ios','android'),
	'SE_RESOURCE_PLIST_PATH' => 'public/files/se_resource/plist/',
	'SE_RESOURCE_XML_PATH' => 'public/files/se_resource/xml/',
	'DEF_LANGUAGE_IDENTIFIER' => "en", // default language identifier,
	'DIR_PLUGIN' => "public/plugin/",
	'EF_TABLE_SQL_TYPES' => array(
		"none" => "NONE",
		"basic" => "Basic",
		"auth" => "Authorization",
		"post" => "Post"
	)
];
