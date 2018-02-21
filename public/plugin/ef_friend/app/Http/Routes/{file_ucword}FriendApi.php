<?php
// API methods : Friend
Route::any(DIR_API.'{wildcard_identifier}/friend/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@addFriend');
Route::any(DIR_API.'{wildcard_identifier}/friend/accept', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@acceptFriend');
Route::any(DIR_API.'{wildcard_identifier}/friend/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@deleteFriend');
Route::any(DIR_API.'{wildcard_identifier}/friend/cancel', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@cancelFriend');
Route::any(DIR_API.'{wildcard_identifier}/friend/reject', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@rejectFriend');
Route::any(DIR_API.'{wildcard_identifier}/friend/get_all_pending', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@getAllPendingRequests');
Route::any(DIR_API.'{wildcard_identifier}/friend/get_all_sent', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@getAllSentRequests');
Route::any(DIR_API.'{wildcard_identifier}/friend/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}FriendController@getAll');