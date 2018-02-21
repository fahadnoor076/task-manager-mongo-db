<?php

namespace App\Http\Controllers\{wildcard_ucword}_Panel;

use App\Http\Controllers\Controller;
use View;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Models\EFEntityPlugin;
// models
//use App\Http\Models\{wildcard_ucword};

class DashboardController extends Controller {
	
	private $_assign_data = array(
		'parent_nav' => '', // parent navigation id
		'dir' => 'dashboard/',
	);
	private $_layout = "";
	private $_json_data = array();
	private $_model = "{wildcard_ucword}"; // name of primary model
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
		// set model path for views
		$this->_assign_data["model_path"] = "App\Http\Models\\";
		// init current module model
		$this->_model = $this->_assign_data["model_path"].$this->_model;
		$this->_model = $this->_assign_data["model"] = new $this->_model;
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;
		// default nav id
		//$this->_assign_data["active_nav"] = $this->_assign_data["parent_nav"].$this->_module;
		$this->_assign_data["active_nav"] = "dashboard";
		// entity vars
		$this->_entity_session_identifier = config('pl_{wildcard_identifier}.SESS_KEY');
		$this->_entity_dir = config('pl_{wildcard_identifier}.DIR_PANEL');
		$this->_entity_pk = "{wildcard_pk}";
		$this->_entity_model = $this->_assign_data["model_path"]."{wildcard_ucword}";
		$this->_entity_model = $this->_assign_data["entity_model"] = new $this->_entity_model;
		// set dir path
		$this->_assign_data["p_dir"] = $this->_entity_dir;
		$this->_assign_data["dir"] = $this->_assign_data["p_dir"].$this->_assign_data["dir"];
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;	
		// check auth
		$this->_entity_model->checkAuth($request);	
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_assign_data["plugin_features"] = isset($this->_plugin_config->features) ? $this->_plugin_config->features : array();	
    }

    /**
     * Return Data to dashboard page
     * 
     * @return type 
     */
    public function index(Request $request) {
		// assign widgets (tiles)
		/*$this->_assign_data["widget"]["tiles"] = $this->_assign_data["entity_model"]
			->select("aw.admin_widget_id AS admin_widget_id")
			->where("aw.type","=","tile")
			->whereNull("aw.deleted_at")
			->whereNull("agw.deleted_at")
			->from("admin_widget AS aw")
			->join("admin_group_widget AS agw","agw.admin_widget_id","=","aw.admin_widget_id")
			->groupBy("aw.admin_widget_id")
			->get();
		*/
		$this->_assign_data["widget"]["tiles"] = array();
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }

    public function reports() {
        $view = View::make(config('pl_{wildcard_identifier}.DIR_PANEL').'reports');
        return $view;
    }

}
