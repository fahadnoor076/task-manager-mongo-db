<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Session;
use Validator;
use Image;
// load models
use App\Http\Models\ApiMethod;
use App\Http\Models\ApiMethodField;
use App\Http\Models\ApiUser;
use App\Http\Models\EFEntityPlugin;

class {wildcard_ucword}FriendController extends Controller { 

    protected $_assignData = array(
        'pDir' => '',
        'dir' => DIR_API
    );
    protected $_apiData = array();
    protected $_layout = "";
    protected $_models = array();
    protected $_jsonData = array();
	protected $_model_path = "\App\Http\Models\\";
	protected $_entity_identifier = "{wildcard_identifier}";
	protected $_entity_pk = "{wildcard_pk}";
	protected $_entity_ucfirst = "{wildcard_ucword}";
	protected $_entity_model;
	protected $_base_entity_id = "{base_entity_id}";
	protected $_target_entity_identifier = "{wildcard_ucword}";
	protected $_target_entity_pk = "{wildcard_pk}";
	protected $_target_entity_ucfirst = "{wildcard_ucword}";
	protected $_target_entity_model;
	protected $_plugin_identifier = "{plugin_identifier}";
	protected $_entity_history_model;
	protected $_plugin_config = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {
		// load entity model
		$this->_entity_model = $this->_model_path.$this->_entity_ucfirst;
		$this->_entity_model = new $this->_entity_model;
		// load target entity model
		$this->_target_entity_model = $this->_model_path.$this->_target_entity_ucfirst;
		$this->_target_entity_model = new $this->_target_entity_model;
		// entity history model
		$this->_entity_history_model = $this->_model_path."EntityHistory";
		$this->_entity_history_model = new $this->_entity_history_model;
		
        // init models
        $this->__models['api_method_model'] = new ApiMethod;
        $this->__models['api_user_model'] = new ApiUser;
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;
		
        // error response by default
        $this->_apiData['kick_user'] = 0;
        $this->_apiData['response'] = "error";

        // check access
        $this->__models['api_user_model']->checkAccess($request);
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_base_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_plugin_config->webservices = isset($this->_plugin_config->webservices) ? $this->_plugin_config->webservices : array();
		
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        
    }
	
	
	/**
     * addFriend
     *
     * @return Response
     */
    public function addFriend(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}-friend_add";
		$activity_reference = "{wildcard_identifier}_friend"; // table name for best referencing / navigation
		$activity_navigation = "{wildcard_identifier}";
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"target_{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1"
		));
		
		// existance
		$is_exists = \DB::table($pModel->table)
			->where("{wildcard_pk}","=",$request->{wildcard_pk})
			->whereRaw("((`{wildcard_pk}` = '".$request->{wildcard_pk}."' AND `target_{wildcard_pk}` = '".$request->target_{wildcard_pk}."') OR (`target_{wildcard_pk}` = '".$request->{wildcard_pk}."' AND `{wildcard_pk}` = '".$request->target_{wildcard_pk}."'))")
			->where("status","<=",1)
			->whereNull("deleted_at")
			->count();
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/add", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if ($request->{wildcard_pk} == $request->target_{wildcard_pk}) {
            $this->_apiData['message'] = trans('api_errors.cannot_friend_self');
        } else if ($is_exists > 0) {
            $this->_apiData['message'] = trans('api_errors.cannot_add_friend',array("entity" => {wildcard_ucword}));
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
	
			// prepare save data
			$save["{wildcard_pk}"] = $request->{wildcard_pk};
			$save["target_{wildcard_pk}"] = $request->target_{wildcard_pk};
			$save["status"] = 0;
			$save['created_at'] = date('Y-m-d H:i:s');
			
			// Insert Like data
			$save["{wildcard_identifier}_".$activity_reference."_id"] = $insert_id = $pModel->put($save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_friend",
				"navigation_type" => $activity_navigation,
				//"navigation_item_id" => $insert_id,
				"navigation_item_id" => $save["{wildcard_pk}"], // we need to send user to entity profile
				"reference_module" => $activity_reference,
				"reference_id" => $insert_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["target_{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{wildcard_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($insert_id);
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	/**
     * deleteFriend
     *
     * @return Response
     */
    public function deleteFriend(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}-friend_delete";
		$activity_reference = "{wildcard_identifier}_friend"; // table name for best referencing / navigation
		$activity_navigation = "{wildcard_identifier}";
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_friend_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1",
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1"
		));
		
		// existance
		$is_exists = \DB::table($pModel->table)
			->whereRaw("(`{wildcard_pk}` = '".$request->{wildcard_pk}."' OR `target_{wildcard_pk}` = '".$request->{wildcard_pk}."')")
			->where("{wildcard_identifier}_friend_id","=",$request->{wildcard_identifier}_friend_id)
			->where("status","=",1)
			->whereNull("deleted_at")
			->count();
				
		// validations        
		if(!in_array("{plugin_identifier}/friend/delete", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if ($is_exists == 0) {
            $this->_apiData['message'] = trans('api_errors.invalid_record_request');
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// get record
			$record = $pModel->get($request->{wildcard_identifier}_friend_id);
	
			// remove
			$pModel->remove($record->{$pModel->primaryKey});
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_friend",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $record->{wildcard_pk} == $request->{wildcard_pk} ? $record->target_{wildcard_pk} :  $record->{wildcard_pk}, // we need to send user to entity profile
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_friend_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk} == $request->{wildcard_pk} ? $record->target_{wildcard_pk} :  $record->{wildcard_pk}
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{wildcard_pk},
				$activity_identifier,
				$activity_data
			);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * acceptFriend
     *
     * @return Response
     */
    public function acceptFriend(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}-friend_accept";
		$activity_reference = "{wildcard_identifier}_friend"; // table name for best referencing / navigation
		$activity_navigation = "{wildcard_identifier}";
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_friend_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,0,target_{wildcard_pk},".$request->{wildcard_pk},
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/delete", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// prepare save data
			$record = $pModel->get($request->{wildcard_identifier}_friend_id);
			$save = (array)$record;
			
			$save['status'] = 1;			
			$save['updated_at'] = date('Y-m-d H:i:s');
			// update data
			$pModel->set($record->{$pModel->primaryKey},$save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_friend",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $record->{wildcard_pk}, // we need to send user to entity profile
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_friend_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk}
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{wildcard_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($request->{wildcard_identifier}_friend_id);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	/**
     * rejectFriend
     *
     * @return Response
     */
    public function rejectFriend(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}-friend_reject";
		$activity_reference = "{wildcard_identifier}_friend"; // table name for best referencing / navigation
		$activity_navigation = "{wildcard_identifier}";
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_friend_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,0,target_{wildcard_pk},".$request->{wildcard_pk},
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/delete", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// prepare save data
			$record = $pModel->get($request->{wildcard_identifier}_friend_id);
			$save = (array)$record;
			
			$save['status'] = 2;			
			$save['updated_at'] = date('Y-m-d H:i:s');
			// update data
			$pModel->set($record->{$pModel->primaryKey},$save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_friend",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $record->{wildcard_pk}, // we need to send user to entity profile
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_friend_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk}
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{wildcard_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($request->{wildcard_identifier}_friend_id);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * cancelFriend
     *
     * @return Response
     */
    public function cancelFriend(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}-friend_reject";
		$activity_reference = "{wildcard_identifier}_friend"; // table name for best referencing / navigation
		$activity_navigation = "{wildcard_identifier}";
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_friend_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,0,{wildcard_pk},".$request->{wildcard_pk},
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/delete", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// prepare save data
			$record = $pModel->get($request->{wildcard_identifier}_friend_id);
			$save = (array)$record;
			
			// remove
			$pModel->remove($record->{$pModel->primaryKey});
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_friend",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $record->{wildcard_pk}, // we need to send user to entity profile
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_friend_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk}
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{wildcard_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($request->{wildcard_identifier}_friend_id);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * getAll
     *
     * @return Response
     */
    public function getAll(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "friends"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/get_all", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// other params
			$keyword = $request->input('keyword', ""); // default name
			$status = $request->has('status') ? $request->input('status', 1) : 1; // default name
			$status = in_array($status,array(0,1)) ? intval($status) : 1; // default name
			// requesting entity id
			$entity_id = $request->{wildcard_pk};
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel
				->where("a.status","=",$status)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}
			$query->whereRaw("(a.`{wildcard_pk}` = '".$entity_id."' OR a.`target_{wildcard_pk}` = '".$entity_id."')");
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", function($join) use($entity_id) {
				$join->on("b.{wildcard_pk}", '=', \DB::raw("IF(a.{wildcard_pk} = '".$entity_id."',a.target_{wildcard_pk},a.{wildcard_pk})"));
			});
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->where("a.status","=",$status)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");	
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}	
			$query->whereRaw("(a.`{wildcard_pk}` = '".$entity_id."' OR a.`target_{wildcard_pk}` = '".$entity_id."')");	
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", function($join) use($entity_id) {
				$join->on("b.{wildcard_pk}", '=', \DB::raw("IF(a.{wildcard_pk} = '".$entity_id."',a.target_{wildcard_pk},a.{wildcard_pk})"));
			});
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy("b.name", "ASC");
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
					// unset unrequired
					if($record->{wildcard_identifier}->{wildcard_pk} == $request->{wildcard_pk}) {
						$record->{wildcard_identifier} = $record->target_{wildcard_identifier};
						unset($record->target_{wildcard_identifier});
					} else {
						unset($record->target_{wildcard_identifier});
					}
					// set
					$data[$activity_reference][] = $record;
				}
			}
			
			// set pagination response
			$data["page"] = array(
				"current" => $page_no,
				"total" => $total_pages,
				"next" => $page_no >= $total_pages ? 0 : $page_no + 1,
				"prev" => $page_no <= 1 ? 0 : $page_no - 1
			);			
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * getAllPendingRequests
     *
     * @return Response
     */
    public function getAllPendingRequests(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "friends"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/get_all_pending", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// other params
			$keyword = $request->input('keyword', ""); // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel
				->where("a.target_{wildcard_pk}","=",$request->{wildcard_pk})
				->where("a.status","=",0)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{wildcard_pk}");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->where("a.target_{wildcard_pk}","=",$request->{wildcard_pk})
				->where("a.status","=",0)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");	
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}		
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{wildcard_pk}");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy("b.name", "ASC");
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
					// unset unrequired
					unset($record->target_{wildcard_identifier});
					// set
					$data[$activity_reference][] = $record;
				}
			}
			
			// set pagination response
			$data["page"] = array(
				"current" => $page_no,
				"total" => $total_pages,
				"next" => $page_no >= $total_pages ? 0 : $page_no + 1,
				"prev" => $page_no <= 1 ? 0 : $page_no - 1
			);			
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	/**
     * getAllSentRequests
     *
     * @return Response
     */
    public function getAllSentRequests(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Friend";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "friends"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_target_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/friend/get_all_sent", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');	
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
			
			// other params
			$keyword = $request->input('keyword', ""); // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel
				->where("a.{wildcard_pk}","=",$request->{wildcard_pk})
				->where("a.status","=",0)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{wildcard_pk}");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->where("a.{wildcard_pk}","=",$request->{wildcard_pk})
				->where("a.status","=",0)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");	
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}		
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{wildcard_pk}");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy("b.name", "ASC");
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
					// unset unrequired
					$record->{wildcard_identifier} = $record->target_{wildcard_identifier};
					unset($record->target_{wildcard_identifier});
					// set
					$data[$activity_reference][] = $record;
				}
			}
			
			// set pagination response
			$data["page"] = array(
				"current" => $page_no,
				"total" => $total_pages,
				"next" => $page_no >= $total_pages ? 0 : $page_no + 1,
				"prev" => $page_no <= 1 ? 0 : $page_no - 1
			);			
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	

}
