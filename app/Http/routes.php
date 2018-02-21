<?php
/*
 * References :
 * Avoid case-sensitive urls (ref : https://moz.com/blog/11-best-practices-for-urls)
 * Camel-case functions names (ref: joomla, magento)
*/

Route::get('/', 'DashboardController@index');
Route::get('dashboard/', 'DashboardController@index');
Route::any('login/', 'UserController@login');
Route::any('logout/', 'UserController@logout');
Route::any('forgot_password/', 'UserController@forgotPassword');
Route::any('confirm_forgot/', 'UserController@confirmForgot');
Route::any('change_password/', 'UserController@changePassword');

//userRole
Route::any('user-role/', 'UserRoleController@index');
Route::any('user-role/ajax/listing', 'UserRoleController@ajaxListing');
Route::any('user-role/add', 'UserRoleController@add');
Route::any('user-role/update/{id}', 'UserRoleController@update');

//userDesignation
Route::any('user-designation/', 'UserDesignationController@index');
Route::any('user-designation/ajax/listing', 'UserDesignationController@ajaxListing');
Route::any('user-designation/add', 'UserDesignationController@add');
Route::any('user-designation/update/{id}', 'UserDesignationController@update');

//user
Route::any('user/', 'UserController@index');
Route::any('user/ajax/listing', 'UserController@ajaxListing');
Route::any('user/add', 'UserController@add');
Route::any('user/update/{id}', 'UserController@update');
//user profile
Route::any('user/profile/{id}', 'UserController@profile');

//***Miscellaneous***//
//Modules
Route::any('user-module/', 'UserModuleController@index');
Route::any('user-module/ajax/listing', 'UserModuleController@ajaxListing');
Route::any('user-module/add', 'UserModuleController@add');
Route::any('user-module/update/{id}', 'UserModuleController@update');

//department
Route::any('department/', 'DepartmentController@index');
Route::any('department/ajax/listing', 'DepartmentController@ajaxListing');
Route::any('department/add', 'DepartmentController@add');
Route::any('department/update/{id}', 'DepartmentController@update');

//brand
Route::any('brand/', 'BrandController@index');
Route::any('brand/ajax/listing', 'BrandController@ajaxListing');
Route::any('brand/add', 'BrandController@add');
Route::any('brand/update/{id}', 'BrandController@update');

//team
Route::any('team/', 'TeamController@index');
Route::any('team/ajax/listing', 'TeamController@ajaxListing');
Route::any('team/add', 'TeamController@add');
Route::any('team/update/{id}', 'TeamController@update');
Route::any('team/members/{id}', 'TeamController@member');

//Phase
Route::any('phase/', 'PhaseController@index');
Route::any('phase/ajax/listing', 'PhaseController@ajaxListing');
Route::any('phase/add', 'PhaseController@add');
Route::any('phase/update/{id}', 'PhaseController@update');

//Clients
Route::any('account/', 'AccountController@index');
Route::any('account/ajax/listing', 'AccountController@ajaxListing');
Route::any('account/add', 'AccountController@add');
Route::any('account/update/{id}', 'AccountController@update');
Route::any('account/details/{id}', 'AccountController@detail');
Route::any('account/remove-opportunity', 'AccountController@removeOpportunity');
//***Miscellaneous End***//

//Project
Route::any('project/', 'ProjectController@index');
Route::any('project/ajax/listing', 'ProjectController@ajaxListing');
Route::any('project/add', 'ProjectController@add');
Route::any('project/update/{id}', 'ProjectController@update');
Route::any('project/detail/{id}', 'ProjectController@detail');
Route::any('project/update-status/{id}', 'ProjectController@updateStatus');
Route::any('project/invoice-details', 'ProjectController@getInvoiceDetails');
Route::any('project/account-invoices', 'ProjectController@getAccountInvoices');
Route::any('project/client-projects', 'ProjectController@getClientProjects');

//Segment Details
Route::any('segment-details/{id}', 'ProjectController@segmentDetail');
//Segment Comment Submit
Route::any('project/segment-comment-submit', 'ProjectController@segmentCommentSubmit');
//Tag User Segment
Route::any('project/user-tag', 'ProjectController@userTag');
Route::any('project/remove-user-tag', 'ProjectController@removeUserTag');


//Task
//default page
Route::any('task/', 'TaskController@task');
//rest of the anchors
Route::any('task/all-tasks/', 'TaskController@alltasks');
Route::any('task/alltasks-ajax/listing', 'TaskController@alltasksAjaxListing');

Route::any('task/all-due-tasks/', 'TaskController@allduetasks');
Route::any('task/allduetasks-ajax/listing', 'TaskController@alldueAjaxListing');

Route::any('task/due-today-tasks/', 'TaskController@duetodaytasks');
Route::any('task/duetodaytasks-ajax/listing', 'TaskController@duetodayAjaxListing');

Route::any('task/clarification-tasks/', 'TaskController@clarificationtasks');
Route::any('task/clarificationtasks-ajax/listing', 'TaskController@clarificationAjaxListing');

Route::any('task/overdue-tasks/', 'TaskController@overduetasks');
Route::any('task/overduetasks-ajax/listing', 'TaskController@overdueAjaxListing');

//task detail page
Route::any('task/detail/{id}', 'TaskController@taskDetail');

//task image upload
//Route::post('task/detail/{id}', 'TaskController@taskDetailSubmit');

// Administrator : Dashboard
Route::get('dashboard/', 'UserController@index');


//***Extras**//
Route::any('user/get-designations/', 'UserController@getDesignationsByRoleId');
Route::any('user/get-brands-by-department', 'UserController@getBrandsByDepartmentId');