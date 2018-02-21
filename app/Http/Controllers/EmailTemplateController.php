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
use App\Http\Models\Admin;
use App\Http\Models\AdminModule;
use App\Http\Models\AdminModulePermission;
use App\Http\Models\EmailTemplate;

class EmailTemplateController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Email Template', // singular title
		'p_title' => 'Email Templates', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "email_template"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "EmailTemplate"; // name of primary model
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
		// init current module model
		$this->_model = $this->_assign_data["model_path"].$this->_model;
		$this->_model = $this->_assign_data["model"] = new $this->_model;
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
		
		// Module permissions
		$this->_assign_data["perm_add"] = $this->_assign_data["admin_module_permission_model"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
		$this->_assign_data["perm_update"] = $this->_assign_data["admin_module_permission_model"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
		$this->_assign_data["perm_del"] = $this->_assign_data["admin_module_permission_model"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
		
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
            $dg_order = "updated_at";
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
		$this->_selectActions($request);

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
			$perm_update = $this->_assign_data["admin_module_permission_model"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
			//$perm_del = $this->_assign_data["admin_module_permission_model"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);

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
				// - update
				if($perm_update) {
					$options .= '<a class="btn btn-xs btn-default" type="button" href="'.\URL::to(DIR_ADMIN.$this->_module.'/update/'.$id_record->{$this->_pk}).'" data-toggle="tooltip" title="Update" data-original-title="Update"><i class="fa fa-pencil"></i></a>';
				}
				/*// - delete - given in checkbox
				if($perm_del) {
					$options .= '<a class="btn btn-xs btn-default grid_action_del" type="button" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fa fa-times"></i></a>';
				}*/
				//$checkbox .= '<input type="checkbox" id="check_id_'.$id_record->{$this->_pk}.'" name="check_ids[]" value="'.$id_record->{$this->_pk}.'" />';
				$options .= '</div>';
				$checkbox .= '<span></span> </label>';
				
				// - status html
				if($paginated_id->notify_target_user == 1) {
					$status = '<a id="status'.$id_record->{$this->_pk}.'" class="btn btn-xs push-10-t btn-success" type="button">Yes</a>';
				} else {
					$status = '<a id="status'.$id_record->{$this->_pk}.'" class="btn btn-xs push-10-t btn-danger" type="button">No</a>';
				}
				// - hint
				$hint = '<a href="javascript:;" data-toggle="popover" title="" data-original-title="" data-placement="right" data-content="'.$id_record->hint.'"><i class="si si-question"></i></a>';
				
				// collect data
                $records["data"][] = array(
                    "ids" => '',
					"hint" => $hint,
					"key" => $id_record->{"key"},
					"subject" => $id_record->subject,
					"updated_at" => $id_record->updated_at == "" ? "never" : date(DATE_FORMAT_ADMIN,strtotime($id_record->updated_at)),
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
        // - admin group
        if($request->{"key"} != "") {
			$q = trim(strtolower($request->{"key"}));
			$query->where('key', 'like', "%$q%");
		}
		// - updated_at
		if($request->subject != "") {
			$q = intval(trim($request->subject));
			$query->where('subject', 'like', "%$q%");
		}
		// - created_at
		if($request->updated_at != "") {
			$q = trim($request->updated_at);
			$query->where('updated_at', 'like', date("Y-m-d", strtotime($q))." %");
		}
		return $query;
    }
	

    /**
     * Add
     * 
     * @return view
     */
    public function add(Request $request) {
        //Checking module Authentication
        $this->_assign_data["admin_module_permission_model"]->checkModuleAuth($this->_module, __FUNCTION__, \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
		
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
			$this->_json_data['text'] = "Please enter Name";
		} else {
			// set record
			$save['name'] = $request->name;
			$save["created_at"] = date("Y-m-d H:i:s");
			
			// insert
			$record_id = $this->_model->put($save);
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been added');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module);
		}
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * Update
     * 
     * @return view
     */
    public function update(Request $request, $id) {
        //Checking module Authentication
        $this->_assign_data["admin_module_permission_model"]->checkModuleAuth($this->_module, __FUNCTION__, \Session::get($this->_entity_session_identifier.'auth')->admin_group_id);
		
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// get record
		$this->_assign_data["data"] = $this->_model->get($id);
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			return redirect(\URL::to(DIR_ADMIN.$this->_module));
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
    private function _update(Request $request, $data) {
        // filter params
		$_REQUEST["body"] = trim($_REQUEST["body"]);
		$request->subject = trim($request->subject);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		
		// get all modules
		if ($_REQUEST["body"] == "") {
			$field_name = "body";
			$this->_json_data['focusElem'] = "textarea[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Content";
		} elseif ($request->subject == "") {
			$field_name = "subject";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Subject";
		} else {
			
			$save = (array)$data;
			// set record
			$save['body'] = $_REQUEST["body"];
			$save['subject'] = $request->subject;
			$save["updated_at"] = date("Y-m-d H:i:s");
			
			// update
			$this->_model->set($save[$this->_pk], $save);
			// set pk
			$record_id = $save[$this->_pk];
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module);
		}
		// return json
		return $this->_json_data;
    }



    /**
     * Select Action
     * 
     * @return query
     */
    private function _selectActions($request) {
		$request->select_action = trim($request->select_action);
		$request->checked_ids = is_array($request->checked_ids) ? $request->checked_ids : array();
		
        if($request->select_action != "" && isset($request->checked_ids[0])) {
			foreach($request->checked_ids as $checked_id) {
				$record = $this->_model->get($checked_id);
				// if valid record
				if($record !== FALSE) {
					// if delete
					/*if($request->select_action == "delete") {
						// remove current
						$this->_model->remove($record->{$this->_pk});
					}*/
					
					/*// active/inactive
					if($request->select_action == "active" || $request->select_action == "ban") {
						$record->status = $request->select_action == "active" ? 1 : 2;
						$record->updated_at = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);
					}*/
					
				}
			}
			
		}
		
    }


}
