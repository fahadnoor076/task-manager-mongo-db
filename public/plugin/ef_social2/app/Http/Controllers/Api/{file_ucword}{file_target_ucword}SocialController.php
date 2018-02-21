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

class {wildcard_ucword}{target_ucword}SocialController extends Controller { 

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
	protected $_target_entity_identifier = "{target_identifier}";
	protected $_target_entity_pk = "{target_pk}";
	protected $_target_entity_ucfirst = "{target_ucword}";
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
		//$this->__models[$this->_base_entity_identifier.'_model'] = $this->_entity_model;
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
     * AddComment
     *
     * @return Response
     */
    public function addComment(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Comment";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-comment_add";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_comment"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'comment' => 'required'
		));
		
		$parent_id = $request->input('parent_id', '');
        $parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/comment/add", $this->_plugin_config->webservices)){
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
			$save['parent_id'] = 0;
			$save["{target_pk}"] = $request->{target_pk};
			$save["{wildcard_pk}"] = $request->{wildcard_pk};
			$save['comment'] = $request->comment;
			$save['reference_data'] = $request->reference_data;			
			$save['created_at'] = date('Y-m-d H:i:s');
			
			// Insert data
			$save[$activity_reference."_id"] = $insert_id = $pModel->put($save);
			
			// update entity counter
			$this->_entity_model->setFieldCount($request->{$this->_entity_model->primaryKey},"count_comment",1);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $insert_id,
				"reference_module" => $activity_reference,
				"reference_id" => $insert_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
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
     * EditComment
     *
     * @return Response
     */
    public function editComment(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Comment";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-comment_edit";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_comment"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_comment_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1,{target_pk},".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'comment' => 'required'
		));
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/comment/edit", $this->_plugin_config->webservices)){
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
			$record = $pModel->get($request->{wildcard_identifier}_comment_id);
			$save = (array)$record;
			
			$save['comment'] = $request->comment;
			$save['reference_data'] = $request->reference_data;			
			$save['updated_at'] = date('Y-m-d H:i:s');
			
			// update data
			$pModel->set($record->{$pModel->primaryKey},$save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{wildcard_identifier}_comment_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_comment_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($record->{$pModel->primaryKey});
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }

	/**
     * deleteComment
     *
     * @return Response
     */
    public function deleteComment(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Comment";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-comment_delete";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_comment"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_comment_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1,{target_pk},".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/comment/delete", $this->_plugin_config->webservices)){
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
			
			// get record
			$record = $pModel->get($request->{wildcard_identifier}_comment_id);
	
			// remove
			$pModel->remove($record->{$pModel->primaryKey});
			
			// update entity counter
			$this->_entity_model->setFieldCount($request->{$this->_entity_model->primaryKey},"count_comment",-1);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{wildcard_identifier}_comment_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_comment_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk}				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * getComment
     *
     * @return Response
     */
    public function getComment(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Comment";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "comment"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_comment_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/comment/get", $this->_plugin_config->webservices)){
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
			
			// get record
			$record = $pModel->get($request->{wildcard_identifier}_comment_id);
			
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($record->{$pModel->primaryKey});
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	/**
     * getAllComments
     *
     * @return Response
     */
    public function getAllComments(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Comment";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "comments"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/comment/get_all", $this->_plugin_config->webservices)){
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
			$query = $pModel->where("a.status","=",1)
				->where("b.status","=",1)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}
			$query->from($pModel->table." AS a");
			$query->join($this->_entity_model->table." AS b", "b.".$this->_entity_model->primaryKey, "=", "a.".$this->_entity_model->primaryKey);
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->where("a.status","=",1)
				->where("b.status","=",1)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}	
			$query->from($pModel->table." AS a");
			$query->join($this->_entity_model->table." AS b", "b.".$this->_entity_model->primaryKey, "=", "a.".$this->_entity_model->primaryKey);
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
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
     * addReview
     *
     * @return Response
     */
    public function addReview(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Review";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-review_add";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_review"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'review' => 'required'
		));
		
		$parent_id = $request->input('parent_id', '');
        $parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/review/add", $this->_plugin_config->webservices)){
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
			$save['parent_id'] = 0;
			$save["{target_pk}"] = $request->{target_pk};
			$save["{wildcard_pk}"] = $request->{wildcard_pk};
			$save['review'] = $request->review;
			$save['reference_data'] = $request->reference_data;			
			$save['created_at'] = date('Y-m-d H:i:s');
			
			// Insert data
			$save[$activity_reference."_id"] = $insert_id = $pModel->put($save);
			
			/*// update entity record
			$this->_entity_model->setFieldCount($request->{$this->_entity_model->primaryKey},"count_comment",1);
			*/
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $insert_id,
				"reference_module" => $activity_reference,
				"reference_id" => $insert_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
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
     * editReview
     *
     * @return Response
     */
    public function editReview(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Review";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-review_edit";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_review"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_review_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1,{target_pk},".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'review' => 'required'
		));
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/review/edit", $this->_plugin_config->webservices)){
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
			$record = $pModel->get($request->{wildcard_identifier}_review_id);
			$save = (array)$record;
			
			$save['review'] = $request->review;
			$save['reference_data'] = $request->reference_data;			
			$save['updated_at'] = date('Y-m-d H:i:s');
			
			// update data
			$pModel->set($record->{$pModel->primaryKey},$save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{wildcard_identifier}_review_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_review_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($record->{$pModel->primaryKey});
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }

	/**
     * deleteReview
     *
     * @return Response
     */
    public function deleteReview(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Review";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-review_delete";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_review"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_review_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1,{target_pk},".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/review/delete", $this->_plugin_config->webservices)){
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
			
			// get record
			$record = $pModel->get($request->{wildcard_identifier}_review_id);
	
			// remove
			$pModel->remove($record->{$pModel->primaryKey});
			
			/*// update entity record
			$entity_record = $this->_entity_model->get($record->{$this->_entity_model->primaryKey});
			$entity_record->count_review = $entity_record->count_review - 1;
			$this->_entity_model->set($entity_record->{$this->_entity_model->primaryKey},(array)$entity_record);
			*/
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{wildcard_identifier}_review_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_review_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk}				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * getReview
     *
     * @return Response
     */
    public function getReview(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Review";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "review"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_review_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/review/get", $this->_plugin_config->webservices)){
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
			
			// get record
			$record = $pModel->get($request->{wildcard_identifier}_review_id);
			
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($record->{$pModel->primaryKey});
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	/**
     * getAllReviews
     *
     * @return Response
     */
    public function getAllReviews(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Review";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "reviews"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		
		// validations        
		if(!in_array("{plugin_identifier}/review/get_all", $this->_plugin_config->webservices)){
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
			$query = $pModel->where("a.status","=",1)
				->where("b.status","=",1)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}			
			$query->from($pModel->table." AS a");
			$query->join($this->_entity_model->table." AS b", "b.".$this->_entity_model->primaryKey, "=", "a.".$this->_entity_model->primaryKey);
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->where("a.status","=",1)
				->where("b.status","=",1)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}		
			$query->from($pModel->table." AS a");
			$query->join($this->_entity_model->table." AS b", "b.".$this->_entity_model->primaryKey, "=", "a.".$this->_entity_model->primaryKey);
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
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
     * addLike
     *
     * @return Response
     */
    public function addLike(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Like";
		$pModel = new $pModel;
		$SOCActivityModel = $this->_model_path."SOCActivityType";
		$SOCActivityModel = new $SOCActivityModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-like_add";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_like"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'activity_type_id' => "exists:".$SOCActivityModel->table.",".$SOCActivityModel->primaryKey.",deleted_at,NULL,activity,like"
		));
		
		// other params
		$activity_type_id = $request->input('activity_type_id', 1); // default id
        $activity_type_id = $activity_type_id == "" ? 1 : $activity_type_id; // set default value
		// existance
		$is_exists = \DB::table($pModel->table)
			->where("{wildcard_pk}","=",$request->{wildcard_pk})
			->where("actor_type","=","{target_identifier}")
			->where("actor_id","=",$request->{target_pk})
			->whereNull("deleted_at")
			->count();
		
		// validations        
		if(!in_array("{plugin_identifier}/like/add", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if ($is_exists > 0) {
            $this->_apiData['message'] = trans('api_errors.already_liked',["entity" => "{wildcard_ucword}"]);
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
	
			// prepare save data
			$save["{wildcard_pk}"] = $request->{wildcard_pk};
			$save["activity_type_id"] = $activity_type_id;
			$save["actor_type"] = "{target_identifier}";
			$save["actor_id"] = $request->{target_pk};			
			$save['created_at'] = date('Y-m-d H:i:s');
			
			// Insert data
			$save[$activity_reference."_id"] = $insert_id = $pModel->put($save);
			
			// update entity counter
			$this->_entity_model->setFieldCount($request->{$this->_entity_model->primaryKey},"count_like",1);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $insert_id,
				"reference_module" => $activity_reference,
				"reference_id" => $insert_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
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
     * editLike
     *
     * @return Response
     */
    public function editLike(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Like";
		$pModel = new $pModel;
		$SOCActivityModel = $this->_model_path."SOCActivityType";
		$SOCActivityModel = new $SOCActivityModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-like_edit";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_like"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_like_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,actor_type,{target_identifier},actor_id,".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'activity_type_id' => "exists:".$SOCActivityModel->table.",".$SOCActivityModel->primaryKey.",deleted_at,NULL,activity,like"
		));
		// other params
		$activity_type_id = $request->input('activity_type_id', 1); // default id
        $activity_type_id = $activity_type_id == "" ? 1 : $activity_type_id; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/like/edit", $this->_plugin_config->webservices)){
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
			$record = $pModel->get($request->{wildcard_identifier}_like_id);
			$save = (array)$record;
			
			$save["activity_type_id"] = $activity_type_id;
			$save['updated_at'] = date('Y-m-d H:i:s');
			
			// update data
			$pModel->set($record->{$pModel->primaryKey},$save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{wildcard_identifier}_like_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_like_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $save["{wildcard_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// map saved data
			$data[$activity_reference] = $pModel->getData($record->{$pModel->primaryKey});
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }

	/**
     * deleteLike
     *
     * @return Response
     */
    public function deleteLike(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Like";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{wildcard_identifier}_{target_identifier}-like_delete";
		$activity_reference = $activity_navigation = "{wildcard_identifier}_like"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_identifier}_like_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,actor_type,{target_identifier},actor_id,".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		$reference_data = $request->input('reference_data', '');
        $reference_data = $reference_data == "" ? "" : $reference_data; // set default value
		
		// validations        
		if(!in_array("{plugin_identifier}/like/delete", $this->_plugin_config->webservices)){
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
			
			// get record
			$record = $pModel->get($request->{wildcard_identifier}_like_id);
	
			// remove
			$pModel->remove($record->{$pModel->primaryKey});
			
			// update entity counter
			$this->_entity_model->setFieldCount($request->{$this->_entity_model->primaryKey},"count_like",-1);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{wildcard_identifier}_like_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{wildcard_identifier}_like_id,
				"against" => "{wildcard_identifier}",
				"against_id" => $record->{wildcard_pk}				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	
	/**
     * getAllLike
     *
     * @return Response
     */
    public function getAllLikes(Request $request) {
		// load models
		$pModel = $this->_model_path."{wildcard_ucword}Like";
		$pModel = new $pModel;
		$SOCActivityModel = $this->_model_path."SOCActivityType";
		$SOCActivityModel = new $SOCActivityModel;
		
		// activity reference
		$activity_reference = "likes"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{wildcard_pk}" => "required|exists:".$this->_entity_model->table.",{wildcard_pk},deleted_at,NULL,status,1",
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			'activity_type_id' => "exists:".$SOCActivityModel->table.",".$SOCActivityModel->primaryKey.",deleted_at,NULL,activity,like"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/like/get_all", $this->_plugin_config->webservices)){
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
			$activity_type_id = $request->input('activity_type_id', ''); // default id
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
				->where("a.actor_type","=","{target_identifier}")
				->where("b.status","=",1)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");
			if($activity_type_id !== "") {
				$query->where("a.activity_type_id","=",$activity_type_id);
			}
			if($keyword !== "") {
				$query->where("c.name","like","%$keyword%");
			}
			$query->from($pModel->table." AS a");
			$query->join($this->_entity_model->table." AS b", "b.".$this->_entity_model->primaryKey, "=", "a.".$this->_entity_model->primaryKey);
			$query->join($this->_target_entity_model->table." AS c", "c.".$this->_target_entity_model->primaryKey, "=", "a.actor_id");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->where("a.actor_type","=","{target_identifier}")
				->where("b.status","=",1)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");	
			if($activity_type_id !== "") {
				$query->where("a.activity_type_id","=",$activity_type_id);
			}
			if($keyword !== "") {
				$query->where("c.name","like","%$keyword%");
			}	
			$query->from($pModel->table." AS a");
			$query->join($this->_entity_model->table." AS b", "b.".$this->_entity_model->primaryKey, "=", "a.".$this->_entity_model->primaryKey);
			$query->join($this->_target_entity_model->table." AS c", "c.".$this->_target_entity_model->primaryKey, "=", "a.actor_id");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
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
     * addFollower
     *
     * @return Response
     */
    public function addFollower(Request $request) {
		// load models
		$pModel = $this->_model_path."{target_ucword}Follower";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{target_identifier}-follower_add";
		$activity_reference = $activity_navigation = "{target_identifier}_follower"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
			"target_{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
		
		// existance
		$is_exists = \DB::table($pModel->table)
			->where("{target_pk}","=",$request->{target_pk})
			->where("target_{target_pk}","=",$request->target_{target_pk})
			->whereNull("deleted_at")
			->count();
		
		// validations        
		if(!in_array("{plugin_identifier}/follower/add", $this->_plugin_config->webservices)){
			$this->_apiData['message'] = trans('api_errors.not_authorized_for_webservice');
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if ($request->{target_pk} == $request->target_{target_pk}) {
            $this->_apiData['message'] = trans('api_errors.cannot_follow_self');
        } else if ($is_exists > 0) {
            $this->_apiData['message'] = trans('api_errors.already_following',["entity" => "{target_ucword}"]);
        } else {
			// success response
			$this->_apiData['response'] = "success";
			// init message
			$this->_apiData['message'] = trans('api_errors.success');
			// init output
			$this->_apiData['data'] = $data = array();
	
			// prepare save data
			$save["{target_pk}"] = $request->{target_pk};
			$save["target_{target_pk}"] = $request->target_{target_pk};
			$save['created_at'] = date('Y-m-d H:i:s');
			
			// Insert data
			$save["{target_identifier}_".$activity_reference."_id"] = $insert_id = $pModel->put($save);
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $insert_id,
				"reference_module" => $activity_reference,
				"reference_id" => $insert_id,
				"against" => "{target_identifier}",
				"against_id" => $save["{target_pk}"]				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
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
     * deleteFollower
     *
     * @return Response
     */
    public function deleteFollower(Request $request) {
		// load models
		$pModel = $this->_model_path."{target_ucword}Follower";
		$pModel = new $pModel;
		
		// history activity identifier
		$activity_identifier = "{target_identifier}-follower_delete";
		$activity_reference = $activity_navigation = "{target_identifier}_follower"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_identifier}_follower_id" => "required|exists:".$pModel->table.",".$pModel->primaryKey.",deleted_at,NULL,{target_pk},".$request->{target_pk},
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1"
		));
				
		// validations        
		if(!in_array("{plugin_identifier}/follower/delete", $this->_plugin_config->webservices)){
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
			
			// get record
			$record = $pModel->get($request->{target_identifier}_follower_id);
	
			// remove
			$pModel->remove($record->{$pModel->primaryKey});
			
			// set for history
			$activity_data = array(
				"plugin_identifier" => "{plugin_identifier}_{wildcard_identifier}_{target_identifier}",
				"navigation_type" => $activity_navigation,
				"navigation_item_id" => $request->{target_identifier}_follower_id,
				"reference_module" => $activity_reference,
				"reference_id" => $request->{target_identifier}_follower_id,
				"against" => "{target_identifier}",
				"against_id" => $record->{target_pk}				
			);
			// put history
			$this->_entity_history_model->putEntityHistory(
				$this->_target_entity_identifier,
				$request->{target_pk},
				$activity_identifier,
				$activity_data
			);
			
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__apiResponse($request,$this->_apiData);
    }
	
	/**
     * getAllFollowers
     *
     * @return Response
     */
    public function getAllFollowers(Request $request) {
		// load models
		$pModel = $this->_model_path."{target_ucword}Follower";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "followers"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/follower/get_all_followers", $this->_plugin_config->webservices)){
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
				->where("a.target_{target_pk}","=",$request->{target_pk})
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{target_pk}");
			$query->join($this->_target_entity_model->table." AS c", "c.".$this->_target_entity_model->primaryKey, "=", "a.target_{target_pk}");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->where("a.target_{target_pk}","=",$request->{target_pk})
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");	
			if($keyword !== "") {
				$query->where("b.name","like","%$keyword%");
			}		
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{target_pk}");
			$query->join($this->_target_entity_model->table." AS c", "c.".$this->_target_entity_model->primaryKey, "=", "a.target_{target_pk}");
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
					unset($record->target_{target_identifier});
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
     * getAllFollowing
     *
     * @return Response
     */
    public function getAllFollowing(Request $request) {
		// load models
		$pModel = $this->_model_path."{target_ucword}Follower";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "followers"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_pk}" => "required|exists:".$this->_target_entity_model->table.",{target_pk},deleted_at,NULL,status,1",
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/follower/get_all_following", $this->_plugin_config->webservices)){
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
				->where("a.{target_pk}","=",$request->{target_pk})
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");
			if($keyword !== "") {
				$query->where("c.name","like","%$keyword%");
			}
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{target_pk}");
			$query->join($this->_target_entity_model->table." AS c", "c.".$this->_target_entity_model->primaryKey, "=", "a.target_{target_pk}");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->where("a.{target_pk}","=",$request->{target_pk})
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");	
			if($keyword !== "") {
				$query->where("c.name","like","%$keyword%");
			}		
			$query->from($pModel->table." AS a");
			$query->join($this->_target_entity_model->table." AS b", "b.".$this->_target_entity_model->primaryKey, "=", "a.{target_pk}");
			$query->join($this->_target_entity_model->table." AS c", "c.".$this->_target_entity_model->primaryKey, "=", "a.target_{target_pk}");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy("c.name", "ASC");
			$query->orderBy($pModel->primaryKey, "DESC");
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$pModel->primaryKey});
					// unset unrequired
					$record->{target_identifier} = $record->target_{target_identifier};
					unset($record->target_{target_identifier});
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
