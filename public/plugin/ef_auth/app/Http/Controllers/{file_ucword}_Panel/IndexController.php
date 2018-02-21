<?php

namespace App\Http\Controllers\{wildcard_ucword}_Panel;

use App\Http\Controllers\Controller;
use View;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Models\EFEntityPlugin;

// models
//use App\Http\Models\Admin;
//use App\Http\Models\AdminGroup;
//use App\Http\Models\AdminModule;
//use App\Http\Models\AdminModulePermission;

class IndexController extends Controller {
	
	private $_assign_data = array(
		's_title' => '{wildcard_title}', // singular title
		'p_title' => '{wildcard_plural_title}', // plural title
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		'dir' => '',
		
	);
	private $_module = "{wildcard_identifier}"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "{wildcard_ucword}"; // name of primary model
	private $_json_data = array();
	private $_layout = "";
	
	// entity vars
	private $_entity_session_identifier, $_entity_dir, $_entity_pk, $_entity_model;
	private $_entity_id = "{base_entity_id}";
	private $_plugin_identifier = "{plugin_identifier}";
	private $_plugin_config = array();

    /**
     * Prevent Unauthorized User
     */
    public function __construct(Request $request) {
		////$this->middleware('auth');
		// construct parent
		parent::__construct();
		
		// init models
		//$this->_assign_data["vendor_model"] = new Admin;
		//$this->_assign_data["vendor_module_permission_model"] = new AdminModulePermission;
		// set model path for views
		$this->_assign_data["model_path"] = "App\Http\Models\\";
		// init current module model
		$this->_model = $this->_assign_data["model_path"].$this->_model;
		$this->_model = $this->_assign_data["model"] = new $this->_model;
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;
		// default nav id
		$this->_assign_data["active_nav"] = $this->_assign_data["parent_nav"]."dashboard";
		// entity vars
		$this->_entity_session_identifier = config('pl_{wildcard_identifier}.SESS_KEY');
		$this->_entity_dir = config('pl_{wildcard_identifier}.DIR_PANEL');
		$this->_entity_pk = "{wildcard_pk}";
		$this->_entity_model = $this->_assign_data["model_path"]."{wildcard_ucword}";
		$this->_entity_model = $this->_assign_data["entity_model"] = new $this->_entity_model;
		// set module name
		$this->_assign_data["module"] = $this->_module;
		// set primary key
		$this->_pk = $this->_assign_data["pk"] = $this->_module."_id";
		// set dir path
		$this->_assign_data["p_dir"] = $this->_entity_dir;
		$this->_assign_data["dir"] = $this->_assign_data["p_dir"].$this->_assign_data["dir"];
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;
		//
		$this->_entity_model= $this->_model;
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_assign_data["plugin_features"] = isset($this->_plugin_config->features) ? $this->_plugin_config->features : array();	
		// check auth
		//$this->_assign_data["vendor_model"]->checkAuth($request, strtolower(trim(config('pl_{wildcard_identifier}.DIR_PANEL'),"/")));
    }

    /**
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function index(Request $request) {
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
}
