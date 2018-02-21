<?php
// API Routes
Route::get('api', rtrim(ucfirst(DIR_API), '/').'\IndexController@index');
Route::match(array('get', 'post'),DIR_API.'load_params', rtrim(ucfirst(DIR_API), '/').'\IndexController@load_params');
// API methods : User
Route::any(DIR_API.'user/login', rtrim(ucfirst(DIR_API), '/').'\UserController@login');
// API methods : Game
Route::any(DIR_API.'game/configurations', rtrim(ucfirst(DIR_API), '/').'\GameController@configurations');
Route::any(DIR_API.'game/virtual_items', rtrim(ucfirst(DIR_API), '/').'\GameController@virtualItems');
Route::any(DIR_API.'game/levels', rtrim(ucfirst(DIR_API), '/').'\GameController@levels');
Route::any(DIR_API.'game/achievements', rtrim(ucfirst(DIR_API), '/').'\GameController@achievements');
Route::any(DIR_API.'qa/get_questions', rtrim(ucfirst(DIR_API), '/').'\QAController@getQuestions');
Route::any(DIR_API.'qa/submit_answers', rtrim(ucfirst(DIR_API), '/').'\QAController@submitAnswers');
// API methods : Asset
Route::any(DIR_API.'asset/get_all', rtrim(ucfirst(DIR_API), '/').'\AssetController@getAll');
Route::any(DIR_API.'se/get_all', rtrim(ucfirst(DIR_API), '/').'\SEController@getAll');

// API methods : Page
Route::any(DIR_API.'page/get_by_slug', rtrim(ucfirst(DIR_API), '/').'\PageController@getBySlug');

// API methods : oAuth
Route::any(DIR_API.'oauth/get_token', rtrim(ucfirst(DIR_API), '/').'\OAuthController@getToken');
Route::any(DIR_API.'oauth/refresh_token', rtrim(ucfirst(DIR_API), '/').'\OAuthController@refreshToken');
