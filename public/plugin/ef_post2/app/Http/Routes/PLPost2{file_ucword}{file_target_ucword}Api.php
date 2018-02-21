<?php
// API methods : General
Route::any(DIR_API.'post2/{wildcard_identifier}_{target_identifier}/types', rtrim(ucfirst(DIR_API), '/').'\PLPost2{wildcard_ucword}{target_ucword}GeneralController@types');
Route::any(DIR_API.'post2/{wildcard_identifier}_{target_identifier}/attachment/types', rtrim(ucfirst(DIR_API), '/').'\PLPost2{wildcard_ucword}{target_ucword}GeneralController@attachmentTypes');
Route::any(DIR_API.'post2/{wildcard_identifier}_{target_identifier}/feedback/types', rtrim(ucfirst(DIR_API), '/').'\PLPost2{wildcard_ucword}{target_ucword}GeneralController@feedbackTypes');
Route::any(DIR_API.'post2/{wildcard_identifier}_{target_identifier}/schedule/types', rtrim(ucfirst(DIR_API), '/').'\PLPost2{wildcard_ucword}{target_ucword}GeneralController@scheduleTypes');
Route::any(DIR_API.'post2/{wildcard_identifier}_{target_identifier}/tag/types', rtrim(ucfirst(DIR_API), '/').'\PLPost2{wildcard_ucword}{target_ucword}GeneralController@tagTypes');
Route::any(DIR_API.'post2/{wildcard_identifier}_{target_identifier}/tags', rtrim(ucfirst(DIR_API), '/').'\PLPost2{wildcard_ucword}{target_ucword}GeneralController@tags');
