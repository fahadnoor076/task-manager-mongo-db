<?php

namespace App\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;
use Illuminate\Http\Request;

//use Mail;
// load models
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Http\Models\Conf;
use App\Http\Models\Setting;
use App\Http\Models\EmailTemplate;

class UserRole extends Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_userRole';
    protected $dates = ['deletedAt'];
	public $timestamps = true;
	public $primaryKey = '_id';
	// enitity vars
	private $_entity_identifier;
	private $_entity_session_identifier = ADMIN_SESS_KEY;
	private $_entity_dir = DIR_ADMIN;
	private $_entity_salt_pattren = ADMIN_SALT;
	
	

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['name', 'email', 'password', 'admin_group_id', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_login_token'];
	
	 public function __construct() {
        // set tables and keys
        $this->__table = $this->table;
        //$this->primaryKey = $this->__table . '_id';
		$this->primaryKey = '_id';
        $this->__keyParam = $this->primaryKey . '-';
        $this->hidden = array();
		// set entity vars
		$this->_entity_identifier = $this->table;

        // set fields
        //
        //$this->__fields = array($this->primaryKey, 'admin_group_id', 'username', 'email', 'password', 'remember_login_token', 'is_active','remember_login_token_created_at', 'forgot_password_hash', 'forgot_password_hash_created_at', 'status', 'last_login_at', 'brand_id', 'created_at', 'updatedAt', 'deletedAt');
    }
	
	
	/**
     * Check master access
     * 
     */
    function checkMasterAccess() {
		$group_id = Auth::user() ? Auth::user()->admin_group_id : 0;
        return $group_id == 1 ? TRUE : FALSE;
    }
	/**
     * Check master authentication
     * 
     */
    /*function checkMasterAuth() {
		if ($this->checkMasterAccess === FALSE) {
            \Request::session()->flash('auth_msg', 'You are not allowed to access this module.!');
            $url_redirect = \URL::previous() == \URL::current() ? url("/") : \URL::previous();
            header("location:" . $url_redirect);
			exit;
        }
		return array();
    }*/
	
	/**
     * Check master access
     * return bool
     */
    function checkAccess(Request $request, $type = "") {
		$key = $this->_entity_session_identifier."logged";
		$return = $request->session()->get($key);
		if($return && $type == "master") {
			$auth = \Session::get($this->_entity_session_identifier."auth");
			//$return = ($auth->admin_group_id == 1 || $auth->admin_group_id == 2) ? TRUE : FALSE;
			$return = TRUE;
		}
		return $return ? $return : FALSE;
    }
	
	/**
     * checkAuth
     * return redirect
     */
    function checkAuth(Request $request, $type = "") {
		// check basic login
		if ($this->checkAccess($request, "") === FALSE) {
			// check cookie login
			$this->checkCookieAuth();
			
            \Session::put($this->_entity_session_identifier.'error_msg', 'Please login to continue...');
			\Session::put($this->_entity_session_identifier.'redirect_url', \URL::current());
            $redirect_url = \URL::to($this->_entity_dir.'login/');
			// save session
			\Session::save();
			header("location:" . $redirect_url);
			exit;
        }
		// get auth record
		//$record = $this->get(\Session::get($this->_entity_session_identifier."auth")->{$this->primaryKey});
		$record = \Session::get($this->_entity_session_identifier."auth");
		// check master auth
		if ($type == "master" && $this->checkAccess($request, $type) === FALSE) {
            \Session::put($this->_entity_session_identifier.'error_msg', 'You donot have access to restricted area');
            $redirect_url = \URL::to($this->_entity_dir.'dashboard/');
			// save session
			\Session::save();
			header("location:" . $redirect_url);
			exit;
        }
		
		// check for inactive account
		#$record->status
		if($record->isActive <> 1) {
			// set msg
			$msg = $record->isActive == 0 ? "Your account is inactive. Please contact Administrator" :
				"Cannot process further. Your account is baned";
			// error msg
			\Session::put($this->_entity_session_identifier.'error_msg', $msg);
			// forget session
			\Session::forget($this->_entity_session_identifier."logged");
			\Session::forget($this->_entity_session_identifier."auth");
			$redirect_url = \URL::to($this->_entity_dir.'login/');
			// save session
			\Session::save();
			header("location:" . $redirect_url);
			exit;
		}
    }
	
	/**
     * redirectLogged
     * return redirect
     */
    function redirectLogged() {
		if(\Session::has($this->_entity_session_identifier."auth")) {
			// get logged record
			//$record = $this->get(\Session::get($this->_entity_session_identifier."auth")->{$this->primaryKey});
			$record = \Session::get($this->_entity_session_identifier."auth")->{$this->primaryKey};
			if($record) {
				// redirect to entity home
				header("location:" . \URL::to($this->_entity_dir));
				exit;
			}
		}
    }
	
	
	/**
     * setLoginSession
	 * @param $data
     * return redirect_url
     */
    function setLoginSession($data) {
		if($data) {
			// set in session
			\Session::put($this->_entity_session_identifier."logged", TRUE);
			//\Session::put($this->_entity_session_identifier."auth", $this->getData($data->{$this->primaryKey}));
			\Session::put($this->_entity_session_identifier."auth", $data);
			// save session
			\Session::save();
		}
		// get redirection url
		$redirect_url = \Session::has($this->_entity_session_identifier."redirect_url") ? \Session::get($this->_entity_session_identifier."redirect_url") : \URL::to($this->_entity_dir);
		return $redirect_url;
    }
	
	/**
     * Check master authentication
     * return redirect
     */
    function logout() {
		// get logged data
		$data = \Session::get($this->_entity_session_identifier."auth");
		// forget session
		\Session::put($this->_entity_session_identifier.'success_msg', "Successfully logged out");
		\Session::forget($this->_entity_session_identifier."logged");
		\Session::forget($this->_entity_session_identifier."auth");
		$redirect_url = \URL::to($this->_entity_dir.'login/');
		// save session
		\Session::save();
		// forget cookie
		$this->removeRememberToken($data);
		// redirect
		header("location:" . $redirect_url);
		exit;
    }
	
	/**
     * setRememberToken
	 * @param object $data
     * return string remember_login_token
     */
    function setRememberToken($data) {
		$remember_login_token = $this->saltCookie($data);
		// set cookie
		setcookie($this->_entity_session_identifier."remember_login_token", $remember_login_token, time() + (REMEMBER_COOKIE_TIME), "/");
		// return token
		return $remember_login_token;
	}
	
	/**
     * removeRememberToken
	 * @param object $data
     * return void
     */
    function removeRememberToken($data) {
		if($data) {
			$record = $this->get($data->{$this->primaryKey});
			$record->remember_login_token = NULL;
			$record->updatedAt = date("Y-m-d H:i:s");
			// reset token
			$this->set($record->{$this->primaryKey},(array)$record);
			// forget cookie
			setcookie($this->_entity_session_identifier."remember_login_token", FALSE, time() - (REMEMBER_COOKIE_TIME), "/");
		}
	}
	
	/**
     * checkCookieAuth
	 * @param $data
     * return redirect_url
     */
    function checkCookieAuth() {
		// get cookie
		$remember_login_token = isset($_COOKIE[$this->_entity_session_identifier."remember_login_token"]) ? $_COOKIE[$this->_entity_session_identifier."remember_login_token"] : FALSE;
		if($remember_login_token && !\Session::has($this->_entity_session_identifier."logged")) {
			$data = $this->getBy("remember_login_token", $remember_login_token);
			if($data) {
				// remove session error/succcess msgs
				\Session::forget($this->_entity_session_identifier.'success_msg');
				\Session::forget($this->_entity_session_identifier.'error_msg');
				
				$redirect_url = $this->setLoginSession($data);
				// redirect user
				header("location:" . $redirect_url);
				exit;
			}
		}
    }
	
	/**
     * saltPassword
     * 
     * @return string
     */
    function saltPassword($password = "") {
		return $this->_entity_salt_pattren . md5(md5($this->_entity_salt_pattren . sha1($this->_entity_salt_pattren . $password)));
    }
	
	/**
     * saltCookie
     * @param object $data
     * @return string
     */
    function saltCookie($data, $get_column_query = 0) {
		// prepare hash
		$cookie_value = $this->_entity_salt_pattren;
		$cookie_value .= "-".$data->{$this->primaryKey}; // add pk
		$cookie_value .= "-".sha1($data->email); // add email
		// encode
		$cookie_value = $this->_entity_salt_pattren.md5($cookie_value);
		return $cookie_value;
    }
	
	
	/**
     * checkLogin
     * 
     * @return object
     */
	function checkLogin($email = "", $password = "") {
        $enc_password = $this->saltPassword($password);
        // fetch
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		
		$array = array(
			"u_email" => $email,
			"u_password" => $enc_password,
		);
		$cursor = $mongo->$db->tm_users->find($array);
		$rows = iterator_to_array($cursor);
		if($rows > 0){
			foreach($rows as $row){
				
			}
		}
		
		return isset($row) ? $row : FALSE;
        //$row = $this->where('email', '=', $email)
			//->where('password', '=', $enc_password)
            //->get(array($this->__fields[0]));

        //return isset($row[0]) ? $this->get($row[0]->{$this->__fields[0]}) : FALSE;
    }
	
	
	/**
     * Generate new
     *
     * @return Admin ID
     */
    function generateNew($data) {
		$data = (object)$data;
        // init models
        $conf_model = new Conf;
        $setting_model = new Setting;
        $email_template_model = new EmailTemplate;

        // configuration
        $conf = $conf_model->getBy('key', 'site');
        $conf = json_decode($conf->value);

		$data->created_at = date('Y-m-d H:i:s');

        // temporary password
		$temp_password = $data->password;
		// saltify & assign password
		$data->password = $this->saltPassword($data->password);
        $data->status = 1;
		// insert admin record
        $id = $this->put((array)$data);

		// send email to new admin
		# admin email
		$setting = $setting_model->getBy('key', 'admin_email');
		$data->from = $setting->value;
		# admin email name
		$setting = $setting_model->getBy('key', 'admin_email_name');
		$data->from_name = $setting->value;
		
		# load email template
		$email_template = $email_template_model->getBy('key', $this->_entity_identifier.'_new_account');
		$wildcard['key'] = explode(',', $email_template->wildcards);
		$wildcard['replace'] = array(
			$conf->site_name, // APP_NAME
			\URL::to($this->_entity_dir), // APP_LINK
			$data->username, // USER_NAME
			$data->email, // USER_EMAIL
			$temp_password, // USER_PASSWORD
		);
		$body = str_replace($wildcard['key'], $wildcard['replace'], $email_template->body);
		# subject
		$data->subject = str_replace($wildcard['key'], $wildcard['replace'], $email_template->subject);
		# send email
		$this->sendMail(
			array($data->email, $data->username),
			$body,
			(array)$data
		);
        // return new id
        return $id;
    }
	
	
	/**
     * Forgot Password
     * @param object $data
     * @return object $data
     */
    function forgotPassword($data) {
        // init models
        $conf_model = new Conf;
        $setting_model = new Setting;
        $email_template_model = new EmailTemplate;

        // configuration
        $conf = $conf_model->getBy('key', 'site');
        $conf = json_decode($conf->value);
		
		// get code
		$code = str_random(FORGOT_PASS_HASH_LENGTH);
		$data->forgot_password_hash = $code;
		$data->forgot_password_hash_created_at = date("Y-m-d H:i:s");
		$this->set($data->{$this->primaryKey}, (array)$data);

		// send email to new admin
		# admin email
		$setting = $setting_model->getBy('key', 'admin_email');
		$data->from = $setting->value;
		# admin email name
		$setting = $setting_model->getBy('key', 'admin_email_name');
		$data->from_name = $setting->value;
		# load email template
		$email_template = $email_template_model->getBy('key', $this->_entity_identifier.'_forgot_password_confirmation');
		# prepare wildcards
		$wildcard['key'] = explode(',', $email_template->wildcards);
		$wildcard['replace'] = array(
			$conf->site_name, // APP_NAME
			\URL::to($this->_entity_dir), // APP_LINK
			$data->username, // USER_NAME
			\URL::to($this->_entity_dir."confirm_forgot/?code=".$code."&email=".$data->email), // CONFIRMATION_LINK
		);
		# subject
		$data->subject = str_replace($wildcard['key'], $wildcard['replace'], $email_template->subject);
		# body
		$body = str_replace($wildcard['key'], $wildcard['replace'], $email_template->body);
		# send email
		$this->sendMail(
			array($data->email, $data->username),
			$body,
			(array)$data
		);
		
		return $data;

    }
	
	/**
     * Recover Password Success
     * @param object $data
     * @return object $data
     */
    function recoverPasswordSuccess($data, $new_password) {
        // init models
        $conf_model = new Conf;
        $setting_model = new Setting;
        $email_template_model = new EmailTemplate;

        // configuration
        $conf = $conf_model->getBy('key', 'site');
        $conf = json_decode($conf->value);
		
		// reset code
		$data->forgot_password_hash = "";
		$data->forgot_password_hash_created_at = NULL;
		// assign new password
		$data->password = $this->saltPassword($new_password);
		$this->set($data->{$this->primaryKey}, (array)$data);

		// send email to new admin
		# admin email
		$setting = $setting_model->getBy('key', 'admin_email');
		$data->from = $setting->value;
		# admin email name
		$setting = $setting_model->getBy('key', 'admin_email_name');
		$data->from_name = $setting->value;
		# load email template
		$email_template = $email_template_model->getBy('key', $this->_entity_identifier.'_recover_password_success');
		# prepare wildcards
		$wildcard['key'] = explode(',', $email_template->wildcards);
		$wildcard['replace'] = array(
			$conf->site_name, // APP_NAME
			\URL::to($this->_entity_dir), // APP_LINK
			\URL::to($this->_entity_dir."forgot_password"), //ADMIN_FORGOT_LINK
			$data->username, // USER_NAME
			$data->email, // USER_EMAIL
			$new_password, // USER_PASSWORD
		);
		# subject
		$data->subject = str_replace($wildcard['key'], $wildcard['replace'], $email_template->subject);
		# body
		$body = str_replace($wildcard['key'], $wildcard['replace'], $email_template->body);
		# send email
		$this->sendMail(
			array($data->email, $data->username),
			$body,
			(array)$data
		);
		
		return $data;

    }
	
	/**
     * Change Password Self
     * @param object $data
     * @return object $data
     */
    function changePasswordSelf($data) {
		$data = (object)$data;
        // init models
        $conf_model = new Conf;
        $setting_model = new Setting;
        $email_template_model = new EmailTemplate;

        // configuration
        $conf = $conf_model->getBy('key', 'site');
        $conf = json_decode($conf->value);

		$data->updatedAt = date('Y-m-d H:i:s');

        // temporary password
		$temp_password = $data->password;
		// saltify & assign password
		$data->password = $this->saltPassword($data->password);
		
		// update admin record
        $this->set($data->{$this->primaryKey},(array)$data);
		$id = $data->{$this->primaryKey};

		// send email to new admin
		# admin email
		$setting = $setting_model->getBy('key', 'admin_email');
		$data->from = $setting->value;
		# admin email name
		$setting = $setting_model->getBy('key', 'admin_email_name');
		$data->from_name = $setting->value;
		
		# load email template
		$email_template = $email_template_model->getBy('key', $this->_entity_identifier.'_password_change_self');
		$wildcard['key'] = explode(',', $email_template->wildcards);
		$wildcard['replace'] = array(
			$conf->site_name, // APP_NAME
			\URL::to($this->_entity_dir), // APP_LINK
			$data->username, // USER_NAME
			$data->email, // USER_EMAIL
			$temp_password, // USER_PASSWORD
		);
		$body = str_replace($wildcard['key'], $wildcard['replace'], $email_template->body);
		# subject
		$data->subject = str_replace($wildcard['key'], $wildcard['replace'], $email_template->subject);
		# send email
		$this->sendMail(
			array($data->email, $data->username),
			$body,
			(array)$data
		);
        // return new id
        return $id;
    
	}
	
	//GetListing
	public function ajaxListing($search_array){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->stm_userRole->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		return $paginated_ids;
	}
	
	//GetUpdateRecord
	public function getUpdateRecord($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
			"_id" => new \MongoId($id),
			"deletedAt" => "");
		$cursor = $mongo->$db->stm_userRole->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	//AddRecord
	function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$mongo->$db->stm_userRole->insert($save);
		$newDocID = $save['_id'];
		return $newDocID;
	}
	
	//UpdateRecord
	function _updateRecord($id, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$newdata = array('$set' => $save);
		$mongo->$db->stm_userRole->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	}
	
	function getActiveListing(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
			'isActive' => '1',
			'deletedAt' => "");
		$cursor = $mongo->$db->stm_userRole->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		return $paginated_ids;
	}
	
	//DeleteRecord
	function _deleteRecord($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$mongo->$db->stm_userRole->remove(array('_id' => new \MongoId($id)));
		// set pk
		return $id;
	}
}
