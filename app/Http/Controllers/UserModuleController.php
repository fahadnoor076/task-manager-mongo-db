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
use App\Http\Models\UserModule;
use App\Http\Models\UserModulePermission;
use App\Http\Models\UserRole;

class UserModuleController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'User Module', // singular title
		'p_title' => 'User Modules', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "user-module"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "UserModule"; // name of primary model
	private $_json_data = array();
	private $_layout = "";
	private $_entity_session_identifier = ADMIN_SESS_KEY;

    /**
     * Prevent Unauthorized User
     */
    public function __construct(Request $request) {
        //$this->middleware('auth');
		// construct parent
		parent::__construct($request);
		date_default_timezone_set('Asia/Karachi');
		
		// init models
		$this->_assign_data["userModuleModel"] = new UserModule;
		$this->_assign_data["userRoleModel"] = new UserRole;
		$this->_assign_data["userModulePermissionModel"] = new UserModulePermission;
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
		// check auth - except in non-authorization module
		$this->_assign_data["userRoleModel"]->checkAuth($request, "master");
    }

    /**
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function index(Request $request) {
		// process delete action
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        $this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
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
            $dg_order = "a.addedAt";
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

		// apply search
		$search_array = array();
        $search_array = $this->_searchParams($request, $search_array);
		
		$paginated_ids = $this->_assign_data["userModuleModel"]->ajaxAllListing($search_array);
		
		$total_records = count($paginated_ids); // total records
		
        //$total_records = count($query->get()); // total records
        // datagrid settings
       if(isset($_REQUEST['length'])){
			$dg_limit = intval($_REQUEST['length']);
		}else{
			$dg_limit = -1;
		}
        //$dg_limit = intval($_REQUEST['length']);
        $dg_limit = $dg_limit < 0 ? $total_records : $dg_limit;
		if(isset($_REQUEST['start'])){
			$dg_start = intval($_REQUEST['start']);
		}else{
			$dg_start = 0;
		}
        //$dg_start = intval($_REQUEST['start']);
        if(isset($_REQUEST['draw'])){
			$dg_draw = intval($_REQUEST['draw']);
		}else{
			$dg_draw = 1;
		}
		//$dg_draw = intval($_REQUEST['draw']);
        $dg_end = $dg_start + $dg_limit;
        $dg_end = $dg_end > $total_records ? $total_records : $dg_end;

		
		
        // if records
        if (!empty($paginated_ids)) {
			$perm_update = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
			$perm_delete = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
			// statuses
			$statuses = config("constants.ADMIN_STATUSES");

            // collect records
			$i=0;
            foreach ($paginated_ids as $key => $paginated_id) {
				$parentName = "<span style='color:red;'>None</span>";
				//$id_record = $this->_model->get($paginated_id->{$this->_pk});
				$id_record = $key;
				
				if(isset($paginated_id) && $paginated_id['parentId'] != "0"){
					$parentName = $this->_assign_data["userModuleModel"]->getRecordById($paginated_id['parentId']);
					if(!empty($parentName)){
						foreach($parentName as $key => $parentName){}
					}
					$parentName = "<spam style='color:green;'>".$parentName['userModuleName']."</span>";
				}
				
				// status html
				$status = "";
				// options html
				$options = '<div class="btn-group">';
				// selectbox html
				$checkbox = '<label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">';
				// manage options
				if($id_record) {
					// edit
					if($perm_update) {
						$options .= '<a class="btn btn-outline-inverse btn-xs" type="button" href="'.\URL::to(DIR_ADMIN.$this->_module.'/update/'.$id_record).'" data-toggle="tooltip" title="Update" data-original-title="Update"><span><i class="fa fa-pencil" aria-hidden="true"></i></span> Edit</a>';
					}
					// delete
					if($perm_delete) {
					$options .= '<a href="#static" class="btn btn-outline-danger btn-xs grid_action_del" type="button" data-toggle="modal" title="" data-item_id="'.$id_record.'" data-form="listing_form" data-original-title="Delete"><span><i class="fa fa-times" aria-hidden="true"></i></span></a>';
					}
					$checkbox .= '<input type="checkbox" id="check_id_'.$id_record.'" name="check_ids[]" value="'.$id_record.'" />';
					// status html
					if($paginated_id['isActive'] == 1) {
						$status = '<a id="status'.$id_record.'" class="btn btn-xs btn-success" type="button">'.$statuses[$paginated_id['isActive']].'</a>';
					} else {
						$status = '<a id="status'.$id_record.'" class="btn btn-xs btn-danger" type="button">'.$statuses[$paginated_id['isActive']].'</a>';
					}
				}
				$options .= '</div>';
				$checkbox .= '<span></span> </label>';
				if($paginated_id['showInMenu'] == 1) {
					$showInMenu = "Yes";
				}else{
					$showInMenu = "No";
				}
				
				// collect data
                $records["data"][] = array(
                    "ids" => $checkbox,
					"parentId" => $parentName,
					"userModuleName" => $paginated_id['userModuleName'],
					"iconClass" => $paginated_id['iconClass'],
					"showInMenu" => $showInMenu,
					"orderId" => $paginated_id['orderId'],
                    "isActive" => $status,
                    "addedAt" => date(DATE_FORMAT_ADMIN,strtotime($paginated_id['addedAt'])),
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
    private function _searchParams($request, $search_array) {
		// search
        // - user module name
        if($request->userModuleName != "") {
			$q = trim($request->userModuleName);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['userModuleName'] = $roleregex;
		}
		
		// - icon class
		if($request->iconClass != "") {
			$q = trim($request->iconClass);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['iconClass'] = $roleregex;
		}
		
		// - show in menu
		if($request->showInMenu != "") {
			$q = trim($request->showInMenu);
			$search_array['showInMenu'] = $q;
		}
		
		// - order id
		if($request->orderId != "") {
			$q = trim($request->orderId);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['orderId'] = $roleregex;
		}
		
		// - is active
		if($request->isActive != "") {
			$q = trim($request->isActive);
			$search_array['isActive'] = $q;
		}
		// - addedAt
		if($request->addedAt != "") {
			$q = trim($request->addedAt);
			$date = date("Y-m-d", strtotime($q));
			$addedregex = new \MongoRegex("/^$date/i");
			$search_array['addedAt'] =  $addedregex;
		}
		return $search_array;
    }

    /**
     * Add
     * 
     * @return view
     */
    public function add(Request $request) {
        //Checking module Authentication
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, __FUNCTION__, \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		$this->_assign_data["parentModules"] = $this->_assign_data["userModuleModel"]->getAllParentModules();
		
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
		$request->userModuleName = trim($request->userModuleName);
		$request->parentId = trim($request->parentId);
		$request->iconClass = trim($request->iconClass);
		$request->isActive = trim($request->isActive);
		$request->showInMenu = trim($request->showInMenu);
		$request->orderId = trim($request->orderId);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		//$this->_json_data['addClass'] = "show";
		$this->_json_data['addClass'] = "has-error";
		
		// get all modules
		if ($request->userModuleName == "") {
			$field_name = "userModuleName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->parentId == "") {
			$field_name = "parentId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->iconClass == "") {
			$field_name = "iconClass";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->showInMenu == "") {
			$field_name = "showInMenu";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			if($request->parentId != "0"){$parentId = new \MongoId($request->parentId);}else{$parentId = $request->parentId;}
			// set record
			$save['userModuleName'] = $request->userModuleName;
			$slug = str_slug($request->userModuleName, '-');
			$save['userModuleSlug'] = $slug;
			$save['parentId'] = $parentId;
			$save['iconClass'] = $request->iconClass;
			$save['isActive'] = $request->isActive;
			$save['showInMenu'] = $request->showInMenu;
			$save['orderId'] = $request->orderId;
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = '';
			$save["deletedAt"] = '';
			
			//INSERT
			$record_id = $this->_assign_data['userModuleModel']->_addRecord($save);
			
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
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, __FUNCTION__, \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// get record
		$this->_assign_data["parentModules"] = $this->_assign_data["userModuleModel"]->getAllParentModules();
		$this->_assign_data["data"] = $this->_assign_data['userModuleModel']->getUpdateRecord($id);
		
		#echo "<pre>";print_r($this->_assign_data["data"]);exit;
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			redirect(\URL::to(DIR_ADMIN.$this->_module));
			return;
		}
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_update($request, $id);
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Update (private)
     * 
     * @return view
     */
    private function _update(Request $request, $id) {
        // filter params
		$request->userModuleName = trim($request->userModuleName);
		$request->parentId = trim($request->parentId);
		$request->iconClass = trim($request->iconClass);
		$request->isActive = trim($request->isActive);
		$request->showInMenu = trim($request->showInMenu);
		$request->orderId = trim($request->orderId);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "has-error";
		
		// get all modules
		if ($request->userModuleName == "") {
			$field_name = "userModuleName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->parentId == "") {
			$field_name = "parentId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->iconClass == "") {
			$field_name = "iconClass";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->showInMenu == "") {
			$field_name = "showInMenu";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$save = (array)$id;
			if($request->parentId != "0"){$parentId = new \MongoId($request->parentId);}else{$parentId = $request->parentId;}
			// set record
			$save['userModuleName'] = $request->userModuleName;
			$slug = str_slug($request->userModuleName, '-');
			$save['userModuleSlug'] = $slug;
			$save['parentId'] = $parentId;
			$save['iconClass'] = $request->iconClass;
			$save['isActive'] = $request->isActive;
			$save['showInMenu'] = $request->showInMenu;
			$save['orderId'] = $request->orderId;
			$save["updatedAt"] = date("Y-m-d H:i:s");
			
			// update
			$record_id = $this->_assign_data['userModuleModel']->_updateRecord($id, $save);
			//$this->_model->set($save[$this->_pk], $save);
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module);
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
		$check_access = $this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, str_replace("_","",__FUNCTION__), \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
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
					$record = $this->_assign_data["userModuleModel"]->getUpdateRecord($id);
					// if invalid record
					if(!empty($record)) {
						// skip master admin
						$record->deletedAt = date("Y-m-d H:i:s");
						$this->_assign_data["userModuleModel"]->_updateRecord($id,(array)$record);
						$i_removed++;
					}
				}
				// msgs
				if($i_removed > 0) {
					\Session::put(ADMIN_SESS_KEY.'success_msg', 'Successfully removed selected records');
				} else {
					\Session::put(ADMIN_SESS_KEY.'error_msg', 'No records found to delete');
				}
				
			} else {
				\Session::put(ADMIN_SESS_KEY.'error_msg', 'No items selected to Delete');
			}
		
		} else {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid access request');
		}
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
				$record = $this->_assign_data["userModuleModel"]->getUpdateRecord($checked_id);
				// if valid record
				if($record !== FALSE) {
					// if delete
					if($request->select_action == "delete") {
						$this->_assign_data["userModuleModel"]->_updateRecord($checked_id, array('deletedAt' => date('Y-m-d H:i:s')));
						#$this->_model->remove($record->{$this->_pk});
						/*$record->deletedAt = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);*/
					}
					// set status
					/*$statuses = config("constants.ADMIN_STATUSES");
					// if valid status
					$valid_key = array_search($request->select_action,$statuses);
					if($valid_key !== FALSE) {
						$record->status = $valid_key;
						$record->updatedAt = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);
					}*/
					
				}
			}
			
		}
		
    }


}
