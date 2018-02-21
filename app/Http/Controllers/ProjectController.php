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
use App\Helpers;

// models
use App\Http\Models\Project;
use App\Http\Models\Brand;
use App\Http\Models\User;
use App\Http\Models\Client;
use App\Http\Models\UserModulePermission;
use App\Http\Models\UserRole;
use App\Http\Models\Department;
use App\Http\Models\UserDesignation;
use App\Http\Models\ProjectSegment;
use App\Http\Models\UserPrivilegeStatus;
use App\Http\Models\ProjectSegmentTask;
use App\Http\Models\TaskUserAssignment;
use App\Http\Models\SegmentBoard;
use App\Http\Models\SegmentUserAssignment;
use App\Http\Models\UserBrand;
use App\Http\Models\ClientBrand;
use App\Http\Models\Invoice;
use App\Http\Models\Phase;
use App\Http\Models\TaskBoard;
use App\Http\Models\InvoiceSegment;
use App\Http\Models\File;

class ProjectController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Project', // singular title
		'p_title' => 'Projects', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "project"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "Project"; // name of primary model
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
		$this->_assign_data["projectModel"] = new Project;
		$this->_assign_data["clientModel"] = new Client;
		$this->_assign_data["brandModel"] = new Brand;
		$this->_assign_data["userModel"] = new User;
		$this->_assign_data["userRoleModel"] = new UserRole;
		$this->_assign_data["departmentModel"] = new Department;
		$this->_assign_data["userDesignationModel"] = new UserDesignation;
		$this->_assign_data["projectSegmentModel"] = new ProjectSegment;
		$this->_assign_data["userModulePermissionModel"] = new UserModulePermission;
		$this->_assign_data["userPrivilegeStatusModel"] = new UserPrivilegeStatus;
		$this->_assign_data['projectSegmentTaskModel'] = new ProjectSegmentTask;
		$this->_assign_data['taskUserAssignmentModel'] = new TaskUserAssignment;
		$this->_assign_data['segmentBoardModel'] = new SegmentBoard;
		$this->_assign_data['segmentUserAssignmentModel'] = new SegmentUserAssignment;
		$this->_assign_data['userBrandModel'] = new UserBrand;
		$this->_assign_data['clientBrandModel'] = new ClientBrand;
		$this->_assign_data["invoiceModel"] = new Invoice;
		$this->_assign_data['phaseModel'] = new Phase;
		$this->_assign_data['taskBoardModel'] = new TaskBoard;
		$this->_assign_data['invoiceSegmentModel'] = new InvoiceSegment;
		$this->_assign_data['fileModel'] = new File;
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
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		
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
		
		//Users Designation Management
		$userId = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		$roleId = \Session::get($this->_entity_session_identifier.'auth')->fkRoleId->{'$id'};
		$role = $this->_assign_data['userRoleModel']->getUpdateRecord($roleId);
		if(!empty($role)){
			foreach($role as $key => $role){}
			if(($role['userRoleName'] == 'User' || $role['userRoleName'] == 'Team Lead') || ($role['userRoleName'] == 'Vice Team Lead')){
				$search_array['fkUserId'] = new \MongoId(\Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'});
			}elseif($role['userRoleName'] == 'Manager'){
				$designationId = \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'};
				$designation = $this->_assign_data['userDesignationModel']->getUpdateRecord($designationId);
				if(!empty($designation)){
					$brandsArr = $this->_assign_data['userBrandModel']->getBrandByUserId($userId);
					if($designation[$designationId]['userDesignationName'] == 'Lead Account Manager'){
						$search_array['brands'] = $brandsArr;
					}elseif($designation[$designationId]['userDesignationName'] == 'Account Manager'){
						$search_array['brands'] = $brandsArr;
						$search_array['fkCreatedById'] = new \MongoId($userId);
					}elseif($designation[$designationId]['userDesignationName'] == 'Project Manager'){
						$departmentId = \Session::get($this->_entity_session_identifier.'auth')->fkDepartmentId->{'$id'};
						$search_array['fkSegmentId'] = new \MongoId($departmentId);
						/*$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}*/
					}
				}
			}
		}
		$paginated_ids = $this->_assign_data["projectModel"]->ajaxListing($search_array);
		
		#echo "<pre>";print_r($paginated_ids);exit;
		
		$total_records = count($paginated_ids['result']); // total records
		
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
				if (is_array($paginated_id)){
					foreach($paginated_id as $paginated_id){
						$key = $paginated_id['_id']->{'$id'};
						#echo "<pre>";print_r($paginated_id);exit;
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
								$options .= '<a class="btn btn-outline-inverse btn-xs" type="button" href="'.\URL::to(DIR_ADMIN.$this->_module.'/update/'.$id_record).'" data-toggle="tooltip" title="Update" data-original-title="Update"><span><i class="fa fa-pencil" aria-hidden="true"></i></span> Edit</a>';
							}
							//Details
							$options .= '<a class="btn btn-outline-success btn-xs" type="button" href="'.\URL::to(DIR_ADMIN.$this->_module.'/detail/'.$id_record).'" data-toggle="tooltip" title="Project Details" data-original-title="Project Details"><span><i class="fa fa-eye" aria-hidden="true"></i></span> Details</a>';
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
							
							$brand = $client = $createdBy = "-";
							if(isset($paginated_id['brandArray'][0])){
								$brand = $paginated_id['brandArray'][0]['brandName'];
							}
							if(isset($paginated_id['clientArray'][0])){
								$client = $paginated_id['clientArray'][0]['clientName'];
							}
							if(isset($paginated_id['userArray'][0])){
								$createdBy = $paginated_id['userArray'][0]['userName'];
							}
							
						}
						$options .= '</div>';
						$checkbox .= '<span></span> </label>';
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"projectName" => $paginated_id['projectName'],
							"projectPriority" => $paginated_id['projectPriority'],
							"projectDueDate" => $paginated_id['projectDueDate'],
							"fkBrandId" => $brand,
							"fkClientId" => $client,
							"fkCreatedById" => $createdBy,
							#"projectManHours" => $paginated_id['projectManHours'],
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
        // - Project name
        if($request->projectName != "") {
			$q = trim($request->projectName);
			$roleregex = new \MongoRegex("/$q/i");
			$search_array['projectName'] = $roleregex;
		}
		
		//Project Priority
		if($request->projectPriority != "") {
			$q = trim($request->projectPriority);
			$search_array['projectPriority'] = $q;
		}
		
		//Project Due Date
		if($request->projectDueDate != "") {
			$q = trim($request->projectDueDate);
			$roleregex = new \MongoRegex("/$q/i");
			$search_array['projectDueDate'] = $roleregex;
		}
		
		//Brand Id
		if($request->fkBrandId != "") {
			$q = trim($request->fkBrandId);
			//$roleregex = new \MongoRegex("/^$q/i");
			$search_array['fkBrandId'] = new \MongoId($q);
		}
		
		//Client Id
		if($request->fkClientId != "") {
			$q = trim($request->fkClientId);
			$client = $this->_assign_data["clientModel"]->getBy('clientName', new \MongoRegex("/$q/i"));
			if(!empty($client)){
				$clientId = $client['_id']->{'$id'};
				$search_array['fkClientId'] = new \MongoId($clientId);
			}else{
				$search_array['fkClientId'] = $q;
			}
		}
		
		//Created By
		if($request->fkCreatedById != "") {
			$q = trim($request->fkCreatedById);
			$user = $this->_assign_data["userModel"]->getBy('userName', new \MongoRegex("/$q/i"));
			if(!empty($user)){
				$userId = $user['_id']->{'$id'};
				$search_array['fkCreatedById'] = new \MongoId($userId);
			}else{
				$search_array['fkCreatedById'] = $q;
			}
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
			$addedregex = new \MongoRegex("/$date/i");
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
		
		//Invoices
		$this->_assign_data["invoices"] = $this->_assign_data["invoiceModel"]->getInvoices($search_array = array());
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["clients"] = $this->_assign_data["clientModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		//BrandsTagged
		$brandsTagged = array();
		$userId = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		$brandsTagged = $this->_assign_data['userBrandModel']->getBrandByUserId($userId);
		$this->_assign_data["brandsTagged"] = $brandsTagged;
		
		$this->_assign_data["brandClients"] = array();
		if(!empty($brandsTagged)){
			$this->_assign_data["brandClients"] = $this->_assign_data['clientBrandModel']->getClientByBrandArray($brandsTagged);
		}
		
		#echo "<pre>";print_r($this->_assign_data["brandClients"]);exit;
		
		//Client Add
		if($request->client_post == 1){
			return $this->_clientAdd($request, $id=false);
		}
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_add($request);
		}
		
		//validate invoice post
		if($request->invoice_post == 1){
			return $this->_addInvoice($request);
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
		#echo "<pre>";print_r($_POST);exit;
        // filter params
		$request->projectName = trim($request->projectName);
		$request->projectDescription = trim($request->projectDescription);
		$request->projectPriority = trim($request->projectPriority);
		$request->projectTotalCost = trim($request->projectTotalCost);
		$request->projectPendingCost = trim($request->projectPendingCost);
		$request->fkBrandId = trim($request->fkBrandId);
		$request->fkClientId = trim($request->fkClientId);
		#$request->fkSegmentIds = trim($request->fkSegmentIds);
		$request->isActive = trim($request->isActive);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		//$this->_json_data['addClass'] = "show";
		$this->_json_data['addClass'] = "has-error";
		$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		
		// get all modules
		if ($request->projectName == "") {
			$field_name = "projectName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->projectPriority == "") {
			$field_name = "projectPriority";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} elseif($request->projectTotalCost == ""){
			$field_name = "projectTotalCost";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
		} elseif ($request->projectPendingCost == "") {
			$field_name = "projectPendingCost";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->fkBrandId)) {
			$field_name = "fkBrandId";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->fkClientId)) {
			$field_name = "fkClientId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->fkSegmentIds)) {
			$field_name = "fkSegmentIds";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// set record
			$save['projectName'] = $request->projectName;
			$slug = str_slug($request->projectName, '-');
			$save['projectSlug'] = $slug;
			$save['projectDescription'] = $request->projectDescription;
			$save['projectPriority'] = $request->projectPriority;
			$save['projectDueDate'] = date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days"));
			$save['projectTotalCost'] = $request->projectTotalCost;
			$save['projectPendingCost'] = $request->projectPendingCost;
			$save['fkBrandId'] = new \MongoId($request->fkBrandId);
			$save['fkClientId'] = new \MongoId($request->fkClientId);
			$save['fkCreatedById'] = new \MongoId($createdById);
			$save['projectStatus'] = 'active';
			$save['isActive'] = $request->isActive;
			$save["archivedAt"] = "";
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = '';
			$save["deletedAt"] = '';
			
			//INSERT
			$record_id = $this->_assign_data['projectModel']->_addRecord($save);
			unset($save);
			
			//Invoice Update
			$invoiceId = $request->invoiceId;
			$invoice['fkProjectId'] = new \MongoId($record_id);
			$invoice['invoiceStatus'] = 0;
			$this->_assign_data['invoiceModel']->_updateRecord($invoiceId, $invoice);
			
			if(!empty($request->fkSegmentIds)){
				foreach($request->fkSegmentIds as $segmentId){
					$save['fkProjectId'] = new \MongoId($record_id);
					$save['fkSegmentId'] = new \MongoId($segmentId);
					$save['fkBrandId'] = new \MongoId($request->fkBrandId);
					$save['isActive'] = 1;
					$save["addedAt"] = date("Y-m-d H:i:s");
					$save["updatedAt"] = "";
					$save["deletedAt"] = "";
					
					$this->_assign_data['projectSegmentModel']->_addRecord($save);
					unset($save);
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
		$segmentKeys = array();
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["clients"] = $this->_assign_data["clientModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$peojectSegments = $this->_assign_data["projectSegmentModel"]->getSegmentListing($id);
		if($peojectSegments > 0){
			foreach($peojectSegments as $projectSegment){
				if(is_array($projectSegment)){
					foreach($projectSegment as $projectSegment){
						$segmentKeys[] = $projectSegment['segmentArray'][0]['_id']->{'$id'};
					}
				}
			}
		}
		$this->_assign_data["projectSegments"] = $segmentKeys;
		
		// get record
		$this->_assign_data["data"] = $this->_assign_data['projectModel']->getUpdateRecord($id);
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			redirect(\URL::to(DIR_ADMIN.$this->_module));
			return;
		}
		
		//Client Add
		if($request->client_post == 1){
			return $this->_clientAdd($request, $id);
		}
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_update($request, $id);
		}
		
		//BrandsTagged
		$brandsTagged = array();
		$userId = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		$brandsTagged = $this->_assign_data['userBrandModel']->getBrandByUserId($userId);
		$this->_assign_data["brandsTagged"] = $brandsTagged;
		
		//clientBrands
		$this->_assign_data["brandClients"] = array();
		if(!empty($brandsTagged)){
			$this->_assign_data["brandClients"] = $this->_assign_data['clientBrandModel']->getClientByBrandArray($brandsTagged);
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
		$request->projectName = trim($request->projectName);
		$request->projectDescription = trim($request->projectDescription);
		$request->projectPriority = trim($request->projectPriority);
		$request->projectTotalCost = trim($request->projectTotalCost);
		$request->projectPendingCost = trim($request->projectPendingCost);
		$request->fkBrandId = trim($request->fkBrandId);
		$request->fkClientId = trim($request->fkClientId);
		$request->projectDueDate = trim($request->projectDueDate);
		#$request->fkSegmentIds = trim($request->fkSegmentIds);
		$request->isActive = trim($request->isActive);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "has-error";
		$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		
		// get all modules
		if ($request->projectName == "") {
			$field_name = "projectName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->projectPriority == "") {
			$field_name = "projectPriority";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} elseif($request->projectTotalCost == ""){
			$field_name = "projectTotalCost";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
		} elseif ($request->projectPendingCost == "") {
			$field_name = "projectPendingCost";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->fkBrandId)) {
			$field_name = "fkBrandId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->fkClientId)) {
			$field_name = "fkClientId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->projectDueDate)) {
			$field_name = "projectDueDate";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif (!isset($request->fkSegmentIds)) {
			$field_name = "fkSegmentIds";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$save = (array)$id;
			// set record
			$save['projectName'] = $request->projectName;
			$slug = str_slug($request->projectName, '-');
			$save['projectSlug'] = $slug;
			$save['projectDescription'] = $request->projectDescription;
			$save['projectPriority'] = $request->projectPriority;
			$save['projectDueDate'] = date('Y-m-d H:i:s' ,strtotime($request->projectDueDate." 05:00:00"));
			$save['projectTotalCost'] = $request->projectTotalCost;
			$save['projectPendingCost'] = $request->projectPendingCost;
			$save['fkBrandId'] = new \MongoId($request->fkBrandId);
			$save['fkClientId'] = new \MongoId($request->fkClientId);
			$save['fkCreatedById'] = new \MongoId($createdById);
			#$save['projectManHours'] = $request->projectManHours;
			$save['isActive'] = $request->isActive;
			$save["updatedAt"] = date("Y-m-d H:i:s");
			
			// update
			$record_id = $this->_assign_data['projectModel']->_updateRecord($id, $save);
			unset($save);
			
			$this->_assign_data['projectSegmentModel']->_removeRecords($id);
			
			if(!empty($request->fkSegmentIds)){
				foreach($request->fkSegmentIds as $segmentId){
					$segmentChk = "";
					$save['fkProjectId'] = new \MongoId($id);
					$save['fkSegmentId'] = new \MongoId($segmentId);
					$save['fkBrandId'] = new \MongoId($request->fkBrandId);
					$save['isActive'] = 1;
					
					$segmentChk = $this->_assign_data['projectSegmentModel']->getUpdateRecord($id, $segmentId);
					if(!empty($segmentChk)){
						$save["updatedAt"] = date("Y-m-d H:i:s");
						$save["deletedAt"] = "";
						$this->_assign_data['projectSegmentModel']->_updateRecord($id, $segmentId, $save);
						unset($save);
					}else{							
						$save["addedAt"] = date("Y-m-d H:i:s");
						$save["updatedAt"] = "";
						$save["deletedAt"] = "";
						$record_id = $this->_assign_data['projectSegmentModel']->_addRecord($save);
						unset($save);
					}
				}
			}
			
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
					$record = $this->_assign_data["projectModel"]->getUpdateRecord($id);
					// if invalid record
					if(!empty($record)) {
						// skip master admin
						$record->deletedAt = date("Y-m-d H:i:s");
						$this->_assign_data["projectModel"]->_updateRecord($id,(array)$record);
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
				$record = $this->_assign_data["projectModel"]->getUpdateRecord($checked_id);
				// if valid record
				if($record !== FALSE) {
					// if delete
					if($request->select_action == "delete") {
						$this->_assign_data["projectModel"]->_updateRecord($checked_id, array('deletedAt' => date('Y-m-d H:i:s')));
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
     * Details
     * 
     * @return view
     */
    public function detail(Request $request, $id) {
		//Privileges
		$this->_assign_data['privileges'] = \Session::get($this->_entity_session_identifier.'privileges');
				
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		$this->_assign_data["userDetail"] = \Session::get($this->_entity_session_identifier.'auth');
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_detail($request, $id);
		}
		
		//submit task form
		if($request->do_task_post == 1){
			return $this->_segmentDetail($request, $id);	
		}
		
		if($request->do_sguser_post == 1){
			return $this->_segmentUserAdd($request, $id);
		}
		
		if($request->do_task_user_post == 1){
			return $this->_taskUserAssignment($request, $id);
		}
		
		//Project
		$project = $this->_assign_data["projectModel"]->getRecordById($id);
		if(!empty($project)){
			foreach($project as $key => $project){}
		}
		$this->_assign_data["project"] = $project;
		
		//Client
		$client = $this->_assign_data["clientModel"]->getUpdateRecord($project['fkClientId']->{'$id'});
		if(!empty($client)){
			foreach($client as $key => $client){}
		}
		$this->_assign_data["client"] = $client;
		
		//Segment ID
		$this->_assign_data["departmentDetails"] = "";
		$department_id = \Session::get($this->_entity_session_identifier.'auth')->fkDepartmentId;
		if(isset($department_id) && $department_id != '0'){
			$department_id = $department_id->{'$id'};
			//Department
			$departmentDetails = $this->_assign_data["departmentModel"]->getUpdateRecord($department_id);
			if(!empty($departmentDetails)){
				foreach($departmentDetails as $key => $details){}
				$this->_assign_data["departmentDetails"] = $details;
			}
			$this->_assign_data['allUsers'] = $this->_assign_data['userModel']->getUsersByDepartmentId($department_id);
		}else{
			$this->_assign_data['allUsers'] = $this->_assign_data['userModel']->getAllRecords();
		}
		
		//Created By
		$createdBy = $this->_assign_data['userModel']->getUpdateRecord($project['fkCreatedById']->{'$id'});
		if(!empty($createdBy)){
			foreach($createdBy as $key => $createdBy){}
		}
		$this->_assign_data["createdBy"] = $createdBy;
		
		//initialize
		$segmentDetails = $segmentAllDetails = array();
		
		$projectSegments = $this->_assign_data["projectSegmentModel"]->getSegmentListing($id);
		if($projectSegments > 0){
			foreach($projectSegments as $projectSegment){
				if(is_array($projectSegment)){
					foreach($projectSegment as $projectSegment){
						if($department_id == $projectSegment['fkSegmentId']->{'$id'}){
							$segmentDetails[] = $projectSegment;
						}
						$segmentAllDetails[] = $projectSegment;
					}
				}
			}
		}
		
		if(empty($segmentDetails)){
			$segmentDetails = $segmentAllDetails;
		}
		$this->_assign_data["projectSegments"] = $segmentDetails;
		#echo "<pre>";print_r($this->_assign_data["projectSegments"]);exit;
		
		
		//Segment Comments
		$comments = "";
		$projectId = $project['_id']->{'$id'};
		if($projectId != ""){
			$comments = $this->_assign_data['segmentBoardModel']->getRecordsByProjectId($projectId);
			if(!empty($comments) && isset($comments['result'])){
				$comments = $comments['result'];
			}
		}
		$this->_assign_data['comments'] = $comments;
		
		//brandEndTIme
		$brand = $this->_assign_data["brandModel"]->getUpdateRecord($project['fkBrandId']->{'$id'});
		if(!empty($brand)){
			foreach($brand as $key => $brand){}
		}
		$this->_assign_data["brand"] = $brand;
		
		//Tasks
		$search_array = array();
		$roleId = \Session::get($this->_entity_session_identifier.'auth')->fkRoleId->{'$id'};
		$role = $this->_assign_data['userRoleModel']->getUpdateRecord($roleId);
		if(!empty($role)){
			foreach($role as $key => $role){}
			if(($role['userRoleName'] == 'User' || $role['userRoleName'] == 'Team Lead') || ($role['userRoleName'] == 'Vice Team Lead')){
				$search_array['fkUserId'] = new \MongoId(\Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'});
			}elseif($role['userRoleName'] == 'Manager'){
				//not useful for now
				/*$departmentId = $roleId = \Session::get($this->_entity_session_identifier.'auth')->fkDepartmentId->{'$id'};
				$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
				if(!empty($department)){
					foreach($department as $key => $department){}
					$department = $department['departmentSlug'];
				}*/
			}
		}
		$search_array['fkProjectId'] = $projectId;
		$tasks = $this->_assign_data["projectSegmentTaskModel"]->getRecordsByProjectId($search_array);
		$this->_assign_data["tasks"] = $tasks;
		#echo "<pre>";print_r($tasks);exit;
		
		//Segment Users
		$segmentusers = $this->_assign_data['segmentUserAssignmentModel']->getListingByProjectId($projectId);
		$this->_assign_data["segmentUsers"] = $segmentusers;
		
		//Phases
		$this->_assign_data['phases'] = $this->_assign_data['phaseModel']->getActiveListing();
		
		//Segment Chat
		$this->_assign_data['segmentChat'] = $this->_assign_data['taskBoardModel']->getSegmentChatByProjectId($projectId);
		
		//Sales Dept
		$salesDept = $this->_assign_data['departmentModel']->getDepartmentBySlug($slug = 'sales');
		if(isset($salesDept) && !empty($salesDept)){
			foreach($salesDept as $key => $salesDept){}
		}
		$this->_assign_data['salesDept'] = $salesDept;
		#echo "<pre>";print_r($this->_assign_data['segmentChat']);exit;
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Update Status
     * 
     * @return status
     */
	public function updateStatus(Request $request, $projectId){
		$projectStatus = $request->projectStatus;
		$resp = $this->_assign_data['projectModel']->updateProjectStatus($projectId, $projectStatus);
		return $resp;
	}
	
	
	/**
     * Records Submit(private)
     * 
     * @return view
     */
	private function _detail(Request $request, $projectId){
		#echo "<pre>";print_r($_FILES);exit;
        // filter params
		$request->segmentId = trim($request->segmentId);
		$request->orderOf = trim($request->orderOf);
		
		//Check Segment Retails
		$segmentDetails = $this->_assign_data['projectSegmentModel']->getUpdateRecord($projectId, $request->segmentId);
		if(!empty($segmentDetails)){foreach($segmentDetails as $key => $segmentDetails){}}
		#echo "<pre>";print_r($segmentDetails);exit;
		
		if($request->orderOf == 'logo'){
			$request->logoName = trim($request->logoName);
			$request->logoSlogan = trim($request->logoSlogan);
			$request->logoStylePreferred = trim($request->logoStylePreferred);
			$request->logoLookAndFeel = trim($request->logoLookAndFeel);
			$request->logoConcepts = trim($request->logoConcepts);
			$request->logoAdditionalComments = trim($request->logoAdditionalComments);
			$request->logoIndustry = trim($request->logoIndustry);
			$request->logoBusinessDescription = trim($request->logoBusinessDescription);
			$request->logoTargetAudience = trim($request->logoTargetAudience);
			#$request->isActive = trim($request->isActive);
			
			// default errors class
			$this->_json_data['removeClass'] = "hide";
			$this->_json_data['addClass'] = "show";
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			
			// get all modules
			if ($request->logoName == "") {
				$field_name = "logoName";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Logo Name";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->logoStylePreferred == "") {
				$field_name = "logoStylePreferred";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Logo Style";
			} elseif($request->logoLookAndFeel == ""){
				$field_name = "logoLookAndFeel";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Look & Feel";
			} elseif ($request->logoConcepts == "") {
				$field_name = "logoConcepts";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter No. of Concepts";
			} elseif ($request->logoIndustry == "") {
				$field_name = "logoIndustry";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please select Logo Industry";
			} elseif ($request->logoTargetAudience == "") {
				$field_name = "logoTargetAudience";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Target Audience";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} else {
				#$save = (array)$id;
				// set record
				$save['fkPhaseId'] = new \MongoId($request->phaseId);
				$save['orderOf'] = $request->orderOf;
				$save['logoName'] = $request->logoName;
				$slug = str_slug($request->logoName, '-');
				$save['logoSlug'] = $slug;
				$save['logoSlogan'] = $request->logoSlogan;
				$save['logoStylePreferred'] = $request->logoStylePreferred;
				$save['logoLookAndFeel'] = $request->logoLookAndFeel;
				$save['logoConcepts'] = $request->logoConcepts;
				$save['logoAdditionalComments'] = $request->logoAdditionalComments;
				$save['logoIndustry'] = $request->logoIndustry;
				$save['logoBusinessDescription'] = $request->logoBusinessDescription;
				$save['logoTargetAudience'] = $request->logoTargetAudience;
				$save['fkCreatedById'] = new \MongoId($createdById);
				$save['updatedAt'] = date("Y-m-d H:i:s");
				
				// update
				$record_id = $this->_assign_data['projectSegmentModel']->_updateRecord($projectId, $request->segmentId, $save);
				unset($save);
				
				//task insertion
				if(isset($segmentDetails['orderOf']) && $segmentDetails['orderOf'] != ""){
				}else{
					$brandId = '0';
					$brandId = $this->_assign_data["projectModel"]->getBy('_id', new \MongoId($projectId));
					if(!empty($brandId)){
						$brandId = $brandId['fkBrandId']->{'$id'};
						$brand = $this->_assign_data["brandModel"]->getUpdateRecord($brandId);
						if(!empty($brand)){
							foreach($brand as $key => $brand){}
						}
						$duedate = getDueDate($brand['endTime']);
					}
					if(!isset($duedate) || $duedate == ""){
						$duedate = date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days"));
					}
					$save['fkProjectId'] = new \MongoId($projectId);
					$save['fkSegmentId'] = new \MongoId($request->segmentId);
					$save['fkPhaseId'] = new \MongoId($request->phaseId);
					$save['fkCreatedById'] = new \MongoId($createdById);
					$save['fkBrandId'] = new \MongoId($brandId);
					$save['orderOf'] = $request->orderOf;
					$save['taskPriority'] = 1;
					$save['taskPrevStatus'] = 0;
					$save['taskStatus'] = 1;
					$save['taskDescription'] = $request->logoBusinessDescription;
					$save['taskDueDate'] = $duedate;
					$save['revisionDueDate'] = "";
					$save['isActive'] = 1;
					$save['addedAt'] = date('Y-m-d H:i:s');
					$save['updatedAt'] = "";
					$save['deletedAt'] = "";
					
					//insert
					$record_id = $this->_assign_data['projectSegmentTaskModel']->_addRecord($save);
					unset($save);
				}
				
				
				if(isset($_FILES['postFiles']['size'][0]) && $_FILES['postFiles']['size'][0] > 0){
					$count = 0;
					foreach($_FILES['postFiles']['name'] as $key => $file){
						$count++;
						#echo "<pre>";print_r($key);echo "<br>";print_r($file);
						$year = date('Y');
						$month = date('m');
						if (!file_exists(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month)) {
							mkdir(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month, 0777, true);
						}
						$destinationPath = base_path().'/resources/assets/taskfiles/'.$year.'/'.$month."/";
						$fileName = $_FILES['postFiles']['name'][$key];
						$rand = strtoupper(substr(uniqid(sha1(time())),0,5));
						#$fileName = $rand. '.' . $request->file('file')->getClientOriginalExtension();
						$ext = pathinfo($fileName, PATHINFO_EXTENSION);
						$fileName = $rand.$count. '.' . $ext;
						$targetFile = $destinationPath.$fileName;
						
						if(move_uploaded_file($_FILES['postFiles']['tmp_name'][$key],$targetFile)){
							//insert file information into db table
							$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
								
							$save['fileName'] = $fileName;
							$save['fileType'] = "reference-material";
							$save['fkProjectId'] = new \MongoId($projectId);
							$save['fkSegmentId'] = new \MongoId($request->segmentId);
							$save['fkPhaseId'] = 0;
							$save['fkTaskId'] = 0;
							$save['fkPostId'] = 0;
							$save['fkAddedById'] = new \MongoId($createdById);
							$save['directoryPath'] = $year.'/'.$month.'/';
							$save["addedAt"] = date("Y-m-d H:i:s");
							$save["updatedAt"] = "";
							$save["deletedAt"] = "";
							$this->_assign_data['fileModel']->_addRecord($save);
							unset($save);
							#unset($_FILES['file']);
						}else{
							echo "An Error Occured!";exit;
						}
					}
					
				}
				
				
				// set session msg
				\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
				
				//redirect
				//$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId);
				header("Location: ".\URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId));
				die();
			}
		}
		elseif($request->orderOf == 'website'){
			$request->websiteBusinessName = trim($request->websiteBusinessName);
			$request->websiteSlogan = trim($request->websiteSlogan);
			$request->websiteConcepts = trim($request->websiteConcepts);
			$request->websiteLookAndFeel = trim($request->websiteLookAndFeel);
			$request->websiteLike = trim($request->websiteLike);
			$request->websiteNavMenuPreference = trim($request->websiteNavMenuPreference);
			$request->websiteExistingDomain = trim($request->websiteExistingDomain);
			$request->websiteDomainPreference = trim($request->websiteDomainPreference);
			$request->websiteAdditionalComments = trim($request->websiteAdditionalComments);
			$request->websiteIndustry = trim($request->websiteIndustry);
			$request->websiteBusinessDescription = trim($request->websiteBusinessDescription);
			$request->websiteTargetAudience = trim($request->websiteTargetAudience);
			#$request->isActive = trim($request->isActive);
			
			// default errors class
			$this->_json_data['removeClass'] = "hide";
			$this->_json_data['addClass'] = "show";
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			
			// get all modules
			if ($request->websiteBusinessName == "") {
				$field_name = "websiteBusinessName";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Business Name";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->websiteConcepts == "") {
				$field_name = "websiteConcepts";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter No. of Concepts";
			} elseif($request->websiteLookAndFeel == ""){
				$field_name = "websiteLookAndFeel";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Look & Feel";
			} elseif ($request->websiteLike == "") {
				$field_name = "websiteLike";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Specific Websites you like";
			} elseif ($request->websiteNavMenuPreference == "") {
				$field_name = "websiteNavMenuPreference";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Navigation Menu Preference";
			} elseif ($request->websiteDomainPreference == "") {
				$field_name = "websiteDomainPreference";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Domain Preference";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->websiteIndustry == "") {
				$field_name = "websiteIndustry";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please select Industry";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->websiteBusinessDescription == "") {
				$field_name = "websiteBusinessDescription";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Business Description";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->websiteTargetAudience == "") {
				$field_name = "websiteTargetAudience";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter Target Audience";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} else {
				#$save = (array)$id;
				// set record
				$save['fkPhaseId'] = new \MongoId($request->phaseId);
				$save['orderOf'] = $request->orderOf;
				$save['websiteBusinessName'] = $request->websiteBusinessName;
				$slug = str_slug($request->websiteBusinessName, '-');
				$save['websiteSlug'] = $slug;
				$save['websiteSlogan'] = $request->websiteSlogan;
				$save['websiteConcepts'] = $request->websiteConcepts;
				$save['websiteLookAndFeel'] = $request->websiteLookAndFeel;
				$save['websiteLike'] = $request->websiteLike;
				$save['websiteNavMenuPreference'] = $request->websiteNavMenuPreference;
				$save['websiteExistingDomain'] = $request->websiteExistingDomain;
				$save['websiteDomainPreference'] = $request->websiteDomainPreference;
				$save['websiteAdditionalComments'] = $request->websiteAdditionalComments;
				$save['websiteIndustry'] = $request->websiteIndustry;
				$save['websiteBusinessDescription'] = $request->websiteBusinessDescription;
				$save['websiteTargetAudience'] = $request->websiteTargetAudience;
				$save['fkCreatedById'] = new \MongoId($createdById);
				$save['updatedAt'] = date("Y-m-d H:i:s");
				
				// update
				$record_id = $this->_assign_data['projectSegmentModel']->_updateRecord($projectId, $request->segmentId, $save);
				unset($save);
				
				if(isset($segmentDetails['orderOf']) && $segmentDetails['orderOf'] != ""){
				}else{
					//task insertion
					$brandId = '0';
					$brandId = $this->_assign_data["projectModel"]->getBy('_id', new \MongoId($projectId));
					if(!empty($brandId)){
						$brandId = $brandId['fkBrandId']->{'$id'};
						$brand = $this->_assign_data["brandModel"]->getUpdateRecord($brandId);
						if(!empty($brand)){
							foreach($brand as $key => $brand){}
						}
						$duedate = getDueDate($brand['endTime']);
					}
					if(!isset($duedate) || $duedate == ""){
						$duedate = date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days"));
					}
					$save['fkProjectId'] = new \MongoId($projectId);
					$save['fkSegmentId'] = new \MongoId($request->segmentId);
					$save['fkPhaseId'] = new \MongoId($request->phaseId);
					$save['fkCreatedById'] = new \MongoId($createdById);
					$save['fkBrandId'] = new \MongoId($brandId);
					$save['orderOf'] = $request->orderOf;
					$save['taskPriority'] = 1;
					$save['taskPrevStatus'] = 0;
					$save['taskStatus'] = 16;
					$save['taskDescription'] = $request->logoBusinessDescription;
					$save['taskDueDate'] = $duedate;
					$save['revisionDueDate'] = "";
					$save['isActive'] = 1;
					$save['addedAt'] = date('Y-m-d H:i:s');
					$save['updatedAt'] = "";
					$save['deletedAt'] = "";
					
					//insert
					$record_id = $this->_assign_data['projectSegmentTaskModel']->_addRecord($save);
					unset($save);
				}
				
				if(isset($_FILES['postFiles']['size'][0]) && $_FILES['postFiles']['size'][0] > 0){
					$count = 0;
					foreach($_FILES['postFiles']['name'] as $key => $file){
						$count++;
						#echo "<pre>";print_r($key);echo "<br>";print_r($file);
						$year = date('Y');
						$month = date('m');
						if (!file_exists(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month)) {
							mkdir(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month, 0777, true);
						}
						$destinationPath = base_path().'/resources/assets/taskfiles/'.$year.'/'.$month."/";
						$fileName = $_FILES['postFiles']['name'][$key];
						$rand = strtoupper(substr(uniqid(sha1(time())),0,5));
						#$fileName = $rand. '.' . $request->file('file')->getClientOriginalExtension();
						$ext = pathinfo($fileName, PATHINFO_EXTENSION);
						$fileName = $rand.$count. '.' . $ext;
						$targetFile = $destinationPath.$fileName;
						
						if(move_uploaded_file($_FILES['postFiles']['tmp_name'][$key],$targetFile)){
							//insert file information into db table
							$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
								
							$save['fileName'] = $fileName;
							$save['fileType'] = "reference-material";
							$save['fkProjectId'] = new \MongoId($projectId);
							$save['fkSegmentId'] = new \MongoId($request->segmentId);
							$save['fkPhaseId'] = 0;
							$save['fkTaskId'] = 0;
							$save['fkPostId'] = 0;
							$save['fkAddedById'] = new \MongoId($createdById);
							$save['directoryPath'] = $year.'/'.$month.'/';
							$save["addedAt"] = date("Y-m-d H:i:s");
							$save["updatedAt"] = "";
							$save["deletedAt"] = "";
							$this->_assign_data['fileModel']->_addRecord($save);
							unset($save);
							#unset($_FILES['file']);
						}else{
							echo "An Error Occured!";exit;
						}
					}
					
				}
				
				
				// set session msg
				\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
				
				//redirect
				//$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId);
				header("Location: ".\URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId));
				die();
			}
		}
		elseif($request->orderOf == 'video'){
			
			$request->videoBusinessName = trim($request->videoBusinessName);
			$request->videoSlogan = trim($request->videoSlogan);
			$request->videoConcepts = trim($request->videoConcepts);
			$request->videoWebsiteAddress = trim($request->videoWebsiteAddress);
			$request->videoAnimationStyle = trim($request->videoAnimationStyle);
			$request->videoPrimaryUse = trim($request->videoPrimaryUse);
			$request->videoAdditionalComments = trim($request->videoAdditionalComments);
			$request->videoIndustry = trim($request->videoIndustry);
			$request->videoBusinessDescription = trim($request->videoBusinessDescription);
			$request->videoTargetAudience = trim($request->videoTargetAudience);
			#$request->isActive = trim($request->isActive);
			
			// default errors class
			$this->_json_data['removeClass'] = "hide";
			$this->_json_data['addClass'] = "show";
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			
			// get all modules
			if ($request->videoBusinessName == "") {
				$field_name = "videoBusinessName";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter business name";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->videoConcepts == "") {
				$field_name = "videoConcepts";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter no. of concepts";
			} elseif($request->videoAnimationStyle == ""){
				$field_name = "videoAnimationStyle";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter prefered style of animation";
			} elseif ($request->videoPrimaryUse == "") {
				$field_name = "videoPrimaryUse";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter primary use of video";
			} elseif ($request->videoIndustry == "") {
				$field_name = "videoIndustry";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please select video industry";
			} elseif ($request->videoBusinessDescription == "") {
				$field_name = "videoBusinessDescription";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter business description";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} elseif ($request->videoTargetAudience == "") {
				$field_name = "videoTargetAudience";
				$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
				$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
				$this->_json_data['text'] = "Please enter target audience";
				//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
			} else {
				#$save = (array)$id;
				// set record
				$save['fkPhaseId'] = new \MongoId($request->phaseId);
				$save['orderOf'] = $request->orderOf;
				$save['videoBusinessName'] = $request->videoBusinessName;
				$slug = str_slug($request->videoBusinessName, '-');
				$save['videoSlug'] = $slug;
				$save['videoSlogan'] = $request->videoSlogan;
				$save['videoConcepts'] = $request->videoConcepts;
				$save['videoWebsiteAddress'] = $request->videoWebsiteAddress;
				$save['videoAnimationStyle'] = $request->videoAnimationStyle;
				$save['videoPrimaryUse'] = $request->videoPrimaryUse;
				$save['videoAdditionalComments'] = $request->videoAdditionalComments;
				$save['videoIndustry'] = $request->videoIndustry;
				$save['videoBusinessDescription'] = $request->videoBusinessDescription;
				$save['videoTargetAudience'] = $request->videoTargetAudience;
				$save['fkCreatedById'] = new \MongoId($createdById);
				$save['updatedAt'] = date("Y-m-d H:i:s");
				
				// update
				$record_id = $this->_assign_data['projectSegmentModel']->_updateRecord($projectId, $request->segmentId, $save);
				unset($save);
				
				if(isset($segmentDetails['orderOf']) && $segmentDetails['orderOf'] != ""){
				}else{
					//task insertion
					$brandId = '0';
					$brandId = $this->_assign_data["projectModel"]->getBy('_id', new \MongoId($projectId));
					if(!empty($brandId)){
						$brandId = $brandId['fkBrandId']->{'$id'};
						$brand = $this->_assign_data["brandModel"]->getUpdateRecord($brandId);
						if(!empty($brand)){
							foreach($brand as $key => $brand){}
						}
						$duedate = getDueDate($brand['endTime']);
					}
					if(!isset($duedate) || $duedate == ""){
						$duedate = date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days"));
					}
					$save['fkProjectId'] = new \MongoId($projectId);
					$save['fkSegmentId'] = new \MongoId($request->segmentId);
					$save['fkPhaseId'] = new \MongoId($request->phaseId);
					$save['fkCreatedById'] = new \MongoId($createdById);
					$save['fkBrandId'] = new \MongoId($brandId);
					$save['orderOf'] = $request->orderOf;
					$save['taskPriority'] = 1;
					$save['taskPrevStatus'] = 0;
					$save['taskStatus'] = 31;
					$save['taskDescription'] = $request->logoBusinessDescription;
					$save['taskDueDate'] = $duedate;
					$save['revisionDueDate'] = "";
					$save['isActive'] = 1;
					$save['addedAt'] = date('Y-m-d H:i:s');
					$save['updatedAt'] = "";
					$save['deletedAt'] = "";
					
					//insert
					$record_id = $this->_assign_data['projectSegmentTaskModel']->_addRecord($save);
					unset($save);
				}
				
				if(isset($_FILES['postFiles']['size'][0]) && $_FILES['postFiles']['size'][0] > 0){
					$count = 0;
					foreach($_FILES['postFiles']['name'] as $key => $file){
						$count++;
						#echo "<pre>";print_r($key);echo "<br>";print_r($file);
						$year = date('Y');
						$month = date('m');
						if (!file_exists(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month)) {
							mkdir(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month, 0777, true);
						}
						$destinationPath = base_path().'/resources/assets/taskfiles/'.$year.'/'.$month."/";
						$fileName = $_FILES['postFiles']['name'][$key];
						$rand = strtoupper(substr(uniqid(sha1(time())),0,5));
						#$fileName = $rand. '.' . $request->file('file')->getClientOriginalExtension();
						$ext = pathinfo($fileName, PATHINFO_EXTENSION);
						$fileName = $rand.$count. '.' . $ext;
						$targetFile = $destinationPath.$fileName;
						
						if(move_uploaded_file($_FILES['postFiles']['tmp_name'][$key],$targetFile)){
							//insert file information into db table
							$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
								
							$save['fileName'] = $fileName;
							$save['fileType'] = "reference-material";
							$save['fkProjectId'] = new \MongoId($projectId);
							$save['fkSegmentId'] = new \MongoId($request->segmentId);
							$save['fkPhaseId'] = 0;
							$save['fkTaskId'] = 0;
							$save['fkPostId'] = 0;
							$save['fkAddedById'] = new \MongoId($createdById);
							$save['directoryPath'] = $year.'/'.$month.'/';
							$save["addedAt"] = date("Y-m-d H:i:s");
							$save["updatedAt"] = "";
							$save["deletedAt"] = "";
							$this->_assign_data['fileModel']->_addRecord($save);
							unset($save);
							#unset($_FILES['file']);
						}else{
							echo "An Error Occured!";exit;
						}
					}
					
				}
				
				// set session msg
				\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
				
				//redirect
				//$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId);
				header("Location: ".\URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId));
				die();
			}
		
		}
		else{
			
		}
		
		// return json
		return $this->_json_data;
    
	}
	
	
	/**
     * Segment Details
     * 
     * @return view
     */
	public function segmentDetail(Request $request, $id){
		//Privileges
		$this->_assign_data['privileges'] = \Session::get($this->_entity_session_identifier.'privileges');
				
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_segmentDetail($request, $id);
		}
		
		//Tasks
		$tasks = $this->_assign_data["projectSegmentTaskModel"]->getRecordsBySegmentId($id);
		$this->_assign_data["tasks"] = $tasks;
		
		#echo "<pre>";print_r($this->_assign_data["tasks"]);exit;
		
		//Segment
		$segment = $this->_assign_data["projectSegmentModel"]->getRecordBySegmentId($id);
		if(!empty($segment)){
			foreach($segment as $key => $segment){}
		}
		$this->_assign_data["segment"] = $segment;
		
		//Project
		$project = $this->_assign_data["projectModel"]->getRecordById($segment['fkProjectId']->{'$id'});
		if(!empty($project)){
			foreach($project as $key => $project){}
		}
		$this->_assign_data["project"] = $project;
		
		//brandEndTIme
		$brand = $this->_assign_data["brandModel"]->getUpdateRecord($project['fkBrandId']->{'$id'});
		if(!empty($brand)){
			foreach($brand as $key => $brand){}
		}
		$this->_assign_data["brand"] = $brand;
		
		//Segment ID
		$this->_assign_data["departmentDetails"] = "";
		$department_id = \Session::get($this->_entity_session_identifier.'auth')->fkDepartmentId;
		if($department_id != "0"){
			$department_id = $department_id->{'$id'};
			//Department
			$departmentDetails = $this->_assign_data["departmentModel"]->getUpdateRecord($department_id);
			if(!empty($departmentDetails)){
				foreach($departmentDetails as $key => $details){}
				$this->_assign_data["departmentDetails"] = $details;
			}
		}
		
		//Created By
		if(isset($segment['fkCreatedById'])){
			$createdBy = $this->_assign_data['userModel']->getUpdateRecord($segment['fkCreatedById']->{'$id'});
			if(!empty($createdBy)){
				foreach($createdBy as $key => $createdBy){}
			}
		}else{
			$createdBy = '';
		}
		$this->_assign_data["createdBy"] = $createdBy;
		
		//Segment Users
		$this->_assign_data["segmentUsers"] = $this->_assign_data['userModel']->getUsersByDepartmentId($segment['fkSegmentId']->{'$id'});
		
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Segment Details
     * 
     * @return redirect
     */
	private function _segmentDetail(Request $request, $id){
		#echo "<pre>";print_r($_POST);exit;
        // filter params
		$request->projectId = trim($request->projectId);
		$request->segmentId = trim($request->segmentId);
		$request->phaseId = trim($request->phaseId);
			
		$request->taskPriority = trim($request->taskPriority);
		$request->taskDescription = trim($request->taskDescription);
		$request->taskDueDate = trim($request->taskDueDate);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
					
		
		// get all modules
		if ($request->taskPriority == "") {
			$field_name = "taskPriority";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select task priority";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			#$save = (array)$id;
			$brandId = '0';
			$brandId = $this->_assign_data["projectModel"]->getBy('_id', new \MongoId($request->projectId));
			if(!empty($brandId)){
				$brandId = $brandId['fkBrandId']->{'$id'};
			}
			// set record
			$save['fkProjectId'] = new \MongoId($request->projectId);
			$save['fkSegmentId'] = new \MongoId($request->segmentId);
			$save['fkPhaseId'] = new \MongoId($request->phaseId);
			$save['fkCreatedById'] = new \MongoId($createdById);
			$save['fkBrandId'] = new \MongoId($brandId);
			$save['orderOf'] = $request->orderOf;
			$save['taskPriority'] = $request->taskPriority;
			$save['taskPrevStatus'] = 0;
			if($request->orderOf == 'logo'){
				$save['taskStatus'] = 1;
			}elseif($request->orderOf == 'website'){
				$save['taskStatus'] = 16;
			}elseif($request->orderOf == 'video'){
				$save['taskStatus'] = 31;
			}else{
				$save['taskStatus'] = 46;
			}
			$save['taskDescription'] = $request->taskDescription;
			$save['taskDueDate'] = trim($request->taskDueDate);
			$save['revisionDueDate'] = "";
			$save['isActive'] = 1;
			$save['addedAt'] = date("Y-m-d H:i:s");
			$save['updatedAt'] = "";
			$save['deletedAt'] = "";
			
			//date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days"));
			
			// update
			$record_id = $this->_assign_data['projectSegmentTaskModel']->_addRecord($save);
			unset($save);
			
			$taskId = $record_id->{'$id'};
			if(!empty($request->fkUserId)){
				foreach($request->fkUserId as $userId){
					$save['fkProjectId'] = new \MongoId($request->projectId);
					$save['fkSegmentId'] = new \MongoId($request->segmentId);
					$save['fkPhaseId'] = new \MongoId($request->phaseId);
					$save['fkTaskId'] = new \MongoId($taskId);
					$save['fkUserId'] = new \MongoId($userId);
					$save['orderOf'] = $request->orderOf;
					$save["addedAt"] = date("Y-m-d H:i:s");
					$save["updatedAt"] = "";
					$save["deletedAt"] = "";
					
					$record_id = $this->_assign_data['taskUserAssignmentModel']->_addRecord($save);
					unset($save);
					
					//segment check user entry
					$check['fkProjectId'] = new \MongoId($request->projectId);
					$check['fkSegmentId'] = new \MongoId($request->segmentId);
					$check['fkUserId'] = new \MongoId($userId);
					$checkRec = $this->_assign_data['segmentUserAssignmentModel']->checkRecord($check);
					unset($check);
					
					if(!empty($checkRec)){
						foreach($checkRec as $key => $value){
							if($value['deletedAt'] != ""){
								$id = $key;
								$update['updatedAt'] = date('Y-m-d H:i:s');
								$update['deletedAt'] = "";
								$updateId = $this->_assign_data['segmentUserAssignmentModel']->_updateRecord($id, $update);
								unset($update);
							}
						}
					}else{
						//Insert
						$save['fkProjectId'] = new \MongoId($request->projectId);
						$save['fkSegmentId'] = new \MongoId($request->segmentId);
						$save['fkUserId'] = new \MongoId($userId);
						$save['fkAddedById'] = new \MongoId($createdById);
						$save["addedAt"] = date("Y-m-d H:i:s");
						$save["updatedAt"] = "";
						$save["deletedAt"] = "";
						$sgrecord_id = $this->_assign_data['segmentUserAssignmentModel']->_addRecord($save);
						unset($save);
					}
				}
				
				//TaskStatusUpdate
				if($request->orderOf == 'logo'){
					$taskstatus['taskStatus'] = 2;
				}elseif($request->orderOf == 'website'){
					$taskstatus['taskStatus'] = 17;
				}elseif($request->orderOf == 'video'){
					$taskstatus['taskStatus'] = 32;
				}else{
					$taskstatus['taskStatus'] = 47;
				}
				$this->_assign_data['projectSegmentTaskModel']->_updateRecordByTaskId($taskId, $taskstatus);
				unset($record_id);
			}
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN."/".$this->_module."/detail/".$request->projectId);
		}
		
		
		// return json
		return $this->_json_data;
    
	}
	
	
	/**
     * Segment Board Comment Submit
     * 
     * @return success
     */
	public function segmentCommentSubmit(Request $request){
		$request->segmentComment = trim($request->segmentComment);
		
		$save['fkProjectId'] = new \MongoId($request->projectId);
		$save['fkSegmentId'] = new \MongoId($request->segmentId);
		$save['fkCommentById'] = new \MongoId(\Session::get($this->_entity_session_identifier.'auth')->{'_id'}->{'$id'});
		$save['segmentComment'] = $request->segmentComment;
		$save['isRead'] = 0;
		$save['addedAt'] = date('Y-m-d H:i:s');
		$save['updatedAt'] = "";
		$save['deletedAt'] = "";
		
		$record_id = $this->_assign_data['segmentBoardModel']->_addRecord($save);
		echo "success";
		exit;
	}
	
	
	/**
     * Segment User Add Submit
     * 
     * @return success
     */
	private function _segmentUserAdd(Request $request, $id){
		// filter params
		$request->fkProjectId = trim($request->fkProjectId);
		$request->fkSegmentId = trim($request->fkSegmentId);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
					
		
		// get all modules
		if ($request->fkUserId == "") {
			$field_name = "fkUserId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select segment users";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$this->_assign_data['segmentUserAssignmentModel']->_deleteRecord($request->fkSegmentId);
			if(!empty($request->fkUserId)){
				foreach($request->fkUserId as $userId){
					$check['fkProjectId'] = new \MongoId($request->fkProjectId);
					$check['fkSegmentId'] = new \MongoId($request->fkSegmentId);
					$check['fkUserId'] = new \MongoId($userId);
					$checkRec = $this->_assign_data['segmentUserAssignmentModel']->checkRecord($check);
					unset($check);
					
					if(!empty($checkRec)){
						foreach($checkRec as $key => $value){
							#if($value['deletedAt'] != ""){
								$id = $key;
								$update['updatedAt'] = date('Y-m-d H:i:s');
								$update['deletedAt'] = "";
								$updateId = $this->_assign_data['segmentUserAssignmentModel']->_updateRecord($id, $update);
								unset($update);
							#}
						}
					}else{
						//Insert
						$save['fkProjectId'] = new \MongoId($request->fkProjectId);
						$save['fkSegmentId'] = new \MongoId($request->fkSegmentId);
						$save['fkUserId'] = new \MongoId($userId);
						$save['fkAddedById'] = new \MongoId($createdById);
						$save["addedAt"] = date("Y-m-d H:i:s");
						$save["updatedAt"] = "";
						$save["deletedAt"] = "";
						$sgrecord_id = $this->_assign_data['segmentUserAssignmentModel']->_addRecord($save);
						unset($save);
					}
					unset($sgrecord_id);
				}
			}
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN."/".$this->_module."/detail/".$request->fkProjectId);
		}
		
		
		// return json
		return $this->_json_data;
    
	
	}
	
	
	/**
     *Client Add Submit
     * 
     * @return success
     */
	private function _clientAdd(Request $request, $id){
        // filter params
		$request->clientName = trim($request->clientName);
		$request->clientEmail = trim($request->clientEmail);
		$request->clientPhone = trim($request->clientPhone);
		$request->clientCompany = trim($request->clientCompany);
		$request->clientAddress = trim($request->clientAddress);
		$request->isActive = trim($request->isActive);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		//$this->_json_data['addClass'] = "show";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		$valid_email = Validator::make(array('clientEmail' => $request->clientEmail), array('clientEmail' => 'required|email'));
		$chk_email = $this->_assign_data["clientModel"]->getRecordByEmail($request->clientEmail);
		
		// get all modules
		if ($request->clientName == "") {
			$field_name = "clientName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter client name";
		} elseif ($valid_email->fails()) {
			$field_name = "clientEmail";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} elseif(!$valid_email->fails() && (sizeof($chk_email) > 0)){
			$field_name = "clientEmail";
			$this->_json_data['addClass'] = "has-warning";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter client email";
		} elseif ($request->clientPhone == "") {
			$field_name = "clientPhone";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter client phone";
		} elseif (!isset($request->fkBrandId)) {
			$field_name = "fkBrandId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select atleast 1 brand";
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select Status";
		} else {
			// set record
			$save['clientName'] = $request->clientName;
			$slug = str_slug($request->clientName, '-');
			$save['clientSlug'] = $slug;
			$save['clientEmail'] = $request->clientEmail;
			$save['clientPhone'] = $request->clientPhone;
			$save['clientCompany'] = $request->clientCompany;
			$save['clientAddress'] = $request->clientAddress;
			$save['addedBy'] = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			$save['isActive'] = $request->isActive;
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = '';
			$save["deletedAt"] = '';
			
			//INSERT
			$record_id = $this->_assign_data['clientModel']->_addRecord($save);
			unset($save);
			
			if(isset($request->fkBrandId)){
				foreach($request->fkBrandId as $brandId){
					unset($save);
					$save['fkClientId'] = new \MongoId($record_id);
					$save['fkBrandId'] = new \MongoId($brandId);
					$save['isActive'] = 1;
					$save["addedAt"] = date("Y-m-d H:i:s");
					$save["updatedAt"] = '';
					$save["deletedAt"] = '';
					
					//INSERT
					$this->_assign_data['clientBrandModel']->_addRecord($save);
				}
			}
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been added');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/add");
		}
		// return json
		return $this->_json_data;
	}
	
	
	/**
     *Get Invoice Details
     * 
     * @return record
     */
	public function getInvoiceDetails(Request $request){
		$invoice = $request->invoiceId;
		$search_array['_id'] = new \MongoId($invoice);
		$invoice = $this->_assign_data['invoiceModel']->getInvoices($search_array);
		$this->_json_data['invoice'] = $invoice['result'][0];
		#echo "<pre>";print_r($this->_json_data['invoice']);exit;
		
		// return json
		return $this->_json_data;
		
	}
	
	
	/**
     * Task User Assignment Submit
     * 
     * @return success
     */
	private function _taskUserAssignment(Request $request, $id){
		#echo "<pre>";print_r($_POST);exit;
		// filter params
		$request->fkProjectId = trim($request->fkProjectId);
		$request->fkSegmentId = trim($request->fkSegmentId);
		$request->fkPhaseId = trim($request->fkPhaseId);
		$request->fkTaskId = trim($request->fkTaskId);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
					
		
		// get all modules
		if ($request->fkUserId == "") {
			$field_name = "fkUserId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select task users";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$this->_assign_data['taskUserAssignmentModel']->_removeRecords($request->fkTaskId);
			if(!empty($request->fkUserId)){
				foreach($request->fkUserId as $userId){
					$check['fkProjectId'] = new \MongoId($request->fkProjectId);
					$check['fkSegmentId'] = new \MongoId($request->fkSegmentId);
					$check['fkTaskId'] = new \MongoId($request->fkTaskId);
					$check['fkUserId'] = new \MongoId($userId);
					$checkRec = $this->_assign_data['taskUserAssignmentModel']->checkRecord($check);
					unset($check);
					
					if(!empty($checkRec)){
						foreach($checkRec as $key => $value){
							$id = $key;
							$update['updatedAt'] = date('Y-m-d H:i:s');
							$update['deletedAt'] = "";
							$updateId = $this->_assign_data['taskUserAssignmentModel']->_updateRecord($id, $update);
							unset($update);
						}
					}else{
						//Insert
						$save['fkProjectId'] = new \MongoId($request->fkProjectId);
						$save['fkSegmentId'] = new \MongoId($request->fkSegmentId);
						$save['fkPhaseId'] = new \MongoId($request->fkPhaseId);
						$save['fkTaskId'] = new \MongoId($request->fkTaskId);
						$save['fkUserId'] = new \MongoId($userId);
						$save['fkAddedById'] = new \MongoId($createdById);
						$save["addedAt"] = date("Y-m-d H:i:s");
						$save["updatedAt"] = "";
						$save["deletedAt"] = "";
						$tkrecord_id = $this->_assign_data['taskUserAssignmentModel']->_addRecord($save);
						unset($save);
					
					}
					unset($tkrecord_id);
					//Segment Insertion
					$check['fkProjectId'] = new \MongoId($request->fkProjectId);
					$check['fkSegmentId'] = new \MongoId($request->fkSegmentId);
					$check['fkUserId'] = new \MongoId($userId);
					$checkRec = $this->_assign_data['segmentUserAssignmentModel']->checkRecord($check);
					unset($check);
					
					if(!empty($checkRec)){
						foreach($checkRec as $key => $value){
							$id = $key;
							$update['updatedAt'] = date('Y-m-d H:i:s');
							$update['deletedAt'] = "";
							$updateId = $this->_assign_data['segmentUserAssignmentModel']->_updateRecord($id, $update);
							unset($update);
						}
					}else{
						//Insert
						$save['fkProjectId'] = new \MongoId($request->fkProjectId);
						$save['fkSegmentId'] = new \MongoId($request->fkSegmentId);
						$save['fkUserId'] = new \MongoId($userId);
						$save['fkAddedById'] = new \MongoId($createdById);
						$save["addedAt"] = date("Y-m-d H:i:s");
						$save["updatedAt"] = "";
						$save["deletedAt"] = "";
						$sgrecord_id = $this->_assign_data['segmentUserAssignmentModel']->_addRecord($save);
						unset($save);
					}
					unset($sgrecord_id);
				}
				
				
				//TaskStatusUpdate
				if(isset($request->orderOf)){
					$tkstatus['taskPrevStatus'] = 1;
					if($request->orderOf == 'logo'){
						$tkstatus['taskStatus'] = 2;
					}elseif($request->orderOf == 'website'){
						$tkstatus['taskStatus'] = 17;
					}elseif($request->orderOf == 'video'){
						$tkstatus['taskStatus'] = 32;
					}else{
						$tkstatus['taskStatus'] = 47;
					}
					$this->_assign_data['projectSegmentTaskModel']->_updateRecordByTaskId($request->fkTaskId, $tkstatus);
				}
			}
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN."/".$this->_module."/detail/".$request->fkProjectId);
		}
		
		
		// return json
		return $this->_json_data;
    
	
	}
	
	/**
     * User Tag
     * 
     * @return success
     */
	public function userTag(Request $request){
		$request->userid = trim($request->userid);
		$request->segmentid = trim($request->segmentid);
		$request->projectid = trim($request->projectid);
		
		//Check
		$check['fkProjectId'] = new \MongoId($request->projectid);
		$check['fkSegmentId'] = new \MongoId($request->segmentid);
		$check['fkUserId'] = new \MongoId($request->userid);
		$checkRec = $this->_assign_data['segmentUserAssignmentModel']->checkRecord($check);
		unset($check);
		
		if(!empty($checkRec)){
			foreach($checkRec as $key => $value){
				$id = $key;
				$update['updatedAt'] = date('Y-m-d H:i:s');
				$update['deletedAt'] = "";
				$updateId = $this->_assign_data['segmentUserAssignmentModel']->_updateRecord($id, $update);
				unset($update);
			}
		}else{
			//Insert
			$save['fkProjectId'] = new \MongoId($request->projectid);
			$save['fkSegmentId'] = new \MongoId($request->segmentid);
			$save['fkUserId'] = new \MongoId($request->userid);
			$save['fkAddedById'] = new \MongoId(\Session::get($this->_entity_session_identifier.'auth')->{'_id'}->{'$id'});
			$save['addedAt'] = date('Y-m-d H:i:s');
			$save['updatedAt'] = "";
			$save['deletedAt'] = "";
			$sgrecord_id = $this->_assign_data['segmentUserAssignmentModel']->_addRecord($save);
			unset($save);
		}
		unset($sgrecord_id);
		echo "success";
		exit;
	}
	
	
	/**
     * Remove User Tag
     * 
     * @return success
     */
	public function removeUserTag(Request $request){
		$request->userid = trim($request->userid);
		$request->segmentid = trim($request->segmentid);
		$request->projectid = trim($request->projectid);
		
		//Check
		$check['fkProjectId'] = new \MongoId($request->projectid);
		$check['fkSegmentId'] = new \MongoId($request->segmentid);
		$check['fkUserId'] = new \MongoId($request->userid);
		$checkRec = $this->_assign_data['segmentUserAssignmentModel']->checkRecord($check);
		unset($check);
		
		if(!empty($checkRec)){
			foreach($checkRec as $key => $value){
				$id = $key;
				$update['deletedAt'] = date('Y-m-d H:i:s');
				$updateId = $this->_assign_data['segmentUserAssignmentModel']->_updateRecord($id, $update);
				unset($update);
			}
		}
		unset($sgrecord_id);
		echo "success";
		exit;
	}
	
	
	/**
     *Get Account Invoices
     * 
     * @return record
     */
	public function getAccountInvoices(Request $request){
		$clientId = $request->clientId;
		$search_array['fkClientId'] = new \MongoId($clientId);
		$invoices = $this->_assign_data['invoiceModel']->getInvoicesByClientId($search_array);
		$invArr = array();
		if(!empty($invoices)){
			foreach($invoices as $key => $invoice){
				array_push($invArr, $invoice);	
			}
		}
		$this->_json_data['invoices'] = $invArr;
		#echo "<pre>";print_r($this->_json_data);exit;
		
		// return json
		return $this->_json_data;
		
	}
	
	
	/**
     *Get Client Projects
     * 
     * @return record
     */
	public function getClientProjects(Request $request){
		$clientId = $request->clientId;
		//Projects
		$projects = $this->_assign_data['projectModel']->getRecordsByClientId($clientId);
		$pjArr = array();
		if(!empty($projects)){
			foreach($projects['result'] as $key => $project){
				array_push($pjArr, $project);
			}
		}
		$this->_json_data["projectsArray"] = $pjArr;
		#echo "<pre>";print_r($projects);exit;
		
		// return json
		return $this->_json_data;
	}
	
	
	/**
     *Post Invoice
     * 
     * @return succes
     */
	private function _addInvoice(Request $request){
		#echo "<pre>";print_r($session);exit;
		// filter params
		$request->invoiceNumber = trim($request->invoiceNumber);
		$request->invoiceType = trim($request->invoiceType);
		$request->projectId = trim($request->projectId);
		$request->clientId = trim($request->clientId);
		if(isset($request->videoSegmentAmount) && $request->videoSegmentAmount != 0){
			$request->videoSegmentAmount = trim($request->videoSegmentAmount);
			$request->videoSegmentDescription = trim($request->videoSegmentDescription);
		}
		if(isset($request->websiteSegmentAmount) && $request->websiteSegmentAmount != 0){
			$request->websiteSegmentAmount = trim($request->websiteSegmentAmount);
			$request->websiteSegmentDescription = trim($request->websiteSegmentDescription);
		}
		if(isset($request->logoSegmentAmount) && $request->logoSegmentAmount != 0){
			$request->logoSegmentAmount = trim($request->logoSegmentAmount);
			$request->logoSegmentDescription = trim($request->logoSegmentDescription);
		}
		$request->invoiceTotalAmount = trim($request->invoiceTotalAmount);
		$request->invoicePaidAmount = trim($request->invoicePaidAmount);
		$request->invoiceDescription = trim($request->invoiceDescription);
		$request->invoiceStatus = trim($request->invoiceStatus);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		#$valid_email = Validator::make(array('clientEmail' => $request->clientEmail), array('clientEmail' => 'required|email'));
		#$chk_email = $this->_assign_data["clientModel"]->getRecordByEmail($request->clientEmail);
		
		// get all modules
		if ($request->invoiceNumber == "") {
			$field_name = "invoiceNumber";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter invoice number";
		} elseif ($request->invoiceType == "") {
			$field_name = "invoiceType";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select invoice type";
		} elseif ($request->projectId == "") {
			$field_name = "projectId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select project";
		} elseif ($request->invoiceSegments == "") {
			$field_name = "invoiceSegments";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select atleast 1 segment";
		} elseif (isset($request->videoSegmentAmount) && ($request->videoSegmentAmount != 0 && $request->videoSegmentAmount == "")) {
			$field_name = "videoSegmentAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter video segment amount";
		} elseif (isset($request->videoSegmentDescription) && ($request->videoSegmentDescription == "" && $request->videoSegmentAmount  != 0)) {
			$field_name = "videoSegmentDescription";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter video segment description";
		} elseif (isset($request->websiteSegmentAmount) && ($request->websiteSegmentAmount != 0 && $request->websiteSegmentAmount == "")) {
			$field_name = "websiteSegmentAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter website segment amount";
		} elseif (isset($request->websiteSegmentDescription) && ($request->websiteSegmentDescription == "" && $request->websiteSegmentAmount  != 0)) {
			$field_name = "websiteSegmentDescription";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter website segment description";
		} elseif (isset($request->logoSegmentAmount) && ($request->logoSegmentAmount != 0 && $request->logoSegmentAmount == "")) {
			$field_name = "logoSegmentAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter logo segment amount";
		} elseif (isset($request->logoSegmentDescription) && ($request->logoSegmentDescription == "" && $request->logoSegmentAmount  != 0)) {
			$field_name = "logoSegmentDescription";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter logo segment description";
		} elseif ($request->invoiceTotalAmount == "") {
			$field_name = "invoiceTotalAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter invoice total amount";
		} elseif ($request->invoicePaidAmount == "") {
			$field_name = "invoicePaidAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter invoice paid amount";
		} elseif ($request->invoicePaidAmount > $request->invoiceTotalAmount) {
			$field_name = "invoicePaidAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Total amount should not be less than Paid amount!";
		} elseif ($request->invoiceStatus == "") {
			$field_name = "invoiceStatus";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select invoice status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			
			$projectId = $request->projectId;
			if($projectId != 0){
				$projectId = new \MongoId($projectId);
			}
			$brandId = \Session::get($this->_entity_session_identifier.'auth')->fkBrandId;
			if($brandId != 0 && $brandId != ""){
				$brandId = new \MongoId($brandId);
			}
			// set record
			$save['fkProjectId'] = $projectId;
			$save['fkClientId'] = new \MongoId($request->clientId);
			$save['fkCreatedById'] = new \MongoId($createdById);
			$save['fkBrandId'] = $brandId;
			$save['invoiceNumber'] = $request->invoiceNumber;
			$save['invoiceType'] = $request->invoiceType;
			$save['invoiceTotalAmount'] = $request->invoiceTotalAmount;
			$save['invoicePaidAmount'] = $request->invoicePaidAmount;
			$save['invoiceDescription'] = $request->invoiceDescription;
			$save['invoiceStatus'] = $request->invoiceStatus;
			$save['isActive'] = 1;
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = "";
			$save["deletedAt"] = "";
			
			// insert
			$record_id = $this->_assign_data['invoiceModel']->_addRecord($save);
			unset($save);
			
			if(isset($request->invoiceSegments[0])){
				foreach($request->invoiceSegments as $segs){
					$dept = $this->_assign_data["departmentModel"]->getUpdateRecord($segs);
					if(!empty($dept)){
						foreach($dept as $key => $dept){}
					}
					$segment = $dept['departmentSlug'];
					$save['fkInvoiceId'] = new \MongoId($record_id);
					$save['fkSegmentId'] = new \MongoId($segs);
					$save['fkProjectId'] = $projectId;
					$save['fkCreatedById'] = new \MongoId($createdById);
					$save['segmentType'] = $segment;
					if($segment == 'logo'){
						$amount = $request->logoSegmentAmount;
						$desc = $request->logoSegmentDescription;
					}elseif($segment == 'website'){
						$amount = $request->websiteSegmentAmount;
						$desc = $request->websiteSegmentDescription;
					}elseif($segment == 'video'){
						$amount = $request->videoSegmentAmount;
						$desc = $request->videoSegmentDescription;
					}
					$save['segmentAmount'] = $amount;
					$save['segmentDescription'] = $desc;
					$save['isActive'] = 1;
					$save["addedAt"] = date("Y-m-d H:i:s");
					$save["updatedAt"] = "";
					$save["deletedAt"] = "";
					
					// insert
					$invseg_id = $this->_assign_data['invoiceSegmentModel']->_addRecord($save);
					unset($save);
				}
			}
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/add/");
		}
		// return json
		return $this->_json_data;
	
	}


}
