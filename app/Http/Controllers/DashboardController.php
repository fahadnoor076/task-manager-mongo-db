<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use View;
use DB;
use Validator;
use Illuminate\Http\Request;
use Redirect;
use Mail;
// models
use App\Http\Models\User;
#use App\Http\Models\AdminGroupWidget;
#use App\Http\Models\AdminWidget;

class DashboardController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Dashboard', // singular title
		'p_title' => 'Dashboards', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "dashboard"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = ""; // name of primary model
	private $_json_data = array();	private $_layout = "";
	// entity vars
	private $_entity_session_identifier = ADMIN_SESS_KEY;

	private $_entity_dir = DIR_ADMIN;
	private $_entity_pk;
	private $_entity_model;
	private $_unAuthRoutes = array(
		DIR_ADMIN."login",
		DIR_ADMIN."logout",
		DIR_ADMIN."forgot_password",
		DIR_ADMIN."confirm_forgot"
	);

    /**
     * Prevent Unauthorized User
     */
    public function __construct(Request $request) {
		//$this->middleware('auth');
		// construct parent
		parent::__construct($request);
		// init models
		$this->_assign_data["userModel"] = new User;
		
		// default nav id
		#$this->_assign_data["active_nav"] = rtrim($this->_assign_data["dir"],"/");
		// set dir path
		#$this->_assign_data["dir"] = $this->_assign_data["p_dir"];
		$this->_assign_data["dir"] = "";
		// set model path for views
		$this->_assign_data["model_path"] = "App\Http\Models\\";
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;	
		// check auth
		if(!in_array($request->path(),$this->_unAuthRoutes)) {
			$this->_assign_data["userModel"]->checkAuth($request, "master");
		}
    }

    /**
     * Return Data to dashboard page
     * 
     * @return type 
     */
    public function index(Request $request) {
		$auth = \Session::get($this->_entity_session_identifier."auth");
		// init models
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }

    public function reports() {
        $view = View::make(DIR_ADMIN.'reports');
        return $view;
    }

}
