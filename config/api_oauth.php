<?php

return [
    "TOKEN_PATTREN" => 'XapP@#_xw-Oo',
	'ENTITY_TYPES' => array(
		"none" => 'None',
		"user" => 'User',
		"device" => "Device"
	),
	"ENTITY_MODELS" => array(
		"user" => "User",
		"device" => "DeviceReg"
	),
	"ENTITY_PKS" => array(
		"user" => "user_id",
		"device" => "device_reg_id"
	),
	"TOKEN_EXPIRY_TIME" => (3600 * 24 * 30),  // 30 days;
];