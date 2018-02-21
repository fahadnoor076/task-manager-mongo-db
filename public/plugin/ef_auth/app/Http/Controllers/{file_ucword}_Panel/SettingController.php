<?php

namespace App\Http\Controllers\{wildcard_ucword}_Panel;

use App\Http\Controllers\Controller;
use View;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Models\EFEntityPlugin;

// models
//use App\Http\Models\Vendor;

class SettingController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Setting', // singular title
		'p_title' => 'Settings', // plural title
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		'dir' => 'setting/',
		
	);
	private $_module = "setting"; // (db module name, directory name, active nav id, routing name)
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
        //$this->middleware('auth');
		// construct parent
		parent::__construct();
		
		// set model path for views
		$this->_assign_data["model_path"] = "App\Http\Models\\";
		// init current module model
		$this->_model = $this->_assign_data["model_path"].$this->_model;
		$this->_model = $this->_assign_data["model"] = new $this->_model;
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;
		// default nav id
		$this->_assign_data["active_nav"] = $this->_assign_data["parent_nav"].$this->_module;
		// entity vars
		$this->_entity_session_identifier = config('pl_{wildcard_identifier}.SESS_KEY');
		$this->_entity_dir = config('pl_{wildcard_identifier}.DIR_PANEL');
		$this->_entity_pk = "{wildcard_pk}";
		$this->_entity_model = $this->_assign_data["model_path"]."{wildcard_ucword}";
		$this->_entity_model = $this->_assign_data["entity_model"] = new $this->_entity_model;
		// set module name
		$this->_assign_data["module"] = $this->_module;
		// set primary key
		$this->_pk = $this->_assign_data["pk"] = "{wildcard_pk}";
		// set dir path
		$this->_assign_data["p_dir"] = $this->_entity_dir;
		$this->_assign_data["dir"] = $this->_assign_data["p_dir"].$this->_assign_data["dir"];
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;
		$this->_entity_model->checkAuth($request,"");
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_assign_data["plugin_features"] = isset($this->_plugin_config->features) ? $this->_plugin_config->features : array();	
    }

    /**
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function index(Request $request) {
		// validate post form (main)
		if($request->do_post == 1) {
			return $this->_update($request);
		}
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		// activate tab
		$this->_assign_data["tab"] = $request->tab == "" ? "step1" : $request->tab;
		
		// create entity dir if not exists
		$dir_path = \Session::get($this->_entity_session_identifier."auth")->{$this->_pk};
		$dir_path = getcwd()."/".config('pl_{wildcard_identifier}.DIR_IMG').$dir_path;
		if(!is_dir($dir_path)) {
			mkdir($dir_path);
		}
		
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function ajaxListing(Request $request) {
        // datagrid params : sorting/order
        $search_value = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
        $dg_order = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : '';
        $dg_sort = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : '';
        $dg_columns = isset($_REQUEST['columns']) ? $_REQUEST['columns'] : '';
        // default ordering
        if ($dg_order == "" && $dg_sort == "") {
            $dg_order = "created_at";
            $dg_sort = "ASC";
        } else {
            // fix invalid column
            $dg_order = $dg_order == 0 ? 1 : $dg_order;
            // get column field name
            $dg_order = $dg_columns[$dg_order]["data"];
			// fix joined column name
			$dg_order = str_replace("|",".",$dg_order);
        }
		
		// perform select actions
		//$this->_selectActions($request);

        // init output
        $records = array();
        $records["data"] = array();

        // init query
        $query = $this->_model->select($this->_pk);
		$query->whereNull("deleted_at");
		
		// apply search
        $query = $this->_searchParams($request, $query);
		

        // get total records count
        $total_records = $query->count(); // total records
        //$total_records = count($query->get()); // total records
        // datagrid settings
        $dg_limit = intval($_REQUEST['length']);
        $dg_limit = $dg_limit < 0 ? $total_records : $dg_limit;
        $dg_start = intval($_REQUEST['start']);
        $dg_draw = intval($_REQUEST['draw']);
        $dg_end = $dg_start + $dg_limit;
        $dg_end = $dg_end > $total_records ? $total_records : $dg_end;


        // get records
        $query = $this->_model->select($this->_pk);
        $query->whereNull("deleted_at");
		
        // apply search
        $query = $this->_searchParams($request, $query);
		$query->take($dg_limit); // limit
        $query->skip($dg_start); // offset
        $query->orderBy($dg_order, $dg_sort); // ordering
        $paginated_ids = $query->get();
		
        // if records
        if (isset($paginated_ids[0])) {
			// Check Permissions
			$perm_update = $this->_assign_data["vendor_module_permission_model"]->checkAccess($this->_module, "update", \Auth::user()->vendor_group_id);
			$perm_del = $this->_assign_data["vendor_module_permission_model"]->checkAccess($this->_module, "delete", \Auth::user()->vendor_group_id);

            // collect records
			$i=0;
            foreach ($paginated_ids as $paginated_id) {
				$id_record = $this->_model->get($paginated_id->{$this->_pk});
				
				// status html
				$status = "";
				// options html
				$options = '<div class="btn-group">';
				// selectbox html
				$checkbox = '<label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">';
				// manage options
				$options .= '<a class="btn btn-xs btn-default" type="button" href="'.\URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module.'/update/'.$id_record->{$this->_pk}).'" data-toggle="tooltip" title="Update" data-original-title="Update"><i class="fa fa-pencil"></i></a>';
				$description = '<a href="javascript:;" data-toggle="popover" title="" data-original-title="" data-placement="top" data-content="'.$id_record->description.'"><i class="si si-question"></i></a>';
				
				
				//$checkbox .= '<input type="checkbox" id="check_id_'.$id_record->{$this->_pk}.'" name="check_ids[]" value="'.$id_record->{$this->_pk}.'" />';
				$options .= '</div>';
				$checkbox .= '<span></span> </label>';
				
				// collect data
                $records["data"][] = array(
                    "ids" => $checkbox,
					"key" => $id_record->{"key"},
                    "value" => $id_record->value,
					"description" => $description,
					"options" => $options
                );
				
				// increament
				$i++;

            }
        }


        $records["draw"] = $dg_draw;
        $records["recordsTotal"] = $total_records;
        $records["recordsFiltered"] = $total_records;

        echo json_encode($records);
    }
	
	/**
     * Search Params
     * @param $query query
     * @return query
     */
    private function _searchParams($request, $query) {
		// search
        // - key
        if($request->{"key"} != "") {
			$q = trim(strtolower($request->{"key"}));
			$query->where('key', 'like', "%$q%");
		}
		// - value
		if($request->value != "") {
			$q = trim($request->value);
			$query->where('value', 'like', date("Y-m-d", strtotime($q))." %");
		}
		return $query;
    }
    
    /**
     * Update
     * 
     * @return view
     */
    public function update(Request $request, $id) {
        //Checking module Authentication
       // $this->_assign_data["vendor_module_permission_model"]->checkModuleAuth($this->_module, __FUNCTION__, \Auth::user()->vendor_group_id);
		
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// get record
		$this->_assign_data["data"] = $this->_model->get($id);
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put($this->_entity_session_identifier.'error_msg', 'Invalid record selection');
			// redirect
			return redirect(\URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module));
		}
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_update($request, $this->_assign_data["data"]);
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Update (private)
     * 
     * @return view
     */
    private function _update(Request $request) {
		
		// params
		$name = trim($request->name);
		$site_logo = explode("?",trim($request->site_logo));
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// validation
		if ($request->name == "") {
			$field_name = "name";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Name";
		} else {
			// data
			$save = (array)$this->_model->get(\Session::get($this->_entity_session_identifier."auth")->{$this->_pk});

			// set
			$save["name"] = $name;
			$save["image"] = isset($site_logo[0]) ? $site_logo[0]."?v=".time() : "";

			// update
			$this->_model->set($save[$this->_pk],$save);

			// update into session
			$this->_model->setLoginSession((object)$save);
			
			// set session msg
			\Session::put($this->_entity_session_identifier.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module);
		}
	
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * Add
     * 
     * @return view
     */
    public function add(Request $request) {
        //Checking module Authentication
        //$vendor_permission = new VendorModulePermission;
        //$vendor_permission->checkModuleAuth($this->_module, __FUNCTION__, $this->_group);
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_add($request);
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Add (private)
     * 
     * @return view
     */
    private function _add(Request $request) {
        // filter params
		$request->name = trim($request->name);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($request->name == "") {
			$field_name = "name";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Group Name";
			$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// set record
			$save['name'] = $request->name;
			$save["created_at"] = date("Y-m-d H:i:s");
			
			// insert
			$record_id = $this->_model->put($save);
			
			// update permissions
			if(isset($request->modules[0])) {
				unset($save);
				for($i=0;$i<count($request->modules);$i++) {
					// set data
					$save['vendor_group_id'] = $record_id;
					$save['vendor_module_id'] = $request->modules[$i];
					$save['view'] = $request->{'view_'.$request->modules[$i]}=='on'? 1 : 0;
					$save['add'] = $request->{'add_'.$request->modules[$i]}=='on'? 1 : 0;
					$save['update'] = $request->{'update_'.$request->modules[$i]}=='on'? 1 : 0;
					$save['delete'] = $request->{'delete_'.$request->modules[$i]}=='on'? 1 : 0;
					$save['view'] = ($save['add']==1 || $save['update']==1 || $save['delete']==1) ? 1 : $save['view'];
					$save["created_at"] = date("Y-m-d H:i:s");
					// insert permission
					$this->_assign_data["vendor_module_permission_model"]->put($save);
					unset($save);
				}
			}
			
			// set session msg
			\Session::put($this->_entity_session_identifier.'success_msg', 'record has been added');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module);
		}
		// return json
		return $this->_json_data;
    }
	
	



    /**
     * Soft deleting admin from database
     * 
     * @param Request $request
     * @return type
     */
    private function _delete(Request $request) {
		//Checking module Authentication
		//$vendor_permission = new VendorModulePermission;
		//$check_access = $this->_assign_data["vendor_module_permission_model"]->checkAccess($this->_module, str_replace("_","",__FUNCTION__), Auth::user()->vendor_group_id);
		$check_access = TRUE; // for this module only
		
		if($check_access) {
			
			// get params
			$request->delete_ids = is_array($request->delete_ids) ? $request->delete_ids : array();	
			
			// got elements?
			if(isset($request->delete_ids[0])) {
				$i_removed = 0;
				// if multiple items
				foreach($request->delete_ids as $id) {
					// get
					$record = $this->_model->get($id);
					// if invalid record
					if($record) {
						// skip master admin
						if($record->{$this->_pk} == 1) {
							continue;
						}
						
						$record->deleted_at = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);
						$i_removed++;
					}
				}
				// msgs
				if($i_removed > 0) {
					\Session::put($this->_entity_session_identifier.'success_msg', 'Successfully removed selected records');
				} else {
					\Session::put($this->_entity_session_identifier.'error_msg', 'No records found to delete');
				}
				
			} else {
				\Session::put($this->_entity_session_identifier.'error_msg', 'No items selected to Delete');
			}
		
		} else {
			// set session msg
			\Session::put($this->_entity_session_identifier.'error_msg', 'Invalid access request');
		}
    }
	
	
	/**
     * logo browser
     * 
     * @return view
     */
    public function logoBrowser(Request $request) {
        //Checking module Authentication
        //$vendor_permission = new VendorModulePermission;
        //$vendor_permission->checkModuleAuth($this->_module, __FUNCTION__, $this->_group);
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
        $view = View::make($this->_assign_data["dir"].$view_file, $this->_assign_data);
        return $view;
    }


}
