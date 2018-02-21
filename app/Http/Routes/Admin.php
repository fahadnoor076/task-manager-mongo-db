<?php
/*
 * References :
 * Avoid case-sensitive urls (ref : https://moz.com/blog/11-best-practices-for-urls)
 * Camel-case functions names (ref: joomla, magento)
*/
// Administrator
// Administrator : Auth
/*Route::controllers(array(
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
));
Route::get(DIR_ADMIN, 'Auth\AuthController@getLogin');
Route::post(DIR_ADMIN, 'Auth\AuthController@postLogin');
Route::get(DIR_ADMIN.'logout', 'Auth\AuthController@getLogout');
// Administrator : Passwords
Route::get(DIR_ADMIN.'forgot/password', 'Auth\PasswordController@getEmail');
Route::post(DIR_ADMIN.'forgot/password', 'Auth\PasswordController@postEmail');
Route::get(DIR_ADMIN.'password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post(DIR_ADMIN.'password/reset', 'Auth\PasswordController@postReset');
Route::get(DIR_ADMIN.'password', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@getChangePassword');
Route::post(DIR_ADMIN.'password', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@postChangePassword');
*/
// Administrator : Core
Route::get(DIR_ADMIN.'administrator/', rtrim(ucfirst(DIR_ADMIN), '/').'\DashboardController@index');
Route::any(DIR_ADMIN.'administrator/login/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@login');
Route::any(DIR_ADMIN.'administrator/logout/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@logout');
Route::any(DIR_ADMIN.'administrator/forgot_password/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@forgotPassword');
Route::any(DIR_ADMIN.'administrator/confirm_forgot/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@confirmForgot');
Route::any(DIR_ADMIN.'administrator/change_password/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@changePassword');

