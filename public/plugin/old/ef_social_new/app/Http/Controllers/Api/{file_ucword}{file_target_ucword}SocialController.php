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
		//$this->_history_model = $this->_model_path."History";
		//$this->_history_model = new $this->_history_model;	
		
		
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
		$this->_plugin_config["webservices"] = isset($this->_plugin_config["webservices"]) : $this->_plugin_config["webservices"] : array();
		
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
    public function like(Request $request) {
		// load models
		$entity_like_model = $this->_model_path."{wildcard_ucword}{target_ucword}Like";
		$entity_like_model = new $entity_like_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// validate type
		$request->type = in_array($request->type,config('pl_{wildcard_identifier}.LIKE_TYPES')) ? $request->type : 0;
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_id}" => 'required|numeric',
			"{wildcard_pk}" => 'required|numeric'
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
			$raw_exists = $entity_like_model->select("{wildcard_identifier}_{target_identifier}_like_id","status")
				->where("{target_pk}","=",$request->{target_pk})
				//->where("type","=",$request->type)
				->where("{wildcard_pk}","=",$request->{wildcard_pk})
				->whereNull("deleted_at")
				->get();
	
			$exists_id = isset($raw_exists[0]) ? $raw_exists[0]->{wildcard_identifier}_{target_identifier}_like_id : 0;
			
			$data["{target_id}"] = $request->{target_pk};
			$data["{wildcard_pk}"] = $request->{wildcard_pk};
			$data["type"] = $request->type;
			$data['status'] = 1;
	
			if($exists_id == 0){
				// Insert Like data
				$data['created_at'] = date('Y-m-d H:i:s');
				$entity_like_model->put($data);
				// set for log
				$data['activity_type'] = 'like';
				$entity_log_model->put($data);
			} else {
				$data['type'] = $request->type;
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
     * Follow
     *
     * @return Response
     */
    public function follow(Request $request) {
		
		// load models
		$entity_follow_model = $this->_model_path."{wildcard_ucword}{target_ucword}Follow";
		$entity_follow_model = new $entity_follow_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_id}" => 'required|numeric',
			"{wildcard_pk}" => 'required|numeric'
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
			$raw_exists = $entity_follow_model->select("{wildcard_identifier}_{target_identifier}_follow_id","status")
				->where("{target_pk}","=",$request->{target_pk})			
				->where("{wildcard_pk}","=",$request->{wildcard_pk})
				->whereNull("deleted_at")
				->get();
			$exists_id = isset($raw_exists[0]) ? $raw_exists[0]->{wildcard_identifier}_{target_identifier}_follow_id : 0;
			
			$data["{target_id}"] = $request->{target_pk};		
			$data["{wildcard_pk}"] = $request->{wildcard_pk};
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
		$entity_follow_model = $this->_model_path."{wildcard_ucword}{target_ucword}Follow";
		$entity_follow_model = new $entity_follow_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_{target_identifier}_follow_id' => 'required|numeric'
		));
		
		// validations					
		if(!in_array("social/unfollow", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
			$this->_apiData['message'] = $validator->errors()->first();
		} else {
			// success response
			$this->_apiData['response'] = "success";	
				
			$follow_data = $entity_follow_model->get($request->{wildcard_identifier}_{target_identifier}_follow_id);
        
			$data['{wildcard_identifier}_{target_identifier}_follow_id'] = $request->{wildcard_identifier}_{target_identifier}_follow_id;
			$data["{target_id}"] = $follow_data->actor_id;		
			$data["{wildcard_pk}"] = $follow_data->target_id;
			$data['status']    = 0;		
			$data1['updated_at'] = date('Y-m-d H:i:s');
		
			// update status
            $entity_follow_model->set($request->{wildcard_identifier}_{target_identifier}_follow_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'unfollow';
			unset($data['{wildcard_identifier}_{target_identifier}_follow_id']);				
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";			
			$data[$this->_entity_identifier.'_{target_identifier}_follow_id'] = $follow_data->{wildcard_identifier}_{target_identifier}_follow_id;
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
		$entity_comment_model = $this->_model_path."{wildcard_ucword}{target_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_id}" => 'required|numeric',
			"{wildcard_pk}" => 'required|numeric',
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
	
			$data["{target_id}"] = $request->{target_pk};
			$data["{wildcard_pk}"] = $request->{wildcard_pk};
			$data['comment'] = $request->comment;
			$data['parent_id'] = 0;
			$data['created_at'] = date('Y-m-d H:i:s');
			
			// Insert Comment data
			$insert_id = $entity_comment_model->put($data);
			// set for log
			$data['activity_type'] = 'comment_add';
			unset($data['comment'],$data['parent_id']);
			$entity_log_model->put($data);
	
			$data[$this->_entity_identifier.'_{target_identifier}_comment_id'] = $insert_id;	
			
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
		$entity_comment_model = $this->_model_path."{wildcard_ucword}{target_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_{target_identifier}_comment_id' => 'required|numeric',
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
				
			$comment_data = $entity_comment_model->get($request->{wildcard_identifier}_{target_identifier}_comment_id);
	
			$parent_id = $request->input('parent_id', '');
			$parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
			
			
			$data['{wildcard_identifier}_{target_identifier}_comment_id'] = $request->{wildcard_identifier}_{target_identifier}_comment_id;
			$data["{target_id}"] = $comment_data->actor_id;
			$data['actor_type'] = $comment_data->actor_type;
			$data["{wildcard_pk}"] = $comment_data->target_id;
			$data1['comment'] = $request->comment;
			$data1['parent_id'] = $comment_data->parent_id;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data1['updated_at'] = date('Y-m-d H:i:s');
		
			// Insert Comment data
            $entity_comment_model->set($request->{wildcard_identifier}_{target_identifier}_comment_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'comment_edit';
			unset($data['{wildcard_identifier}_{target_identifier}_comment_id']);	
			unset($data['parent_id']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data['comment'] = $request->comment;
			$data[$this->_entity_identifier.'_{target_identifier_comment_id'] = $comment_data->{wildcard_identifier}_{target_identifier}_comment_id;
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
		$entity_comment_model = $this->_model_path."{wildcard_ucword}{target_ucword}Comment";
		$entity_comment_model = new $entity_comment_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_{target_identifier}_comment_id' => 'required|numeric'
		));
		
		// validations
       	if(!in_array("social/comment/delete", $this->_plugin_config["webservices"])){
			$this->_apiData['message'] = 'You are not authorized to access this service.';			
		} else if ($validator->fails()) {
            $this->_apiData['message'] = $validator->errors()->first();
        } else {
			// success response
			$this->_apiData['response'] = "success";	
				
			$comment_data = $entity_comment_model->get($request->{wildcard_identifier}_{target_identifier}_comment_id);
	
			$parent_id = $request->input('parent_id', '');
			$parent_id = $parent_id == "" ? 0 : $parent_id; // set default value
			
			
			$data['{wildcard_identifier}_{target_identifier}_comment_id'] = $request->{wildcard_identifier}_{target_identifier}_comment_id;
			$data["{target_id}"] = $comment_data->actor_id;
			$data['actor_type'] = $comment_data->actor_type;
			$data["{wildcard_pk}"] = $comment_data->target_id;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data1['deleted_at'] = date('Y-m-d H:i:s');
		
			// Insert Comment data
            $entity_comment_model->set($request->{wildcard_identifier}_{target_identifier}_comment_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'comment_delete';
			unset($data['{wildcard_identifier}_{target_identifier}_comment_id']);	
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_{target_identifier}_comment_id'] = $comment_data->{wildcard_identifier}_{target_identifier}_comment_id;
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
		$entity_review_model = $this->_model_path."{wildcard_ucword}{target_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			"{target_id}" => 'required|numeric',
			"{wildcard_pk}" => 'required|numeric',
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
	
			$data["{target_id}"] = $request->{target_pk};
			$data["{wildcard_pk}"] = $request->{wildcard_pk};
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

			$data[$this->_entity_identifier.'_{target_identifier}_review_id'] = $insert_id;
            
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
		$entity_review_model = $this->_model_path."{wildcard_ucword}{target_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_{target_identifier}_review_id' => 'required|numeric',
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
				
			$review_data = $entity_review_model->get($request->{wildcard_identifier}_{target_identifier}_review_id);
				
			$data['{wildcard_identifier}_{target_identifier}_review_id'] = $request->{wildcard_identifier}_{target_identifier}_review_id;
			$data["{target_id}"] = $review_data->actor_id;
			$data['actor_type'] = $review_data->actor_type;
			$data["{wildcard_pk}"] = $review_data->target_id;
			$data1['review'] = $request->review;
			$data1['rating'] = $request->rating;
			$data['created_at'] = date('Y-m-d H:i:s');
			$data1['updated_at'] = date('Y-m-d H:i:s');
		
			// Update Review data
            $entity_review_model->set($request->{wildcard_identifier}_{target_identifier}_review_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'review_edit';
			unset($data['{wildcard_identifier}_{target_identifier}_review_id'],$data['review'],$data['rating']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_{target_identifier}_review_id'] = $review_data->{wildcard_identifier}_{target_identifier}_review_id;
            
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
		$entity_review_model = $this->_model_path."{wildcard_ucword}{target_ucword}Review";
		$entity_review_model = new $entity_review_model;
		$entity_log_model = $this->_model_path."{wildcard_ucword}{target_ucword}Log";
		$entity_log_model = new $entity_log_model;
		
		// trim/escape all
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// param validations
		$validator = Validator::make($request->all(), array(
			'{wildcard_identifier}_{target_identifier}_review_id' => 'required|numeric',
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
			$review_data = $entity_review_model->get($request->{wildcard_identifier}_{target_identifier}_review_id);
		
			$data['{wildcard_identifier}_{target_identifier}_review_id'] = $request->{wildcard_identifier}_{target_identifier}_review_id;
			$data["{target_id}"] = $review_data->actor_id;
			$data['actor_type'] = $review_data->actor_type;
			$data["{wildcard_pk}"] = $review_data->target_id;
			$data1['review'] = $review_data->review;
			$data1['rating'] = $review_data->rating;
			$data1['deleted_at'] = date('Y-m-d H:i:s');
		
			// Update Review data
            $entity_review_model->set($request->{wildcard_identifier}_{target_identifier}_review_id, (array) $data1);
            // set for log
			$data['activity_type'] = 'review_delete';
			unset($data['{wildcard_identifier}_{target_identifier}_review_id'],$data['review'],$data['rating']);
			$entity_log_model->put($data);
		
			// message
			$this->_apiData['message'] = "Success";

			$data[$this->_entity_identifier.'_{target_identifier}_review_id'] = $review_data->{wildcard_identifier}_{target_identifier}_review_id;
		    // assign to output
            $this->_apiData['data'] = $data;    
        }    
        return $this->__api_response($this->_apiData);
    }
}
