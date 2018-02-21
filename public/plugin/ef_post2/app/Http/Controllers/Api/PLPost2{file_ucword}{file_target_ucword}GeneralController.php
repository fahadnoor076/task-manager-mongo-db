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

class PLPost2{wildcard_ucword}{target_ucword}GeneralController extends Controller { 

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
     * types
     *
     * @return Response
     */
    public function types(Request $request) {
		// load models
		$pModel = $this->_model_path."PLPostType";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "post_types"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"order_by" => "in:title,identifier,created_at",
			"sorting" => "in:asc,desc"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/types", $this->_plugin_config->webservices)){
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
			$request->order_by = $request->order_by != "" ? $request->order_by : "title"; // default name
			$request->sorting = $request->sorting != "" ? strtolower($request->sorting) : "asc"; // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel->whereNull("deleted_at");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->whereNull("deleted_at");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($request->order_by, strtoupper($request->sorting));
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
     * attachmentTypes
     *
     * @return Response
     */
    public function attachmentTypes(Request $request) {
		// load models
		$pModel = $this->_model_path."PLAttachmentType";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "attachment_types"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"order_by" => "in:title,identifier,created_at",
			"sorting" => "in:asc,desc"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/attachment/types", $this->_plugin_config->webservices)){
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
			$request->order_by = $request->order_by != "" ? $request->order_by : "title"; // default name
			$request->sorting = $request->sorting != "" ? strtolower($request->sorting) : "asc"; // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel->whereNull("deleted_at");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->whereNull("deleted_at");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($request->order_by, strtoupper($request->sorting));
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
     * feedbackTypes
     *
     * @return Response
     */
    public function feedbackTypes(Request $request) {
		// load models
		$pModel = $this->_model_path."PLPostFeedbackType";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "feedback_types"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"order_by" => "in:title,identifier,created_at",
			"sorting" => "in:asc,desc"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/feedback/types", $this->_plugin_config->webservices)){
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
			$request->order_by = $request->order_by != "" ? $request->order_by : "title"; // default name
			$request->sorting = $request->sorting != "" ? strtolower($request->sorting) : "asc"; // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel->whereNull("deleted_at");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->whereNull("deleted_at");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($request->order_by, strtoupper($request->sorting));
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
     * scheduleTypes
     *
     * @return Response
     */
    public function scheduleTypes(Request $request) {
		// load models
		$pModel = $this->_model_path."PLPostScheduleType";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "schedule_types"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"order_by" => "in:title,identifier,created_at",
			"sorting" => "in:asc,desc"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/schedule/types", $this->_plugin_config->webservices)){
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
			$request->order_by = $request->order_by != "" ? $request->order_by : "title"; // default name
			$request->sorting = $request->sorting != "" ? strtolower($request->sorting) : "asc"; // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel->whereNull("deleted_at");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->whereNull("deleted_at");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($request->order_by, strtoupper($request->sorting));
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
     * tagTypes
     *
     * @return Response
     */
    public function tagTypes(Request $request) {
		// load models
		$pModel = $this->_model_path."PLTagType";
		$pModel = new $pModel;
		
		// activity reference
		$activity_reference = "tag_types"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"order_by" => "in:title,identifier,created_at",
			"sorting" => "in:asc,desc"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/tag/types", $this->_plugin_config->webservices)){
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
			$request->order_by = $request->order_by != "" ? $request->order_by : "title"; // default name
			$request->sorting = $request->sorting != "" ? strtolower($request->sorting) : "asc"; // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel->whereNull("deleted_at");
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel->whereNull("deleted_at");
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($request->order_by, strtoupper($request->sorting));
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
     * tags
     *
     * @return Response
     */
    public function tags(Request $request) {
		// load models
		$pModel = $this->_model_path."PLTag";
		$pModel = new $pModel;
		$tagTypeModel = $this->_model_path."PLTagType";
		$tagTypeModel = new $tagTypeModel;
		$tagTypeMapModel = $this->_model_path."PLTagTypeMap";
		$tagTypeMapModel = new $tagTypeMapModel;
		
		// activity reference
		$activity_reference = "tags"; // table name for best referencing / navigation
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"sorting" => "in:asc,desc",
			"order_by" => "in:title,identifier,created_at",
			"tag_type_id" => "exists:".$tagTypeModel->table.",".$tagTypeModel->primaryKey.",deleted_at,NULL"
		));
		
		// validations        
		if(!in_array("{plugin_identifier}/tags", $this->_plugin_config->webservices)){
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
			$request->order_by = $request->order_by != "" ? $request->order_by : "title"; // default name
			$request->sorting = $request->sorting != "" ? strtolower($request->sorting) : "asc"; // default name
			
			// init record set
			$data[$activity_reference] = array();
			
			// default page_no / limit
			$page_no = $request->input('page_no', 0);
			$page_no = intval($page_no) > 0 ? intval($page_no) : 1; // set default value
			$limit = $request->input('limit', 0);
			$limit = intval($limit) > 0 ? intval($limit) : PAGE_LIMIT_API; // set default value
			
			// find records
			$query = $pModel->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");
			$query->from($pModel->table." AS a");
			$query->join($tagTypeMapModel->table." AS b", "b.".$pModel->primaryKey, "=", "a.".$pModel->primaryKey);
			$query->join($tagTypeModel->table." AS c", "c.".$tagTypeModel->primaryKey, "=", "b.".$tagTypeModel->primaryKey);
			if($request->tag_type_id != "") {
				$query->where("c.".$tagTypeModel->primaryKey,"=",$request->tag_type_id);
			}
			$total_records = $query->count();
			
			// offfset / limits / valid pages
			$total_pages = ceil($total_records / $limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $limit * ($page_no - 1);
			
			// query records
			$query = $pModel
				->select("a.".$pModel->primaryKey,"c.".$tagTypeModel->primaryKey,"b.".$tagTypeMapModel->primaryKey)
				->whereNull("a.deleted_at")
				->whereNull("b.deleted_at")
				->whereNull("c.deleted_at");
			if($request->tag_type_id != "") {
				$query->where("c.".$tagTypeModel->primaryKey,"=",$request->tag_type_id);
			}
			$query->from($pModel->table." AS a");
			$query->join($tagTypeMapModel->table." AS b", "b.".$pModel->primaryKey, "=", "a.".$pModel->primaryKey);
			$query->join($tagTypeModel->table." AS c", "c.".$tagTypeModel->primaryKey, "=", "b.".$tagTypeModel->primaryKey);
			// limit / order
			$query->take($limit);
			$query->skip($offset);
			$query->orderBy($request->order_by, strtoupper($request->sorting));
			// get records
			$raw_records = $query->get();
			
			// if found
			if(isset($raw_records[0])) {
				// loop through
				foreach($raw_records as $raw_record) {
					$record = $pModel->getData($raw_record->{$tagTypeMapModel->primaryKey});
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
