<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Crypt;
use View;
use Illuminate\Http\Request;
use Redirect;
// models
use App\Http\Models\Admin;
use App\Http\Models\AdminModule;
use App\Http\Models\AdminModulePermission;
use App\Http\Models\Setting;
use App\Http\Models\Conf;

class QueryController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Query Interface', // singular title
		'p_title' => 'Query Interface', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "query_interface"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	//private $_model = "Setting"; // name of primary model
	private $_json_data = array();	private $_layout = "";
	// entity vars
	private $_entity_session_identifier = ADMIN_SESS_KEY;

    /**
     * Prevent Unauthorized User
     */
    public function __construct(Request $request) {
        //$this->middleware('auth');
		// construct parent
		parent::__construct($request);
		
		// init models
		$this->_assign_data["admin_model"] = new Admin;
		$this->_assign_data["admin_module_permission_model"] = new AdminModulePermission;
		// set model path for views
		$this->_assign_data["model_path"] = "App\Http\Models\\";
		/*// init current module model
		$this->_model = $this->_assign_data["model_path"].$this->_model;
		$this->_model = $this->_assign_data["model"] = new $this->_model;*/
		// default nav id
		$this->_assign_data["active_nav"] = $this->_assign_data["parent_nav"].$this->_module;
		// set dir path
		$this->_assign_data["dir"] = $this->_assign_data["p_dir"].$this->_module."/";
		// set module name
		$this->_assign_data["module"] = $this->_module;
		// set primary key
		$this->_pk = $this->_assign_data["pk"] = $this->_module."_id";
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;
		// check Auth
		$this->_assign_data["admin_model"]->checkAuth($request);
    }

    /**
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function index(Request $request) {
		//Checking module Authentication
        $this->_assign_data["admin_module_permission_model"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
		
		// init models
		$conf_model = new Conf;
		$config = $conf_model->getBy("key","site");
		
		// validate post form (main)
		if($request->do_post == 1) {
			return $this->_process($request);
		}
		
		// activate tab
		$this->_assign_data["tab"] = $request->tab == "" ? "step1" : $request->tab;
		// assign configurations
		$this->_assign_data["config"] = json_decode($config->value,false);
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Process
     * 
     * @return view
     */
    private function _process($request) {
		// params
		$query = trim($request->statement);
		
		// bad commands
		$bad_commands = "@(update\s|delete\s|insert\s|alter\s|drop\s|truncate\s|grant\s)@i";
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// validation
		if ($request->statement == "") {
			$field_name = "statement";
			$this->_json_data['focusElem'] = "textarea[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter SQL Statement";
			$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else if (preg_match($bad_commands,$request->statement)) {
			$field_name = "statement";
			$this->_json_data['focusElem'] = "textarea[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Only SELECT operations can be performed";
			$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// init params
			$this->_assign_data["success"] = 1;
			$this->_assign_data["message"] = "";
			$this->_assign_data["records"] = array();
			
			try {
				//$records = \DB::select("update admin_module set name = 'aaasss' where admin_module_id = 9");
				$records = \DB::select($request->statement);
				$this->_assign_data["success"] = 1;
				$this->_assign_data["records"] = isset($records[0]) ? $records : array();
				/*echo "<pre>";
				var_dump($records);
				echo "</pre>";
				exit;*/
			} catch (\Illuminate\Database\QueryException $e) {
				//exit("in here 1 ...");
				$this->_assign_data["success"] = 0;
				$this->_assign_data["message"] = "SQL Error: " . $e->getMessage();
			} catch(Exception $e) {
				//exit("in here 2 ...");
				$this->_assign_data["success"] = 0;
				$this->_assign_data["message"] = $e->getMessage();
			}
			// target element
			$this->_json_data['targetElem'] = "div[id=tab-step2] div.block-content";
			
			// trigger results tab
			$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step2]", "event" => "click");
			
			//redirect
			$this->_json_data['html'] = View::make($this->_assign_data["dir"]."results", $this->_assign_data)->__toString();
		}
	
		// return json
		return $this->_json_data;
    }


}
