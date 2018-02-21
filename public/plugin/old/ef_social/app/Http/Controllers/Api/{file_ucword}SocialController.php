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

class {wildcard_ucword}SocialController extends Controller { 

    private $_assignData = array(
        'pDir' => '',
        'dir' => DIR_API
    );
    private $_apiData = array();
    private $_layout = "";
    private $_models = array();
    private $_jsonData = array();
	private $_model_path = "\App\Http\Models\\";
	private $_entity_identifier = "{wildcard_identifier}";
	private $_entity_pk = "{wildcard_pk}";
	private $_entity_ucfirst = "{wildcard_ucword}";
	private $_entity_model;
	private $_entity_id = "{base_entity_id}";
	private $_plugin_identifier = "{plugin_identifier}";
	private $_plugin_config = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
		// load entity model
		//$this->_entity_model = $this->_model_path.$this->_entity_ucfirst;
		//$this->_entity_model = new $this->_entity_model;	
		
		
        // init models
        $this->__models['api_method_model'] = new ApiMethod;
        $this->__models['api_user_model'] = new ApiUser;
		//$this->__models[$this->_entity_identifier.'_model'] = $this->_entity_model;
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;
		
        // error response by default
        $this->_apiData['kick_user'] = 0;
        $this->_apiData['response'] = "error";

