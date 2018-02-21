<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Session;
use Validator;
use Image;
// load models
use App\Http\Models\ApiMethod;
use App\Http\Models\ApiMethodField;
use App\Http\Models\ApiUser;
use App\Http\Models\EFEntityPlugin;

class {wildcard_ucword}Controller extends Controller {

    private $_assignData = array(
        'pDir' => '',
        'dir' => DIR_API
    );
    private $_apiData = array();
    private $_layout = "";
    private $_models = array();
    private $_jsonData = array();
	private $_model_path = "\App\Http\Models\\";	
	private $_entity_identifier = "{wildcard_identifier}";
	private $_entity_pk = "{wildcard_pk}";
	private $_entity_ucfirst = "{wildcard_ucword}";
	private $_entity_id = "{base_entity_id}";
	private $_plugin_identifier = "{plugin_identifier}";
	private $_plugin_config = array();
	

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
		// load entity model
		// get all webservices data
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;		
		$this->_entity_model = $this->_model_path.$this->_entity_ucfirst;
		$this->_entity_model = new $this->_entity_model;
		
        // init models
        $this->__models['api_method_model'] = new ApiMethod;
        $this->__models['api_user_model'] = new ApiUser;
		$this->__models[$this->_entity_identifier.'_model'] = $this->_entity_model;

        // error response by default
        $this->_apiData['kick_user'] = 0;
        $this->_apiData['response'] = "error";

