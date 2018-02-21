<?php
// API methods : {wildcard_ucword}
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/like', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@like');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@addComment');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@editComment');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/comment/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@deleteComment');

Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/add', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@addReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/edit', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@editReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/review/delete', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@deleteReview');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/follow', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@follow');
Route::any(DIR_API.'social/{wildcard_identifier}/{target_identifier}/unfollow', rtrim(ucfirst(DIR_API), '/').'\{wildcard_ucword}{target_ucword}SocialController@unfollow');
