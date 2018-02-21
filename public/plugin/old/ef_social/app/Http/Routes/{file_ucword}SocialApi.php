<?php
// API methods : {wildcard_ucword}
Route::any(DIR_API.'{wildcard_identifier}_social/like/post', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@post');
Route::any(DIR_API.'{wildcard_identifier}_social/like/get_all', rtrim(ucfirst(DIR_API), '/').'\RetailerSocialController@getAllLike');
Route::any(DIR_API.'{wildcard_identifier}_social/comment/get', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getComment');
Route::any(DIR_API.'{wildcard_identifier}_social/comment/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getAllComment');
Route::any(DIR_API.'{wildcard_identifier}_social/comment/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@addComment');
Route::any(DIR_API.'{wildcard_identifier}_social/comment/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@editComment');
Route::any(DIR_API.'{wildcard_identifier}_social/comment/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@deleteComment');

Route::any(DIR_API.'{wildcard_identifier}_social/review/get', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getReview');
Route::any(DIR_API.'{wildcard_identifier}_social/review/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getAllReview');
Route::any(DIR_API.'{wildcard_identifier}_social/review/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@addReview');
Route::any(DIR_API.'{wildcard_identifier}_social/review/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@editReview');
Route::any(DIR_API.'{wildcard_identifier}_social/review/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@deleteReview');

Route::any(DIR_API.'{wildcard_identifier}_social/follow', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@follow');
Route::any(DIR_API.'{wildcard_identifier}_social/unfollow', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@unfollow');

Route::any(DIR_API.'{wildcard_identifier}_social/friend/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@addFriend');
Route::any(DIR_API.'{wildcard_identifier}_social/friend/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@deleteFriend');
Route::any(DIR_API.'{wildcard_identifier}_social/friend/accept_ignore_request', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@acceptIgnoreFriend');
Route::any(DIR_API.'{wildcard_identifier}_social/friend/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getAllFriend');
Route::any(DIR_API.'{wildcard_identifier}_social/friend/pending_list', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getPendingFriend');

/*Route::any(DIR_API.'{wildcard_identifier}_social/post/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@addPost');
Route::any(DIR_API.'{wildcard_identifier}_social/post/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@editPost');
Route::any(DIR_API.'{wildcard_identifier}_social/post/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@deletePost');
Route::any(DIR_API.'{wildcard_identifier}_social/post/get', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getPost');
Route::any(DIR_API.'{wildcard_identifier}_social/post/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@getAllPost');*/
Route::any(DIR_API.'{wildcard_identifier}_social/share/post', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@postShare');
Route::any(DIR_API.'{wildcard_identifier}_social/share/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@deleteShare');

Route::any(DIR_API.'{wildcard_identifier}_social/tag/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@addTag');
Route::any(DIR_API.'{wildcard_identifier}_social/tag/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}SocialController@deleteTag');