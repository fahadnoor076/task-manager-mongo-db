<?php
// Vendor : Core
Route::get("{wildcard_identifier}_panel", '{wildcard_ucword}_Panel\DashboardController@index');
Route::any('{wildcard_identifier}_panel/login/', '{wildcard_ucword}_Panel\{wildcard_ucword}Controller@login');
Route::any('{wildcard_identifier}_panel/logout/', '{wildcard_ucword}_Panel\{wildcard_ucword}Controller@logout');
Route::any('{wildcard_identifier}_panel/forgot_password/', '{wildcard_ucword}_Panel\{wildcard_ucword}Controller@forgotPassword');
Route::any('{wildcard_identifier}_panel/confirm_forgot/', '{wildcard_ucword}_Panel\{wildcard_ucword}Controller@confirmForgot');
Route::any('{wildcard_identifier}_panel/change_password/', '{wildcard_ucword}_Panel\{wildcard_ucword}Controller@changePassword');

// Vendor : Dashboard
Route::get('{wildcard_identifier}_panel/dashboard/', '{wildcard_ucword}_Panel\DashboardController@index');

// Vendor : Setting
Route::any('{wildcard_identifier}_panel/setting/', '{wildcard_ucword}_Panel\SettingController@index');
Route::any('{wildcard_identifier}_panel/setting/ajax/listing', '{wildcard_ucword}_Panel\SettingController@ajaxListing');
Route::any('{wildcard_identifier}_panel/setting/update/{id}', '{wildcard_ucword}_Panel\SettingController@update');
Route::any('{wildcard_identifier}_panel/setting/logo_browser', '{wildcard_ucword}_Panel\SettingController@logoBrowser');

// WEB SERVICES
Route::any(DIR_API.'{wildcard_identifier}/mobile_login', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}Controller@mobileLogin');
Route::any(DIR_API.'{wildcard_identifier}/social_login', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}Controller@scoialLogin');
Route::any(DIR_API.'{wildcard_identifier}/custom_login', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}Controller@customLogin');
Route::any(DIR_API.'{wildcard_identifier}/register', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}Controller@register');;
Route::any(DIR_API.'{wildcard_identifier}/is_social_registered', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}Controller@isSocialRegistered');
Route::any(DIR_API.'{wildcard_identifier}/get', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}Controller@get');