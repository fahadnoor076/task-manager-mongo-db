<?php
// API methods : General
Route::any(DIR_API.'social/post/user/comment/add', rtrim(ucfirst(DIR_API), '/').'\PostUserSocialController@addComment');
Route::any(DIR_API.'social/post/user/comment/edit', rtrim(ucfirst(DIR_API), '/').'\PostUserSocialController@editComment');
Route::any(DIR_API.'social/post/user/comment/delete', rtrim(ucfirst(DIR_API), '/').'\PostUserSocialController@deleteComment');
Route::any(DIR_API.'social/post/user/comment/get', rtrim(ucfirst(DIR_API), '/').'\PostUserSocialController@getComment');
Route::any(DIR_API.'social/post/user/comment/get_all', rtrim(ucfirst(DIR_API), '/').'\PostUserSocialController@getAllComments');