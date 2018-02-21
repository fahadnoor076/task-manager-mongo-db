<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use View;
use Input;
use Session;
use Validator;
use Carbon\Carbon;
// load models
/*use App\Http\Models\User as UserModel;
use App\Http\Models\Friend;
use App\Http\Models\MediaVote;
use App\Http\Models\Media;
use App\Http\Models\UserFollow;
use App\Http\Models\UserDpReport;
use App\Http\Models\UserUpgrade;*/
use App\Http\Models\Admin;


class Index extends Controller {

	private $_assignData = array(
		'pDir' => '',
		'dir' => DIR_API
	);
	private $_apiData = array();	private $_layout = "";
	// entity vars
	private $_entity_session_identifier = ADMIN_SESS_KEY;
	private $_models = array();
	private $_jsonData = array();

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// init models
		$this->__models['admin_model'] = new Admin;
		
		// error response by default
		$this->_apiData['error'] = 1;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		
	}
	
	/**
	 * User Login
	 *
	 * @return Response
	*/
	public function login()
	{
		// get params
		$email = trim(strip_tags(Input::get('email', '')));
		$password = trim(strip_tags(Input::get('password', '')));
		
		// validations
		$valid_email = Validator::make(array('email' => $email), array('email' => 'email'));
		$valid_login = $this->__models['admin_model']->check_login($email,$password);
		
		// get user data
		$admin = $this->__models['admin_model']->get($valid_login);
		
		// validations
		if($email == "") {
			$this->_apiData['message'] = "Please enter Email";
		}
		else if($valid_email->fails()) {
			$this->_apiData['message'] = 'Please enter valid Email';
		}
		else if($valid_login === 0) {
			$this->_apiData['message'] = 'Invalid Login. Please try again';
		}
		else if($password == "") {
			$this->_apiData['message'] = "Please enter Password";
		}
		else if($admin === FALSE) {
			$this->_apiData['message'] = "Invalid Login. Please try again";
		}
		elseif($admin->status == 0){
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($admin->status > 1){
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else {
			// success response
			$this->_apiData['error'] = 0;
			// init output data array
			$this->_apiData['data'] = array();
			
			// update last login as current timestamp
			$admin->last_login = strtotime("now");
			
			// update user data
			$this->__models['admin_model']->set($admin->admin_id,(array)$admin);
			
			// unset unrequired
			unset($admin->password,$admin->hash);
			
			// response data
			$data['admin'] = $admin;
			
			// assign to output
			$this->_apiData['data'] = $data;			
		}
		
		return $this->_apiData;
	}
	
	/**
	 * User Register
	 *
	 * @return Response
	*/
	public function register()
	{
		// init models
		//$this->__models['api_admin_model'] = new ApiUser;
		
		// check access
		//$this->__models['api_admin_model']->check_access();
		
		// get params
		$first_name = trim(strip_tags(Input::get('first_name', '')));
		$last_name = trim(strip_tags(Input::get('last_name', '')));
		$email = trim(strip_tags(Input::get('email', '')));
		$type = trim(strip_tags(Input::get('type', '')));
		$type = in_array($type, array("email","facebook","twitter","gplus")) ? $type : "email"; // set default value
		$image = trim(strip_tags(Input::get('image', '')));
		$device_type = trim(strip_tags(Input::get('device_type', '')));
		$device_type = in_array($device_type, array("android","ios")) ? $device_type : "android"; // set default value
		$device_token = trim(strip_tags(Input::get('device_token', '')));
		$longitude = trim(strip_tags(Input::get('longitude', 0)));
		$longitude = $longitude == "" ? 0 : $longitude; // set default value
		$latitude = trim(strip_tags(Input::get('latitude', 0)));
		$latitude = $latitude == "" ? 0 : $latitude; // set default value
		$uid = trim(strip_tags(Input::get('uid', 0)));
		$uid = $uid == "" ? 0 : $uid; // set default value
		$password = trim(strip_tags(Input::get('password', '')));
		$c_password = trim(strip_tags(Input::get('c_password', '')));
		
		// validations
		$valid_email = Validator::make(array('email' => $email), array('email' => 'email'));
		//$exists_email = Validator::make(array('email' => $email), array('email' => 'unique:user'));
		//$valid_login = $this->__models['admin_model']->check_login($email,$password);
		$row_type_exists = $this->__models['admin_model']
			->where('type', '=', $type)
			->where('email', '=', $email)
			->get(array("user_id")
		);
		$exists_type = isset($row_type_exists[0]) ? TRUE : FALSE;
		$valid_image = base64_decode($image, true);
		
		if($first_name == '') {
			$this->_apiData['message'] = 'Please enter First Name';
		}
		else if($last_name == '') {
			$this->_apiData['message'] = 'Please enter Last Name';
		}
		else if($email == '') {
			$this->_apiData['message'] = 'Please enter Email';
		}
		else if($valid_email->fails()) {
			$this->_apiData['message'] = 'Please enter valid Email';
		}
		else if($exists_type) {
			$this->_apiData['message'] = 'Account type already exists. Please try Login';
		}
		else if($type == 'email' && $password == "") {
			$this->_apiData['message'] = 'Please enter Password';
		}
		else if($password == "") {
			$this->_apiData['message'] = 'Please enter password';
		}
		else if(strlen($password) < 8) {
			$this->_apiData['message'] = 'Password must be contain alteast 8 characters';
		}
		/*else if($type == 'email' && $exists_email->fails()) {
			$this->_apiData['message'] = 'Email already exists, Please try other Email';
		}
		else if($password != $c_password) {
			$this->_apiData['message'] = 'Confirm Password did not match';
		}
		else if($type == 'email' && $valid_login === 0) {
			$this->_apiData['message'] = 'Invalid Login. Please try again';
		}
		else if($image == "") {
			$this->_apiData['message'] = 'Please provide Image';
		}*/
		else if($image != "" && !$valid_image) {
			$this->_apiData['message'] = 'Invalid Image data. Please provide valid Base64 encoded image';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = array();
			
			// collect data for new account
			$user['first_name'] = $first_name;
			$user['last_name'] = $last_name;
			$user['email'] = $email;
			$user['password'] = $password;
			$user['type'] = $type;
			$user['image'] = "";
			$user['uid'] = $uid;
			$user['device_type'] = $device_type;
			$user['device_token'] = $device_token;
			$user['longitude'] = $longitude;
			$user['latitude'] = $latitude;
			
			// insert record, send email
			$user_id = $this->__models['admin_model']->create_user($user);
			
			// save image
			$user_img = "dp-".$user_id.".jpg";
			if($image == "") {
				@copy(DIR_USER_IMG."default.png",DIR_USER_IMG.$user_img);
			} else {
				@file_put_contents(DIR_USER_IMG.$user_img,base64_decode($image));
			}
			// get user data
			unset($user);
			$user = $this->__models['admin_model']->get($user_id);
			$user->image = $user_img;
			// update
			$this->__models['admin_model']->set($user->user_id,(array)$user);
			
			// set remote img path
			$user->image = url("/")."/".DIR_USER_IMG.$user->image;
			
			// unset unrequired
			unset($user->password,$user->temp_password,$user->signup_hash,$user->forgot_hash);
			
			// response data
			$data['user'] = $user;
			//$data['message'] = "Your account has been created. You will receive a confirmation email in a while";
			$data['message'] = "Successfully registered";
			
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
	public function get()
	{
		// get params
		$user_id = trim(strip_tags(Input::get('user_id', 0)));
		$user_id = $user_id == "" ? 0 : $user_id; // set default value
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		
		if($user_id == 0) {
			$this->_apiData['message'] = 'Please enter User ID';
		}
		else if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid User Request';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// kick user
			$this->_apiData['kick_user'] = $user->status == 3 ? 1 : 0;
			
			// init output data array
			$this->_apiData['data'] = array();
			
			// set remote img path
			$user->image = $user->image == "" ? "default.png" : $user->image;
			$user->image = preg_match("@^http@",$user->image) ? $user->image : url("/")."/".DIR_USER_IMG.$user->image;
			
			// unset unrequired
			unset($user->password,$user->temp_password,$user->signup_hash,$user->forgot_hash);
			
			// sort keys ascendingly to find easily
			$user = (array)$user;
			ksort($user);
			// back to original
			$user = (object)$user;
			
			// response data
			$data['user'] = $user;
			
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
	public function social_login()
	{
		// get params
		$first_name = trim(strip_tags(Input::get('first_name', '')));
		$last_name = trim(strip_tags(Input::get('last_name', '')));
		$email = trim(strip_tags(Input::get('email', '')));
		$type = trim(strip_tags(Input::get('type', '')));
		$type = in_array($type, array("facebook","twitter","gplus")) ? $type : ""; // set default value
		$uid = trim(strip_tags(Input::get('uid', 0)));
		$uid = $uid == "" ? 0 : $uid; // set default value
		$image_url = trim(strip_tags(Input::get('image_url', '')));
		$device_type = trim(strip_tags(Input::get('device_type', '')));
		$device_type = in_array($device_type, array("android","ios")) ? $device_type : ""; // set default value
		$device_token = trim(strip_tags(Input::get('device_token', '')));
		$longitude = trim(strip_tags(Input::get('longitude', 0)));
		$longitude = $longitude == "" ? 0 : $longitude; // set default value
		$latitude = trim(strip_tags(Input::get('latitude', 0)));
		$latitude = $latitude == "" ? 0 : $latitude; // set default value
		
		// validations
		$valid_email = Validator::make(array('email' => $email), array('email' => 'email'));
		/*$row_type_exists = $this->__models['admin_model']
			->where('type', '=', $type)
			->where('email', '=', $email)
			->get(array("user_id")
		);
		$exists_type = isset($row_type_exists[0]) ? $row_type_exists[0]->user_id : 0;
		
		// get user data
		$user = $this->__models['admin_model']->get($exists_type);
		*/
		// validations
		if($first_name == '') {
			$this->_apiData['message'] = 'Please enter First Name';
		}
		else if($last_name == '') {
			$this->_apiData['message'] = 'Please enter Last Name';
		}
		else if($email == "") {
			$this->_apiData['message'] = "Please enter Email";
		}
		else if($valid_email->fails()) {
			$this->_apiData['message'] = 'Please enter valid Email';
		}
		else if($type == "") {
			$this->_apiData['message'] = 'Please provide Login Type';
		}
		else if($uid == 0) {
			$this->_apiData['message'] = 'Please provide UID';
		}
		/*else if($image_url == "") {
			$this->_apiData['message'] = 'Please provide Social Image URL';
		}
		else if($user === FALSE) {
			$this->_apiData['message'] = "Invalid Login. Please try again";
		}
		elseif($user->status == 0){
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}*/
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = array();
			
			$row_type_exists = $this->__models['admin_model']
				->where('type', '=', $type)
				->where('email', '=', $email)
				->get(array("user_id")
			);
			$user_id = isset($row_type_exists[0]) ? $row_type_exists[0]->user_id : 0;
			// get user data
			//$user = $this->__models['admin_model']->get($user_id);
			
			// create account if not exists
			if($user_id === 0) {
				$user['email'] = $email;
				$user['type'] = $type;
				$user['image'] = $image_url == "" ? "default.png" : $image_url;
				$user['uid'] = $uid;
				// active user
				$user['status'] = 1;
				// apply package settings
				$user = (array)$this->__models['admin_model']->apply_package((object)$user,1); // set free package
				
				$user_id = $this->__models['admin_model']->put($user);
				
				// unset record array
				unset($user);
			}
			
			// get user data
			$user = $this->__models['admin_model']->get($user_id);
			
			// update last login as current timestamp
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->uid = $uid;
			$user->image = $image_url;
			$user->last_login_datetime = time();
			$user->device_type = $device_type == "" ? $user->device_type : $device_type;
			$user->device_token = $device_token == "" ? $user->device_token : $device_token;
			$user->longitude = $longitude == 0 ? $user->longitude : $longitude;
			$user->longitude = $latitude == 0 ? $user->latitude : $latitude;
			
			// update user data
			$this->__models['admin_model']->set($user->user_id,(array)$user);
			
			// set remote img path
			$user->image = $user->image == "" ? "default.png" : $user->image;
			$user->image = preg_match("@^http@",$user->image) ? $user->image : url("/")."/".DIR_USER_IMG.$user->image;
			
			// unset unrequired
			unset($user->password,$user->temp_password,$user->signup_hash,$user->forgot_hash);
			
			// response data
			$data['user'] = $user;
			
			// assign to output
			$this->_apiData['data'] = $data;			
		}
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Forgot Password
	 *
	 * @return Response
	*/
	public function forgot_password()
	{
		// get params
		$email = trim(strip_tags(Input::get('email', '')));
		$type = "email"; // set default value
		
		// validations
		$valid_email = Validator::make(array('email' => $email), array('email' => 'email'));
		
		// fetch data
		$row_exists = $this->__models['admin_model']
			->where('email', '=', $email)
			->where('type', '=', $type)
			->get(array("user_id")
		);
		$user_id = isset($row_exists[0]) ? $row_exists[0]->user_id : 0;
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		
		if($email == '') {
			$this->_apiData['message'] = 'Please enter Email';
		}
		else if($valid_email->fails()) {
			$this->_apiData['message'] = 'Please enter valid Email';
		}
		else if($user === FALSE) {
			$this->_apiData['message'] = "Invalid account request";
		}
		elseif($user->status == 0){
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';

		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = array();
			
			// generate forgot password request
			$this->__models['admin_model']->forgot_password_request($user);
			
			// response data
			$data['message'] = "Please check your email for password retrieval instructions";
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Edit
	 *
	 * @return Response
	*/
	public function edit()
	{
		// get params
		$user_id = (int)trim(strip_tags(Input::get('user_id', 0)));
		$first_name = trim(strip_tags(Input::get('first_name', '')));
		$last_name = trim(strip_tags(Input::get('last_name', '')));
		$image = trim(strip_tags(Input::get('image', '')));
		$valid_image = base64_decode($image, true);
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = "Invalid account request";
		}
		elseif($user->status == 0){
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($first_name == '') {
			$this->_apiData['message'] = 'Please enter First Name';
		}
		else if($last_name == '') {
			$this->_apiData['message'] = 'Please enter Last Name';
		}
		else if($image != "" && !$valid_image) {
			$this->_apiData['message'] = 'Invalid Image data. Please provide valid Base64 encoded image';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = array();
			
			// set data
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			
			if($image != "") {
				// save image
				$user_img = "dp-".$user_id.".jpg";
				@file_put_contents(DIR_USER_IMG.$user_img,base64_decode($image));
				$user->image = $user_img;
			}
			
			// update user data
			$this->__models['admin_model']->set($user->user_id,(array)$user);
			
			// set remote img path
			$user->image = $user->image == "" ? "default.png" : $user->image;
			$user->image = preg_match("@^http@",$user->image) ? $user->image : url("/")."/".DIR_USER_IMG.$user->image;
			
			// unset unrequired
			unset($user->password,$user->temp_password,$user->signup_hash,$user->forgot_hash);
			
			// response data
			$data['user'] = $user;
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Change Password
	 *
	 * @return Response
	*/
	public function change_password()
	{
		// get params
		$user_id = (int)trim(strip_tags(Input::get('user_id', 0)));
		$password = trim(strip_tags(Input::get('password', '')));
		$c_password = trim(strip_tags(Input::get('c_password', '')));
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = "Invalid account request";
		}
		elseif($user->status == 0){
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($password == '') {
			$this->_apiData['message'] = 'Please enter Password';
		}
		else if($n_password == '') {
			$this->_apiData['message'] = 'Please enter new Password';
		}
		else if($user->password != $this->__models['admin_model']->salt_password($password)) {
			$this->_apiData['message'] = 'Invalid old Password';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = array();
			
			// set data
			$user->password = $this->__models['admin_model']->salt_password($n_password);
			
			// update user data
			$this->__models['admin_model']->set($user->user_id,(array)$user);
			
			// set remote img path
			$user->image = $user->image == "" ? "default.png" : $user->image;
			$user->image = preg_match("@^http@",$user->image) ? $user->image : url("/")."/".DIR_USER_IMG.$user->image;
			
			// unset unrequired
			unset($user->password,$user->temp_password,$user->signup_hash,$user->forgot_hash);
			
			// response data
			$data['user'] = $user;
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * User Profile
	 *
	 * @return Response
	*/
	public function profile()
	{
		// get params
		$user_id = trim(strip_tags(Input::get('user_id', 0)));
		$user_id = $user_id == "" ? 0 : $user_id; // set default value
		$target_user_id = trim(strip_tags(Input::get('target_user_id', 0)));
		$target_user_id = $target_user_id == "" ? 0 : $target_user_id; // set default value
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		$target_user = $this->__models['admin_model']->get($target_user_id);
		
		if($user_id == 0) {
			$this->_apiData['message'] = 'Please enter User ID';
		}
		else if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid User Request';
		}
		elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($target_user_id == 0) {
			$this->_apiData['message'] = 'Please enter Target User ID';
		}
		else if($target_user === FALSE) {
			$this->_apiData['message'] = 'Invalid Target User Request';
		}
		elseif($target_user->status != 1){
			$this->_apiData['message'] = 'Target user profile is not available';
		}
		else {
			// init models
			$this->__models['media_vote_model'] = new MediaVote;
			$this->__models['media_model'] = new Media;
			$this->__models['user_view_model'] = new UserView;
			
			// success response
			$this->_apiData['response'] = "success";
			// kick user
			$this->_apiData['kick_user'] = $user->status == 3 ? 1 : 0;
			
			// init output data array
			$this->_apiData['data'] = array();
			
			// fetch record
			$query = $this->__models['admin_model']->make_query($target_user_id, $user_id);
			$raw_records = $query->get();
			if(isset($raw_records[0])) {
				$raw_record = $raw_records[0];
				$target_user->user_id = $raw_record->user_id;
				$target_user->count_following = $raw_record->count_following;
				$target_user->count_being_followed = $raw_record->count_being_followed;
				$target_user->count_viewing = $raw_record->count_viewing;
				$target_user->count_being_viewed = $raw_record->count_being_viewed;
				$target_user->count_friend = $raw_record->count_friend;
				$target_user->count_image = $raw_record->count_image;
				$target_user->count_video = $raw_record->count_video;
				$target_user->is_following = $raw_record->user_count_follow > 0 ?  1 : 0;
				$target_user->is_friend = $raw_record->user_count_friend > 0 ?  1 : 0;
				$target_user->friend_request_status = 0;
				// friendship status
				if($target_user->is_friend > 0) {
					// init models
					$this->__models['friend_model'] = new Friend;
					
					$record_id = $this->__models['friend_model']->check($user_id, $target_user_id);
					$friend = $this->__models['friend_model']->get($record_id);
					$target_user->friend_request_status = $friend->status;
				}
				
				// remove raw
				unset($target_user->user_count_follow,$target_user->user_count_friend);
			}
			
			// set remote img path
			$target_user->image = $target_user->image == "" ? "default.png" : $target_user->image;
			$target_user->image = preg_match("@^http@",$target_user->image) ? $target_user->image : url("/")."/".DIR_USER_IMG.$target_user->image;
			
			// unset unrequired
			unset($target_user->password,$target_user->temp_password,$target_user->signup_hash,$target_user->forgot_hash,$target_user->device_type,$target_user->device_token,$target_user->email,$target_user->is_notify_request);
			
			// get recently voted media. max : 3
			// - init
			$target_user->recently_voted_media = array();
			// - find
			$query = $this->__models['media_vote_model']->distinct()->select("media_id");
			$query->where('user_id', '=', $target_user->user_id);
			$query->where('status', '=', 1);
			$query->take(3);
			$query->orderBy("datetime", "DESC");
			$raw_records = $query->get();
			// - set
			if(isset($raw_records[0])) {
				foreach($raw_records as $raw_record) {
					$record = $this->__models['media_model']->get($raw_record->media_id);
					$recently_voted["media_id"] = $record->media_id;
					$recently_voted["type"] = $record->type;
					// set remote data path
					$recently_voted["image"] = url("/")."/".DIR_MEDIA.$record->image;
					$recently_voted["video"] = $record->video == "" ? "" : url("/")."/".DIR_MEDIA.$record->video;
					
					// sort keys ascendingly to find easily
					ksort($recently_voted);
					
					$target_user->recently_voted_media[] = $recently_voted;
				}
			}
			
			// sort keys ascendingly to find easily
			$target_user = (array)$target_user;
			ksort($target_user);
			// back to original
			$target_user = (object)$target_user;
			
			// response data
			$data['user'] = $target_user;
			
			// record user profile view
			$user_view["user_id"] = $user_id;
			$user_view["target_user_id"] = $target_user_id;
			$user_view["datetime"] = strtotime("now");
			$this->__models['user_view_model']->put($user_view);
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Follow
	 *
	 * @return Response
	*/
	public function follow()
	{
		// init models
		$this->__models['user_follow_model'] = new UserFollow;
		
		// get params
		$user_id = trim(strip_tags(Input::get('user_id', 0)));
		$user_id = $user_id == "" ? 0 : $user_id; // set default value
		$target_user_id = trim(strip_tags(Input::get('target_user_id', 0)));
		$target_user_id = $target_user_id == "" ? 0 : $target_user_id; // set default value
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		$target_user = $this->__models['admin_model']->get($target_user_id);
		$record_id = $this->__models['user_follow_model']->check($user_id, $target_user_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid user Request';
		}
		elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($target_user_id == 0) {
			$this->_apiData['message'] = 'Please enter Target User ID';
		}
		else if($target_user === FALSE) {
			$this->_apiData['message'] = 'Invalid Target User Request';
		}
		elseif($target_user->status != 1){
			$this->_apiData['message'] = 'Target user profile is not available';
		}
		else if($record_id != 0) {
			$this->_apiData['message'] = 'Sorry, you are already following this user';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = $data = array();
			
			// save
			$save["user_id"] = $user->user_id;
			$save["target_user_id"] = $target_user->user_id;
			$save["datetime"] = strtotime("now");
			
			$this->__models['user_follow_model']->put($save);
			
			// success message
			$this->_apiData['message'] = "User successfully following";
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Unfollow
	 *
	 * @return Response
	*/
	public function unfollow()
	{
		// init models
		$this->__models['user_follow_model'] = new UserFollow;
		
		// get params
		$user_id = trim(strip_tags(Input::get('user_id', 0)));
		$user_id = $user_id == "" ? 0 : $user_id; // set default value
		$target_user_id = trim(strip_tags(Input::get('target_user_id', 0)));
		$target_user_id = $target_user_id == "" ? 0 : $target_user_id; // set default value
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		$target_user = $this->__models['admin_model']->get($target_user_id);
		$record_id = $this->__models['user_follow_model']->check($user_id, $target_user_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid user Request';
		}
		elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($target_user_id == 0) {
			$this->_apiData['message'] = 'Please enter Target User ID';
		}
		else if($target_user === FALSE) {
			$this->_apiData['message'] = 'Invalid Target User Request';
		}
		elseif($target_user->status != 1){
			$this->_apiData['message'] = 'Target user profile is not available';
		}
		else if($record_id == 0) {
			$this->_apiData['message'] = 'Sorry, you are not following this user';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = $data = array();
			
			// save
			$save["user_id"] = $user->user_id;
			$save["target_user_id"] = $target_user->user_id;
			$save["datetime"] = strtotime("now");
			
			$this->__models['user_follow_model']->remove($record_id);
			
			// success message
			$this->_apiData['message'] = "User successfully un-followed";
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Report DP
	 *
	 * @return Response
	*/
	public function reportdp()
	{
		// init models
		$this->__models['user_dp_report_model'] = new UserDpReport;
		
		// get params
		$user_id = trim(strip_tags(Input::get('user_id', 0)));
		$user_id = $user_id == "" ? 0 : $user_id; // set default value
		$target_user_id = trim(strip_tags(Input::get('target_user_id', 0)));
		$target_user_id = $target_user_id == "" ? 0 : $target_user_id; // set default value
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		$target_user = $this->__models['admin_model']->get($target_user_id);
		$record_id = $this->__models['user_dp_report_model']->check($user_id, $target_user_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid user Request';
		}
		elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($target_user_id == 0) {
			$this->_apiData['message'] = 'Please enter Target User ID';
		}
		else if($target_user === FALSE) {
			$this->_apiData['message'] = 'Invalid Target User Request';
		}
		elseif($target_user->status != 1){
			$this->_apiData['message'] = 'Target user profile is not available';
		}
		else if($report_id != 0) {
			$this->_apiData['message'] = 'Sorry, you have already reported this user image';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = $data = array();
			
			// save
			$save["user_id"] = $user->user_id;
			$save["target_user_id"] = $target_user->target_user_id;
			$save["status"] = 1;
			$save["date"] = strtotime("today");
			$save["datetime"] = strtotime("now");
			
			$this->__models['user_dp_report_model']->put($save);
			
			// success message
			$this->_apiData['message'] = "Media successfully reported";
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
	
	
	/**
	 * Upgrade User
	 *
	 * @return Response
	*/
	public function upgrade()
	{
		// init models
		$this->__models['user_upgrade_model'] = new UserUpgrade;
		
		// get params
		$user_id = trim(strip_tags(Input::get('user_id', 0)));
		$user_id = $user_id == "" ? 0 : $user_id; // set default value
		$package_id = (int)trim(strip_tags(Input::get('package_id', 0)));
		$package_id = $package_id == 0 ? 2 : $package_id; // set default value
		
		// get data
		$user = $this->__models['admin_model']->get($user_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid user Request';
		}
		elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else if($user->package_id == $package_id) {
			$this->_apiData['message'] = 'Sorry, you are already upgraded to this package';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = $data = array();
			
			// save
			$save["user_id"] = $user->user_id;
			$save["package_id"] = $package_id;
			$save["date"] = strtotime("today");
			$save["datetime"] = strtotime("now");
			
			$this->__models['user_upgrade_model']->put($save);
			
			// apply to user data
			$user = $this->__models['admin_model']->apply_package($user, $package_id);
			$this->__models['admin_model']->set($user->user_id,(array)$user);
			
			// set remote img path
			$user->image = $user->image == "" ? "default.png" : $user->image;
			$user->image = preg_match("@^http@",$user->image) ? $user->image : url("/")."/".DIR_USER_IMG.$user->image;
			
			// unset unrequired
			unset($user->password,$user->temp_password,$user->signup_hash,$user->forgot_hash);
			
			// sort keys ascendingly to find easily
			$user = (array)$user;
			ksort($user);
			// back to original
			$user = (object)$user;
			
			// response data
			$data['user'] = $user;
			
			// success message
			$this->_apiData['message'] = "User successfully upgraded";
			
			// assign to output
			$this->_apiData['data'] = $data;		
		}
		
		
		return $this->__api_response($this->_apiData);
	}
}
