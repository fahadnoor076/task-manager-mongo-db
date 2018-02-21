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
use App\Http\Models\Brand;
use App\Http\Models\Department;
use App\Http\Models\UserRole;
use App\Http\Models\UserDesignation;
use App\Http\Models\UserModulePermission;
use App\Http\Models\UserBrand;

class UserController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'User', // singular title
		'p_title' => 'Users', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => 'administration-', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "user"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "User"; // name of primary model
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
		$this->_assign_data["brandModel"] = new Brand;
		$this->_assign_data["userRoleModel"] = new UserRole;
		$this->_assign_data['userBrandModel'] = new UserBrand;
		$this->_assign_data["departmentModel"] = new Department;
		$this->_assign_data["userDesignationModel"] = new UserDesignation;
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
		//$this->_pk = $this->_assign_data["pk"] = $this->_module."_id";
		$this->_pk = $this->_assign_data["pk"] = "_id";
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;
		// entity conf
		$this->_entity_pk = $this->_pk;
		$this->_entity_model = $this->_model;
		// check auth - except in non-authorization module
		//if(!in_array($request->path(),$this->_unAuthRoutes) && !$request->is(DIR_ADMIN."confirm_forgot/*")) {
		if(!in_array($request->path(),$this->_unAuthRoutes)) {
			$this->_assign_data["userModel"]->checkAuth($request, "master");
		}
    }

    /**
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function index(Request $request) {
		// process delete action
		#$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        #$this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        #$this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        #$this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		$this->_assign_data["perm_add"] = 1;
        $this->_assign_data["perm_update"] = 1;
        $this->_assign_data["perm_del"] = 1; 
		
		$this->_assign_data["roles"] = $this->_assign_data["userRoleModel"]->getActiveListing();
		$this->_assign_data["designations"] = $this->_assign_data["userDesignationModel"]->getActiveListing();
		
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
		
		$paginated_ids = $this->_assign_data["userModel"]->ajaxListing($search_array);
		

        // get total records count
        $total_records = count($paginated_ids['result']); // total records
        //$total_records = count($query->get()); // total records
        // datagrid settings
        $dg_limit = intval($_REQUEST['length']);
        $dg_limit = $dg_limit < 0 ? $total_records : $dg_limit;
        $dg_start = intval($_REQUEST['start']);
        $dg_draw = intval($_REQUEST['draw']);
        $dg_end = $dg_start + $dg_limit;
        $dg_end = $dg_end > $total_records ? $total_records : $dg_end;

        // apply search
        $search_array = $this->_searchParams($request, $search_array);
		
		/*$query->take($dg_limit); // limit
        $query->skip($dg_start); // offset
        $query->orderBy($dg_order, $dg_sort); // ordering
        $paginated_ids = $query->get();*/
		
        // if records
        if (!empty($paginated_ids)) {
			$perm_update = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
			$perm_delete = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
			// statuses
			$statuses = config("constants.ADMIN_STATUSES");

            // collect records
			$i=0;
            foreach ($paginated_ids as $paginated_id) {
				if (is_array($paginated_id)){
					foreach($paginated_id as $paginated_id){
						//echo "<pre>";print_r($paginated_id);exit;
						$key = $paginated_id['_id']->{'$id'};
				
						//$id_record = $this->_model->get($paginated_id->{$this->_pk});
						$id_record = $key;
						
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
								$options .= '<a class="btn btn-xs btn-default" type="button" href="'.\URL::to(DIR_ADMIN.$this->_module.'/update/'.$id_record).'" data-toggle="tooltip" title="Update" data-original-title="Update"><i class="fa fa-pencil"></i></a>';
							}
							// delete
							if($perm_delete) {
								$options .= '<a class="btn btn-outline-danger btn-xs grid_action_del" type="button" data-toggle="tooltip" title="" data-item_id="'.$id_record.'" data-form="listing_form" data-original-title="Delete"><span><i class="fa fa-times" aria-hidden="true"></i></span></a>';
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
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"userName" =>$paginated_id['userName'],
							"userEmail" =>$paginated_id['userEmail'],
							"userContactPersonal" =>$paginated_id['userContactPersonal'],
							"userRoleName" => $paginated_id['roleArray'][0]['userRoleName'],
							"userDesignationName" => $paginated_id['designationArray'][0]['userDesignationName'],
							"isActive" => $status,
							"addedAt" => date(DATE_FORMAT_ADMIN,strtotime($paginated_id['addedAt'])),
							"options" => $options
						);
						
						// increament
						$i++;
					}
				}
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
		// - user name
        if($request->userName != "") {
			$q = trim($request->userName);
			$nameregex = new \MongoRegex("/^$q/i");
			$search_array['userName'] = $nameregex;
		}
		// - user email
        if($request->userEmail != "") {
			$q = trim($request->userEmail);
			$emailregex = new \MongoRegex("/^$q/i");
			$search_array['userEmail'] = $emailregex;
		}
		// - user phone
        if($request->userContactPersonal != "") {
			$q = trim($request->userContactPersonal);
			$phoneregex = new \MongoRegex("/^$q/i");
			$search_array['userContactPersonal'] = $phoneregex;
		}
        // - user role id
        if($request->fkRoleId != "") {
			$q = trim($request->fkRoleId);
			$search_array['fkRoleId'] = new \MongoId($q);
		}
		
		// - user designation id
        if($request->fkDesignationId != "") {
			$q = trim($request->fkDesignationId);
			$search_array['fkDesignationId'] = new \MongoId($q);
		}
		
		// - username
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
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_add($request);
		}
		
		$this->_assign_data["roles"] = $this->_assign_data["userRoleModel"]->getActiveListing();
		$this->_assign_data["designations"] = $this->_assign_data["userDesignationModel"]->getActiveListing();
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["departments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Add (private)
     * 
     * @return view
     */
    private function _add(Request $request) {
		#echo "<pre>";print_r($_POST);exit;
        // filter params
		$request->userName = trim($request->userName);
		$request->userEmail = trim($request->userEmail);
		$request->userPassword = trim($request->userPassword);
		$request->userContactPersonal = trim($request->userContactPersonal);
		$request->userContactEmergency = trim($request->userContactEmergency);
		$request->fkRoleId = trim($request->fkRoleId);
		$request->fkDesignationId = trim($request->fkDesignationId);
		$request->fkDepartmentId = trim($request->fkDepartmentId);
		#$request->fkBrandId = trim($request->fkBrandId);
		
		if($request->designation_slug == 'super_admin' || $request->designation_slug == 'admin'){
			$request->fkDepartmentId = "0";
			$request->fkBrandId = "0";
		}		
		
		// default errors class
		//$this->_json_data['removeClass'] = "has-warning";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		$valid_email = Validator::make(array('userEmail' => $request->userEmail), array('userEmail' => 'required|email'));
		$chk_email = $this->_assign_data["userModel"]->getRecordByEmail($request->userEmail);
		
		//echo "<pre>";print_r($chk_email);exit;
		
		// get all modules
		if ($request->userName == "") {
			$field_name = "userName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Username";
		} else if ($valid_email->fails()) {
			$field_name = "userEmail";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} else if(!$valid_email->fails() && (sizeof($chk_email) > 0)){
				$field_name = "userEmail";
				$this->_json_data['addClass'] = "has-warning";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
		} else if ($request->userPassword == "") {
			$field_name = "userPassword";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->userContactPersonal == "") {
			$field_name = "userContactPersonal";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->userContactEmergency == "") {
			$field_name = "userContactEmergency";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkRoleId == "") {
			$field_name = "fkRoleId";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Role";
		} else if ($request->fkDesignationId == "") {
			$field_name = "fkDesignationId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkDepartmentId == "") {
			$field_name = "fkDepartmentId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkBrandId == "") {
			$field_name = "fkBrandId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// set record
			$save['userName'] = $request->userName;
			$save['userEmail'] = $request->userEmail;
			$save['userPassword'] = $request->userPassword;
			$save['userContactPersonal'] = $request->userContactPersonal;
			$save['userContactEmergency'] = $request->userContactEmergency;
			$save['fkRoleId'] = new \MongoId($request->fkRoleId);
			$save['fkDesignationId'] = new \MongoId($request->fkDesignationId);
			if($request->fkDepartmentId == '0'){
				$save['fkDepartmentId'] = "0";
			}else{
				$save['fkDepartmentId'] = new \MongoId($request->fkDepartmentId);
			}
			//$save['fkDepartmentId'] = new \MongoId($request->fkDepartmentId);
			$save['isActive'] = $request->isActive;
			$save['rememberToken'] = "";
			$save['lastLoginAt'] = "";
			$save['isLoggedIn'] = 0;
			$save['forgetPasswordHash'] = "";
			$save['forgetPasswordHashCreatedAt'] = "";
			$save['rememberLoginToken'] = "";
			$save['rememberLoginTokenCreatedAt'] = "";
			$save['userProfileImage'] = "";
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = "";
			$save["deletedAt"] = "";
			
			// insert
			//$record_id = $this->_model->put($save);
			//$record_id = $this->_model->generateNew($save);
			
			//INSERT
			$record_id = $this->_assign_data["userModel"]->_addRecord($save);
			unset($save);
			
			#$save['fkBrandId'] = new \MongoId($request->fkBrandId);
			if(!empty($request->fkBrandId)){
				foreach($request->fkBrandId as $brandId){
					if($brandId != 0){
						$save['fkUserId'] = new \MongoId($record_id);
						$save['fkBrandId'] = new \MongoId($brandId);
						$save['isActive'] = "1";
						$save["addedAt"] = date("Y-m-d H:i:s");
						$save["updatedAt"] = "";
						$save["deletedAt"] = "";
						
						$userBrandId = $this->_assign_data['userBrandModel']->_addRecord($save);
						unset($save);
					}
				}
			}
			
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
		$this->_assign_data["data"] = $this->_assign_data['userModel']->getUpdateRecord($id);
		$this->_assign_data["roles"] = $this->_assign_data["userRoleModel"]->getActiveListing();
		$this->_assign_data["designations"] = $this->_assign_data["userDesignationModel"]->getActiveListing();
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["departments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$this->_assign_data["userBrands"] = $this->_assign_data["userBrandModel"]->getBrandByUserId($id);
		/*$brandsArr = array();
		if(!empty($userBrands)){
			foreach($userBrands as $key => $ubrand){
				array_push($brandsArr, $ubrand['fkBrandId']);
			}
		}
		$this->_assign_data["userBrands"] = $brandsArr;*/
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			return redirect(\URL::to(DIR_ADMIN.$this->_module));
		}
		
		// redirect on invalid record
		/*if($this->_assign_data["data"]->{$this->_pk} == 1) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Cannot update admin record');
			// redirect
			return redirect(\URL::to(DIR_ADMIN.$this->_module));
		}*/
		
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
		$request->userName = trim($request->userName);
		$request->userEmail = trim($request->userEmail);
		$request->userPassword = trim($request->userPassword);
		$request->userContactPersonal = trim($request->userContactPersonal);
		$request->userContactEmergency = trim($request->userContactEmergency);
		$request->fkRoleId = trim($request->fkRoleId);
		$request->fkDesignationId = trim($request->fkDesignationId);
		$request->fkDepartmentId = trim($request->fkDepartmentId);
		#$request->fkBrandId = trim($request->fkBrandId);
		
		
		// default errors class
		//$this->_json_data['removeClass'] = "has-warning";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		$valid_email = Validator::make(array('userEmail' => $request->userEmail), array('userEmail' => 'required|email'));
		//$chk_email = $this->_assign_data["userModel"]->getRecordByEmail($request->userEmail);
		
		//echo "<pre>";print_r($chk_email);exit;
		
		// get all modules
		if ($request->userName == "") {
			$field_name = "userName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Username";
		} else if ($valid_email->fails()) {
			$field_name = "userEmail";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->userContactPersonal == "") {
			$field_name = "userContactPersonal";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->userContactEmergency == "") {
			$field_name = "userContactEmergency";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkRoleId == "") {
			$field_name = "fkRoleId";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Role";
		} else if ($request->fkDesignationId == "") {
			$field_name = "fkDesignationId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkDepartmentId == "") {
			$field_name = "fkDepartmentId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkBrandId == "") {
			$field_name = "fkBrandId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// set record
			$save['userName'] = $request->userName;
			$save['userEmail'] = $request->userEmail;
			if($request->userPassword != ""){
				$save['userPassword'] = $request->userPassword;
			}
			$save['userContactPersonal'] = $request->userContactPersonal;
			$save['userContactEmergency'] = $request->userContactEmergency;
			$save['fkRoleId'] = new \MongoId($request->fkRoleId);
			$save['fkDesignationId'] = new \MongoId($request->fkDesignationId);
			$save['fkDepartmentId'] = new \MongoId($request->fkDepartmentId);
			$save['isActive'] = $request->isActive;
			$save["updatedAt"] = date("Y-m-d H:i:s");
			
			// insert
			//$record_id = $this->_model->put($save);
			//$record_id = $this->_model->generateNew($save);
			
			// update
			$record_id = $this->_assign_data['userModel']->_updateRecord($id, $save);
			unset($save);
			
			//brand tagging
			if(!empty($request->fkBrandId)){
				$this->_assign_data['userBrandModel']->_deleteRecord($id);
				foreach($request->fkBrandId as $brandId){
					if($brandId != 0){
						$check['fkUserId'] = new \MongoId($id);
						$check['fkBrandId'] = new \MongoId($brandId);
						$checkRec = $this->_assign_data['userBrandModel']->checkRecord($check);
						unset($check);
						
						if(!empty($checkRec)){
							foreach($checkRec as $key => $value){
								#if($value['deletedAt'] != ""){
								$update['updatedAt'] = date('Y-m-d H:i:s');
								$update['deletedAt'] = "";
								$updateId = $this->_assign_data['userBrandModel']->_updateRecord($key, $update);
								unset($update);
								#}
							}
						}else{
							//Insert
							$save['fkUserId'] = new \MongoId($id);
							$save['fkBrandId'] = new \MongoId($brandId);
							$save['isActive'] = "1";
							$save["addedAt"] = date("Y-m-d H:i:s");
							$save["updatedAt"] = "";
							$save["deletedAt"] = "";
							$sgrecord_id = $this->_assign_data['userBrandModel']->_addRecord($save);
							unset($save);
						}
						unset($sgrecord_id);
					}
				}
			}
			
			
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been added');
			
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
				$record = $this->_assign_data["userModel"]->getUpdateRecord($checked_id);
				// if valid record
				if($record !== FALSE) {
					// if delete
					if($request->select_action == "delete") {
						$this->_assign_data["userModel"]->_updateRecord($checked_id, array('deletedAt' => date('Y-m-d H:i:s')));
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
	
	
	/**
     * login
     * 
     * @return type Array()
     */
    public function login(Request $request) {
		// check remember me
		$this->_entity_model->checkCookieAuth();
		// redirect logged
		$this->_entity_model->redirectLogged();
		
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		// validate post form
		if($request->do_post == 1) {
			return $this->_login($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _login(Request $request) {
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// filter params
		$request->email = strip_tags($request->email);
		$request->remember = intval(strip_tags($request->remember));
		$request->password = $request->password;
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email'));
		
		// record
		$record = $this->_entity_model->checkLogin($request->email, $request->password);
		$id = "";
		if($record !== FALSE){
			$id = $record['_id']->{'$id'};
		}
		
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->password == "") {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Password";
		} else if ($record === FALSE) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Invalid login provided";
		} else if ($record['isActive'] == 0) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Your account is inactive, Please contact Administrator.";
		} else if ($record['isActive'] == 2) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Cannot login. Account is baned by Administrator.";
		} else {

			// set record
			$save = (array)$record;

			$save["lastLoginAt"] = date("Y-m-d H:i:s");
			$save["isLoggedIn"] = "1";
			$save["rememberLoginToken"] = NULL;

			// remember me
			if(isset($request->remember) && $request->remember > 0) {
				$remember_login_token = $this->_entity_model->setRememberToken((object)$save);
				$save["rememberLoginToken"] = $remember_login_token;

			}

			// save
			#$this->_entity_model->set($save[$this->_entity_pk], $save);
			
			// set login session
			$redirect_url = $this->_entity_model->setLoginSession((object)$save, $id);
			
			//redirect
			$this->_json_data['redirect'] = $redirect_url;
		}
		// return json
		return $this->_json_data;
    }
	
	/**
     * forgotPassword
     * 
     * @return type Array()
     */
    public function forgotPassword(Request $request) {
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
		// page action
		$this->_assign_data["page_action"] = "Forgot Password";
		$this->_assign_data["route_action"] = $view_file;
		// validate post form
		if($request->do_post == 1) {
			return $this->_forgotPassword($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].$view_file, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _forgotPassword(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// filter params
		$request->email = strip_tags($request->email);
		$request->remember = intval(strip_tags($request->remember));
		$request->password = $request->password;
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email'));
		
		// record
		$record = $this->_entity_model->getBy("userEmail",$request->email);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if (empty($record)) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Sorry, no such email exists in our system";
		} else if ($record['isActive'] <> 1) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Cannot login. Account is either inactive or baned by Administrator.";
		} else {
			// forgot password
			$this->_entity_model->forgotPassword($record);
			
			//redirect
			$this->_json_data['redirect'] =  \URL::to($this->_entity_dir."login");
		}
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * confirmForgot
     * 
     * @return type Array()
     */
    public function confirmForgot(Request $request) {
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
		// filter params
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// page action
		$this->_assign_data["page_action"] = "Reset Password";
		$this->_assign_data["route_action"] = $view_file;
		
		// check params
		$check['userEmail'] = $request->email;
		$check['forgetPasswordHash'] = $request->code;
		$raw_record = $this->_assign_data['userModel']->getRecordByForgetHash($check);
		
		/*$raw_record = $this->_entity_model->select($this->_entity_pk)
			->where("email","=", $request->email)
			->where("forgot_password_hash","=", $request->code)
			->whereNull("deleted_at")
			->get();*/
		$raw_id = isset($raw_record) ? $raw_record[$this->_entity_pk]->{'$id'} : 0;
		$record = $this->_entity_model->get($raw_id);
		
		// assign params
		$this->_assign_data["code"] = $request->code;
		$this->_assign_data["email"] = $request->email;
		$this->_assign_data["record"] = $record;
		
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_confirmForgot($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].$view_file, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _confirmForgot(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// check params
		$raw_record = $this->_entity_model->select($this->_entity_pk)
			->where("email","=", $request->email)
			->where("forgot_password_hash","=", $request->code)
			->whereNull("deleted_at")
			->get();
		$raw_id = isset($raw_record[0]) ? $raw_record[0]->{$this->_entity_pk} : 0;
		$record = $this->_entity_model->get($raw_id);
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email'));
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->code == "") {
			$field_name = "code";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Confirmation Code";
		} else if ($record === FALSE) {
			$field_name = "code";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Invalid Confirmation Link. Please contact Administrator";
		} else if ($request->password == "") {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter New Password";
		} else if (strlen($request->password) < 6) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "New Password must be at-least 6 character long";
		} else if ($request->confirm_password == "") {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please confirm Password";
		} else if ($request->password != $request->confirm_password) {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Passwords do not match";
		} else {
			// set data
			$this->_entity_model->recoverPasswordSuccess($record, $request->password);
			
			// session message
			\Session::put($this->_entity_session_identifier.'success_msg', "Please login with your new password");
			
			//redirect
			$this->_json_data['redirect'] =  \URL::to($this->_entity_dir."login");
		}
		// return json
		return $this->_json_data;
    }

	
	/**
     * Return data to admin listing page
     * 
     * @return type redirect
     */
    public function logout(Request $request) {
		$this->_entity_model->logout();
    }


	
	
	/**
     * Make view for change password page
     * 
     * @return type
     */
    public function changePassword(Request $request) {
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
		// page action
		$this->_assign_data["page_action"] = "Change Password";
		$this->_assign_data["route_action"] = $view_file;
		// validate post form
		if($request->do_post == 1) {
			return $this->_changePassword($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].$view_file, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _changePassword(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		

        // get all modules
		if ($request->current_password == "") {
			$field_name = "current_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Current Password";
		} else if ($request->new_password == "") {
			$field_name = "new_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter New Password";
		} /*else if ($request->confirm_password != $request->new_password) {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Passwords do not match";
		}*/ else if ($request->confirm_password == "") {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please confirm Password";
		} else if ($request->new_password != $request->confirm_password) {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Passwords do not match";
		} else {
			$save = $this->_entity_model->get(\Session::get(ADMIN_SESS_KEY."auth")->{$this->_pk}->{'$id'});
			// set data
			$save['userPassword'] = $request->new_password;
			
			// change password
			$this->_entity_model->changePasswordSelf((array)$save);
			
			// set session msg
            \Session::put(ADMIN_SESS_KEY.'success_msg', 'Password successfully changed');
			
			// view file
			$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', str_replace("_","",__FUNCTION__)));

            //redirect
            $this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$view_file);
		}
		// return json
		return $this->_json_data;
    }
	
	

	/**
     * Make view for change password page
     * 
     * @return type
     */
    public function xchangePassword() {
		// override passwords
		$this->_assign_data["p_title"] = $this->_assign_data["s_title"] = "Change Password";
		
        $view = View::make(DIR_ADMIN.'change_password', $this->_assign_data);
        return $view;
    }
	
    /**
     * Change admin password
     * 
     * @param Request $request
     * @return type
     */
    public function xpostChangePassword(Request $request) {
        $rules = array(
            'change_password' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $change_password = $request->change_password;
            $admin_id = $request->user()->admin_id;

            DB::select("UPDATE 
                        admin SET password = '" . bcrypt($change_password) . "'
                         WHERE admin_id = '" . $admin_id . "'");

            $request->session()->flash('success_msg', 'Your password has been updated successfully!');
            return redirect(DIR_ADMIN.'dashboard');
        }
    }

    /**
     * Make view for change passcode page
     * 
     * @return type
     */
    public function xgetChangePasscode() {      
        //Checking module Authentication
        $admin_permission = new AdminModulePermission;
        $admin_permission->checkModuleAuth($this->_module, 'update', $this->_group);

        // echo 'change function';
        //$final_data['user_data'] = DB::select("SELECT value FROM setting WHERE id = '1'");
        $view = View::make(DIR_ADMIN.'change_passcode', $this->_assign_data);
        return $view;
    }

    /**
     * Change passcode page
     * 
     * @param Request $request
     * @return type
     */
    public function xpostChangePasscode(Request $request) {
        //Checking module Authentication
        $admin_permission = new AdminModulePermission;
        $admin_permission->checkModuleAuth($this->_module, 'update', $this->_group);

        $rules = array(
            'change_passcode' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {

            $change_passcode = $request->change_passcode;
            DB::select("UPDATE setting SET value = '" . $change_passcode . "' WHERE id = '1' ");
            $request->session()->flash('success_msg', 'The passcode has been updated successfully!');
            return Redirect::to(DIR_ADMIN.'dashboard');
        }
    }
	
	
	/**
     * Get Designations
     * 
     * @param Request $request
     * @return type
     */
    public function getDesignationsByRoleId(Request $request){
		$roleId = $request->roleId;
		$records = $this->_assign_data["userDesignationModel"]->getDesignationsByRoleId($roleId);
		if($records){
			return $records;
		}else{
			return 'error';
		}
	}
	
	
	/**
     * Get Designations
     * 
     * @param Request $request
     * @return type
     */
    public function getBrandsByDepartmentId(Request $request){
		$departmentId = $request->departmentId;
		$records = $this->_assign_data["departmentModel"]->getDepartmentById($departmentId);
		foreach($records as $key => $value){
		}
		if($value['departmentSlug'] == 'sales'){
			$records = $this->_assign_data["brandModel"]->getActiveListing();
			return $records;
		}else{
			return 'error';
		}
	}
	
	/**
     * User Profile
     * 
     * @return view
     */
    public function profile(Request $request, $id) {
        //Checking module Authentication
		#$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, __FUNCTION__, \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		//Check User Existence
		$details = $this->_assign_data['userModel']->getUpdateRecord($id);
		if(!empty($details)){
			$this->_assign_data["data"] = $details;
		}else{
			return redirect(\URL::to('error'));
		}
		
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			return redirect(\URL::to(DIR_ADMIN.$this->_module));
		}
		
		// redirect on invalid record
		/*if($this->_assign_data["data"]->{$this->_pk} == 1) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Cannot update admin record');
			// redirect
			return redirect(\URL::to(DIR_ADMIN.$this->_module));
		}*/
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_profile($request, $id);
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Profile Update (private)
     * 
     * @return view
     */
    private function _profile(Request $request, $id) {
        // filter params
		$request->userName = trim($request->userName);
		$request->userEmail = trim($request->userEmail);
		$request->userPassword = trim($request->userPassword);
		$request->userContactPersonal = trim($request->userContactPersonal);
		$request->userContactEmergency = trim($request->userContactEmergency);
		$request->fkRoleId = trim($request->fkRoleId);
		$request->fkDesignationId = trim($request->fkDesignationId);
		$request->fkDepartmentId = trim($request->fkDepartmentId);
		#$request->fkBrandId = trim($request->fkBrandId);
		
		
		// default errors class
		//$this->_json_data['removeClass'] = "has-warning";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		$valid_email = Validator::make(array('userEmail' => $request->userEmail), array('userEmail' => 'required|email'));
		//$chk_email = $this->_assign_data["userModel"]->getRecordByEmail($request->userEmail);
		
		//echo "<pre>";print_r($chk_email);exit;
		
		// get all modules
		if ($request->userName == "") {
			$field_name = "userName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Username";
		} else if ($valid_email->fails()) {
			$field_name = "userEmail";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->userContactPersonal == "") {
			$field_name = "userContactPersonal";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->userContactEmergency == "") {
			$field_name = "userContactEmergency";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkRoleId == "") {
			$field_name = "fkRoleId";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Role";
		} else if ($request->fkDesignationId == "") {
			$field_name = "fkDesignationId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkDepartmentId == "") {
			$field_name = "fkDepartmentId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} else if ($request->fkBrandId == "") {
			$field_name = "fkBrandId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Password";
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// set record
			$save['userName'] = $request->userName;
			$save['userEmail'] = $request->userEmail;
			if($request->userPassword != ""){
				$save['userPassword'] = $request->userPassword;
			}
			$save['userContactPersonal'] = $request->userContactPersonal;
			$save['userContactEmergency'] = $request->userContactEmergency;
			$save['fkRoleId'] = new \MongoId($request->fkRoleId);
			$save['fkDesignationId'] = new \MongoId($request->fkDesignationId);
			$save['fkDepartmentId'] = new \MongoId($request->fkDepartmentId);
			$save['isActive'] = $request->isActive;
			$save["updatedAt"] = date("Y-m-d H:i:s");
			
			// insert
			//$record_id = $this->_model->put($save);
			//$record_id = $this->_model->generateNew($save);
			
			// update
			$record_id = $this->_assign_data['userModel']->_updateRecord($id, $save);
			unset($save);
			
			//brand tagging
			if(!empty($request->fkBrandId)){
				$this->_assign_data['userBrandModel']->_deleteRecord($id);
				foreach($request->fkBrandId as $brandId){
					if($brandId != 0){
						$check['fkUserId'] = new \MongoId($id);
						$check['fkBrandId'] = new \MongoId($brandId);
						$checkRec = $this->_assign_data['userBrandModel']->checkRecord($check);
						unset($check);
						
						if(!empty($checkRec)){
							foreach($checkRec as $key => $value){
								#if($value['deletedAt'] != ""){
								$update['updatedAt'] = date('Y-m-d H:i:s');
								$update['deletedAt'] = "";
								$updateId = $this->_assign_data['userBrandModel']->_updateRecord($key, $update);
								unset($update);
								#}
							}
						}else{
							//Insert
							$save['fkUserId'] = new \MongoId($id);
							$save['fkBrandId'] = new \MongoId($brandId);
							$save['isActive'] = "1";
							$save["addedAt"] = date("Y-m-d H:i:s");
							$save["updatedAt"] = "";
							$save["deletedAt"] = "";
							$sgrecord_id = $this->_assign_data['userBrandModel']->_addRecord($save);
							unset($save);
						}
						unset($sgrecord_id);
					}
				}
			}
			
			
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been added');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module);
		}
		// return json
		return $this->_json_data;
    }
	
}
