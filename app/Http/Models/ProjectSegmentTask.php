<?php

namespace App\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;
use Illuminate\Http\Request;

//use Mail;
// load models
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Http\Models\Conf;
use App\Http\Models\Setting;
use App\Http\Models\EmailTemplate;

class ProjectSegmentTask extends Base implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_projectSegmentTask';
    protected $dates = ['deletedAt'];
	public $timestamps = true;
	public $primaryKey = '_id';
	// enitity vars
	private $_entity_identifier;
	private $_entity_session_identifier = ADMIN_SESS_KEY;
	private $_entity_dir = DIR_ADMIN;
	private $_entity_salt_pattren = ADMIN_SALT;
	
	

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['name', 'email', 'password', 'admin_group_id', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = ['password', 'rememberLoginToken'];
	
	 public function __construct() {
        // set tables and keys
        $this->__table = $this->table;
        //$this->primaryKey = $this->__table . '_id';
		$this->primaryKey = '_id';
        $this->__keyParam = $this->primaryKey . '-';
        $this->hidden = array();
		// set entity vars
		#$this->_entity_identifier = $this->table;
		$this->_entity_identifier = 'admin';

        // set fields
        //
        $this->__fields = array($this->primaryKey, 'fkProjectId', 'fkSegmentId', 'fkPhase', 'fkCreatedById', 'fkBrandId', 'orderOf', 'taskPriority', 'taskPrevStatus', 'taskStatus', 'taskDescription', 'taskDueDate', 'revisionDueDate', 'isActive', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	
	/**
     * Add Records
     * @param Search array
     * @return object $data
     */
	 function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$mongo->$db->$table->insert($save); 
		$newDocID = $save['_id'];
		return $newDocID;
	 }
	 
	 /**
     * Get Records By Task ID
     * @param Search array
     * @return object $data
     */
	 function getRecordByTaskId($taskId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("_id" => new \MongoId($taskId));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	 }
	 
	 
	/**
     * Get Records By Segment ID
     * @param Search array
     * @return object $data
     */
	 function getRecordsBySegmentId($segmentId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("fkSegmentId" => new \MongoId($segmentId));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	 }
	 
	 
	/**
     * Get Records By Segment ID
     * @param Search array
     * @return object $data
     */
	 function getRecordsByProjectId($search_array){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		$search_array['fkProjectId'] = new \MongoId($search_array['fkProjectId']);
		$c = $mongo->$db->selectCollection($table);
		if(isset($search_array['fkUserId'])){
			$assignToId = $search_array['fkUserId'];
			unset($search_array['fkUserId']);
			
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_taskUserAssignment",
						'localField' => "_id",
						'foreignField' => "fkTaskId",
						'as' => "assignToArray",
					),
				),
				/*array(
					'$match' => array( 'assignToArray' => array('fkUserId' => $assignToId) ),
				),*/
				
				array(
					'$addFields' => array( 'assignToArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$assignToArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkUserId', $assignToId)))),0))),
				),
				array(
					'$unwind' =>'$assignToArray'
				),
				
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}else{
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
		}
		
		$cursor = $c->aggregate($pipeline);
		#echo "<pre>";print_r($cursor);exit;
		return $cursor; 
		 
		 
		 
		 
		 
		/*if(!empty($search_array) && isset($search_array['fkUserId'])){
			
		}else{
			$mongo = mongoInitialization();
			$db = MASTER_DB_NAME;
			$table = $this->__table;
			$search_array = array("fkProjectId" => new \MongoId($projectId));
			$cursor = $mongo->$db->$table->find($search_array);
			$record = iterator_to_array($cursor);
			return $record;
		}*/
	 }
	 
	
	/**
     * Records Listing
     * @param Search array
     * @return object $data
     */
	 function getSegmentListing($projectId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkProjectId'] = new \MongoId($projectId);
		$search_array['isActive'] = 1;
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
		$pipeline = array(
			array(
				'$match' => $search_array
			),
			array(
				'$lookup' => array('from' => "stm_department",
					'localField' => "fkSegmentId",
					'foreignField' => "_id",
					'as' => "segmentArray",
				),
			),
		);
		
		$cursor = $c->aggregate($pipeline);
		return $cursor;
		
		/*$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->$table->find($search_array)->sort(array("addedAt" => -1));
		$record = iterator_to_array($cursor);
		return $record;*/
	 }
	 
	 
	 /**
     * Remove Records
     * @param Search array
     * @return object $data
     */
	 function _removeRecords($projectId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$search_array['fkProjectId'] = new \MongoId($projectId);
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		if(!empty($record)){
			foreach($record as $key => $value){
				$save['deletedAt'] = date('Y-m-d H:i:s');
				$newdata = array('$set' => $save);
				$mongo->$db->$table->update(array("_id" => new \MongoId($key)), $newdata);
				unset($save);
			}
		}
		return "done";
	 }
	 
	 
	 /**
     * Get Records
     * @param Search array
     * @return object $data
     */
	 function getUpdateRecord($projectId, $segmentId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("fkProjectId" => new \MongoId($projectId), "fkSegmentId" => new \MongoId($segmentId));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	 }
	 
	 /**
     * Get Records
     * @param Search array
     * @return object $data
     */
	 function getUpdateRecordByTaskId($taskId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("_id" => new \MongoId($taskId));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	 }
	 
	 
	 /**
     * Get Records
     * @param Search array
     * @return object $data
     */
	 
	 
	 function _updateRecord($projectId, $segmentId, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("fkProjectId" => new \MongoId($projectId), "fkSegmentId" => new \MongoId($segmentId)), $newdata);
		return "done";
	 }
	 
	 /**
     * Get Records
     * @param Search array
     * @return object $data
     */
	 function _updateRecordByTaskId($taskId, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("_id" => new \MongoId($taskId)), $newdata);
		return "done";
	 }
	 
	 
	 /**
     * Get Record By ID
     * @param Search array
     * @return object $data
     */
	 function getRecordById($id){
		 $mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("_id" => new \MongoId($id));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	 }
	 
	 
	 /**
     * Get All Tasks
     * @param Search array
     * @return object $data
     */
	 public function alltasksAjaxListing($search_array){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
		if(isset($search_array['brands'])){
			$search_array['fkBrandId'] = array('$in' => $search_array['brands']);
			unset($search_array['brands']);
		}
		if(isset($search_array['fkUserId'])){
			$assignToId = $search_array['fkUserId'];
			unset($search_array['fkUserId']);
			
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_taskUserAssignment",
						'localField' => "_id",
						'foreignField' => "fkTaskId",
						'as' => "assignToArray",
					),
				),
				/*array(
					'$match' => array( 'assignToArray' => array('fkUserId' => $assignToId) ),
				),*/
				
				array(
					'$addFields' => array( 'assignToArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$assignToArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkUserId', $assignToId)))),0))),
				),
				array(
					'$unwind' =>'$assignToArray'
				),
				
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}elseif(isset($search_array['fkCreatedById'])){
			$projectCreatedBy = $search_array['fkCreatedById'];
			unset($search_array['fkCreatedById']);
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$addFields' => array( 'projectArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$projectArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkCreatedById', $projectCreatedBy)))),0))),
				),
				array(
					'$unwind' =>'$projectArray'
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
			
		}else{
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
		}
		$cursor = $c->aggregate($pipeline);
		#echo "<pre>";print_r($cursor);exit;
		return $cursor;
	 }
	 
	 
	 /**
     * Get All Due Tasks
     * @param Search array
     * @return object $data
     */
	 public function alldueAjaxListing($search_array){
		#echo "<pre>";print_r($search_array);exit;
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
		if(isset($search_array['brands'])){
			$search_array['fkBrandId'] = array('$in' => $search_array['brands']);
			unset($search_array['brands']);
		}
		#$search_array['taskDueDate'] = array('$gte' => date('Y-m-d H:i:s'));
		if(isset($search_array['fkUserId'])){
			$assignToId = $search_array['fkUserId'];
			unset($search_array['fkUserId']);
			
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => array(
						'$or' => array(
							array("revisionDueDate" => array('$gte' => date('Y-m-d H:i:s'))),
							array("taskDueDate" => array('$gte' => date('Y-m-d H:i:s')))
						),
					),
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_taskUserAssignment",
						'localField' => "_id",
						'foreignField' => "fkTaskId",
						'as' => "assignToArray",
					),
				),
				array(
					'$addFields' => array( 'assignToArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$assignToArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkUserId', $assignToId)))),0))),
				),
				array(
					'$unwind' =>'$assignToArray'
				),
				
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}elseif(isset($search_array['fkCreatedById'])){
			$projectCreatedBy = $search_array['fkCreatedById'];
			unset($search_array['fkCreatedById']);
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => array(
						'$or' => array(
							array("revisionDueDate" => array('$gte' => date('Y-m-d H:i:s'))),
							array("taskDueDate" => array('$gte' => date('Y-m-d H:i:s')))
						),
					),
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$addFields' => array( 'projectArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$projectArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkCreatedById', $projectCreatedBy)))),0))),
				),
				array(
					'$unwind' =>'$projectArray'
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
			
		}else{
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => array(
						'$or' => array(
							array("revisionDueDate" => array('$gte' => date('Y-m-d H:i:s'))),
							array("taskDueDate" => array('$gte' => date('Y-m-d H:i:s')))
						),
					),
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}
		$cursor = $c->aggregate($pipeline);
		#echo "<pre>";print_r($cursor);exit;
		return $cursor;
	 }
	 
	 
	 /**
     * Get Due Today Tasks
     * @param Search array
     * @return object $data
     */
	 public function duetodayAjaxListing($search_array){
		#echo "<pre>";print_r($currDate);exit;
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		if(isset($search_array['brands'])){
			$search_array['fkBrandId'] = array('$in' => $search_array['brands']);
			unset($search_array['brands']);
		}
		$c = $mongo->$db->selectCollection($table);
		#$search_array['taskDueDate'] = array('$gte' => date('Y-m-d H:i:s'));
		if(date('H') > 0 && date('H') < 5){
			#$search_array['taskDueDate'] = array(array('$gt' => date('Y-m-d H:i:s'), '$lte' => date('Y-m-d 05:00:00')));
			$dateMatchArr = array(
				'$or' => array(
					array("revisionDueDate" => array('$gt' => date('Y-m-d H:i:s'), '$lte' => date('Y-m-d 05:00:00'))),
					array("taskDueDate" => array('$gt' => date('Y-m-d H:i:s'), '$lte' => date('Y-m-d 05:00:00')))
				),
			);
		}else{
			$currDate = date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:01" . "+1 days"));
			#$search_array['taskDueDate'] = array('$gt' => date('Y-m-d H:i:s'), '$lte' => $currDate);
			$dateMatchArr = array(
				'$or' => array(
					array("revisionDueDate" => array('$gt' => date('Y-m-d H:i:s'), '$lte' => $currDate)),
					array("taskDueDate" => array('$gt' => date('Y-m-d H:i:s'), '$lte' => $currDate))
				),
			);
		}
		if(isset($search_array['fkUserId'])){
			$assignToId = $search_array['fkUserId'];
			unset($search_array['fkUserId']);
			
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => $dateMatchArr,
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_taskUserAssignment",
						'localField' => "_id",
						'foreignField' => "fkTaskId",
						'as' => "assignToArray",
					),
				),
				/*array(
					'$match' => array( 'assignToArray' => array('fkUserId' => $assignToId) ),
				),*/
				
				array(
					'$addFields' => array( 'assignToArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$assignToArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkUserId', $assignToId)))),0))),
				),
				array(
					'$unwind' =>'$assignToArray'
				),
				
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}elseif(isset($search_array['fkCreatedById'])){
			$projectCreatedBy = $search_array['fkCreatedById'];
			unset($search_array['fkCreatedById']);
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => $dateMatchArr,
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$addFields' => array( 'projectArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$projectArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkCreatedById', $projectCreatedBy)))),0))),
				),
				array(
					'$unwind' =>'$projectArray'
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
			
		}else{
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => $dateMatchArr,
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}
		$cursor = $c->aggregate($pipeline);
		#echo "<pre>";print_r($cursor);exit;
		return $cursor;
	 }
	 
	 /**
     * Get Clarifications Tasks
     * @param Search array
     * @return object $data
     */
	 public function clarificationAjaxListing($search_array){
		#echo "<pre>";print_r($search_array);exit;
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		if(isset($search_array['brands'])){
			$search_array['fkBrandId'] = array('$in' => $search_array['brands']);
			unset($search_array['brands']);
		}
		$c = $mongo->$db->selectCollection($table);
		$search_array['taskDueDate'] = array('$lt' => date('Y-m-d H:i:s'));
		$pipeline = array(
			array(
				'$match' => $search_array
			),
			array(
				'$lookup' => array('from' => "stm_project",
					'localField' => "fkProjectId",
					'foreignField' => "_id",
					'as' => "projectArray",
				),
			),
			array(
				'$lookup' => array('from' => "stm_user",
					'localField' => "fkCreatedById",
					'foreignField' => "_id",
					'as' => "userArray",
				),
			),
			array(
				'$sort' => array( 'addedAt' => -1 ),
			),
			
		);
		
		$cursor = $c->aggregate($pipeline);
		#echo "<pre>";print_r($cursor);exit;
		return $cursor;
	 }
	 
	 
	 /**
     * Get Over Due Tasks
     * @param Search array
     * @return object $data
     */
	 public function overdueAjaxListing($search_array){
		#echo "<pre>";print_r($search_array);exit;
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		if(isset($search_array['brands'])){
			$search_array['fkBrandId'] = array('$in' => $search_array['brands']);
			unset($search_array['brands']);
		}
		$c = $mongo->$db->selectCollection($table);
		#$search_array['taskDueDate'] = array('$lt' => date('Y-m-d H:i:s'));
		if(isset($search_array['fkUserId'])){
			$assignToId = $search_array['fkUserId'];
			unset($search_array['fkUserId']);
			
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => array(
						'$and' => array(
							array("revisionDueDate" => array('$lt' => date('Y-m-d H:i:s'))),
							array("taskDueDate" => array('$lt' => date('Y-m-d H:i:s')))
						),
					),
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_taskUserAssignment",
						'localField' => "_id",
						'foreignField' => "fkTaskId",
						'as' => "assignToArray",
					),
				),
				/*array(
					'$match' => array( 'assignToArray' => array('fkUserId' => $assignToId) ),
				),*/
				
				array(
					'$addFields' => array( 'assignToArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$assignToArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkUserId', $assignToId)))),0))),
				),
				array(
					'$unwind' =>'$assignToArray'
				),
				
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}elseif(isset($search_array['fkCreatedById'])){
			$projectCreatedBy = $search_array['fkCreatedById'];
			unset($search_array['fkCreatedById']);
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => array(
						'$and' => array(
							array("revisionDueDate" => array('$lt' => date('Y-m-d H:i:s'))),
							array("taskDueDate" => array('$lt' => date('Y-m-d H:i:s')))
						),
					),
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$addFields' => array( 'projectArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$projectArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkCreatedById', $projectCreatedBy)))),0))),
				),
				array(
					'$unwind' =>'$projectArray'
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
			
		}else{
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$match' => array(
						'$and' => array(
							array("revisionDueDate" => array('$lt' => date('Y-m-d H:i:s'))),
							array("taskDueDate" => array('$lt' => date('Y-m-d H:i:s')))
						),
					),
				),
				array(
					'$lookup' => array('from' => "stm_project",
						'localField' => "fkProjectId",
						'foreignField' => "_id",
						'as' => "projectArray",
					),
				),
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
				
			);
		}
		$cursor = $c->aggregate($pipeline);
		#echo "<pre>";print_r($cursor);exit;
		return $cursor;
	 }
	 
	 
	 /**
     * Get Records By Task ID
     * @param Search array
     * @return object $data
     */
	 function getRecordWithUserByTaskId($taskId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("_id" => new \MongoId($taskId), "deletedAt" => "");
		$c = $mongo->$db->selectCollection($table);
		$pipeline = array(
			array(
				'$match' => $search_array
			),
			array(
				'$lookup' => array('from' => "stm_user",
					'localField' => "fkCreatedById",
					'foreignField' => "_id",
					'as' => "userArray",
				),
			),
		);
		$cursor = $c->aggregate($pipeline);
		return $cursor;
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	 }
	 
	 
}
