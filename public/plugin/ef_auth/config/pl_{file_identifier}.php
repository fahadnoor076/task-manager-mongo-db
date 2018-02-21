<?php

return [
    "DIR_PANEL" => "{wildcard_identifier}_panel/",
	"SESS_KEY" => strtoupper("{wildcard_identifier}")."-",
	"DIR_IMG" => "public/files/{wildcard_identifier}_img/",
	"SALT" => 'Xi@#_xw-Oo',
	"REMEMBER_COOKIE_TIME" => (3600 * 24 * 30),  // 30 days;
	"FORGOT_PASS_HASH_LENGTH" => 20,
	"SIGNUP_HASH_LENGTH" => 50,
	'STATUSES' => array(
		0 => 'Inactive',
		1 => 'Active',
		2 => 'Ban'
	),
	"DATE_FORMAT" => "Y-m-d",
	
	
];