// Administrator : Dashboard
Route::get(DIR_ADMIN.'administrator/dashboard/', rtrim(ucfirst(DIR_ADMIN), '/').'\DashboardController@index');
// Administrator : Group
Route::any(DIR_ADMIN.'administrator/admin_group/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminGroupController@index');
Route::any(DIR_ADMIN.'administrator/admin_group/add', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminGroupController@add');
Route::any(DIR_ADMIN.'administrator/admin_group/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminGroupController@update');
// Administrator : Widget
Route::any(DIR_ADMIN.'administrator/admin_widget/', rtrim(ucfirst(DIR_ADMIN), '/').'\WidgetController@index');
Route::any(DIR_ADMIN.'administrator/admin_widget/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\WidgetController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/admin_widget/add', rtrim(ucfirst(DIR_ADMIN), '/').'\WidgetController@add');
Route::any(DIR_ADMIN.'administrator/admin_widget/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\WidgetController@update');
Route::any(DIR_ADMIN.'administrator/admin_widget/preview/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\WidgetController@preview');
Route::any(DIR_ADMIN.'administrator/parse_widget/{type}/{identifier}', rtrim(ucfirst(DIR_ADMIN), '/').'\WidgetController@parse');
// Administrator : Admin
Route::any(DIR_ADMIN.'administrator/admin/', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@index');
Route::any(DIR_ADMIN.'administrator/admin/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/admin/add', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@add');
Route::any(DIR_ADMIN.'administrator/admin/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\AdminController@update');
// Administrator : User
Route::any(DIR_ADMIN.'administrator/user/', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@index');
Route::any(DIR_ADMIN.'administrator/user/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@ajaxListing');
//Route::any(DIR_ADMIN.'administrator/user/add', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@add');
Route::any(DIR_ADMIN.'administrator/user/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@update');
Route::any(DIR_ADMIN.'administrator/user/validate_notification', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@validateNotification');
Route::any(DIR_ADMIN.'administrator/user/send_notification', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@sendNotification');
Route::any(DIR_ADMIN.'administrator/user/ajax_user_graph', rtrim(ucfirst(DIR_ADMIN), '/').'\UserController@ajaxUserGraph');

// Administrator : Maintainance : Disabilities
Route::any(DIR_ADMIN.'administrator/disability/', rtrim(ucfirst(DIR_ADMIN), '/').'\DisabilityController@index');
Route::any(DIR_ADMIN.'administrator/disability/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\DisabilityController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/disability/add', rtrim(ucfirst(DIR_ADMIN), '/').'\DisabilityController@add');
Route::any(DIR_ADMIN.'administrator/disability/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\DisabilityController@update');
// Administrator : Maintainance : Notification
Route::any(DIR_ADMIN.'administrator/history_notification/', rtrim(ucfirst(DIR_ADMIN), '/').'\HistoryNotificationController@index');
Route::any(DIR_ADMIN.'administrator/history_notification/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\HistoryNotificationController@ajaxListing');
//Route::any(DIR_ADMIN.'administrator/history_notification/add', rtrim(ucfirst(DIR_ADMIN), '/').'\HistoryNotificationController@add');
Route::any(DIR_ADMIN.'administrator/history_notification/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\HistoryNotificationController@update');
// Administrator : Maintainance : PackageLimit
Route::any(DIR_ADMIN.'administrator/package/', rtrim(ucfirst(DIR_ADMIN), '/').'\PackageController@index');
Route::any(DIR_ADMIN.'administrator/package/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\PackageController@ajaxListing');
//Route::any(DIR_ADMIN.'administrator/package/add', rtrim(ucfirst(DIR_ADMIN), '/').'\PackageController@add');
Route::any(DIR_ADMIN.'administrator/package/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\PackageController@update');
// Administrator : CMS Pages
Route::any(DIR_ADMIN.'administrator/page/', rtrim(ucfirst(DIR_ADMIN), '/').'\PageController@index');
Route::any(DIR_ADMIN.'administrator/page/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\PageController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/page/add', rtrim(ucfirst(DIR_ADMIN), '/').'\PageController@add');
Route::any(DIR_ADMIN.'administrator/page/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\PageController@update');
// Administrator : Email Templates
Route::any(DIR_ADMIN.'administrator/email_template/', rtrim(ucfirst(DIR_ADMIN), '/').'\EmailTemplateController@index');
Route::any(DIR_ADMIN.'administrator/email_template/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\EmailTemplateController@ajaxListing');
//Route::any(DIR_ADMIN.'administrator/email_template/add', rtrim(ucfirst(DIR_ADMIN), '/').'\EmailTemplateController@add');
Route::any(DIR_ADMIN.'administrator/email_template/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\EmailTemplateController@update');
// Administrator : Setting
Route::any(DIR_ADMIN.'administrator/setting/', rtrim(ucfirst(DIR_ADMIN), '/').'\SettingController@index');
Route::any(DIR_ADMIN.'administrator/setting/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\SettingController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/setting/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\SettingController@update');
Route::any(DIR_ADMIN.'administrator/setting/logo_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\SettingController@logoBrowser');
// Administrator : Maintainance : Banner Ads
Route::any(DIR_ADMIN.'administrator/banner_ad/', rtrim(ucfirst(DIR_ADMIN), '/').'\BannerAdController@index');
Route::any(DIR_ADMIN.'administrator/banner_ad/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\BannerAdController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/banner_ad/add', rtrim(ucfirst(DIR_ADMIN), '/').'\BannerAdController@add');
Route::any(DIR_ADMIN.'administrator/banner_ad/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\BannerAdController@update');
Route::any(DIR_ADMIN.'administrator/banner_ad/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\BannerAdController@imageBrowser');
// Administrator : Maintainance : Shop Managment
Route::any(DIR_ADMIN.'administrator/virtual_item/', rtrim(ucfirst(DIR_ADMIN), '/').'\VirtualItemController@index');
Route::any(DIR_ADMIN.'administrator/virtual_item/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\VirtualItemController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/virtual_item/add', rtrim(ucfirst(DIR_ADMIN), '/').'\VirtualItemController@add');
Route::any(DIR_ADMIN.'administrator/virtual_item/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\VirtualItemController@update');
Route::any(DIR_ADMIN.'administrator/virtual_item/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\VirtualItemController@imageBrowser');
// Administrator : Query Interface
Route::any(DIR_ADMIN.'administrator/query_interface/', rtrim(ucfirst(DIR_ADMIN), '/').'\QueryController@index');
// Administrator : Maintainance : Banner Ads
Route::any(DIR_ADMIN.'administrator/flurry/', rtrim(ucfirst(DIR_ADMIN), '/').'\FlurryController@index');
// Administrator : Maintainance : Asset
Route::any(DIR_ADMIN.'administrator/asset/', rtrim(ucfirst(DIR_ADMIN), '/').'\AssetController@index');
Route::any(DIR_ADMIN.'administrator/asset/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\AssetController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/asset/add', rtrim(ucfirst(DIR_ADMIN), '/').'\AssetController@add');
Route::any(DIR_ADMIN.'administrator/asset/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\AssetController@imageBrowser');
Route::any(DIR_ADMIN.'administrator/asset/raw', rtrim(ucfirst(DIR_ADMIN), '/').'\AssetController@raw');


Route::any(DIR_ADMIN.'administrator/se_resource/', rtrim(ucfirst(DIR_ADMIN), '/').'\SEResourceController@index');
Route::any(DIR_ADMIN.'administrator/se_resource/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\SEResourceController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/se_resource/add', rtrim(ucfirst(DIR_ADMIN), '/').'\SEResourceController@add');
Route::any(DIR_ADMIN.'administrator/se_resource/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\SEResourceController@update');
Route::any(DIR_ADMIN.'administrator/se_resource/file_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\SEResourceController@fileBrowser');

// Administrator : Game : Configuration
Route::any(DIR_ADMIN.'administrator/game_config/', rtrim(ucfirst(DIR_ADMIN), '/').'\GameConfigController@index');
// Administrator : Game : Level
Route::any(DIR_ADMIN.'administrator/level/', rtrim(ucfirst(DIR_ADMIN), '/').'\LevelController@index');
Route::any(DIR_ADMIN.'administrator/level/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\LevelController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/level/add', rtrim(ucfirst(DIR_ADMIN), '/').'\LevelController@add');
Route::any(DIR_ADMIN.'administrator/level/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\LevelController@update');
// Administrator : Game : Achievement
Route::any(DIR_ADMIN.'administrator/achievement/', rtrim(ucfirst(DIR_ADMIN), '/').'\AchievementController@index');
Route::any(DIR_ADMIN.'administrator/achievement/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\AchievementController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/achievement/add', rtrim(ucfirst(DIR_ADMIN), '/').'\AchievementController@add');
Route::any(DIR_ADMIN.'administrator/achievement/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\AchievementController@update');
// Administrator : Q&A : Questions
Route::any(DIR_ADMIN.'administrator/qa_content/', rtrim(ucfirst(DIR_ADMIN), '/').'\QAContentController@index');
Route::any(DIR_ADMIN.'administrator/qa_content/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\QAContentController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/qa_content/add', rtrim(ucfirst(DIR_ADMIN), '/').'\QAContentController@add');
Route::any(DIR_ADMIN.'administrator/qa_content/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\QAContentController@update');
Route::any(DIR_ADMIN.'administrator/qa_content/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\QAContentController@imageBrowser');

// Administrator : Entity Framework : Entity
Route::any(DIR_ADMIN.'administrator/ef_entity/', rtrim(ucfirst(DIR_ADMIN), '/').'\EFEntityController@index');
Route::any(DIR_ADMIN.'administrator/ef_entity/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\EFEntityController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/ef_entity/add', rtrim(ucfirst(DIR_ADMIN), '/').'\EFEntityController@add');
Route::any(DIR_ADMIN.'administrator/ef_entity/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\EFEntityController@update');
// Administrator : Queue Upload
Route::any(DIR_ADMIN.'administrator/qa_content/queue_upload', rtrim(ucfirst(DIR_ADMIN), '/').'\QAContentController@queueUpload');
// Administrator : Payment Conf
Route::any(DIR_ADMIN.'administrator/payment_conf/', rtrim(ucfirst(DIR_ADMIN), '/').'\PaymentConfController@index');
Route::any(DIR_ADMIN.'administrator/payment_conf/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\PaymentConfController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/payment_conf/add', rtrim(ucfirst(DIR_ADMIN), '/').'\PaymentConfController@add');
Route::any(DIR_ADMIN.'administrator/payment_conf/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\PaymentConfController@update');
// Administrator : Q&A : Content
Route::any(DIR_ADMIN.'administrator/qa_questions/', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@index');
Route::any(DIR_ADMIN.'administrator/qa_questions/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/qa_questions/add', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@add');
Route::any(DIR_ADMIN.'administrator/qa_questions/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@update');
Route::any(DIR_ADMIN.'administrator/qa_questions/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@imageBrowser');
Route::any(DIR_ADMIN.'administrator/qa_questions/init_import', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@initImport');
Route::any(DIR_ADMIN.'administrator/qa_questions/process_import', rtrim(ucfirst(DIR_ADMIN), '/').'\QAQuestionsController@processImport');

// Administrator : Packages : Categories
Route::any(DIR_ADMIN.'administrator/categories/', rtrim(ucfirst(DIR_ADMIN), '/').'\CategoriesController@index');
Route::any(DIR_ADMIN.'administrator/categories/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\CategoriesController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/categories/add', rtrim(ucfirst(DIR_ADMIN), '/').'\CategoriesController@add');
Route::any(DIR_ADMIN.'administrator/categories/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\CategoriesController@update');
Route::any(DIR_ADMIN.'administrator/categories/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\CategoriesController@imageBrowser');

// Administrator : Packages : Custom Packages
Route::any(DIR_ADMIN.'administrator/custom_packages/', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomPackagesController@index');
Route::any(DIR_ADMIN.'administrator/custom_packages/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomPackagesController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/custom_packages/add', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomPackagesController@add');
Route::any(DIR_ADMIN.'administrator/custom_packages/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomPackagesController@update');

// Administrator : Packages : Custom Links Builder
Route::any(DIR_ADMIN.'administrator/custom_links/', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@index');
Route::any(DIR_ADMIN.'administrator/custom_links/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/custom_links/ajax_subcats', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@ajaxSubcats');
Route::any(DIR_ADMIN.'administrator/custom_links/ajax_packages', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@ajaxPackages');
Route::any(DIR_ADMIN.'administrator/custom_links/add', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@add');
Route::any(DIR_ADMIN.'administrator/custom_links/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@update');
Route::any(DIR_ADMIN.'administrator/custom_links/email/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\CustomLinksController@email');


// Administrator : Packages : Coupons Management
Route::any(DIR_ADMIN.'administrator/coupons_management/', rtrim(ucfirst(DIR_ADMIN), '/').'\CouponsManagementController@index');
Route::any(DIR_ADMIN.'administrator/coupons_management/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\CouponsManagementController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/coupons_management/add', rtrim(ucfirst(DIR_ADMIN), '/').'\CouponsManagementController@add');
Route::any(DIR_ADMIN.'administrator/coupons_management/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\CouponsManagementController@update');

// Administrator : Packages : Addons Management
Route::any(DIR_ADMIN.'administrator/package_addon/', rtrim(ucfirst(DIR_ADMIN), '/').'\AddonsController@index');
Route::any(DIR_ADMIN.'administrator/package_addon/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\AddonsController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/package_addon/add', rtrim(ucfirst(DIR_ADMIN), '/').'\AddonsController@add');
Route::any(DIR_ADMIN.'administrator/package_addon/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\AddonsController@update');

// Administrator : Order Management
Route::any(DIR_ADMIN.'administrator/orders_management/', rtrim(ucfirst(DIR_ADMIN), '/').'\OrdersManagementController@index');
Route::any(DIR_ADMIN.'administrator/orders_management/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\OrdersManagementController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/orders_management/details/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\OrdersManagementController@orderDetails');

Route::any(DIR_ADMIN.'administrator/orders_management/pdf/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\OrdersManagementController@orderPdf');
Route::any(DIR_ADMIN.'administrator/orders_management/pending_order/{status}/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\OrdersManagementController@pendingOrder');

// Administrator : Brands Management
Route::any(DIR_ADMIN.'administrator/brands/', rtrim(ucfirst(DIR_ADMIN), '/').'\BrandsController@index');
Route::any(DIR_ADMIN.'administrator/brands/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\BrandsController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/brands/add', rtrim(ucfirst(DIR_ADMIN), '/').'\BrandsController@add');
Route::any(DIR_ADMIN.'administrator/brands/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\BrandsController@update');

// Administrator : Clients Management
Route::any(DIR_ADMIN.'administrator/clients/', rtrim(ucfirst(DIR_ADMIN), '/').'\ClientsController@index');
Route::any(DIR_ADMIN.'administrator/clients/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\ClientsController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/clients/add', rtrim(ucfirst(DIR_ADMIN), '/').'\ClientsController@add');
Route::any(DIR_ADMIN.'administrator/clients/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\ClientsController@update');


// Administrator : Patterns Management
Route::any(DIR_ADMIN.'administrator/portfolio/', rtrim(ucfirst(DIR_ADMIN), '/').'\PortfolioController@index');
Route::any(DIR_ADMIN.'administrator/portfolio/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\PortfolioController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/portfolio/add', rtrim(ucfirst(DIR_ADMIN), '/').'\PortfolioController@add');
Route::any(DIR_ADMIN.'administrator/portfolio/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\PortfolioController@update');
Route::any(DIR_ADMIN.'administrator/portfolio/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\PortfolioController@imageBrowser');

// Administrator : Palette Management
Route::any(DIR_ADMIN.'administrator/palette/', rtrim(ucfirst(DIR_ADMIN), '/').'\PaletteController@index');
Route::any(DIR_ADMIN.'administrator/palette/ajax/listing', rtrim(ucfirst(DIR_ADMIN), '/').'\PaletteController@ajaxListing');
Route::any(DIR_ADMIN.'administrator/palette/add', rtrim(ucfirst(DIR_ADMIN), '/').'\PaletteController@add');
Route::any(DIR_ADMIN.'administrator/palette/update/{id}', rtrim(ucfirst(DIR_ADMIN), '/').'\PaletteController@update');
Route::any(DIR_ADMIN.'administrator/palette/image_browser', rtrim(ucfirst(DIR_ADMIN), '/').'\PaletteController@imageBrowser');

// Administrator : Add Order
Route::any(DIR_ADMIN.'administrator/add_order/', rtrim(ucfirst(DIR_ADMIN), '/').'\OrdersManagementController@addOrder');

// Administrator : Add Web Design Repository
Route::any(DIR_ADMIN.'administrator/add_website_repository/', rtrim(ucfirst(DIR_ADMIN), '/').'\WebsiteRepositoryController@addDesign');
