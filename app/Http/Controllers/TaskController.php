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
use App\Http\Models\File;
use App\Http\Models\TaskBoard;

class TaskController extends Controller {
	
	private $_assign_data = array(
		's_title' => 'Task', // singular title
		'p_title' => 'Tasks', // plural title
		'p_dir' => DIR_ADMIN, // parent directory
		'page_action' => 'Listing', // default page action
		'parent_nav' => '', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "task"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "ProjectSegmentTask"; // name of primary model
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
		$this->_assign_data['fileModel'] = new File;
		$this->_assign_data['taskBoardModel'] = new TaskBoard;
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
     * Return redirect
     * 
     * @return type Array()
     */
	public function task(){
		header('Location: '.\URL::to(DIR_ADMIN.$this->_module."/due-today-tasks/"));
		exit;
	}

    
	
	/**
     * Return data to admin listing page
     * 
     * @return type Array()
     */
	public function alltasks(Request $request){
		
		if($request->do_task_user_post == 1){
			return $this->_taskUserAssignment($request);
		}
		// process delete action
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        $this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$search_array['deletedAt'] = '';
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		//allduecount
		$allduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->alldueAjaxListing($search_array);
		if(!empty($allduetasksCount)){
			$this->_assign_data["allduetasksCount"] = count($allduetasksCount['result']);
		}else{
			$this->_assign_data["allduetasksCount"] = "0";
		}
		
		//duetodaycount
		$duetodaytasksCount = $this->_assign_data["projectSegmentTaskModel"]->duetodayAjaxListing($search_array);
		if(!empty($duetodaytasksCount)){
			$this->_assign_data["duetodaytasksCount"] = count($duetodaytasksCount['result']);
		}else{
			$this->_assign_data["duetodaytasksCount"] = "0";
		}
		
		//clarificationscount
		$clarificationtasksCount = $this->_assign_data["projectSegmentTaskModel"]->clarificationAjaxListing($search_array);
		if(!empty($clarificationtasksCount)){
			$this->_assign_data["clarificationtasksCount"] = count($clarificationtasksCount['result']);
		}else{
			$this->_assign_data["clarificationtasksCount"] = "0";
		}
		
		//overduecount
		$overduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->overdueAjaxListing($search_array);
		if(!empty($overduetasksCount)){
			$this->_assign_data["overduetasksCount"] = count($overduetasksCount['result']);
		}else{
			$this->_assign_data["overduetasksCount"] = "0";
		}
		
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
	}
	
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function alltasksAjaxListing(Request $request) {
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
				/*$designationId = \Session::get($this->_entity_session_identifier.'auth')->fkDepartmentId->{'$id'};
				$department = $this->_assign_data['departmentModel']->getUpdateRecord($designationId);
				if(!empty($department)){
					foreach($department as $dkey => $department){}
					$search_array['orderOf'] = $department['departmentSlug'];
				}*/
				//not useful for now
				/*$departmentId = $roleId = \Session::get($this->_entity_session_identifier.'auth')->fkDepartmentId->{'$id'};
				$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
				if(!empty($department)){
					foreach($department as $key => $department){}
					$department = $department['departmentSlug'];
				}*/
			}
		}
		
		$paginated_ids = $this->_assign_data["projectSegmentTaskModel"]->alltasksAjaxListing($search_array);
		
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
							
							//getbrand
							$brandName = $projectName = $segmentName = $createdBy = "-";
							$projectId = $paginated_id['fkProjectId']->{'$id'};
							$brand = $this->_assign_data['projectModel']->getBrandByProjectId($projectId);
							if(!empty($brand)){
								foreach($brand as $key => $brand){}
								if(isset($brand['brandName'])){
									$brandName = $brand['brandName'];
								}
							}
							
							//projectName
							if(isset($paginated_id['projectArray'][0])){
								$projectName = $paginated_id['projectArray'][0]['projectName'];
							}elseif(isset($paginated_id['projectArray'])){
								$projectName = $paginated_id['projectArray']['projectName'];
							}
							
							//segmentName
							if(isset($paginated_id['orderOf'])){
								$segmentName = ucfirst($paginated_id['orderOf']);
							}
							
							//userName
							if(isset($paginated_id['userArray'][0])){
								$createdBy = $paginated_id['userArray'][0]['userName'];
							}
							
							//Assigned To
							$assignToArray = array();
							$appendUser = '';
							$taskId = $paginated_id['_id']->{'$id'};
							$taskusers = $this->_assign_data['taskUserAssignmentModel']->getTaskListing($taskId);
							if(isset($taskusers['result'][0])){
								foreach($taskusers as $taskuser){
									if(is_array($taskuser)){
										foreach($taskuser as $taskuser){
											$userName = $taskuser['userArray'][0]['userName'];
											array_push($assignToArray, $userName);
										}
									}
								}
							}
							if(!empty($assignToArray)){
								foreach($assignToArray as $key => $user){
									 $appendUser .= '<span class="label label-default btn-rounded">'.$user.'</span>';
								}
							}
							
							$appendUser .= '<a href="#userAdd'.$i.'" class="btn" data-toggle="modal"><i class="fa fa-plus fa-lg"></i></a>';
							
							
							$appendUser .= '<div class="modal fade" id="userAdd'.$i.'" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Users</h4> </div><form name="addUserForm" class="modal-form" method="post"><div class="modal-body"><h4>Assign to:</h4><div class="form-group"><select class="form-control" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>';
							$taskUsers = array();
							$taskUsers = $this->_assign_data["userModel"]->getUsersByDepartmentId($paginated_id["fkSegmentId"]->{'$id'});
							$usel = "";
							if(isset($taskUsers) && $taskUsers > 0){
								foreach($taskUsers as $key => $usera){
									if(!empty($assignToArray)){
										$usel = (in_array($usera['userName'], $assignToArray))? "selected=selected" : "";
									}
									$appendUser .= '<option value="'.$key.'" '.$usel.'>'.$usera['userName'].'</option>';
								}
							}
							$appendUser .= '</select><div class="error" id="error_fkUserId"></div></div></div><div class="modal-footer"><input type="hidden" name="fkProjectId" value="'. $paginated_id["fkProjectId"]->{'$id'}.'" /><input type="hidden" name="fkSegmentId" value="'.$paginated_id["fkSegmentId"]->{'$id'}.'" /><input type="hidden" name="fkPhaseId" value="'.$paginated_id["fkPhaseId"]->{'$id'}.'" /><input type="hidden" name="fkTaskId" value="'.$paginated_id["_id"]->{'$id'}.'" /><input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'" /><input type="hidden" name="orderOf" value="'.$paginated_id["orderOf"].'" /><input type="hidden" name="do_task_user_post" value="1" /><button type="button" class="btn btn-outline-inverse" data-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Save changes</button></div></form></div></div></div>';
							
							//TaskStatus
							$taskStatusArr = array();
							if($paginated_id['orderOf'] == 'logo'){
								$taskStatusArr = logoTaskStatus();
							}elseif($paginated_id['orderOf'] == 'website'){
								$taskStatusArr = websiteTaskStatus();
							}elseif($paginated_id['orderOf'] == 'video'){
								$taskStatusArr = videoTaskStatus();
							}else{
								$taskStatusArr = mobileTaskStatus();
							}
							
