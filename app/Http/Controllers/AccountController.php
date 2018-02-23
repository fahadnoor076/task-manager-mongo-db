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
use App\Http\Models\Client;
use App\Http\Models\Brand;
use App\Http\Models\ClientBrand;
use App\Http\Models\UserModulePermission;
use App\Http\Models\UserRole;
use App\Http\Models\Project;
use App\Http\Models\ProjectOpportunity;
use App\Http\Models\Department;
use App\Http\Models\Invoice;
use App\Http\Models\InvoiceSegment;

class AccountController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Account', // singular title
		'p_title' => 'Accounts', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "account"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "Client"; // name of primary model
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
		$this->_assign_data["clientModel"] = new Client;
		$this->_assign_data["clientBrandModel"] = new ClientBrand;
		$this->_assign_data["brandModel"] = new Brand;
		$this->_assign_data["userRoleModel"] = new UserRole;
		$this->_assign_data["userModulePermissionModel"] = new UserModulePermission;
		$this->_assign_data["projectModel"] = new Project;
		$this->_assign_data["projectOpportunityModel"] = new ProjectOpportunity;
		$this->_assign_data["departmentModel"] = new Department;
		$this->_assign_data["invoiceModel"] = new Invoice;
		$this->_assign_data["invoiceSegmentModel"] = new InvoiceSegment;
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
		$this->_pk = $this->_assign_data["pk"] = "_id";
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
		
		$paginated_ids = $this->_assign_data["clientModel"]->ajaxListing($search_array);
		
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
					//details
					$options .= '<a class="btn btn-outline-success btn-xs" type="button" href="'.\URL::to(DIR_ADMIN.$this->_module.'/details/'.$id_record).'" data-toggle="tooltip" title="Account Details" data-original-title="Account Details"><span><i class="fa fa-eye" aria-hidden="true"></i></span> Details</a>';
					
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
					
					$clientBrands = $this->_assign_data["clientBrandModel"]->getBrandsListing($id_record);
					if(!empty($clientBrands)){
						$brandsArray = array();
						foreach($clientBrands as $brands){
							array_push($brandsArray, $brands['brandName']);
						}
						if(isset($brandsArray[1])){
							$brands = implode(', ', $brandsArray);
						}else{
							$brands = $brandsArray[0];
						}
					}else{
						$brands = "No Brand(s) Tagged!";
					}
				}
				$options .= '</div>';
				$checkbox .= '<span></span> </label>';
				
				// collect data
                $records["data"][] = array(
                    "ids" => $checkbox,
					"clientName" => $paginated_id['clientName'],
					"clientEmail" => $paginated_id['clientEmail'],
					"clientPhone" => $paginated_id['clientPhone'],
					"clientCompany" => $paginated_id['clientCompany'],
					"clientAddress" => $paginated_id['clientAddress'],
					"clientBrands" => $brands,
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
        // - Client name
        if($request->clientName != "") {
			$q = trim($request->clientName);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['clientName'] = $roleregex;
		}
		
		//Client Email
		if($request->clientEmail != "") {
			$q = trim($request->clientEmail);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['clientEmail'] = $roleregex;
		}
		
		//Client Phone
		if($request->clientPhone != "") {
			$q = trim($request->clientPhone);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['clientPhone'] = $roleregex;
		}
		
		//Client Company
		if($request->clientCompany != "") {
			$q = trim($request->clientCompany);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['clientCompany'] = $roleregex;
		}
		
		//Client Address
		if($request->clientAddress != "") {
			$q = trim($request->clientAddress);
			$roleregex = new \MongoRegex("/^$q/i");
			$search_array['clientAddress'] = $roleregex;
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
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		
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
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($valid_email->fails()) {
			$field_name = "clientEmail";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} elseif(!$valid_email->fails() && (sizeof($chk_email) > 0)){
			$field_name = "clientEmail";
			$this->_json_data['addClass'] = "has-warning";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
		} elseif ($request->clientPhone == "") {
			$field_name = "clientPhone";
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
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			// set record
			$save['clientName'] = $request->clientName;
			$slug = str_slug($request->clientName, '-');
			$save['clientSlug'] = $slug;
			$save['clientEmail'] = $request->clientEmail;
			$save['clientPhone'] = $request->clientPhone;
			$save['clientCompany'] = $request->clientCompany;
			$save['clientAddress'] = $request->clientAddress;
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
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		
		// get record
		$this->_assign_data["data"] = $this->_assign_data['clientModel']->getUpdateRecord($id);
		$clientBrands = $this->_assign_data['clientBrandModel']->getBrandsListing($id);
		$brandsArray = array();
		if(is_array($clientBrands) && !empty($clientBrands)){
			foreach($clientBrands as $brands){
				array_push($brandsArray, $brands['brandId']);
			}
		}
		
		$this->_assign_data["taggedBrands"] = $brandsArray;
		
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
		$request->clientName = trim($request->clientName);
		$request->clientEmail = trim($request->clientEmail);
		$request->clientPhone = trim($request->clientPhone);
		$request->clientCompany = trim($request->clientCompany);
		$request->clientAddress = trim($request->clientAddress);
		$request->isActive = trim($request->isActive);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		$valid_email = Validator::make(array('clientEmail' => $request->clientEmail), array('clientEmail' => 'required|email'));
		#$chk_email = $this->_assign_data["clientModel"]->getRecordByEmail($request->clientEmail);
		
		// get all modules
		if ($request->clientName == "") {
			$field_name = "clientName";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please enter Role Name";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} elseif ($valid_email->fails()) {
			$field_name = "clientEmail";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = $valid_email->errors()->first();
		} /*elseif(!$valid_email->fails() && (sizeof($chk_email) > 0)){
			$field_name = "clientEmail";
			$this->_json_data['addClass'] = "has-warning";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
		} */elseif ($request->clientPhone == "") {
			$field_name = "clientPhone";
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
		} elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$save = (array)$id;
			// set record
			$save['clientName'] = $request->clientName;
			$slug = str_slug($request->clientName, '-');
			$save['clientSlug'] = $slug;
			$save['clientEmail'] = $request->clientEmail;
			$save['clientPhone'] = $request->clientPhone;
			$save['clientCompany'] = $request->clientCompany;
			$save['clientAddress'] = $request->clientAddress;
			$save['isActive'] = $request->isActive;
			$save["updatedAt"] = date("Y-m-d H:i:s");
			
			// update
			$record_id = $this->_assign_data['clientModel']->_updateRecord($id, $save);
			unset($save);
			
			$del = $this->_assign_data['clientBrandModel']->_removeRecord($id);
			
			if(isset($request->fkBrandId)){
				foreach($request->fkBrandId as $brandId){
					unset($save);
					$save['fkClientId'] = new \MongoId($id);
					$save['fkBrandId'] = new \MongoId($brandId);
					$save['isActive'] = 1;
					$save["addedAt"] = date("Y-m-d H:i:s");
					$save["updatedAt"] = date("Y-m-d H:i:s");
					$save["deletedAt"] = '';
					
					//INSERT
					$this->_assign_data['clientBrandModel']->_addRecord($save);
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
					$record = $this->_assign_data["clientModel"]->getUpdateRecord($id);
					// if invalid record
					if(!empty($record)) {
						// skip master admin
						$record->deletedAt = date("Y-m-d H:i:s");
						$this->_assign_data["clientModel"]->_updateRecord($id,(array)$record);
						$i_removed++;
						
						//remove client brand tagging
						$this->_assign_data["clientBrandModel"]->_updateRecordByClientId($id,(array)$record);
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
				$record = $this->_assign_data["clientModel"]->getUpdateRecord($checked_id);
				// if valid record
				if($record !== FALSE) {
					// if delete
					if($request->select_action == "delete") {
						$this->_assign_data["clientModel"]->_updateRecord($checked_id, array('deletedAt' => date('Y-m-d H:i:s')));
						#$this->_model->remove($record->{$this->_pk});
						/*$record->deletedAt = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);*/
						
						$this->_assign_data["clientBrandModel"]->_updateRecordByClientId($checked_id, array('deletedAt' => date('Y-m-d H:i:s')));
						//Remove Brand tagging
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
     * Detail
     * 
     * @return view
     */
    public function detail(Request $request, $id) {
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		if(strlen($id) != 24){
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			redirect(\URL::to(DIR_ADMIN.$this->_module));
			return;
		}
		
		// validate post form
		if($request->opportunity_post == 1){
			return $this->_detail($request, $id);
		}
		// validate post form
		if($request->opportunity_update == 1){
			return $this->_updatedetail($request, $id);
		}
		
		// validate invoice form
		if($request->invoice_post == 1){
			return $this->_invoiceDetail($request, $id);
		}
		
		
		// get record
		$data = $this->_assign_data['clientModel']->getUpdateRecord($id);
		if(!empty($data)){
			foreach($data as $data){}
		}
		$this->_assign_data["data"] = $data;
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			redirect(\URL::to(DIR_ADMIN.$this->_module));
			return;
		}
		
		$clientBrands = $this->_assign_data['clientBrandModel']->getBrandsListing($id);
		$brandsArray = array();
		if(is_array($clientBrands) && !empty($clientBrands)){
			foreach($clientBrands as $brands){
				array_push($brandsArray, $brands['brandId']);
			}
		}
		$this->_assign_data["taggedBrands"] = $brandsArray;
		
		//Projects
		$this->_assign_data["projects"] = $this->_assign_data['projectModel']->getRecordsByClientId($id);
		$projectsArr = array();
		if(isset($this->_assign_data["projects"]["result"])){
			$projects = $this->_assign_data["projects"]["result"];
			foreach($projects as $key => $pj){
				$projectsArr[$pj['_id']->{'$id'}] = $pj['projectName'];	
			}
		}
		$this->_assign_data["projectsArray"] = $projectsArr;
		#echo "<pre>";print_r($this->_assign_data["projectsArray"]);exit;
		
		//Opportuinty
		$this->_assign_data["opportunities"] = $this->_assign_data["projectOpportunityModel"]->getActiveListingByClientId($id);
		
		//Segments
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->ajaxListing($search_array=false);
		
		//Invoices
		$this->_assign_data["invoices"] = $this->_assign_data["invoiceModel"]->ajaxListingByClientId($id, $search_array=false);
		
		#echo "<pre>";print_r($this->_assign_data["invoices"]);exit;
		
		#echo "<pre>";print_r($this->_assign_data["projects"]);exit;
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Detail (private)
     * 
     * @return view
     */
    private function _detail(Request $request, $id) {
        // filter params
		$request->projectId = trim($request->projectId);
		$request->clientId = trim($request->clientId);
		$request->opportunityAmount = trim($request->opportunityAmount);
		$request->opportunityDescription = trim($request->opportunityDescription);
		$request->opportunityStatus = trim($request->opportunityStatus);
		
		// default errors class
		//$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		#$valid_email = Validator::make(array('clientEmail' => $request->clientEmail), array('clientEmail' => 'required|email'));
		#$chk_email = $this->_assign_data["clientModel"]->getRecordByEmail($request->clientEmail);
		
		// get all modules
		if ($request->projectId == "") {
			$field_name = "projectId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select project";
		} elseif ($request->opportunityAmount == "") {
			$field_name = "opportunityAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter opportunity amount";
		} elseif ($request->opportunityDescription == "") {
			$field_name = "opportunityDescription";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter opportunity description";
		} elseif (!isset($request->opportunityStatus)) {
			$field_name = "opportunityStatus";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select opportunity status";
		} /*elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} */else {
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			$save = (array)$id;
			// set record
			$save['fkProjectId'] = new \MongoId($request->projectId);
			$save['fkClientId'] = new \MongoId($request->clientId);
			$save['fkCreatedById'] = new \MongoId($createdById);
			$save['fkUpdatedById'] = "";
			$save['opportunityAmount'] = $request->opportunityAmount;
			$save['opportunityDescription'] = $request->opportunityDescription;
			$save['opportunityStatus'] = $request->opportunityStatus;
			$save['opportunityStatus'] = $request->opportunityStatus;
			$save['isActive'] = 1;
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = "";
			$save["deletedAt"] = "";
			
			// update
			$record_id = $this->_assign_data['projectOpportunityModel']->_addRecord($save);
			unset($save);
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/details/".$request->clientId);
		}
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * Detail (private)
     * 
     * @return view
     */
	private function _updatedetail(Request $request, $id){
        // filter params
		#$request->projectId = trim($request->projectId);
		#$request->clientId = trim($request->clientId);
		$request->opportunityAmount = trim($request->opportunityAmount);
		$request->opportunityDescription = trim($request->opportunityDescription);
		$request->opportunityStatus = trim($request->opportunityStatus);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "has-error";
		
		// validator
		#$valid_email = Validator::make(array('clientEmail' => $request->clientEmail), array('clientEmail' => 'required|email'));
		#$chk_email = $this->_assign_data["clientModel"]->getRecordByEmail($request->clientEmail);
		
		// get all modules
		if ($request->projectId == "") {
			$field_name = "projectId";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select project";
		} elseif ($request->opportunityAmount == "") {
			$field_name = "opportunityAmount";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter opportunity amount";
		} elseif ($request->opportunityDescription == "") {
			$field_name = "opportunityDescription";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter opportunity description";
		} elseif (!isset($request->opportunityStatus)) {
			$field_name = "opportunityStatus";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select opportunity status";
		} /*elseif ($request->isActive == "") {
			$field_name = "isActive";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			//$this->_json_data['text'] = "Please select Status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} */else {
			$updatedById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			$save = (array)$id;
			// set record
			$save['fkProjectId'] = new \MongoId($request->projectId);
			#$save['fkClientId'] = new \MongoId($request->clientId);
			#$save['fkCreatedById'] = new \MongoId($createdById);
			$save['fkUpdatedById'] = new \MongoId($updatedById);
			$save['opportunityAmount'] = $request->opportunityAmount;
			$save['opportunityDescription'] = $request->opportunityDescription;
			$save['opportunityStatus'] = $request->opportunityStatus;
			$save['isActive'] = 1;
			$save["updatedAt"] = date("Y-m-d H:i:s");
			
			// update
			$record_id = $this->_assign_data['projectOpportunityModel']->_updateRecord($request->_id, $save);
			unset($save);
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/details/".$request->clientId);
		}
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * Delete
     * 
     * @return status
     */
	function removeOpportunity(Request $request){
		$id = $request->id;
		$this->_assign_data["projectOpportunityModel"]->_removeRecord($id);
		return "success";
	}
	
	
	/**
     * Invoice Post (private)
     * 
     * @return view
     */
	private function _invoiceDetail(Request $request, $id){
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
		} elseif ($request->invoiceStatus == "") {
			$field_name = "invoiceStatus";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_" . $field_name . "]";
			$this->_json_data['text'] = "Please select invoice status";
			//$this->_json_data['trigger'] = array("elem" => "a[href=#tab-step1]", "event" => "click");
		} else {
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			$save = (array)$id;
			
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
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/details/".$request->clientId);
		}
		// return json
		return $this->_json_data;
	}


}
