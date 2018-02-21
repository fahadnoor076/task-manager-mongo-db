<?php
// API methods : Comment
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@addComment');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@editComment');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@deleteComment');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/get', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getComment');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getAllComments');

// API methods : Like
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/like/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@addLike');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/like/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@editLike');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/like/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@deleteLike');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/like/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getAllLikes');

// API methods : Review
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@addReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@editReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@deleteReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/get', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/get_all', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getAllReviews');


// API methods : Follower
Route::any(DIR_API.'social/{target_identifier}/follower/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@addFollower');
Route::any(DIR_API.'social/{target_identifier}/follower/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@deleteFollower');
Route::any(DIR_API.'social/{target_identifier}/follower/get_all_followers', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getAllFollowers');
Route::any(DIR_API.'social/{target_identifier}/follower/get_all_following', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@getAllFollowing');