        // check access
        $this->__models['api_user_model']->check_access();
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_plugin_config = isset($this->_plugin_config->webservices) ? $this->_plugin_config->webservices : array();
		$this->_plugin_config["webservices"] = $this->_plugin_config;
		
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        
    }
	
	/**
     * MoibleLogin
     *
     * @return Response
     */
    public function mobileLogin(Request $request) {
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'device_udid' => 'required|alpha_num',
			'device_type' => 'required|in:android,ios'
		));
		
		// get user existance
		$raw_exists = $this->__models[$this->_entity_identifier.'_model']->select("user_id")
			->where("device_type","=",$request->device_type)
			->where("device_udid","=",$request->device_udid)
			->whereNull("deleted_at")
			->get();
		$exists_id = isset($raw_exists[0]) ? $raw_exists[0]->user_id : 0;
		
		// get data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($exists_id);

        // validations		
		if(!in_array("user/mobile_login", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			

            // create account if not exists
            if ($entity === FALSE) {
				
				// set data
				$entity['device_udid'] = $request->device_udid;
				$entity['device_type'] = $request->device_type;
				$entity['status'] = 1;		
				// create user
				$entity_id = $this->__models[$this->_entity_identifier.'_model']->signupViaDevice($entity);
				// unset user array
				unset($entity);
				// get user data
            	$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
				
            }

            // update last login as current timestamp
            $entity->last_login_at = date('Y-m-d H:i:s');
			// device data
            if ($request->device_token != "" && $request->device_type != "") {
				$entity->device_type = $request->device_type;
                $entity->device_token = $request->device_token;
                // remove this device token from other user ids
                $this->__models[$this->_entity_identifier.'_model']->replaceToken($entity->{$this->_entity_pk}, $entity->device_token);
            }
			
			// update user data
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);

            
            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }

	
	/**
     * CustomLogin
     *
     * @return Response
     */
    public function customLogin(Request $request) {
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'email' => 'required',
			'password' => 'required',
			'device_type' => 'required|in:android,ios'
		));
		
		// get user existance
		//$raw_exists = $this->__models[$this->_entity_identifier.'_model']->check_login($request->email, $request->password);
		//$exists_id = isset($raw_exists) ? $raw_exists : 0;		
		// get data
		//$entity = $this->__models[$this->_entity_identifier.'_model']->get($exists_id);
		$entity = $this->__models[$this->_entity_identifier.'_model']->checkLogin($request->email, $request->password);
		
        // validations		
		if(!in_array("user/custom_login", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } elseif($entity === FALSE){
			$this->_apiData['message'] = 'Invalid login credentials';
		} elseif($entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();

            // update last login as current timestamp
            $entity->last_login_at = date('Y-m-d H:i:s');
			// device data
            if ($request->device_token != "" && $request->device_type != "") {
				$entity->device_type = $request->device_type;
                $entity->device_token = $request->device_token;
                // remove this device token from other user ids
                $this->__models[$this->_entity_identifier.'_model']->replaceToken($entity->{$this->_entity_pk}, $entity->device_token);
            }
			
			// update user data
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);
            
            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * User Custom Register
     *
     * @return Response
     */
    public function register(Request $request) {
        // get params
       	//$type = trim(strip_tags($request->input('platform_type', '')));
        //$type = in_array($type, array("facebook", "twitter", "gplus","custom","device")) ? $type : ""; // set default value
		
		$device_type = trim(strip_tags($request->input('device_type', '')));
        $device_type = in_array($device_type, array("android", "ios")) ? $device_type : ""; // set default value
        $device_token = trim(strip_tags($request->input('device_token', '')));
        $name = trim(strip_tags($request->input('name', '')));
		$first_name = trim(strip_tags($request->input('first_name', '')));
		$last_name = trim(strip_tags($request->input('last_name', '')));
        $gender = trim(strip_tags($request->input('gender', '')));
		$gender = in_array($gender, array("male", "female")) ? $gender : "male"; // set default value
		$dob = trim(strip_tags($request->input('dob', '')));
		$dob = $dob == "" ? NULL : date("Y-m-d",strtotime($dob)); //set default value
		$email = trim(str_replace(" ","",strip_tags($request->input('email', ''))));
		$password = trim(str_replace(" ","",strip_tags($request->input('password', ''))));
		//$image_url = trim(strip_tags($request->input('image_url', '')));
		//$thumb_url = trim(strip_tags($request->input('thumb_url', '')));
		//$location = trim(strip_tags($request->input('location', '')));
		
        
        // validations
        $valid_email = Validator::make(array('email' => $email), array('email' => 'email'));
        $row_type_exists = $this->__models[$this->_entity_identifier.'_model']
			->where('email', '=', $email)			
			->get(array("user_id"));
		
		$exists_id = isset($row_type_exists[0]) ? $row_type_exists[0]->user_id : 0;
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($exists_id);		
		
        // validations		
		if(!in_array("user/register", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} /*else if ($type == "") {
             $this->_apiData['message'] = 'Please provide Platform Type';
        } else if ($device_type == "") {
            $this->_apiData['message'] = 'Please provide Device Type';
        }*/ else if ($name == '') {
            $this->_apiData['message'] = 'Please enter Name';
        // }else if ($first_name == '') 
		// {
            // $this->_apiData['message'] = 'Please enter First Name';
        // } else if ($last_name == '') {
            // $this->_apiData['message'] = 'Please enter Last Name';
        // } else if ($entity === FALSE && $dob == "") {
            // $this->_apiData['message'] = "Please provide Date of Birth";
        // } else if ($email == "") {
            // $this->_apiData['message'] = "Please enter Email";
        // } else if ($valid_email->fails()) {
            // $this->_apiData['message'] = 'Please enter valid Email';
        // 
		} else if ($email == '') {
            $this->_apiData['message'] = 'Please enter Email';
		} else if ($password == '') {
            $this->_apiData['message'] = 'Please enter password';
		} else if($entity !== FALSE ){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Email address is already exists.';
		}else {
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			

            // create account if not exists
            if ($entity === FALSE) {
				// get defaults
				$age = $this->__models[$this->_entity_identifier.'_model']->getAge($dob);
				
				// set data
				$entity['name'] = $name;
				$entity['first_name'] = $first_name;
				$entity['last_name'] = $last_name;
				$entity['gender'] = $gender;
				$entity['email'] = $email;
				$entity['password'] = $password;
				//$entity['platform_type'] = $type;
				//$entity['platform_id'] = $uid;
				$entity['dob'] = $dob;
				$entity['status'] = 1;	
				//$entity['location'] = $location;
				
				// create user
				$entity_id = $this->__models[$this->_entity_identifier.'_model']->createCustom($entity);
				// unset user array
				unset($entity);
				// get user data
            	$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
				
				// save user image
				// - take from photo_urls
				/*if($image_url != "") {
					$img_data = @file_get_contents($image_url);
					
					$entity_img = "dp-" . $entity->{$this->_entity_pk} . ".jpg";
                    $create_success = @file_put_contents(getcwd() . "/" . DIR_USER_IMG . $entity_img, $img_data);

                    // if dp created successfully, create thumbnail
                    if ($create_success) {
                        $entity_thumb = "thumb-" . $entity->{$this->_entity_pk} . ".jpg";
                        $thumb_data = file_get_contents(url("/") . "/" . "thumb/user/150x150/" . $entity_img);
                        @file_put_contents(getcwd() . "/" . DIR_USER_IMG . $entity_thumb, $thumb_data);
						
						// assign new values
						$entity->image = $entity_img;
						$entity->thumb = $entity_thumb;
                    }
					
				}*/
				
            }

            // update last login as current timestamp
            $entity->name = $name;
			$entity->first_name = $first_name;
			$entity->last_name = $last_name;
			$entity->email = $email;
            $entity->last_login_at = date('Y-m-d H:i:s');
			// device data
            if ($device_token != "" && $device_type != "") {
                $entity->device_type = $device_type;
                $entity->device_token = $device_token;
                // remove this device token from other user ids
                $this->__models[$this->_entity_identifier.'_model']->replaceToken($entity->{$this->_entity_pk}, $entity->device_token);
            }
			// if user came back, activate account
			if($entity->deleted_at !== NULL) {
				$entity->deleted_at = NULL;
				// set current location to true
				$entity->use_current_location = 1;
			}

            // update user data
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);

            
            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	
	/**
     * User Social Login
     *
     * @return Response
     */
    public function socialLogin(Request $request) {
        // get params
        $type = trim(strip_tags($request->input('platform_type', '')));
        $type = in_array($type, array("facebook", "twitter", "gplus")) ? $type : ""; // set default value
		$uid = trim(strip_tags($request->input('platform_id', 0)));
        $uid = $uid == "" ? 0 : $uid; // set default value
		$device_type = trim(strip_tags($request->input('device_type', '')));
        $device_type = in_array($device_type, array("android", "ios")) ? $device_type : ""; // set default value
        $device_token = trim(strip_tags($request->input('device_token', '')));
        $name = trim(strip_tags($request->input('name', '')));
		$first_name = trim(strip_tags($request->input('first_name', '')));
		$last_name = trim(strip_tags($request->input('last_name', '')));
        $gender = trim(strip_tags($request->input('gender', '')));
		$gender = in_array($gender, array("male", "female")) ? $gender : "male"; // set default value
		$dob = trim(strip_tags($request->input('dob', '')));
		$dob = $dob == "" ? "0000-00-00" : date("Y-m-d",strtotime($dob)); //set default value
		$email = trim(str_replace(" ","",strip_tags($request->input('email', ''))));
		$image_url = trim(strip_tags($request->input('image_url', '')));
		//$thumb_url = trim(strip_tags($request->input('thumb_url', '')));
		//$location = trim(strip_tags($request->input('location', '')));
		
        
        // validations
        $valid_email = Validator::make(array('email' => $email), array('email' => 'email'));
        $row_type_exists = $this->__models[$this->_entity_identifier.'_model']
			->where('platform_type', '=', $type)
			->where('platform_id', '=', $uid)
			->get(array("user_id"));
		
		$exists_id = isset($row_type_exists[0]) ? $row_type_exists[0]->user_id : 0;
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($exists_id);		
		
       // validations		
		if(!in_array("user/social_login", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($type == "") {
             $this->_apiData['message'] = 'Please provide Platform Type';
        } else if ($uid == 0) {
            $this->_apiData['message'] = 'Please provide Platform ID';
        } else if ($device_type == "") {
            $this->_apiData['message'] = 'Please provide Device Type';
        } else if ($name == '') {
            $this->_apiData['message'] = 'Please enter Name';
        // }else if ($first_name == '') 
		// {
            // $this->_apiData['message'] = 'Please enter First Name';
        // } else if ($last_name == '') {
            // $this->_apiData['message'] = 'Please enter Last Name';
        // } else if ($entity === FALSE && $dob == "") {
            // $this->_apiData['message'] = "Please provide Date of Birth";
        // } else if ($email == "") {
            // $this->_apiData['message'] = "Please enter Email";
        // } else if ($valid_email->fails()) {
            // $this->_apiData['message'] = 'Please enter valid Email';
        // 
		} else if($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			

            // create account if not exists
            if ($entity === FALSE) {
				// get defaults
				$age = $this->__models[$this->_entity_identifier.'_model']->getAge($dob);
				
				// set data
				$entity['name'] = $name;
				$entity['first_name'] = $first_name;
				$entity['last_name'] = $last_name;
				$entity['gender'] = $gender;
				$entity['email'] = $email;
				$entity['platform_type'] = $type;
				$entity['platform_id'] = $uid;
				$entity['dob'] = $dob;
				$entity['status'] = 1;	
				//$entity['location'] = $location;
				
				// create user
				$entity_id = $this->__models[$this->_entity_identifier.'_model']->createSocialUser($entity);
				// unset user array
				unset($entity);
				// get user data
            	$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
				
				// save user image
				// - take from photo_urls
				/*if($image_url != "") {
					$img_data = @file_get_contents($image_url);
					
					$entity_img = "dp-" . $entity->{$this->_entity_pk} . ".jpg";
                    $create_success = @file_put_contents(getcwd() . "/" . DIR_USER_IMG . $entity_img, $img_data);

                    // if dp created successfully, create thumbnail
                    if ($create_success) {
                        $entity_thumb = "thumb-" . $entity->{$this->_entity_pk} . ".jpg";
                        $thumb_data = file_get_contents(url("/") . "/" . "thumb/user/150x150/" . $entity_img);
                        @file_put_contents(getcwd() . "/" . DIR_USER_IMG . $entity_thumb, $thumb_data);
						
						// assign new values
						$entity->image = $entity_img;
						$entity->thumb = $entity_thumb;
                    }
					
				}*/
				
            }

            // update last login as current timestamp
            $entity->name = $name;
			$entity->first_name = $first_name;
			$entity->last_name = $last_name;
			if($email != "") {
				$entity->email = $email;
			}
            $entity->last_login_at = date('Y-m-d H:i:s');
			// device data
            if ($device_type != "") {
                $entity->device_type = $device_type;
				if ($device_token != "") {
					$entity->device_token = $device_token;
					// remove this device token from other user ids
					$this->__models[$this->_entity_identifier.'_model']->replaceToken($entity->{$this->_entity_pk}, $entity->device_token);
				}
            }
			
			// if user came back, activate account
			if($entity->deleted_at !== NULL) {
				$entity->deleted_at = NULL;
			}

            // update user data
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);

            
            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * is_social_registered
     *
     * @return Response
     */
    public function isSocialRegistered(Request $request) {
        // get params
        $type = trim(strip_tags($request->input('platform_type', '')));
        $type = in_array($type, array("facebook", "twitter", "gplus")) ? $type : ""; // set default value
        $uid = trim(strip_tags($request->input('platform_id', '')));
        $uid = $uid == "" ? '' : $uid; // set default value
        $device_type = trim(strip_tags($request->input('device_type', '')));
        $device_type = in_array($device_type, array("android", "ios")) ? $device_type : ""; // set default value
        $device_token = trim(strip_tags($request->input('device_token', '')));

        $row_type_exists = $this->__models[$this->_entity_identifier.'_model']
                ->where('platform_type', '=', $type)
                ->where('platform_id', '=', $uid)
				->whereNull('deleted_at')
                ->get(array("user_id")
        );
		
        $entity_id = isset($row_type_exists[0]) ? $row_type_exists[0]->user_id : 0;

        // get user data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		// validations		
		if(!in_array("user/is_social_registered", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($type == "") {
            $this->_apiData['message'] = 'Please provide Social Login Type';
        } else if ($uid == "") {
            $this->_apiData['message'] = 'Please provide UID';
        } else if ($device_type == "") {
            $this->_apiData['message'] = 'Please provide Device Type';
        } else if ($entity === FALSE) {
            $this->_apiData['message'] = 'User does not exist';
        } elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
            // success response
            $this->_apiData['response'] = "success";
			// init output data array
            $this->_apiData['data'] = $data = array();

            // update last login as current timestamp
            $entity->last_login_at = date('Y-m-d H:i:s');
			
            // device data
            if ($device_type != "") {
                $entity->device_type = $device_type;
				if ($device_token != "") {
					$entity->device_token = $device_token;
					// remove this device token from other user ids
					$this->__models[$this->_entity_identifier.'_model']->replaceToken($entity->{$this->_entity_pk}, $entity->device_token);
				}
            }
			// set
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);

            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	/**
     * User data
     *
     * @return Response
     */
    public function get(Request $request) {
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
        // get params
        $entity_id = intval($request->{$this->_entity_pk});
        $device_type = $request->device_type;
        $device_type = in_array($device_type, array("android", "ios")) ? $device_type : "android"; // set default value
        $device_token = $request->device_token;

        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		// validations		
		if(!in_array("user/get", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($entity_id == 0) {
            $this->_apiData['message'] = 'Please enter '.$this->_entity_ucfirst.' ID';
        } else if ($entity === FALSE) {
            $this->_apiData['message'] = $this->_entity_ucfirst.' does not exist';
        } elseif($entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
            // init models
            //$this->__models['predefined_model'] = new Predefined;

            // success response
            $this->_apiData['response'] = "success";
			// init output data array
            $this->_apiData['data'] = $data = array();
			
			$this->_apiData['message'] = "Success";

            // device data
            if ($device_type != "") {
                $entity->device_type = $device_type;
				if ($device_token != "") {
					$entity->device_token = $device_token;
					// remove this device token from other user ids
					$this->__models[$this->_entity_identifier.'_model']->replaceToken($entity->{$this->_entity_pk}, $entity->device_token);
				}
            }
			

            // get user data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	

	/**
     * Logout
     *
     * @return Response
     */
    public function logout() {
        // get params
        $entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));

        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);

        if ($entity === FALSE) {
            $this->_apiData['message'] = "Invalid account request";
        } else {

            // success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();

            // set data
            $entity->device_token = "";

            // update user data
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);

            // set message
            $this->_apiData['message'] = "Token successfully cleared";
            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Profile
     *
     * @return Response
     */
    public function profile() {
        // get params
        $entity_id = trim(strip_tags($request->input($this->_entity_id, 0)));
        $entity_id = $entity_id == "" ? 0 : $entity_id; // set default value
		$target_user_id = intval(trim(strip_tags($request->input('target_user_id', 0))));
        $device_type = trim(strip_tags($request->input('device_type', '')));
        $device_type = in_array($device_type, array("android", "ios")) ? $device_type : "android"; // set default value
        $device_token = trim(strip_tags($request->input('device_token', '')));

        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		$target_user = $this->__models[$this->_entity_identifier.'_model']->get($target_user_id);
		$profile = $this->__models[$this->_entity_identifier.'_model']->getData($target_user->user_id,$entity->{$this->_entity_pk});
		

        if ($entity_id == 0) {
            $this->_apiData['message'] = 'Please enter User ID';
        } else if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($target_user === FALSE) {
            $this->_apiData['message'] = 'Invalid profile user request';
        } else if ($target_user->deleted_at !== NULL) {
            $this->_apiData['message'] = 'Target profile removed';
        } elseif($entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else if ($profile->is_user_blocked > 0) {
            $this->_apiData['message'] = 'Sorry ! You cannot access this profile';
        } else {
            // init models
            //$this->__models['predefined_model'] = new Predefined;

            // success response
            $this->_apiData['response'] = "success";
			// init output data array
            $this->_apiData['data'] = $data = array();

            // get user data
            //$data['profile'] = $this->__models[$this->_entity_identifier.'_model']->getData($target_user->user_id,$entity->{$this->_entity_pk});
			$data['profile'] = $profile;
			
			// message
			$this->_apiData['message'] = "Success";
			
            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Update Notification Settings
     *
     * @return Response
     */
    public function updateNotificationSettings() {
		// init models
		//$this->__models["preference_model"] = new Preference;
		
        // get params
        $entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
		$keys = trim(strip_tags($request->input('keys', "")));
		$switches = trim(strip_tags($request->input('switches', '')));
		

        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		

        if ($entity === FALSE) {
            $this->_apiData['message'] = "Invalid user request";
        } elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else if ($keys == "") {
            $this->_apiData['message'] = "Please provide keys";
        } else if ($switches == "") {
            $this->_apiData['message'] = "Please provide switches values";
        } else {
			// init models
			$this->__models["preference_model"] = new Preference;
			$this->__models["user_preference_model"] = new UserPreference;

            // success response
            $this->_apiData['response'] = "success";
			// init output data array
            $this->_apiData['data'] = $data = array();
			
			$keys_data = explode(",",$keys);
			$switches_data = explode(",",$switches);
			
			if(isset($keys_data[0])) {
				$i=0; // itrator for other data
				foreach($keys_data as $key) {
					$key_data = $this->__models["preference_model"]->getBy("key",$key);
					// if valid key
					if($key_data !== FALSE) {
						// get user preference record
						$raw_existance = $this->__models['user_preference_model']
							->where('preference_id', '=', $key_data->preference_id)
							->where($this->_entity_id, '=', $entity->{$this->_entity_pk})
							->get(array("user_preference_id"));
						$record_id = isset($raw_existance[0]) ? $raw_existance[0]->user_preference_id : 0;
						$record = $this->__models['user_preference_model']->get($record_id);
						// if record exists
						if($record !== FALSE) {
							$key_value = isset($switches_data[$i]) ? intval($switches_data[$i]) : 1;
							$record->value = $key_value;
							// update
							$this->__models['user_preference_model']->set($record_id, (array)$record);
						}
					}
					// increment itrator
					$i++;
				}
			}
			
			// output data
			$data["user"] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
            // set message
            $this->_apiData['message'] = "Settings successfully updated";
            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Update Profile
     *
     * @return Response
     */
    public function updateProfile() {
        // get params
		$entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
        $gender = trim(strip_tags($request->input('gender', '')));
		$gender = in_array($gender, array("male", "female")) ? $gender : ""; // default value
		$about_me = trim(strip_tags($request->input('about_me', '')));
		//$looking_for = trim(strip_tags($request->input('looking_for', '')));
		//$looking_for = in_array($looking_for, array("friendship", "dating")) ? $looking_for : ""; // default value
		$works = trim(strip_tags($request->input('works', '')));
		$selected_works = trim(strip_tags($request->input('selected_works', '')));
		$educations = trim(strip_tags($request->input('educations', '')));
		$selected_educations = trim(strip_tags($request->input('selected_educations', '')));
		$disabilities_ids = trim(strip_tags($request->input('disabilities_ids', '')));
		$show_disability = intval(trim(strip_tags($request->input('show_disability', 0))));
		$show_disability = $show_disability > 1 ? 1 : $show_disability; // default value
		$dob = trim(strip_tags($request->input('dob', '')));
		$interest_raw_data = trim(strip_tags($request->input('interest_raw_data', '')));
		$get_general_data = intval(trim(strip_tags($request->input('get_general_data', 0))));
		$get_general_data = $get_general_data > 1 ? 1 : $get_general_data; // default value
		$update_disability = intval(trim(strip_tags($request->input('update_disability', 0))));
		$update_disability = $update_disability > 1 ? 1 : $update_disability; // default value
		
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);		
		
        // validations
        if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } /*else if ($disabilities_ids == '') {
            $this->_apiData['message'] = 'Please provide disabilities';
        }*/ elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else if ($works != "" && $selected_works == "") {
            $this->_apiData['message'] = "Please provide selected works";
        } else if ($educations != "" && $selected_educations == "") {
            $this->_apiData['message'] = "Please provide selected educations";
        } else {
			// init models
			$this->__models['user_disability_model'] = new UserDisability;
			$this->__models['user_background_model'] = new UserBackground;
			
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();

            // update last login as current timestamp
            $entity->about_me = $about_me;
			//$entity->looking_for = $looking_for == "" ? $entity->looking_for : $looking_for;
			$entity->gender = $gender == "" ? $entity->gender : $gender;
			$entity->show_disability = $show_disability;
			$entity->updated_at = date('Y-m-d H:i:s');
			if($dob != "") {
				$dob = date("Y-m-d",strtotime($dob));
				$age = $this->__models[$this->_entity_identifier.'_model']->getAge($dob);
				$entity->dob = $dob;
				$entity->age = $age;
			}
			if($interest_raw_data != "") {
				$entity->interest_raw_data = $interest_raw_data;
			}
			
			
			// update user data
            $this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array) $entity);
			
			// other updates
			// - work
			if($works != "") {
				// flush old
				$this->__models['user_background_model']->removeUserBackground($entity_id,"work");
				// save user works (background)
				$this->__models['user_background_model']->putUserBackground($entity_id, "work", $works, $selected_works);
			}
			// - education
			if($educations != "") {
				// flush old
				$this->__models['user_background_model']->removeUserBackground($entity_id,"education");
				// save user works (background)
				$this->__models['user_background_model']->putUserBackground($entity_id, "education", $educations, $selected_educations);
			}
			// - disabilities
			if($update_disability == 1) {
				// flush old
				$this->__models['user_disability_model']->removeUserDisabilities($entity_id);
				if($disabilities_ids != "") {
					// flush old
					//$this->__models['user_disability_model']->removeUserDisabilities($entity_id);
					// save user works (background)
					$this->__models['user_disability_model']->putUserDisabilities($entity_id,$disabilities_ids);
				}
			}

            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Successfully updated profile";
			
			// get extra data
			if($get_general_data > 0) {
				// init models
				$this->__models['disability_model'] = new Disability;
				$this->__models['package_model'] = new Package;
				
				// disabilities
				$query = $this->__models['disability_model']->select(array("disability_id"));
				$query->orderBy("name", "ASC");
				$query->whereNull("deleted_at");
				$raw_records = $query->get();
				
				if(isset($raw_records[0])) {
					foreach($raw_records as $raw_record) {
						$record = $this->__models['disability_model']->getData($raw_record->disability_id);
						$data['diabilities'][] = $record;
					}
				}
				
				// packages
				$query = $this->__models['package_model']->select(array("package_id"));
				$query->orderBy("package_id", "ASC");
				$raw_records = $query->get();
				
				if(isset($raw_records[0])) {
					foreach($raw_records as $raw_record) {
						$record = $this->__models['package_model']->getData($raw_record->package_id);
						$data['packages'][] = $record;
					}
				}
				
			}
			

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Update Social Info
     *
     * @return Response
     */
    public function updateSocialInfo() {
        // get params
		$entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
        $network = trim(strip_tags($request->input('network', '')));
		$network = in_array($network, array("instagram", "facebook", "twitter")) ? $network : "instagram"; // default value
		$uid = trim(strip_tags($request->input('uid', '')));
		$access_token = trim(strip_tags($request->input('access_token', '')));
		$profile_url = trim(strip_tags($request->input('profile_url', '')));
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);		
		
        // validations
        if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($uid == '') {
            $this->_apiData['message'] = 'Please provide UID';
        }/* else if ($access_token == '') {
            $this->_apiData['message'] = 'Please Access Token';
        }*/ elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// init models
			$this->__models['user_social_info_model'] = new UserSocialInfo;
			
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// check existance
			$row_type_exists = $this->__models['user_social_info_model']
				->where($this->_entity_id, '=', $entity_id)
				->where('network', '=', $network)
				->get(array("user_social_info_id"));
			
			$exists_id = isset($row_type_exists[0]) ? $row_type_exists[0]->user_social_info_id : 0;
			
			$entity_social_info = $this->__models['user_social_info_model']->get($exists_id);
			
			// prepare array shape
			$entity_social_info = $entity_social_info !== FALSE ? (array)$entity_social_info : array();
			
			
			$entity_social_info["user_id"] = $entity_id;
			$entity_social_info["network"] = $network;
			$entity_social_info["uid"] = $uid;
			$entity_social_info["access_token"] = $access_token;
			$entity_social_info["profile_url"] = $profile_url;
			
			if(isset($entity_social_info["user_social_info_id"])) {
				$entity_social_info["updated_at"] = date('Y-m-d H:i:s');
				$entity_social_info["deleted_at"] = NULL;
				// update
				$this->__models['user_social_info_model']->set($entity_social_info["user_social_info_id"], (array)$entity_social_info);
			} else {
				$entity_social_info["created_at"] = date('Y-m-d H:i:s');
				// insert
				$this->__models['user_social_info_model']->put($entity_social_info);
			}

            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = 'Successfully updated info';

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Upgrade
     *
     * @return Response
     */
    public function upgrade() {
		// init models
		$this->__models["package_model"] = new Package;
		
        // get params
		$entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
        $package_id = intval(trim(strip_tags($request->input('package_id', ''))));
		
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		$entity_data = $this->__models[$this->_entity_identifier.'_model']->getData($entity_id);
		$package = $this->__models['package_model']->get($package_id);
		
        // validations
        if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($package === FALSE) {
            $this->_apiData['message'] = 'Invalid package request';
        } else if ($package->package_id == 1) {
            $this->_apiData['message'] = 'The free package is by default for every user';
        } else if ($entity_data->user_package["package_id"] == $package->package_id) {
            $this->_apiData['message'] = 'You are already subscribed to this package';
        } elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// init models
			$this->__models['user_package_model'] = new UserPackage;
			
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// insert
			$this->__models['user_package_model']->putUserPackage($entity->{$this->_entity_pk}, $package->{"key"});
			
            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "User successfully upgraded";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Block
     *
     * @return Response
     */
    public function block() {
        // get params
        $entity_id = trim(strip_tags($request->input($this->_entity_id, 0)));
        $entity_id = $entity_id == "" ? 0 : $entity_id; // set default value
		$target_user_id = intval(trim(strip_tags($request->input('target_user_id', 0))));

        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		$target_user = $this->__models[$this->_entity_identifier.'_model']->get($target_user_id);
		$entity_data = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk},$target_user->user_id);
		$target_user_data = $this->__models[$this->_entity_identifier.'_model']->getData($target_user->user_id,$entity->{$this->_entity_pk});

        if ($entity_id == 0) {
            $this->_apiData['message'] = 'Please enter User ID';
        } else if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($target_user === FALSE) {
            $this->_apiData['message'] = 'Invalid profile user request';
        } elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else if ($entity_data->is_user_blocked > 0) {
            $this->_apiData['message'] = 'You have already blocked this user';
        } else if ($target_user_data->is_user_blocked > 0) {
            $this->_apiData['message'] = 'User has already blocked you';
        } else {
            // init models
            $this->__models['user_block_model'] = new UserBlock;

            // success response
            $this->_apiData['response'] = "success";
			
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// save
			$save["user_id"] = $entity->{$this->_entity_pk};
			$save["target_user_id"] = $target_user->user_id;
			$save["created_at"] = date('Y-m-d H:i:s');
			$this->__models['user_block_model']->put($save);

			// message
			$this->_apiData['message'] = "User successfully blocked";

            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	
	/**

     * Save Facebook friends
     *
     * @return Response
     */
    public function saveFbFriends() {
        // get params
		$entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
		$uids = trim(strip_tags($request->input('uids', '')));
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		
		// validations
        if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($uids == '') {
            $this->_apiData['message'] = 'Please provide uids';
        }  elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// init models
			$this->__models['user_social_friend_model'] = new UserSocialFriend;
			
			
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// get user friends
			//$entity_social_friends = $this->__models['user_social_friend_model']->getFriends($entity_id,"facebook");
			
			// if user dont have photo, then save - otherwise ignore
			// let mobile developer take care of this
			//if(isset($entity_social_friends[0])) {
				//$uids_data = preg_match("@,@",$uids) ? explode(",",$uids) : array();
				$uids_data = explode(",",$uids);
				
				if(isset($uids_data[0])) {
					// flush old
					$this->__models['user_social_friend_model']->removeFriends($entity_id, "facebook");
					
					// save user photos (background)
					$this->__models['user_social_friend_model']->putFriends($entity_id,"facebook", $uids);
				}
			//}
			
			

            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Delete user
     *
     * @return Response
     */
    public function delete() {
        // get params
		$entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		
		// validations
        if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } elseif($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// init models
			$this->__models['user_social_friend_model'] = new UserSocialFriend;
			
			
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// put deleted date
			//$entity->deleted_at = date("Y-m-d H:i:s");
			//$this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk},(array)$entity);
			// remove user account and related tasks
			$this->__models[$this->_entity_identifier.'_model']->remove($entity->{$this->_entity_pk});

            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Report
     *
     * @return Response
     */
    public function report() {
		// init models
		$this->__models['user_report_model'] = new UserReport;
		
		// get params
        $entity_id = trim(strip_tags($request->input($this->_entity_id, 0)));
        $entity_id = $entity_id == "" ? 0 : $entity_id; // set default value
		$target_user_id = intval(trim(strip_tags($request->input('target_user_id', 0))));

        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		$target_user = $this->__models[$this->_entity_identifier.'_model']->get($target_user_id);
		$check = $this->__models['user_report_model']->check($entity_id, $target_user_id, 1);
		
        if ($entity_id == 0) {
            $this->_apiData['message'] = 'Please enter User ID';
        } else if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($target_user === FALSE) {
            $this->_apiData['message'] = 'Invalid profile user request';
        } elseif($entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else if ($check > 0) {
            $this->_apiData['message'] = 'You have already reported this user. Please wait for administrator\'s review';
        } else {
            // success response
            $this->_apiData['response'] = "success";
			
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// save
			$save["user_id"] = $entity->{$this->_entity_pk};
			$save["target_user_id"] = $target_user->user_id;
			$save["status"] = 1;
			$save["created_at"] = date('Y-m-d H:i:s');
			$this->__models['user_report_model']->put($save);

			// message
			$this->_apiData['message'] = "User successfully reported, we will soon review and take take appropriate action";

            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * removePackage
     *
     * @return Response
     */
    public function removePackage() {
		// init models
		$this->__models["package_model"] = new Package;
		$this->__models['user_package_model'] = new UserPackage;
		
        // get params
		$entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
        $package_id = intval(trim(strip_tags($request->input('package_id', ''))));
		
		
		// get user data
		$entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		$entity_data = $this->__models[$this->_entity_identifier.'_model']->getData($entity_id);
		$package = $this->__models['package_model']->get($package_id);
		$record_id = $this->__models['user_package_model']->check($entity_id,$package_id, 0);
		
        // validations
        if ($entity === FALSE) {
            $this->_apiData['message'] = 'Invalid user request';
        } else if ($package === FALSE) {
            $this->_apiData['message'] = 'Invalid package request';
        } else if ($package->package_id == 1) {
            $this->_apiData['message'] = 'Cannot remove free package';
        } else if ($record_id == 0) {
            $this->_apiData['message'] = 'No record found for this package';
        } elseif($entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
			// init models
			//$this->__models['user_package_model'] = new UserPackage;
			
			// success response
            $this->_apiData['response'] = "success";
            // init output data array
            $this->_apiData['data'] = $data = array();
			
			// update
			$entity_package = $this->__models['user_package_model']->get($record_id);
			
			
			$this->__models['user_package_model']->removePackage($entity->{$this->_entity_pk}, $package->package_id);
			/*if($entity_package !== FALSE) {
				$entity_package->is_expired = 1;
				$entity_package->deleted_at = date("Y-m-d H:i:s");
				$entity_package->valid_until = date("Y-m-d H:i:s");
				$this->__models['user_package_model']->set($entity_package->user_package_id,(array)$entity_package);
			}*/
			
            // response data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "User package successfully removed";

            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Randomize
     *
     * @return Response
     */
    public function randomize() {
		// param validations
		$validator = Validator::make(Input::all(), array(
			$this->_entity_id => 'required|exists:user,user_id'
		));
		
		// get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($request->input($this->_entity_id, 0));
		
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if (preg_match("@temp_@",$entity->email)) {
            $this->_apiData['message'] = "already randomized";
        } else if($entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} elseif($entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		} else {
            // init models
            //$this->__models['predefined_model'] = new Predefined;

            // success response
            $this->_apiData['response'] = "success";
			// init output data array
            $this->_apiData['data'] = $data = array();
			
			$this->_apiData['message'] = "Success";

			$entity->name = $entity->name. " ".time();
			$entity->email = str_replace("@","temp_".time()."@",$entity->email);
			$entity->uid = $entity->uid."-".time();
			// update user data
			$this->__models[$this->_entity_identifier.'_model']->set($entity->{$this->_entity_pk}, (array)$entity);
			

            // get user data
            $data['user'] = $this->__models[$this->_entity_identifier.'_model']->getData($entity->{$this->_entity_pk});
			
			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Update Device info
     *
     * @return Response
     */
    public function updateDeviceInfo() {
		// trim/escape all
		Input::merge(array_map('strip_tags', Input::all()));
		Input::merge(array_map('trim', Input::all()));
		
        // get params
        $entity_id = intval(trim(strip_tags($request->input($this->_entity_id, 0))));
		$type = trim(strip_tags($request->input('type', '')));

        /*$type = in_array($type, array("android", "ios")) ? $type : "android"; // set default value
		$name = trim(strip_tags($request->input("name", "")));
		$model = trim(strip_tags($request->input("model", "")));
		$os = trim(strip_tags($request->input("os", "")));
		$os_version = trim(strip_tags($request->input("os_version", "")));
		$app_version = trim(strip_tags($request->input("app_version", "")));*/
		$build_id = trim(strip_tags($request->input("build_id", "")));
		
		// param validations
		$validator = Validator::make(Input::all(), array(
			$this->_entity_id => 'required|exists:user,user_id',
			'type' => 'required|in:android,ios',
			'brand' => 'required',
			'model' => 'required',
			'manufacturer' => 'required',
			'os_version' => 'required',
			'api_level' => 'required',
			//'build_id' => 'required', // only for android
			'app_version' => 'required'
		));
		
        // get data
        $entity = $this->__models[$this->_entity_identifier.'_model']->get($entity_id);
		
		if ($entity === FALSE) {
            $this->_apiData['message'] = "Invalid user request";
        } /*else if($entity !== FALSE && $entity->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		} else if($entity !== FALSE && $entity->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}*/ else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if ($type == "android" && $build_id == "") {
            $this->_apiData['message'] = "Please provide Build ID";
        } else {
			// init models
			$this->__models["user_device_model"] = new UserDevice;
			
			// success response
            $this->_apiData['response'] = "success";
			// init output data array
            $this->_apiData['data'] = $data = array();
			
			// get user device data
			$entity_device = $this->__models["user_device_model"]->getBy("user_id",$entity_id);
			$save = $entity_device ? (array)$entity_device : array();
			// set data
			$save["user_id"] = $request->input($this->_entity_id, 0);
			$save["type"] = $request->input('type', "");
			$save["brand"] = $request->input('brand', "");
			$save["model"] = $request->input('model', "");
			$save["manufacturer"] = $request->input('manufacturer', "");
			$save["os_version"] = $request->input('os_version', "");
			$save["api_level"] = $request->input('api_level', "");
			$save["build_id"] = $request->input('build_id', "");
			$save["app_version"] = $request->input('app_version', "");
			// insert / update
			if($entity_device) {
				$save["updated_at"] = date("Y-m-d H:i:s");
				$this->__models["user_device_model"]->set($save["user_device_id"], $save);
			} else {
				$save["created_at"] = date("Y-m-d H:i:s");
				$this->__models["user_device_model"]->insert($save);
			}
			
			
			// set message
            $this->_apiData['message'] = "Device info successfully updated";
            // assign to output
            $this->_apiData['data'] = $data;
        }


        return $this->__api_response($this->_apiData);
    }
	
	

}