        // check access
        $this->__models['api_user_model']->check_access();
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_plugin_config = isset($this->_plugin_config->webservices) ? $this->_plugin_config->webservices : array();
		$this->_plugin_config["webservices"] = $this->_plugin_config;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        
    }
	
	/**
     * Like
     *
     * @return Response
     */
    public function post(Request $request) {
		// load models
		$entity_like_model = $this->_model_path."{wildcard_ucword}Like";
		$entity_like_model = new $entity_like_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric',
			'actor_type' => 'required',
			'target_id' => 'required|numeric'
		));
			
		// validations		
		if(!in_array("social/like/post", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
	
			// get user existance
			$raw_exists = $entity_like_model->select("{wildcard_identifier}_like_id","status")
				->where("actor_id","=",$request->actor_id)
				->where("actor_type","=",$request->actor_type)
				->where("target_id","=",$request->target_id)
				->whereNull("deleted_at")
				->get();
	
			$exists_id = isset($raw_exists[0]) ? $raw_exists[0]->{wildcard_identifier}_like_id : 0;
			
			$data['actor_id'] = $request->actor_id;
			$data['actor_type'] = $request->actor_type;
			$data['target_id'] = $request->target_id;
			$data['status'] = 1;
	
			if($exists_id == 0){
				// Insert Like data
				$data['created_at'] = date('Y-m-d H:i:s');
				$entity_like_model->put($data);
				// set for log
				$data['activity_type'] = 'like';
				$entity_log_model->put($data);
			} else {
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(isset($raw_exists[0]->status) && $raw_exists[0]->status == 1){
					$data['status'] = 0;
				}
				// update like data
				$entity_like_model->set($exists_id, (array) $data);
			}

			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Get All Like
     *
     * @return Response
     */
    public function getAllLike(Request $request) {
		// load models
		$entity_like_model = $this->_model_path."RetailerLike";
		$entity_like_model = new $entity_like_model;
		$entity_log_model = $this->_model_path."RetailerLog";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric'			
		));
			
		// validations		
		if(!in_array("social/like/get_all", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
	
			// get user existance
			$raw_exists = $entity_like_model->where("actor_id","=",$request->actor_id)->whereNull("deleted_at")->get();

			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $raw_exists;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Follow
     *
     * @return Response
     */
    public function follow(Request $request) {
		
		// load models
		$entity_follow_model = $this->_model_path."{wildcard_ucword}Follow";
		$entity_follow_model = new $entity_follow_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric',
			'actor_type' => 'required',
			'target_id' => 'required|numeric'
		));
		
		// validations		
		if(!in_array("social/follow", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		}else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";
	
			// get user existance
			$raw_exists = $entity_follow_model->select("{wildcard_identifier}_follow_id","status")
				->where("actor_id","=",$request->actor_id)			
				->where("target_id","=",$request->target_id)
				->whereNull("deleted_at")
				->get();
			$exists_id = isset($raw_exists[0]) ? $raw_exists[0]->{wildcard_identifier}_follow_id : 0;
			
			$data['actor_id'] = $request->actor_id;		
			$data['target_id'] = $request->target_id;
			$data['status'] = 1;
	
			if($exists_id == 0){
				// Insert Like data
				$data['created_at'] = date('Y-m-d H:i:s');
				$entity_follow_model->put($data);
				// set for log
				$data['activity_type'] = 'follow';
				$entity_log_model->put($data);
			} else {
				$data['updated_at'] = date('Y-m-d H:i:s');
				if(isset($raw_exists[0]->status) && $raw_exists[0]->status == 1){
					$data['status'] = 0;
				}
				// update like data
				$entity_follow_model->set($exists_id, (array) $data);
			}

			// message
			$this->_apiData['message'] = "Success";

            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	/**
     * UnFollow
     *
     * @return Response
     */
	public function unfollow(Request $request) {
		// load models
		$entity_follow_model = $this->_model_path."{wildcard_ucword}Follow";
		$entity_follow_model = new $entity_follow_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_follow_id' => 'required|numeric'
			));
		
		// validations					
		if(!in_array("social/unfollow", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
			$this->_apiData['message'] = $validator->errors()->first();
		} else {
			// success response
			$this->_apiData['response'] = "success";	
				
			$follow_data = $entity_follow_model->get($request->{wildcard_identifier}_follow_id);
        
			$data['{wildcard_identifier}_follow_id'] = $request->{wildcard_identifier}_follow_id;
			$data['actor_id'] = $follow_data->actor_id;		
			$data['target_id'] = $follow_data->target_id;
			$data['status']    = 0;		
			$data1['updated_at'] = date('Y-m-d H:i:s');
		
			// update status
            $entity_follow_model->set($request->{wildcard_identifier}_follow_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'unfollow';
			unset($data['{wildcard_identifier}_follow_id']);				
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";			
			$data[$this->_entity_identifier.'_follow_id'] = $follow_data->{wildcard_identifier}_follow_id;
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
    /**
     * AddComment
     *
     * @return Response
     */
    public function addComment(Request $request) {
		
		// load models
		$entity_comment_model = $this->_model_path."{wildcard_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric',
			'actor_type' => 'required',
			'target_id' => 'required|numeric',
			'comment' => 'required'
		));
		
		$parent_id = $request->input('parent_id', '');
        $parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
		
		// validations        
		if(!in_array("social/comment/add", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";	
	
			$data['actor_id'] = $request->actor_id;
			$data['actor_type'] = $request->actor_type;
			$data['target_id'] = $request->target_id;
			$data['comment'] = $request->comment;
			$data['parent_id'] = 0;
			$data['created_at'] = date('Y-m-d H:i:s');
			
			// Insert Comment data
			$insert_id = $entity_comment_model->put($data);
			// set for log
			$data['activity_type'] = 'comment_add';
			unset($data['comment'],$data['parent_id']);
			$entity_log_model->put($data);
	
			$data[$this->_entity_identifier.'_comment_id'] = $insert_id;	
			
			// message
			$this->_apiData['message'] = "Success";

			$data['comment'] = $request->comment;
			// assign to output
			$this->_apiData['data'] = $data;
        }
		 
        return $this->__api_response($this->_apiData);
    }


    /**
     * EditComment
     *
     * @return Response
     */
    public function editComment(Request $request) {
		// load models
		$entity_comment_model = $this->_model_path."{wildcard_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_comment_id' => 'required|numeric',
			'comment' => 'required'
		));
		
		// validations		
		if(!in_array("social/comment/edit", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";	
				
			$comment_data = $entity_comment_model->get($request->{wildcard_identifier}_comment_id);
	
			$parent_id = $request->input('parent_id', '');
			$parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
			
			
			$data['{wildcard_identifier}_comment_id'] = $request->{wildcard_identifier}_comment_id;
			$data['actor_id'] = $comment_data->actor_id;
			$data['actor_type'] = $comment_data->actor_type;
			$data['target_id'] = $comment_data->target_id;
			$data1['comment'] = $request->comment;
			$data1['parent_id'] = $comment_data->parent_id;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data1['updated_at'] = date('Y-m-d H:i:s');
		
			// Insert Comment data
            $entity_comment_model->set($request->{wildcard_identifier}_comment_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'comment_edit';
			unset($data['{wildcard_identifier}_comment_id']);	
			unset($data['parent_id']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data['comment'] = $request->comment;
			$data[$this->_entity_identifier.'_comment_id'] = $comment_data->{wildcard_identifier}_comment_id;
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }

	/**
     * EditComment
     *
     * @return Response
     */
    public function deleteComment(Request $request) {
		
		// load models
		$entity_comment_model = $this->_model_path."{wildcard_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_comment_id' => 'required|numeric'
		));
		
		// validations
       	if(!in_array("social/comment/delete", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";	
				
			$comment_data = $entity_comment_model->get($request->{wildcard_identifier}_comment_id);
	
			$parent_id = $request->input('parent_id', '');
			$parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
			
			
			$data['{wildcard_identifier}_comment_id'] = $request->{wildcard_identifier}_comment_id;
			$data['actor_id'] = $comment_data->actor_id;
			$data['actor_type'] = $comment_data->actor_type;
			$data['target_id'] = $comment_data->target_id;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data1['deleted_at'] = date('Y-m-d H:i:s');
		
			// Insert Comment data
            $entity_comment_model->set($request->{wildcard_identifier}_comment_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'comment_delete';
			unset($data['{wildcard_identifier}_comment_id']);	
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_comment_id'] = $comment_data->{wildcard_identifier}_comment_id;
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Get Comment
     *
     * @return Response
     */
    public function getComment(Request $request) {
		
		// load models
		$entity_comment_model = $this->_model_path."{wildcard_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_comment_id' => 'required|numeric'
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{

			// success response
			$this->_apiData['response'] = "success";	
				
			$data = $entity_comment_model->get($request->{wildcard_identifier}_comment_id);
			// message
			$this->_apiData['message'] = "Success";
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Get all Comments
     *
     * @return Response
     */
    public function getAllComment(Request $request) {
		
		// load models
		$entity_comment_model = $this->_model_path."{wildcard_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric'
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{

			// success response
			$this->_apiData['response'] = "success";	
				
			$data = $entity_comment_model->get($request->actor_id);
			// message
			$this->_apiData['message'] = "Success";
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
    /**
     * AddReview
     *
     * @return Response
     */
    public function addReview(Request $request) {
		
		// load models
		$entity_review_model = $this->_model_path."{wildcard_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric',
			'actor_type' => 'required',
			'target_id' => 'required|numeric',
			'rating' => 'numeric'
		));
		
		$rating = intval($request->input('rating', 0));
		$rating = $rating > 5 ? 5 : $rating;
        
		// validations       		
		if(!in_array("social/review/add", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else if ($request->review == '' && $rating == 0) {
            $this->_apiData['message'] = "Please Insert review or Rating";
        } else {
			// success response
			$this->_apiData['response'] = "success";
	
			$data['actor_id'] = $request->actor_id;
			$data['actor_type'] = $request->actor_type;
			$data['target_id'] = $request->target_id;
			$data['review'] = $request->review;
			$data['rating'] = $rating;
			$data['created_at'] = date('Y-m-d H:i:s');
	
			// Insert Comment data
			$insert_id = $entity_review_model->put($data);

            // set for log
			$data['activity_type'] = 'review_add';
			unset($data['review'],$data['rating']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_review_id'] = $insert_id;
            
            $data['review'] = $request->review;
			$data['rating'] = $rating;
            
            // assign to output
            $this->_apiData['data'] = $data;
        }

        return $this->__api_response($this->_apiData);
    }


    /**
     * EditReview
     *
     * @return Response
     */
    public function editReview(Request $request) {
		// load models
		$entity_review_model = $this->_model_path."{wildcard_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_review_id' => 'required|numeric',
			'rating' => 'numeric'
		));
		
		$rating = intval($request->input('rating', 0));
		$rating = $rating > 5 ? 5 : $rating;
        
		// validations
       	if(!in_array("social/review/edit", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else if ($request->review == '' && $rating == 0) {
            $this->_apiData['message'] = "Please Insert review or Rating";
        } else {
			// success response
			$this->_apiData['response'] = "success";	
				
			$review_data = $entity_review_model->get($request->{wildcard_identifier}_review_id);
				
			$data['{wildcard_identifier}_review_id'] = $request->{wildcard_identifier}_review_id;
			$data['actor_id'] = $review_data->actor_id;
			$data['actor_type'] = $review_data->actor_type;
			$data['target_id'] = $review_data->target_id;
			$data1['review'] = $request->review;
			$data1['rating'] = $request->rating;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data1['updated_at'] = date('Y-m-d H:i:s');
		
			// Update Review data
            $entity_review_model->set($request->{wildcard_identifier}_review_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'review_edit';
			unset($data['{wildcard_identifier}_review_id'],$data['review'],$data['rating']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_review_id'] = $review_data->{wildcard_identifier}_review_id;
            
            $data['review'] = $request->review;
			$data['rating'] = $rating;

            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }

    /**
     * DeleteReview
     *
     * @return Response
     */
    public function deleteReview(Request $request) {		
		// load models
		$entity_review_model = $this->_model_path."{wildcard_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_review_id' => 'required|numeric',
		));
		
		// validations
        if(!in_array("social/review/delete", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else { 
			// success response
			$this->_apiData['response'] = "success";
			// get data
			$review_data = $entity_review_model->get($request->{wildcard_identifier}_review_id);
		
			$data['{wildcard_identifier}_review_id'] = $request->{wildcard_identifier}_review_id;
			$data['actor_id'] = $review_data->actor_id;
			$data['actor_type'] = $review_data->actor_type;
			$data['target_id'] = $review_data->target_id;
			$data1['review'] = $review_data->review;
			$data1['rating'] = $review_data->rating;
			$data1['deleted_at'] = date('Y-m-d H:i:s');
		
			// Update Review data
            $entity_review_model->set($request->{wildcard_identifier}_review_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'review_delete';
			unset($data['{wildcard_identifier}_review_id'],$data['review'],$data['rating']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_review_id'] = $review_data->{wildcard_identifier}_review_id;
		    // assign to output
            $this->_apiData['data'] = $data;    
        }    
        return $this->__api_response($this->_apiData);
    }
	
	
	/**
     * Get Review
     *
     * @return Response
     */
    public function getReview(Request $request) {
		
		// load models
		$entity_review_model = $this->_model_path."{wildcard_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_review_id' => 'required|numeric'
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{

			// success response
			$this->_apiData['response'] = "success";	
				
			$data = $entity_review_model->get($request->{wildcard_identifier}_review_id);
			// message
			$this->_apiData['message'] = "Success";
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Get all Reviews
     *
     * @return Response
     */
    public function getAllReview(Request $request) {
		
		// load models
		$entity_review_model = $this->_model_path."{wildcard_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric'
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{

			// success response
			$this->_apiData['response'] = "success";	
				
			$data = $entity_review_model->get($request->actor_id);
			// message
			$this->_apiData['message'] = "Success";
            // assign to output
            $this->_apiData['data'] = $data;
        }    
        return $this->__api_response($this->_apiData);
    }
	
	/**
     * Add Friend
     *
     * @return Response
     */	
	public function addFriend(Request $request){		
		// load models
		$entity_friend_model = $this->_model_path."{wildcard_ucword}Friend";		
		$entity_friend_model = new $entity_friend_model;
		$user_model = $this->_model_path."User";
		$user_model = new $user_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// get data
		$user = $user_model->get($request->actor_id);
		$target_user = $user_model->get($request->target_id);
		
		//check record is already exist
		$query = $entity_friend_model->whereRaw("((actor_id = '".$request->actor_id."' AND target_id = '".$request->target_id."') OR (actor_id = '".$request->target_id."' AND target_id = '".$$request->actor_id."'))");
		$query->whereNull("deleted_at");
		$raw_records = $query->get();
		$record_id = ($raw_records[0]) ? $raw_records[0]->{wildcard_identifier}_friend_id : 0;					
		$record = $entity_friend_model->get($record_id);
		
		if($actor_id == '') {
			$this->_apiData['message'] = 'Please enter Actor ID';
		}else if($actor_type == '') {
			$this->_apiData['message'] = 'Please enter Actor Type';
		}else if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid user Request';
		}elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}else if($target_id == 0) {
			$this->_apiData['message'] = 'Please enter Target ID';
		}else if($target_user === FALSE) {
			$this->_apiData['message'] = 'Invalid Target User Request';
		}elseif($target_user->status != 1){
			$this->_apiData['message'] = 'Target user profile is not available';
		}else if($record_id != 0 && $record->status == 1) {
			$this->_apiData['message'] = 'Sorry, you are already friend with this user';
		}else if($target_user->user_id == $user->user_id) {
			$this->_apiData['message'] = 'You cannot add yourself';
		}else if($record_id != 0 && $record->status == 0 && ($record->actor_id == $user->user_id)) {
			$this->_apiData['message'] = 'Sorry, your request is still pending';
		}else if($record_id != 0 && $record->status == 0 && ($record->actor_id == $target_user->user_id)) {
			$this->_apiData['message'] = 'Sorry, user is already waiting for your approval';
		}else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = $data = array();			
			// save
			$save["actor_id"] = $request->actor_id;
			$save["actor_type"] = $request->actor_type;
			$save["target_id"] = $target_user->user_id;
			$save["status"] = 0;
			$save["datetime"] = strtotime("now");
			// insert friend data
			$friend_id = $entity_friend_model->put($save);			
 			// set for log
			$data['activity_type'] = 'friend_add';
			unset($data['{wildcard_identifier}_friend_id']);				
			$entity_log_model->put($data);
						
			// success message
			$this->_apiData['message'] = "Friend request sent";			
			// get user data
			$data[$this->_entity_identifier.'_friend_id'] = $friend_id;
			// assign to output
			$this->_apiData['data'] = $data;		
		}		
		return $this->__api_response($this->_apiData);
	
	}
	
	/**
     * Delete Friend
     *
     * @return Response
     */
	public function deleteFriend(Request $request){		
		// load models
		$entity_friend_model = $this->_model_path."{wildcard_ucword}Friend";
		$entity_friend_model = new $entity_friend_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_friend_id' => 'required|numeric',
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{ 		
			$friend_data = $entity_friend_model->get($request->{wildcard_identifier}_friend_id);
	
			// success response
			$this->_apiData['response'] = "success";
			// set data
			$data['actor_id'] = $friend_data->actor_id;	
			$data['actor_type'] = $friend_data->actor_type;		
		    $data['target_id'] = $friend_data->target_id;		 
			$data['deleted_at'] = date('Y-m-d H:i:s');
		
			// Update Friend data
            $entity_friend_model->set($request->{wildcard_identifier}_friend_id, (array) $data);
            // set for log
			$data['activity_type'] = 'friend_delete';
			unset($data['{wildcard_identifier}_friend_id']);	
			unset($data['deleted_at']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_friend_id'] = $friend_data->{wildcard_identifier}_friend_id;
		    // assign to output
            $this->_apiData['data'] = $data;    
        }    
        return $this->__api_response($this->_apiData);
    }	
	
	/**
     * Accept/Ignore Friend Request
     *
     * @return Response
     */
	public function acceptIgnoreFriend(Request $request){	  
	  // load models
	  $entity_friend_model = $this->_model_path."{wildcard_ucword}Friend";
	  $entity_friend_model = new $entity_friend_model;
	  $entity_log_model = $this->_model_path."{wildcard_ucword}Log";
	  $entity_log_model = new $entity_log_model;
	  
	  // trim/escape all
	  $request->merge(array_map('strip_tags', $request->all()));
	  $request->merge(array_map('trim', $request->all()));
	  
	  // param validations
	  $validator = Validator::make($request->all(), array(
		  '{wildcard_identifier}_friend_id' => 'required|numeric',
	  ));
	  
	  // validations
	  if ($validator->fails()) {
		  $this->_apiData['message'] = $validator->errors()->first();
	  }else{ 		
		  $friend_data = $entity_friend_model->get($request->{wildcard_identifier}_friend_id);
  
		  // success response
		  $this->_apiData['response'] = "success";
		  // set data
		  $data['actor_id']   = $friend_data->actor_id;	
		  $data['actor_type'] = $friend_data->actor_type;		
		  $data['target_id']  = $friend_data->target_id;		 
		  $data['status']     = $request->status;
		  $data['updated_at'] = date('Y-m-d H:i:s');
	  
		  // Update Friend data
		  $entity_friend_model->set($request->{wildcard_identifier}_friend_id, (array) $data);
		  // set for log
		  if($request->status == '1')
		  $data['activity_type'] = 'friend_accept';
		  else if($request->status == '2')
		  $data['activity_type'] = 'friend_reject';		  
		  unset($data['{wildcard_identifier}_friend_id']);	
		  unset($data['updated_at']);		 
		  $entity_log_model->put($data);
	  
		  // message
		  $this->_apiData['message'] = "Success";

		  $data[$this->_entity_identifier.'_friend_id'] = $friend_data->{wildcard_identifier}_friend_id;
		  // assign to output
		  $this->_apiData['data'] = $data;    
	  }    
	  return $this->__api_response($this->_apiData);
  }
  
  	/**
     * Get All Friend List
     *
     * @return Response
     */
    public function getAllFriend(Request $request){	
		// load models
		$entity_friend_model = $this->_model_path."{wildcard_ucword}Friend";
		$entity_friend_model = new $entity_friend_model;
		$user_model = $this->_model_path."User";
		$user_model = new $user_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// get data
		$user = $user_model->get($request->actor_id);
		
		if($user === FALSE) {
			$this->_apiData['message'] = 'Invalid user Request';
		}
		elseif($user->status == 0){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is inactive. Please check your activation email sent on registration.';
		}
		elseif($user->status > 1){
			// kick user
			$this->_apiData['kick_user'] = 1;
			// message
			$this->_apiData['message'] = 'Your account is either removed or banned by Administrator. Please contact Admin for details.';
		}
		else {
			// success response
			$this->_apiData['response'] = "success";
			// init output data array
			$this->_apiData['data'] = $data = array();
			
			// set initial array for records
			$data["friend_list"] = array();
			
			// find total pages
			$query = $entity_friend_model;			
			$user_id = $request->actor_id;
			$query->join("user", function($join) use($user_id) {
				$join->on("user.user_id", '=', \DB::raw("IF(f.actor_id = '".$user_id."',f.target_ud,f.actor_id)"));
			});
			$query->whereRaw("(f.`actor_id` = '".$user_id."' OR f.`target_id` = '".$user_id."')");
			$query->from("{wildcard_identifier}_friend AS f");
			$query->whereNull("f.deleted_at");
			$query->where("user.status","=", 1);
			$total_records = $query->count();			
			$page_no = ($request->page_no)?$request->page_no: 0;
			// offfset / limits / valid pages
			$page_limit = 10;
			$total_pages = ceil($total_records / $page_limit);
			$page_no = $page_no >= $total_pages ? $total_pages : $page_no;
			$page_no = $page_no <= 1 ? 1 : $page_no;
			$offset = $page_limit * ($page_no - 1);			
			// query records
			$query = $entity_friend_model
				->selectRaw("user.name AS user_name, user.user_id AS friend_user_id, f.{wildcard_identifier}_friend_id");			
			$user_id = $request->actor_id;
			$query->join("user", function($join) use($user_id) {
				//$join->on('users.id', '=', 'contacts.user_id');
				$join->on("user.user_id", '=', \DB::raw("IF(f.actor_id = '".$user_id."',f.target_id,f.actor_id)"));
			});
			$query->whereRaw("(f.`actor_id` = '".$user_id."' OR f.`target_id` = '".$user_id."')");
			$query->take($page_limit);
			$query->skip($offset);
			//$query->orderBy("user_name", "ASC");
			$query->orderBy("f.created_at", "DESC");
			$query->groupBy(array("user.user_id"));
			$query->from("{wildcard_identifier}_friend AS f");
			$query->whereNull("f.deleted_at");
			$query->whereNull("user.deleted_at");
			$query->where("user.status","=", 1);
			$raw_records = $query->get();
			//echo $query->toSql();
			//exit;
			
			$data["friend_list"][] = $raw_records;
			
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
		
		
		return $this->__api_response($this->_apiData);	
	}
	
	/**
     * Share Post
     *
     * @return Response
     */
	public function postShare(Request $request){		
		// load models
		$entity_share_model = $this->_model_path."{wildcard_ucword}Share";
		$entity_share_model = new $entity_share_model;
		
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric',
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{
			// success response
			$this->_apiData['response'] = "success";
			// set data
			$data['actor_id']   = $request->actor_id;	
			$data['actor_type'] = $request->actor_type;		
		    $data['post_id']    = $request->post_id;	
			$data['title']    = $request->title;
			$data['referral_id']    = $request->referral_id;			 
			$data['created_at'] = date('Y-m-d H:i:s');
		
			// insert share data
			$insert_id = $entity_share_model->put($data);		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_share_id'] = $insert_id;
		    // assign to output
            $this->_apiData['data'] = $data;    
        }    
        return $this->__api_response($this->_apiData);
    }	
	
	/**
     * Delete Share
     *
     * @return Response
     */
	public function deleteShare(Request $request){		
		// load models
		$entity_share_model = $this->_model_path."{wildcard_ucword}Share";
		$entity_share_model = new $entity_share_model;
		
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_share_id' => 'required|numeric',
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{
			// success response
			$this->_apiData['response'] = "success";
			// set data	 
			$data['deleted_at'] = date('Y-m-d H:i:s');
			$entity_share_model->set($request->{wildcard_identifier}_share_id, (array) $data);		
			// message
			$this->_apiData['message'] = "Success";
			$share_data = $entity_share_model->get($request->{wildcard_identifier}_share_id); 	
		    // assign to output
            $this->_apiData['data'] = $share_data;    
        }    
        return $this->__api_response($this->_apiData);
    }	

	
	/**
     * Add Tag
     *
     * @return Response
     */
	public function addTag(Request $request){		
		// load models
		$entity_tag_model = $this->_model_path."{wildcard_ucword}Tag";
		$entity_tag_model = new $entity_tag_model;
		
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'actor_id' => 'required|numeric',
			'actor_type' => 'required',
			'target_id' => 'required|numeric',
			'target_type' => 'required'
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{
			// success response
			$this->_apiData['response'] = "success";
			// set data
			$data['actor_id']   = $request->actor_id;	
			$data['actor_type'] = $request->actor_type;		
		    $data['target_id']   = $request->target_id;
			$data['target_type']   = $request->target_type;					 
			$data['created_at'] = date('Y-m-d H:i:s');		
			// insert tag data
			$insert_id = $entity_tag_model->put($data);		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_tag_id'] = $insert_id;
		    // assign to output
            $this->_apiData['data'] = $data;    
        }    
        return $this->__api_response($this->_apiData);
    }	
	
	/**
     * Delete Share
     *
     * @return Response
     */
	public function deleteTag(Request $request){		
		// load models
		$entity_tag_model = $this->_model_path."{wildcard_ucword}Tag";
		$entity_tag_model = new $entity_tag_model;
		
		$entity_log_model = $this->_model_path."{wildcard_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_tag_id' => 'required|numeric',
		));
		
		// validations
        if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        }else{
			// success response
			$this->_apiData['response'] = "success";
			// set data	 
			$data['deleted_at'] = date('Y-m-d H:i:s');
			$entity_tag_model->set($request->{wildcard_identifier}_tag_id, (array) $data);		
			// message
			$this->_apiData['message'] = "Success";
			$tag_data = $entity_tag_model->get($request->{wildcard_identifier}_tag_id); 	
		    // assign to output
            $this->_apiData['data'] = $tag_data;    
        }    
        return $this->__api_response($this->_apiData);
    }
	
}