							if(!empty($taskStatusArr)){
								foreach($taskStatusArr as $key => $status){
									if($key == $paginated_id['taskStatus']){
										$taskStatus = $status;
									}
								}
							}
							
							if(isset($paginated_id['revisionDueDate']) && $paginated_id['revisionDueDate'] != ""){
								$dueDate = $paginated_id['revisionDueDate'];
							}else{
								$dueDate = $paginated_id['taskDueDate'];
							}
							
						}
						$options .= '</div>';
						$checkbox .= '<span></span> </label>';
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"projectName" => $projectName,
							"segmentName" => $segmentName,
							"taskStatus" => $taskStatus,
							"taskPriority" => "High Paid",
							"taskBrand" => $brandName,
							"taskDueDate" => $dueDate,
							"taskAssignedTo" => $appendUser,
							"taskAssignedBy" => $createdBy,
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
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function allduetasks(Request $request) {
		
		if($request->do_task_user_post == 1){
			return $this->_taskUserAssignment($request);
		}
		
		// process delete action
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        $this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$search_array['deletedAt'] = '';
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		//alltasksCount
		$alltasksCount = $this->_assign_data["projectSegmentTaskModel"]->alltasksAjaxListing($search_array);
		if(!empty($alltasksCount)){
			$this->_assign_data["alltasksCount"] = count($alltasksCount['result']);
		}else{
			$this->_assign_data["alltasksCount"] = "0";
		}
		
		//duetodaycount
		$duetodaytasksCount = $this->_assign_data["projectSegmentTaskModel"]->duetodayAjaxListing($search_array);
		if(!empty($duetodaytasksCount)){
			$this->_assign_data["duetodaytasksCount"] = count($duetodaytasksCount['result']);
		}else{
			$this->_assign_data["duetodaytasksCount"] = "0";
		}
		
		//clarificationscount
		$clarificationtasksCount = $this->_assign_data["projectSegmentTaskModel"]->clarificationAjaxListing($search_array);
		if(!empty($clarificationtasksCount)){
			$this->_assign_data["clarificationtasksCount"] = count($clarificationtasksCount['result']);
		}else{
			$this->_assign_data["clarificationtasksCount"] = "0";
		}
		
		//overduecount
		$overduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->overdueAjaxListing($search_array);
		if(!empty($overduetasksCount)){
			$this->_assign_data["overduetasksCount"] = count($overduetasksCount['result']);
		}else{
			$this->_assign_data["overduetasksCount"] = "0";
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function alldueAjaxListing(Request $request) {
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		//AllDueTasks
		$paginated_ids = $this->_assign_data["projectSegmentTaskModel"]->alldueAjaxListing($search_array);
		
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
							
							//getbrand
							$brandName = $projectName = $segmentName = $createdBy = "-";
							$projectId = $paginated_id['fkProjectId']->{'$id'};
							$brand = $this->_assign_data['projectModel']->getBrandByProjectId($projectId);
							if(!empty($brand)){
								foreach($brand as $key => $brand){}
								if(isset($brand['brandName'])){
									$brandName = $brand['brandName'];
								}
							}
							
							//projectName
							if(isset($paginated_id['projectArray'][0])){
								$projectName = $paginated_id['projectArray'][0]['projectName'];
							}elseif(isset($paginated_id['projectArray'])){
								$projectName = $paginated_id['projectArray']['projectName'];
							}
							
							//segmentName
							if(isset($paginated_id['orderOf'])){
								$segmentName = ucfirst($paginated_id['orderOf']);
							}
							
							//userName
							if(isset($paginated_id['userArray'][0])){
								$createdBy = $paginated_id['userArray'][0]['userName'];
							}
							
							//Assigned To
							$assignToArray = array();
							$appendUser = '';
							$taskId = $paginated_id['_id']->{'$id'};
							$taskusers = $this->_assign_data['taskUserAssignmentModel']->getTaskListing($taskId);
							if(isset($taskusers['result'][0])){
								foreach($taskusers as $taskuser){
									if(is_array($taskuser)){
										foreach($taskuser as $taskuser){
											$userName = $taskuser['userArray'][0]['userName'];
											array_push($assignToArray, $userName);
										}
									}
								}
							}
							if(!empty($assignToArray)){
								foreach($assignToArray as $key => $user){
									 $appendUser .= '<span class="label label-default btn-rounded">'.$user.'</span>';
								}
							}
							//Add User Form
							$appendUser .= '<a href="#userAdd'.$i.'" class="btn" data-toggle="modal"><i class="fa fa-plus fa-lg"></i></a>';
							
							
							$appendUser .= '<div class="modal fade" id="userAdd'.$i.'" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Users</h4> </div><form name="addUserForm" class="modal-form" method="post"><div class="modal-body"><h4>Assign to:</h4><div class="form-group"><select class="form-control" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>';
							$taskUsers = array();
							$taskUsers = $this->_assign_data["userModel"]->getUsersByDepartmentId($paginated_id["fkSegmentId"]->{'$id'});
							$usel = "";
							if(isset($taskUsers) && $taskUsers > 0){
								foreach($taskUsers as $key => $usera){
									if(!empty($assignToArray)){
										$usel = (in_array($usera['userName'], $assignToArray))? "selected=selected" : "";
									}
									$appendUser .= '<option value="'.$key.'" '.$usel.'>'.$usera['userName'].'</option>';
								}
							}
							$appendUser .= '</select><div class="error" id="error_fkUserId"></div></div></div><div class="modal-footer"><input type="hidden" name="fkProjectId" value="'. $paginated_id["fkProjectId"]->{'$id'}.'" /><input type="hidden" name="fkSegmentId" value="'.$paginated_id["fkSegmentId"]->{'$id'}.'" /><input type="hidden" name="fkPhaseId" value="'.$paginated_id["fkPhaseId"]->{'$id'}.'" /><input type="hidden" name="fkTaskId" value="'.$paginated_id["_id"]->{'$id'}.'" /><input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'" /><input type="hidden" name="orderOf" value="'.$paginated_id["orderOf"].'" /><input type="hidden" name="do_task_user_post" value="1" /><button type="button" class="btn btn-outline-inverse" data-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Save changes</button></div></form></div></div></div>';
							
							//TaskStatus
							$taskStatusArr = array();
							if($paginated_id['orderOf'] == 'logo'){
								$taskStatusArr = logoTaskStatus();
							}elseif($paginated_id['orderOf'] == 'website'){
								$taskStatusArr = websiteTaskStatus();
							}elseif($paginated_id['orderOf'] == 'video'){
								$taskStatusArr = videoTaskStatus();
							}else{
								$taskStatusArr = mobileTaskStatus();
							}
							
							if(!empty($taskStatusArr)){
								foreach($taskStatusArr as $key => $status){
									if($key == $paginated_id['taskStatus']){
										$taskStatus = $status;
									}
								}
							}
							
							if(isset($paginated_id['revisionDueDate']) && $paginated_id['revisionDueDate'] != ""){
								$dueDate = $paginated_id['revisionDueDate'];
							}else{
								$dueDate = $paginated_id['taskDueDate'];
							}
							
						}
						$options .= '</div>';
						$checkbox .= '<span></span> </label>';
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"projectName" => $projectName,
							"segmentName" => $segmentName,
							"taskStatus" => $taskStatus,
							"taskPriority" => "High Paid",
							"taskBrand" => $brandName,
							"taskDueDate" => $dueDate,
							"taskAssignedTo" => $appendUser,
							"taskAssignedBy" => $createdBy,
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
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function duetodaytasks(Request $request) {
		
		if($request->do_task_user_post == 1){
			return $this->_taskUserAssignment($request);
		}
		// process delete action
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        $this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$search_array['deletedAt'] = '';
		
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		//alltasksCount
		$alltasksCount = $this->_assign_data["projectSegmentTaskModel"]->alltasksAjaxListing($search_array);
		if(!empty($alltasksCount)){
			$this->_assign_data["alltasksCount"] = count($alltasksCount['result']);
		}else{
			$this->_assign_data["alltasksCount"] = "0";
		}
		
		//allduetasksCount
		$allduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->alldueAjaxListing($search_array);
		if(!empty($allduetasksCount)){
			$this->_assign_data["allduetasksCount"] = count($allduetasksCount['result']);
		}else{
			$this->_assign_data["allduetasksCount"] = "0";
		}
		
		//clarificationscount
		$clarificationtasksCount = $this->_assign_data["projectSegmentTaskModel"]->clarificationAjaxListing($search_array);
		if(!empty($clarificationtasksCount)){
			$this->_assign_data["clarificationtasksCount"] = count($clarificationtasksCount['result']);
		}else{
			$this->_assign_data["clarificationtasksCount"] = "0";
		}
		
		//overduecount
		$overduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->overdueAjaxListing($search_array);
		if(!empty($overduetasksCount)){
			$this->_assign_data["overduetasksCount"] = count($overduetasksCount['result']);
		}else{
			$this->_assign_data["overduetasksCount"] = "0";
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function duetodayAjaxListing(Request $request) {
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		$paginated_ids = $this->_assign_data["projectSegmentTaskModel"]->duetodayAjaxListing($search_array);
		
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
							
							//getbrand
							$brandName = $projectName = $segmentName = $createdBy = "-";
							$projectId = $paginated_id['fkProjectId']->{'$id'};
							$brand = $this->_assign_data['projectModel']->getBrandByProjectId($projectId);
							if(!empty($brand)){
								foreach($brand as $key => $brand){}
								if(isset($brand['brandName'])){
									$brandName = $brand['brandName'];
								}
							}
							
							//projectName
							if(isset($paginated_id['projectArray'][0])){
								$projectName = $paginated_id['projectArray'][0]['projectName'];
							}elseif(isset($paginated_id['projectArray'])){
								$projectName = $paginated_id['projectArray']['projectName'];
							}
							
							//segmentName
							if(isset($paginated_id['orderOf'])){
								$segmentName = ucfirst($paginated_id['orderOf']);
							}
							
							//userName
							if(isset($paginated_id['userArray'][0])){
								$createdBy = $paginated_id['userArray'][0]['userName'];
							}
							
							//Assigned To
							$assignToArray = array();
							$appendUser = '';
							$taskId = $paginated_id['_id']->{'$id'};
							$taskusers = $this->_assign_data['taskUserAssignmentModel']->getTaskListing($taskId);
							if(isset($taskusers['result'][0])){
								foreach($taskusers as $taskuser){
									if(is_array($taskuser)){
										foreach($taskuser as $taskuser){
											$userName = $taskuser['userArray'][0]['userName'];
											array_push($assignToArray, $userName);
										}
									}
								}
							}
							if(!empty($assignToArray)){
								foreach($assignToArray as $key => $user){
									 $appendUser .= '<span class="label label-default btn-rounded">'.$user.'</span>';
								}
							}
							//Add User Form
							$appendUser .= '<a href="#userAdd'.$i.'" class="btn" data-toggle="modal"><i class="fa fa-plus fa-lg"></i></a>';
							
							
							$appendUser .= '<div class="modal fade" id="userAdd'.$i.'" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Users</h4> </div><form name="addUserForm" class="modal-form" method="post"><div class="modal-body"><h4>Assign to:</h4><div class="form-group"><select class="form-control" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>';
							$taskUsers = array();
							$taskUsers = $this->_assign_data["userModel"]->getUsersByDepartmentId($paginated_id["fkSegmentId"]->{'$id'});
							$usel = "";
							if(isset($taskUsers) && $taskUsers > 0){
								foreach($taskUsers as $key => $usera){
									if(!empty($assignToArray)){
										$usel = (in_array($usera['userName'], $assignToArray))? "selected=selected" : "";
									}
									$appendUser .= '<option value="'.$key.'" '.$usel.'>'.$usera['userName'].'</option>';
								}
							}
							$appendUser .= '</select><div class="error" id="error_fkUserId"></div></div></div><div class="modal-footer"><input type="hidden" name="fkProjectId" value="'. $paginated_id["fkProjectId"]->{'$id'}.'" /><input type="hidden" name="fkSegmentId" value="'.$paginated_id["fkSegmentId"]->{'$id'}.'" /><input type="hidden" name="fkPhaseId" value="'.$paginated_id["fkPhaseId"]->{'$id'}.'" /><input type="hidden" name="fkTaskId" value="'.$paginated_id["_id"]->{'$id'}.'" /><input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'" /><input type="hidden" name="orderOf" value="'.$paginated_id["orderOf"].'" /><input type="hidden" name="do_task_user_post" value="1" /><button type="button" class="btn btn-outline-inverse" data-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Save changes</button></div></form></div></div></div>';
						
							
							//TaskStatus
							$taskStatusArr = array();
							if($paginated_id['orderOf'] == 'logo'){
								$taskStatusArr = logoTaskStatus();
							}elseif($paginated_id['orderOf'] == 'website'){
								$taskStatusArr = websiteTaskStatus();
							}elseif($paginated_id['orderOf'] == 'video'){
								$taskStatusArr = videoTaskStatus();
							}else{
								$taskStatusArr = mobileTaskStatus();
							}
							
							if(!empty($taskStatusArr)){
								foreach($taskStatusArr as $key => $status){
									if($key == $paginated_id['taskStatus']){
										$taskStatus = $status;
									}
								}
							}
							
							if(isset($paginated_id['revisionDueDate']) && $paginated_id['revisionDueDate'] != ""){
								$dueDate = $paginated_id['revisionDueDate'];
							}else{
								$dueDate = $paginated_id['taskDueDate'];
							}
							
						}
						$options .= '</div>';
						$checkbox .= '<span></span> </label>';
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"projectName" => $projectName,
							"segmentName" => $segmentName,
							"taskStatus" => $taskStatus,
							"taskPriority" => "High Paid",
							"taskBrand" => $brandName,
							"taskDueDate" => $dueDate,
							"taskAssignedTo" => $appendUser,
							"taskAssignedBy" => $createdBy,
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
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function clarificationtasks(Request $request) {
		if($request->do_task_user_post == 1){
			return $this->_taskUserAssignment($request);
		}
		// process delete action
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        $this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$search_array['deletedAt'] = '';
		
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		//alltasksCount
		$alltasksCount = $this->_assign_data["projectSegmentTaskModel"]->alltasksAjaxListing($search_array);
		if(!empty($alltasksCount)){
			$this->_assign_data["alltasksCount"] = count($alltasksCount['result']);
		}else{
			$this->_assign_data["alltasksCount"] = "0";
		}
		
		//allduetasksCount
		$allduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->alldueAjaxListing($search_array);
		if(!empty($allduetasksCount)){
			$this->_assign_data["allduetasksCount"] = count($allduetasksCount['result']);
		}else{
			$this->_assign_data["allduetasksCount"] = "0";
		}
		
		//duetodaytasksCount
		$duetodaytasksCount = $this->_assign_data["projectSegmentTaskModel"]->duetodayAjaxListing($search_array);
		if(!empty($duetodaytasksCount)){
			$this->_assign_data["duetodaytasksCount"] = count($duetodaytasksCount['result']);
		}else{
			$this->_assign_data["duetodaytasksCount"] = "0";
		}
		
		//overduetasksCount
		$overduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->overdueAjaxListing($search_array);
		if(!empty($overduetasksCount)){
			$this->_assign_data["overduetasksCount"] = count($overduetasksCount['result']);
		}else{
			$this->_assign_data["overduetasksCount"] = "0";
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function clarificationAjaxListing(Request $request) {
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		$paginated_ids = $this->_assign_data["projectSegmentTaskModel"]->alltasksAjaxListing($search_array);
		
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
							
							//getbrand
							$brandName = $projectName = $segmentName = $createdBy = "-";
							$projectId = $paginated_id['fkProjectId']->{'$id'};
							$brand = $this->_assign_data['projectModel']->getBrandByProjectId($projectId);
							if(!empty($brand)){
								foreach($brand as $key => $brand){}
								if(isset($brand['brandName'])){
									$brandName = $brand['brandName'];
								}
							}
							
							//projectName
							if(isset($paginated_id['projectArray'][0])){
								$projectName = $paginated_id['projectArray'][0]['projectName'];
							}elseif(isset($paginated_id['projectArray'])){
								$projectName = $paginated_id['projectArray']['projectName'];
							}
							
							//segmentName
							if(isset($paginated_id['orderOf'])){
								$segmentName = ucfirst($paginated_id['orderOf']);
							}
							
							//userName
							if(isset($paginated_id['userArray'][0])){
								$createdBy = $paginated_id['userArray'][0]['userName'];
							}
							
							//Assigned To
							$assignToArray = array();
							$appendUser = '';
							$taskId = $paginated_id['_id']->{'$id'};
							$taskusers = $this->_assign_data['taskUserAssignmentModel']->getTaskListing($taskId);
							if(isset($taskusers['result'][0])){
								foreach($taskusers as $taskuser){
									if(is_array($taskuser)){
										foreach($taskuser as $taskuser){
											$userName = $taskuser['userArray'][0]['userName'];
											array_push($assignToArray, $userName);
										}
									}
								}
							}
							if(!empty($assignToArray)){
								foreach($assignToArray as $key => $user){
									 $appendUser .= '<span class="label label-default btn-rounded">'.$user.'</span>';
								}
							}
							//Add User Form
							$appendUser .= '<a href="#userAdd'.$i.'" class="btn" data-toggle="modal"><i class="fa fa-plus fa-lg"></i></a>';
							
							
							$appendUser .= '<div class="modal fade" id="userAdd'.$i.'" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Users</h4> </div><form name="addUserForm" class="modal-form" method="post"><div class="modal-body"><h4>Assign to:</h4><div class="form-group"><select class="form-control" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>';
							$taskUsers = array();
							$taskUsers = $this->_assign_data["userModel"]->getUsersByDepartmentId($paginated_id["fkSegmentId"]->{'$id'});
							$usel = "";
							if(isset($taskUsers) && $taskUsers > 0){
								foreach($taskUsers as $key => $usera){
									if(!empty($assignToArray)){
										$usel = (in_array($usera['userName'], $assignToArray))? "selected=selected" : "";
									}
									$appendUser .= '<option value="'.$key.'" '.$usel.'>'.$usera['userName'].'</option>';
								}
							}
							$appendUser .= '</select><div class="error" id="error_fkUserId"></div></div></div><div class="modal-footer"><input type="hidden" name="fkProjectId" value="'. $paginated_id["fkProjectId"]->{'$id'}.'" /><input type="hidden" name="fkSegmentId" value="'.$paginated_id["fkSegmentId"]->{'$id'}.'" /><input type="hidden" name="fkPhaseId" value="'.$paginated_id["fkPhaseId"]->{'$id'}.'" /><input type="hidden" name="fkTaskId" value="'.$paginated_id["_id"]->{'$id'}.'" /><input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'" /><input type="hidden" name="orderOf" value="'.$paginated_id["orderOf"].'" /><input type="hidden" name="do_task_user_post" value="1" /><button type="button" class="btn btn-outline-inverse" data-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Save changes</button></div></form></div></div></div>';
						
							
							//TaskStatus
							$taskStatusArr = array();
							if($paginated_id['orderOf'] == 'logo'){
								$taskStatusArr = logoTaskStatus();
							}elseif($paginated_id['orderOf'] == 'website'){
								$taskStatusArr = websiteTaskStatus();
							}elseif($paginated_id['orderOf'] == 'video'){
								$taskStatusArr = videoTaskStatus();
							}else{
								$taskStatusArr = mobileTaskStatus();
							}
							
							if(!empty($taskStatusArr)){
								foreach($taskStatusArr as $key => $status){
									if($key == $paginated_id['taskStatus']){
										$taskStatus = $status;
									}
								}
							}
							
							if(isset($paginated_id['revisionDueDate']) && $paginated_id['revisionDueDate'] != ""){
								$dueDate = $paginated_id['revisionDueDate'];
							}else{
								$dueDate = $paginated_id['taskDueDate'];
							}
							
						}
						$options .= '</div>';
						$checkbox .= '<span></span> </label>';
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"projectName" => $projectName,
							"segmentName" => $segmentName,
							"taskStatus" => $taskStatus,
							"taskPriority" => "High Paid",
							"taskBrand" => $brandName,
							"taskDueDate" => $dueDate,
							"taskAssignedTo" => $appendUser,
							"taskAssignedBy" => $createdBy,
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
     * Return data to admin listing page
     * 
     * @return type Array()
     */
    public function overduetasks(Request $request) {
		
		if($request->do_task_user_post == 1){
			return $this->_taskUserAssignment($request);
		}
		// process delete action
		$this->_assign_data["userModulePermissionModel"]->checkModuleAuth($this->_module, "view", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});

        // Module permissions
        $this->_assign_data["perm_add"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "add", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_update"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "update", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
        $this->_assign_data["perm_del"] = $this->_assign_data["userModulePermissionModel"]->checkAccess($this->_module, "delete", \Session::get($this->_entity_session_identifier.'auth')->fkDesignationId->{'$id'});
		
		$this->_assign_data["brands"] = $this->_assign_data["brandModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		$search_array['deletedAt'] = '';
		
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		//alltasksCount
		$alltasksCount = $this->_assign_data["projectSegmentTaskModel"]->alltasksAjaxListing($search_array);
		if(!empty($alltasksCount)){
			$this->_assign_data["alltasksCount"] = count($alltasksCount['result']);
		}else{
			$this->_assign_data["alltasksCount"] = "0";
		}
		
		//allduetasksCount
		$allduetasksCount = $this->_assign_data["projectSegmentTaskModel"]->alldueAjaxListing($search_array);
		if(!empty($allduetasksCount)){
			$this->_assign_data["allduetasksCount"] = count($allduetasksCount['result']);
		}else{
			$this->_assign_data["allduetasksCount"] = "0";
		}
		
		//duetodaytasksCount
		$duetodaytasksCount = $this->_assign_data["projectSegmentTaskModel"]->duetodayAjaxListing($search_array);
		if(!empty($duetodaytasksCount)){
			$this->_assign_data["duetodaytasksCount"] = count($duetodaytasksCount['result']);
		}else{
			$this->_assign_data["duetodaytasksCount"] = "0";
		}
		
		//clarificationscount
		$clarificationtasksCount = $this->_assign_data["projectSegmentTaskModel"]->clarificationAjaxListing($search_array);
		if(!empty($clarificationtasksCount)){
			$this->_assign_data["clarificationtasksCount"] = count($clarificationtasksCount['result']);
		}else{
			$this->_assign_data["clarificationtasksCount"] = "0";
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function overdueAjaxListing(Request $request) {
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
						$department = $this->_assign_data['departmentModel']->getUpdateRecord($departmentId);
						if(!empty($department)){
							foreach($department as $dkey => $department){}
							$search_array['orderOf'] = $department['departmentSlug'];
						}
					}
				}
			}
		}
		
		$paginated_ids = $this->_assign_data["projectSegmentTaskModel"]->overdueAjaxListing($search_array);
		
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
							
							//getbrand
							$brandName = $projectName = $segmentName = $createdBy = "-";
							$projectId = $paginated_id['fkProjectId']->{'$id'};
							$brand = $this->_assign_data['projectModel']->getBrandByProjectId($projectId);
							if(!empty($brand)){
								foreach($brand as $key => $brand){}
								if(isset($brand['brandName'])){
									$brandName = $brand['brandName'];
								}
							}
							
							//projectName
							if(isset($paginated_id['projectArray'][0])){
								$projectName = $paginated_id['projectArray'][0]['projectName'];
							}elseif(isset($paginated_id['projectArray'])){
								$projectName = $paginated_id['projectArray']['projectName'];
							}
							
							//segmentName
							if(isset($paginated_id['orderOf'])){
								$segmentName = ucfirst($paginated_id['orderOf']);
							}
							
							//userName
							if(isset($paginated_id['userArray'][0])){
								$createdBy = $paginated_id['userArray'][0]['userName'];
							}
							
							//Assigned To
							$assignToArray = array();
							$appendUser = '';
							$taskId = $paginated_id['_id']->{'$id'};
							$taskusers = $this->_assign_data['taskUserAssignmentModel']->getTaskListing($taskId);
							if(isset($taskusers['result'][0])){
								foreach($taskusers as $taskuser){
									if(is_array($taskuser)){
										foreach($taskuser as $taskuser){
											$userName = $taskuser['userArray'][0]['userName'];
											array_push($assignToArray, $userName);
										}
									}
								}
							}
							if(!empty($assignToArray)){
								foreach($assignToArray as $key => $user){
									 $appendUser .= '<span class="label label-default btn-rounded">'.$user.'</span>';
								}
							}
							//Add User Form
							$appendUser .= '<a href="#userAdd'.$i.'" class="btn" data-toggle="modal"><i class="fa fa-plus fa-lg"></i></a>';
							
							
							$appendUser .= '<div class="modal fade" id="userAdd'.$i.'" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">Users</h4> </div><form name="addUserForm" class="modal-form" method="post"><div class="modal-body"><h4>Assign to:</h4><div class="form-group"><select class="form-control" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>';
							$taskUsers = array();
							$taskUsers = $this->_assign_data["userModel"]->getUsersByDepartmentId($paginated_id["fkSegmentId"]->{'$id'});
							$usel = "";
							if(isset($taskUsers) && $taskUsers > 0){
								foreach($taskUsers as $key => $usera){
									if(!empty($assignToArray)){
										$usel = (in_array($usera['userName'], $assignToArray))? "selected=selected" : "";
									}
									$appendUser .= '<option value="'.$key.'" '.$usel.'>'.$usera['userName'].'</option>';
								}
							}
							$appendUser .= '</select><div class="error" id="error_fkUserId"></div></div></div><div class="modal-footer"><input type="hidden" name="fkProjectId" value="'. $paginated_id["fkProjectId"]->{'$id'}.'" /><input type="hidden" name="fkSegmentId" value="'.$paginated_id["fkSegmentId"]->{'$id'}.'" /><input type="hidden" name="fkPhaseId" value="'.$paginated_id["fkPhaseId"]->{'$id'}.'" /><input type="hidden" name="fkTaskId" value="'.$paginated_id["_id"]->{'$id'}.'" /><input type="hidden" name="_token" id="csrf-token" value="'.csrf_token().'" /><input type="hidden" name="orderOf" value="'.$paginated_id["orderOf"].'" /><input type="hidden" name="do_task_user_post" value="1" /><button type="button" class="btn btn-outline-inverse" data-dismiss="modal">Close</button><button type="submit" class="btn btn-success">Save changes</button></div></form></div></div></div>';
						
							
							//TaskStatus
							$taskStatusArr = array();
							if($paginated_id['orderOf'] == 'logo'){
								$taskStatusArr = logoTaskStatus();
							}elseif($paginated_id['orderOf'] == 'website'){
								$taskStatusArr = websiteTaskStatus();
							}elseif($paginated_id['orderOf'] == 'video'){
								$taskStatusArr = videoTaskStatus();
							}else{
								$taskStatusArr = mobileTaskStatus();
							}
							
							if(!empty($taskStatusArr)){
								foreach($taskStatusArr as $key => $status){
									if($key == $paginated_id['taskStatus']){
										$taskStatus = $status;
									}
								}
							}
							
							if(isset($paginated_id['revisionDueDate']) && $paginated_id['revisionDueDate'] != ""){
								$dueDate = $paginated_id['revisionDueDate'];
							}else{
								$dueDate = $paginated_id['taskDueDate'];
							}
							
						}
						$options .= '</div>';
						$checkbox .= '<span></span> </label>';
						
						// collect data
						$records["data"][] = array(
							"ids" => $checkbox,
							"projectName" => $projectName,
							"segmentName" => $segmentName,
							"taskStatus" => $taskStatus,
							"taskPriority" => "High Paid",
							"taskBrand" => $brandName,
							"taskDueDate" => $dueDate,
							"taskAssignedTo" => $appendUser,
							"taskAssignedBy" => $createdBy,
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
			$project = $this->_assign_data["projectModel"]->getBy('projectName', new \MongoRegex("/$q/i"));
			if(!empty($project)){
				$projectId = $project['_id']->{'$id'};
				$search_array['fkProjectId'] = new \MongoId($projectId);
			}else{
				$search_array['fkProjectId'] = $q;
			}
		}
		
		//Segment Name
		if($request->segmentName != "") {
			$q = trim($request->segmentName);
			$search_array['fkSegmentId'] = new \MongoId($q);
		}
		
		//Task Priority
		if($request->taskPriority != "") {
			$q = trim($request->taskPriority);
			$search_array['taskPriority'] = $q;
		}
		
		//Task Brand
		if($request->fkBrandId != "") {
			$q = trim($request->fkBrandId);
			$search_array['fkBrandId'] = new \MongoId($q);
		}
		
		//Task Due Date
		if($request->taskDueDate != "") {
			$q = trim($request->taskDueDate);
			$dateregex = new \MongoRegex("/$q/i");
			$search_array['taskDueDate'] = $dateregex;
		}
		
		//Assign By
		if($request->taskAssignedBy != "") {
			$q = trim($request->taskAssignedBy);
			$assignTo = $this->_assign_data["userModel"]->getBy('userName', new \MongoRegex("/$q/i"));
			if(!empty($assignTo)){
				$userId = $assignTo['_id']->{'$id'};
				$search_array['fkCreatedById'] = new \MongoId($userId);
			}else{
				$search_array['fkCreatedById'] = $q;
			}
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
		$this->_assign_data["clients"] = $this->_assign_data["clientModel"]->getActiveListing();
		$this->_assign_data["segments"] = $this->_assign_data["departmentModel"]->getActiveListing();
		
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
			
			if(!empty($request->fkSegmentIds)){
				foreach($request->fkSegmentIds as $segmentId){
					$save['fkProjectId'] = new \MongoId($record_id);
					$save['fkSegmentId'] = new \MongoId($segmentId);
					$save['isActive'] = 1;
					$save["addedAt"] = date("Y-m-d H:i:s");
					$save["updatedAt"] = "";
					$save["deletedAt"] = "";
					
					$record_id = $this->_assign_data['projectSegmentModel']->_addRecord($save);
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
		if($department_id != 0){
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
		$tasks = $this->_assign_data["projectSegmentTaskModel"]->getRecordsByProjectId($projectId);
		$this->_assign_data["tasks"] = $tasks;
		#echo "<pre>";print_r($tasks);exit;
		
		//Segment Users
		$segmentusers = $this->_assign_data['segmentUserAssignmentModel']->getListingByProjectId($projectId);
		$this->_assign_data["segmentUsers"] = $segmentusers;
		#echo "<pre>";print_r($this->_assign_data['project']);exit;
		
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
		#echo "<pre>";print_r($_POST);exit;
        // filter params
		$request->segmentId = trim($request->segmentId);
		$request->orderOf = trim($request->orderOf);
		
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
				
				
				
				// set session msg
				\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
				
				//redirect
				$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId);
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
				
				
				
				// set session msg
				\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
				
				//redirect
				$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId);
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
				
				
				
				// set session msg
				\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
				
				//redirect
				$this->_json_data['redirect'] = \URL::to(DIR_ADMIN.$this->_module."/detail/".$projectId);
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
		$segment = $this->_assign_data["projectSegmentModel"]->getRecordById($id);
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
		if($department_id != 0){
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
			// set record
			$save['fkProjectId'] = new \MongoId($request->projectId);
			$save['fkSegmentId'] = new \MongoId($request->segmentId);
			$save['fkCreatedById'] = new \MongoId($createdById);
			$save['orderOf'] = $request->orderOf;
			$save['taskPriority'] = $request->taskPriority;
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
					$save['fkTaskId'] = new \MongoId($taskId);
					$save['fkUserId'] = new \MongoId($userId);
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
				unset($record_id);
			}
			
			// set session msg
			\Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(DIR_ADMIN."/segment-details/".$request->segmentId);
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
     * Segment User Add Submit
     * 
     * @return success
     */
	public function taskDetail(Request $request, $id){
		#echo "<pre>";print_r($_POST);exit;
		//Privileges
		$this->_assign_data['privileges'] = \Session::get($this->_entity_session_identifier.'privileges');
				
		// page action
		$this->_assign_data["id"] = $id;
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		//userId
		$this->_assign_data["userId"] = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		
		// validate post form
		if($request->do_file_post == 1) {
			return $this->taskFileSubmit($request, $id);
		}
		
		if($request->do_task_post == 1) {
			return $this->_taskSubmit($request, $id);
		}
		
		//Tasks
		$tasks = $this->_assign_data["projectSegmentTaskModel"]->getRecordWithUserByTaskId($id);
		foreach($tasks['result'] as $key => $task){}
		$this->_assign_data["data"] = $task;
		
		//Task Files
		$taskFiles = $this->_assign_data["fileModel"]->getRecordByTaskId($task['_id']->{'$id'});
		$this->_assign_data["taskFiles"] = $taskFiles;
		
		#echo "<pre>";print_r($taskFiles);exit;
		
		//Segment
		$segment = $this->_assign_data["projectSegmentModel"]->getRecordBySegmentId($this->_assign_data["data"]['fkSegmentId']->{'$id'});
		if(!empty($segment)){
			foreach($segment['result'] as $key => $segment){}
		}
		$this->_assign_data["segment"] = $segment;
		
		//Project
		$project = $this->_assign_data["projectModel"]->getRecordById($segment['fkProjectId']->{'$id'});
		if(!empty($project)){
			foreach($project as $key => $project){}
		}
		$this->_assign_data["project"] = $project;
		
		//Task Chat
		$search_arr['fkTaskId'] = new \MongoId($task['_id']->{'$id'});
		$taskChat = $this->_assign_data["taskBoardModel"]->ajaxListingByTaskId($search_arr);
		if(!empty($taskChat['result'])){
			//foreach($taskChat as $key => $project){}
			$taskChat = $taskChat['result'];
		}
		$this->_assign_data["taskChat"] = $taskChat;
		
		
		//Segment Users
		$this->_assign_data["segmentUsers"] = $this->_assign_data['userModel']->getUsersByDepartmentId($segment['fkSegmentId']->{'$id'});
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
	}
	
	
	/**
     * Task File Add Submit
     * 
     * @return success
     */
	private function taskFileSubmit(Request $request){
		echo "<pre>";print_r($_FILES);exit;
		/*if(!empty($_FILES)){
			$year = date('Y');
			$month = date('m');
			if (!file_exists(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month)) {
				mkdir(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month, 0777, true);
			}
			$destinationPath = base_path().'/resources/assets/taskfiles/'.$year.'/'.$month."/";
			$fileName = $_FILES['file']['name'];
			$rand = strtoupper(substr(uniqid(sha1(time())),0,5));
					$fileName = $rand. '.' . 
						$request->file('file')->getClientOriginalExtension();
			$targetFile = $destinationPath.$fileName;
			
			if(move_uploaded_file($_FILES['file']['tmp_name'],$targetFile)){
				//insert file information into db table
				$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
					
				$save['fileName'] = $fileName;
				$save['fileType'] = "";
				$save['fkProjectId'] = new \MongoId($request->projectId);
				$save['fkSegmentId'] = new \MongoId($request->segmentId);
				$save['fkPhaseId'] = new \MongoId($request->phaseId);
				$save['fkTaskId'] = new \MongoId($request->taskId);
				$save['fkPostId'] = $request->postId;
				$save['fkAddedById'] = new \MongoId($createdById);
				$save['directoryPath'] = $year.'/'.$month.'/';
				$save["addedAt"] = date("Y-m-d H:i:s");
				$save["updatedAt"] = "";
				$save["deletedAt"] = "";
				$record_id = $this->_assign_data['fileModel']->_addRecord($save);
				unset($save);
				unset($_FILES['file']);
			}
			
		}*/

		$fileName = '';
		if($request->hasFile('file')){
			$media = $request->file('file');
			$rand = strtoupper(substr(uniqid(sha1(time())),0,5));
			$fileName = $rand. '.' . 
				$request->file('file')->getClientOriginalExtension();	
			$year = date('Y');
			$month = date('m');
			if (!file_exists(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month)) {
				mkdir(base_path().'/resources/assets/taskfiles/'.$year.'/'.$month, 0777, true);
			}
			$destinationPath = base_path() . '/resources/assets/taskfiles/'.$year.'/'.$month;
			
			$media->move($destinationPath, $fileName);
			#$request->task_file =  $fileName;
			$createdById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
			
			$save['fileName'] = $fileName;
			$save['fileType'] = "";
			$save['fkProjectId'] = new \MongoId($request->projectId);
			$save['fkSegmentId'] = new \MongoId($request->segmentId);
			$save['fkPhaseId'] = new \MongoId($request->phaseId);
			$save['fkTaskId'] = new \MongoId($request->taskId);
			$save['fkBrandId'] = new \MongoId($request->brandId);
			$save['fkPostId'] = $request->postId;
			$save['fkAddedById'] = new \MongoId($createdById);
			$save['directoryPath'] = $year.'/'.$month.'/';
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = "";
			$save["deletedAt"] = "";
			$record_id = $this->_assign_data['fileModel']->_addRecord($save);
			unset($save);
			//unset($_FILES['file']);
			//empty($request->file('file'));
			
            $this->_json_data['targetElem'] = "div[id=fileIds]";
            $this->_json_data['val'] = $record_id;
			unset($record_id);
			return $this->_json_data;
		}else{
			
		}
	}
	
	
	/**
     * Task Detail Submit
     * 
     * @return success
     */
	private function _taskSubmit(Request $request){
		#echo "<pre>";print_r($_FILES);exit;
		$request->postDesc = trim($request->postDesc);
		$request->projectId = trim($request->projectId);
		$request->segmentId = trim($request->segmentId);
		$request->phaseId = trim($request->phaseId);
		$request->taskId = trim($request->taskId);
		$request->brandId = trim($request->brandId);
		$request->taskType = trim($request->taskType);
		$addedById = \Session::get($this->_entity_session_identifier.'auth')->_id->{'$id'};
		
		if($request->taskType != ""){
			if($request->taskType == 1){
				$typeSlug = "clarification";
			}elseif($request->taskType == 2){
				$typeSlug = "design";
			}elseif($request->taskType == 3){
				$typeSlug = "revision";
			}elseif($request->taskType == 4){
				$typeSlug = "redraw";
			}elseif($request->taskType == 5){
				$typeSlug = "appove";
			}elseif($request->taskType == 6){
				$typeSlug = "reject";
			}elseif($request->taskType == 7){
				$typeSlug = "proceed";
			}elseif($request->taskType == 8){
				$typeSlug = "final-files";
			}
			
			//get task status
			$taskDetail = $this->_assign_data['projectSegmentTaskModel']->getUpdateRecordByTaskId($request->taskId);
			if(!empty($taskDetail)){
				foreach($taskDetail as $key => $taskDetail){}
			}
			
			$save['postDesc'] = $request->postDesc;
			$save['postType'] = $request->taskType;
			$save['postTypeSlug'] = $typeSlug;
			if($request->taskType == 3 || $request->taskType == 4){
				$brand = $this->_assign_data['brandModel']->getUpdateRecord($request->brandId);
				if(!empty($brand)){
					foreach($brand as $key => $brand){}
				}
				$duedate = getDueDate($brand['endTime']);
				$save['dueDate'] = $duedate;
			}
			$save['fkProjectId'] = new \MongoId($request->projectId);
			$save['fkSegmentId'] = new \MongoId($request->segmentId);
			$save['fkPhaseId'] = new \MongoId($request->phaseId);
			$save['fkTaskId'] = new \MongoId($request->taskId);
			$save['fkBrandId'] = new \MongoId($request->brandId);
			$save['fkAddedById'] = new \MongoId($addedById);
			$save['orderOf'] = $request->orderOf;
			if(isset($_FILES['postFiles']['size'][0]) && $_FILES['postFiles']['size'][0] > 0){
				$save['hasFile'] = 1;	
			}else{
				$save['hasFile'] = 0;
			}
			$save['isActive'] = 1;
			$save['addedAt'] = date('Y-m-d H:i:s');
			$save['updatedAt'] = "";
			$save['deletedAt'] = "";
			$tskStatus['taskStatus'] = $taskDetail['taskStatus'];
			if($request->taskType == 1){
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
			}elseif($request->taskType == 2){
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					if($taskDetail['taskStatus'] == 2){
						$tskStatus['taskStatus'] = 3;
					}elseif($taskDetail['taskStatus'] == 7){
						$tskStatus['taskStatus'] = 8;
					}elseif($taskDetail['taskStatus'] == 10){
						$tskStatus['taskStatus'] = 11;
					}elseif($taskDetail['taskStatus'] == 13){
						$tskStatus['taskStatus'] = 14;
					}
				}elseif($request->orderOf == 'website'){
					if($taskDetail['taskStatus'] == 17){
						$tskStatus['taskStatus'] = 18;
					}elseif($taskDetail['taskStatus'] == 22){
						$tskStatus['taskStatus'] = 23;
					}elseif($taskDetail['taskStatus'] == 25){
						$tskStatus['taskStatus'] = 26;
					}elseif($taskDetail['taskStatus'] == 28){
						$tskStatus['taskStatus'] = 29;
					}
				}elseif($request->orderOf == 'video'){
					if($taskDetail['taskStatus'] == 32){
						$tskStatus['taskStatus'] = 33;
					}elseif($taskDetail['taskStatus'] == 37){
						$tskStatus['taskStatus'] = 38;
					}elseif($taskDetail['taskStatus'] == 40){
						$tskStatus['taskStatus'] = 41;
					}elseif($taskDetail['taskStatus'] == 43){
						$tskStatus['taskStatus'] = 44;
					}
				}else{
					if($taskDetail['taskStatus'] == 47){
						$tskStatus['taskStatus'] = 48;
					}elseif($taskDetail['taskStatus'] == 52){
						$tskStatus['taskStatus'] = 53;
					}elseif($taskDetail['taskStatus'] == 55){
						$tskStatus['taskStatus'] = 56;
					}elseif($taskDetail['taskStatus'] == 58){
						$tskStatus['taskStatus'] = 59;
					}
				}
				
			}elseif($request->taskType == 3){
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					$tskStatus['taskStatus'] = 13;
				}elseif($request->orderOf == 'website'){
					$tskStatus['taskStatus'] = 28;
				}elseif($request->orderOf == 'video'){
					$tskStatus['taskStatus'] = 43;
				}else{
					$tskStatus['taskStatus'] = 58;
				}
				$tskStatus['revisionDueDate'] = $duedate;
				
			}elseif($request->taskType == 4){
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					$tskStatus['taskStatus'] = 10;
				}elseif($request->orderOf == 'website'){
					$tskStatus['taskStatus'] = 25;
				}elseif($request->orderOf == 'video'){
					$tskStatus['taskStatus'] = 40;
				}else{
					$tskStatus['taskStatus'] = 55;
				}
				$tskStatus['revisionDueDate'] = $duedate;
				
			}elseif($request->taskType == 5){
				$save['postDesc'] = 'Design has been Approved!';
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					$tskStatus['taskStatus'] = 5;
				}elseif($request->orderOf == 'website'){
					$tskStatus['taskStatus'] = 20;
				}elseif($request->orderOf == 'video'){
					$tskStatus['taskStatus'] = 35;
				}else{
					$tskStatus['taskStatus'] = 50;
				}
				
			}elseif($request->taskType == 6){
				$save['postDesc'] = "Design has been Rejected<br><b>Reason: </b><br>".$save['postDesc'];
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					if($taskDetail['taskStatus'] == 3){
						$tskStatus['taskStatus'] = 2;
					}elseif($taskDetail['taskStatus'] == 8){
						$tskStatus['taskStatus'] = 7;
					}elseif($taskDetail['taskStatus'] == 11){
						$tskStatus['taskStatus'] = 10;
					}elseif($taskDetail['taskStatus'] == 14){
						$tskStatus['taskStatus'] = 13;
					}
				}elseif($request->orderOf == 'website'){
					if($taskDetail['taskStatus'] == 18){
						$tskStatus['taskStatus'] = 17;
					}elseif($taskDetail['taskStatus'] == 23){
						$tskStatus['taskStatus'] = 22;
					}elseif($taskDetail['taskStatus'] == 26){
						$tskStatus['taskStatus'] = 25;
					}elseif($taskDetail['taskStatus'] == 29){
						$tskStatus['taskStatus'] = 28;
					}
				}elseif($request->orderOf == 'video'){
					if($taskDetail['taskStatus'] == 33){
						$tskStatus['taskStatus'] = 32;
					}elseif($taskDetail['taskStatus'] == 38){
						$tskStatus['taskStatus'] = 37;
					}elseif($taskDetail['taskStatus'] == 41){
						$tskStatus['taskStatus'] = 40;
					}elseif($taskDetail['taskStatus'] == 44){
						$tskStatus['taskStatus'] = 43;
					}
				}else{
					if($taskDetail['taskStatus'] == 48){
						$tskStatus['taskStatus'] = 47;
					}elseif($taskDetail['taskStatus'] == 53){
						$tskStatus['taskStatus'] = 52;
					}elseif($taskDetail['taskStatus'] == 56){
						$tskStatus['taskStatus'] = 55;
					}elseif($taskDetail['taskStatus'] == 59){
						$tskStatus['taskStatus'] = 58;
					}
				}
				
			}elseif($request->taskType == 7){
				$save['postDesc'] = 'Design has been Processed!';
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					if($taskDetail['taskPrevStatus'] == 3){
						$tskStatus['taskStatus'] = 4;
					}elseif($taskDetail['taskPrevStatus'] == 8){
						$tskStatus['taskStatus'] = 9;
					}elseif($taskDetail['taskPrevStatus'] == 11){
						$tskStatus['taskStatus'] = 12;
					}elseif($taskDetail['taskPrevStatus'] == 14){
						$tskStatus['taskStatus'] = 15;
					}
				}elseif($request->orderOf == 'website'){
					if($taskDetail['taskPrevStatus'] == 18){
						$tskStatus['taskStatus'] = 19;
					}elseif($taskDetail['taskPrevStatus'] == 23){
						$tskStatus['taskStatus'] = 24;
					}elseif($taskDetail['taskPrevStatus'] == 26){
						$tskStatus['taskStatus'] = 27;
					}elseif($taskDetail['taskPrevStatus'] == 29){
						$tskStatus['taskStatus'] = 30;
					}
				}elseif($request->orderOf == 'video'){
					if($taskDetail['taskPrevStatus'] == 33){
						$tskStatus['taskStatus'] = 34;
					}elseif($taskDetail['taskPrevStatus'] == 38){
						$tskStatus['taskStatus'] = 39;
					}elseif($taskDetail['taskPrevStatus'] == 41){
						$tskStatus['taskStatus'] = 42;
					}elseif($taskDetail['taskPrevStatus'] == 44){
						$tskStatus['taskStatus'] = 45;
					}
				}else{
					if($taskDetail['taskPrevStatus'] == 48){
						$tskStatus['taskStatus'] = 49;
					}elseif($taskDetail['taskPrevStatus'] == 53){
						$tskStatus['taskStatus'] = 54;
					}elseif($taskDetail['taskPrevStatus'] == 56){
						$tskStatus['taskStatus'] = 57;
					}elseif($taskDetail['taskPrevStatus'] == 59){
						$tskStatus['taskStatus'] = 60;
					}
				}
				
			}elseif($request->taskType == 8){
				$save['postDesc'] = $request->postDesc;
				$record_id = $this->_assign_data['taskBoardModel']->_addRecord($save);
				
				//Update Task Status
				if($request->orderOf == 'logo'){
					$tskStatus['taskStatus'] = 7;
				}elseif($request->orderOf == 'website'){
					$tskStatus['taskStatus'] = 22;
				}elseif($request->orderOf == 'video'){
					$tskStatus['taskStatus'] = 37;
				}else{
					$tskStatus['taskStatus'] = 52;
				}
				
			}
			
			//Update Task Status
			if($taskDetail['taskStatus'] != 5){
				$tskStatus['taskPrevStatus'] = $taskDetail['taskStatus'];
			}
			$taskId = $request->taskId;
			$this->_assign_data['projectSegmentTaskModel']->_updateRecordByTaskId($taskId, $tskStatus);
			unset($save);
			
			//insert files
			$postId = $record_id;
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
						$save['fileType'] = "post-file";
						$save['fkProjectId'] = new \MongoId($request->projectId);
						$save['fkSegmentId'] = new \MongoId($request->segmentId);
						$save['fkPhaseId'] = new \MongoId($request->phaseId);
						$save['fkTaskId'] = new \MongoId($request->taskId);
						$save['fkPostId'] = new \MongoId($postId);				//$request->postId;
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
			
			//update files
			/*$token = $request->_token;
			$update['fileType'] = $request->taskType;
			$update['fkPostId'] = new \MongoId($record_id);
			$this->_assign_data['fileModel']->_updateRecordByToken($token, $update);
			unset($update);
			unset($record_id);*/
			
			// set session msg
            \Session::put(ADMIN_SESS_KEY.'success_msg', 'record has been added');

            //redirect
			header("Location: ".\URL::to("task/detail/".$request->taskId));
			die();
            #$this->_json_data['redirect'] = \URL::to("task/detail/".$request->taskId);
        
			// return json
			#return $this->_json_data;
		}
	}
	
	
	/**
     * Task User Assignment Submit
     * 
     * @return success
     */
	private function _taskUserAssignment(Request $request){
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
				$taskDetail = $this->_assign_data['projectSegmentTaskModel']->getRecordByTaskId($request->fkTaskId);
				if(!empty($taskDetail)){
					foreach($taskDetail as $key => $taskDetail){}
				}
				if(isset($taskDetail['taskStatus']) && $taskDetail['taskStatus'] == 1){
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
			//$this->_json_data['redirect'] = \URL::to(DIR_ADMIN."/".$this->_module."/detail/".$request->fkProjectId);
		}
		
		
		// return json
		#return $this->_json_data;
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit;
    
	
	}


}
