<?php
// API methods : User
Route::any(DIR_API.'user/get', rtrim(ucfirst(DIR_API), '/').'\UserController@get');
Route::any(DIR_API.'user/mobilelogin', rtrim(ucfirst(DIR_API), '/').'\UserController@mobileLogin');
Route::any(DIR_API.'user/sociallogin', rtrim(ucfirst(DIR_API), '/').'\UserController@socialLogin');