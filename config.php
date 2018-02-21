<?php

/*
 * ---------------------------------------------------------------
 *  Custom Constants
 * ---------------------------------------------------------------
 */
 
 	// App constants
	define('APP_NAME', 'Task Manager 2.0'); // app name
	// HTTP Protocol
	define('HTTP_TYPE', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://' : 'http://');
	
	// Master Database Constants
	if(preg_match('/localhost/',isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : exec("hostname"))) {
		// app dir details
		define('APP_ALIAS',''); // app dir name
		define('ADD_PATH', 'mongo_new'); // preceding path to app dir
		//  for saving cookies
		define('APP_DOMAIN', preg_match('/Chrome\/|MSIE/',@$_SERVER["HTTP_USER_AGENT"]) ? '' : $_SERVER['HTTP_HOST']); // chrome/IE cookie fix on local host
		
		// db details
		define('MASTER_DB_HOST', '');
		define('MASTER_DB_USER', '');
		define('MASTER_DB_PASS', '');
		define('MASTER_DB_NAME', 'tm_mongo');
		define('MASTER_DB_PREFIX', '');
		//MongoDB Object
		define('MONGO_OBJECT', 0);
		// Facebook Constants
		//define('FB_APP_ID', '251805731669383'); // fb app id
		//define('FB_SECRET', '6968ab1f706a369cb15285e627bce84c'); // fb app secret
		//define('FB_PERMS', 'public_profile,email,user_location');
		//define('FB_APP_URL', HTTP_TYPE.'apps.facebook.com/school_finder_local/'); // fb app url
		//define('FB_APP_ADMIN_URL', FB_APP_URL.'administrator/');
		//define('FB_PAGE_URL', HTTP_TYPE.'facebook.com/karachikarachi/app_'.FB_APP_ID); // fanpage url	
	}
	else {
		// app dir details
		define('APP_ALIAS', ''); // app dir name
		define('ADD_PATH', ''); // preceding path to app dir //web/cubix2.5.1/ 		//product/designspecs/
		define('APP_DOMAIN', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : exec("hostname")); //  for saving cookies
		// db details
		define('MASTER_DB_HOST', 'localhost');	//
		define('MASTER_DB_USER', 'tmuser');	//
		define('MASTER_DB_PASS', 'salsofttm@123');	//
		define('MASTER_DB_NAME', 'task_manager');	//
		define('MASTER_DB_PREFIX', '');
		//MongoDB Object
		define('MONGO_OBJECT', 1);
		// Facebook Constants
		//define('FB_APP_ID', '316163708589381'); // fb app id
		//define('FB_SECRET', 'a5a1610326eda5a532454be88384cfb8'); // fb app secret
		//define('FB_PERMS', 'public_profile,email,user_location');
		//define('FB_APP_URL', HTTP_TYPE.'apps.facebook.com/momtalk_schoolfinder/'); // fb app url
		//define('FB_APP_ADMIN_URL', FB_APP_URL.'administrator/');
		//define('FB_PAGE_URL', HTTP_TYPE.'facebook.com/MAGGIPAKISTAN/app_'.FB_APP_ID); // fanpage url
	}
	// Slave Database Constants
	define('SLAVE_DB_HOST', MASTER_DB_HOST);
	define('MYSQL_PORT', 3306);
	
	// Cache constants
	define('CACHE_ON', FALSE); // bool : TRUE = on | FALSE = off
	define('CACHE_DRIVER', 'file'); // memcache, memcached, file, apc, dummy
	define('CACHE_LIFE', (60*24)); // one day (in minutes)
	
	// Memcache Constants (if enabled)
	define('MEMCACHE_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : exec("hostname"));
	define('MEMCACHE_PORT', 11211);
	define('MEM_KEY',strtoupper(str_replace(array(" ","."),'-',APP_NAME)).'-');
	
	// Session Constants
	define('ADMIN_SESS_KEY', strtolower(MEM_KEY).'admin-');
	define('REMEMBER_COOKIE_TIME', (3600 * 24 * 30)); // 30 days;
	define('USER_SESS_KEY', strtolower(MEM_KEY).'user-');
	define('BRAND_SESS_KEY', strtolower(MEM_KEY).'brand-');
	define('API_SESS_KEY', strtolower(MEM_KEY).'api-');
	define('API_USER_SESS_KEY', strtolower(MEM_KEY).'api_user-');
	
	
	// Salt for password security
	define('ADMIN_SALT', 'Xx@#_Ww-Oo');
	define('API_USER_SALT', 'Xx@#_Aa-Oo');
	define('API_SALT', 'Aas--dD-');
	define('BRAND_SALT', 'Xi@#_xw-Oo');
	//define('CLIENT_TOKEN_SALT', 'cLt_t0k3n-xXwOo');
	define('USER_SALT', 'Xx@#_Ww-Ox');
	define('GROUP_SALT', 'Xx@#_Ww-Oi');
	define('FORGOT_PASS_HASH_LENGTH', 20);
	
	// directory constants
	define('DIR_ADMIN', '');
	define('DIR_BRAND', 'brand/');
	define('DIR_BRAND_PANEL', 'brand_panel/');
	define('DIR_API', 'api/');
	define('DIR_IMG', 'public/images/');
	define('DIR_FILES', 'public/files/');
	define('DIR_RAW_FILES', 'public/raw_files/');
	define('DIR_PROFILE_IMG', DIR_FILES.'profile/');
	define('DIR_CATEGORY_IMG', DIR_FILES.'category/');
	define('DIR_BANNER_IMG', DIR_FILES.'banner/');
	define('DIR_USER_IMG', DIR_FILES.'user/');
	define('DIR_DISH', DIR_FILES.'media/');
	define('DIR_WATERMARK', DIR_FILES.'watermark/');
	
	// Paging constants
	define("PAGE_LIMIT_ADMIN", 25);
	define("PAGE_LIMIT_API", 10);
	define("PAGE_LIMIT", 10);
	
	// date formats
	define('DATE_TIME_FORMAT_DB', 'Y-m-d H:i:s');
	define('DATE_FORMAT_DB', 'Y-m-d');
	
	define('DATE_TIME_FORMAT_ADMIN', 'Y-m-d H:i:s');
	define('DATE_FORMAT_ADMIN', 'd M Y');
	define('DATE_GRAPH', date("Y-m"));
	
	// other constants
	ini_set('session.use_trans_sid', 1);
	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	
	
	// mail server configuration
	define('MAIL_DRIVER', 'mail'); // "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
	define('MAIL_HOST', '');
	define('MAIL_PORT', NULL);
	define('MAIL_USERNAME', "");
	define('MAIL_PASSWORD', "");
	
	// html5 webkit support for IE
	header('X-UA-Compatible: IE=edge,chrome=1');
	
	// version control for cache
	define('VERSION', 0);
	
	define('DO_URL_REWRITE', TRUE); // bool : TRUE = on | FALSE = off
	define('SOFT_DELETE', TRUE);
	
	// NodeJS configuration
	define('NODEJS_PORT', 3002);
	
	// push notification
	define('PN_IOS_FILE', "ck.pem");
	define('PN_IOS_URL', "ssl://gateway.push.apple.com:2195");
	define('MASS_PN_CODE', 99); // code for sending mass notification
	//define('PN_IOS_FILE', "dev.pem");
	//define('PN_IOS_URL', "ssl://gateway.sandbox.push.apple.com:2195");
	
	
	
	// API Access
	//define('API_ACCESS_URL', "http://198.20.103.178/api/"); //
	//define('API_ACCESS_URL', "http://cubixcube.com/".ADD_PATH."api");
    //define('API_ACCESS_URL', "http://r4outdoors.com/api/");
	define('API_ACCESS_EMAIL', "ahsan.jawaid@salsoft.net");
	define('API_ACCESS_PASS', "");
	
	
	define('APP_DEBUG', TRUE);
	define('APP_KEY', sha1(MEM_KEY));
	//define('APP_TIMEZONE', 'GMT');
	define('APP_TIMEZONE', 'Asia/Karachi');
	
	//define('DATE_GRAPH', date("Y-m"));
	
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(E_ALL);
	
	
	
?>