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

class Project extends Base implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_project';
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
        $this->__fields = array($this->primaryKey, 'projectName', 'projectSlug', 'projectDescription', 'projectPriority', 'projectDueDate', 'projectTotalCost', 'projectPendingCost', 'fkBrandId', 'fkClientId', 'fkCreatedById', 'projectStatus', 'isActive', 'archivedAt', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	/**
     * Records Listing
     * @param Search array
     * @return object $data
     */
	 function ajaxListing($search_array){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$c = $mongo->$db->selectCollection($table);
		$search_array['archivedAt'] = "";
		$search_array['deletedAt'] = "";
		if(isset($search_array['brands'])){
			$search_array['fkBrandId'] = array('$in' => $search_array['brands']);
			unset($search_array['brands']);
		}
		if(isset($search_array['fkSegmentId'])){
			$segmentId = $search_array['fkSegmentId'];
			unset($search_array['fkSegmentId']);
			$pipeline = array(
				array(
					'$match' => $search_array,
				),
				array(
					'$lookup' => array('from' => "stm_brand",
						'localField' => "fkBrandId",
						'foreignField' => "_id",
						'as' => "brandArray",
					),
				),
				
				array(
					'$lookup' => array('from' => "stm_client",
						'localField' => "fkClientId",
						'foreignField' => "_id",
						'as' => "clientArray",
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
					'$lookup' => array('from' => "stm_projectSegment",
						'localField' => "_id",
						'foreignField' => "fkProjectId",
						'as' => "segmentArray",
					),
				),
				array(
					'$addFields' => array( 'segmentArray' => 
						array('$arrayElemAt' => array(array('$filter' => array('input' => '$segmentArray', 'as' => 'comp', 'cond' => array('$eq' => array('$$comp.fkSegmentId', $segmentId)))),0))),
				),
				array(
					'$unwind' =>'$segmentArray'
				),
				
				array(
					'$sort' => array( 'addedAt' => -1 ),
				),
			);
		}
		else{
			#echo "<pre>";print_r($search_array);exit;
			$pipeline = array(
				array(
					'$match' => $search_array,
				),
				array(
					'$lookup' => array('from' => "stm_brand",
						'localField' => "fkBrandId",
						'foreignField' => "_id",
						'as' => "brandArray",
					),
				),
				
				array(
					'$lookup' => array('from' => "stm_client",
						'localField' => "fkClientId",
						'foreignField' => "_id",
						'as' => "clientArray",
					),
				),
				
				array(
					'$lookup' => array('from' => "stm_user",
						'localField' => "fkCreatedById",
						'foreignField' => "_id",
						'as' => "userArray",
					),
				),
			);
		}
		#echo "<pre>";print_r($pipeline);exit;
		$cursor = $c->aggregate($pipeline);
		return $cursor;
		
		
		
		/*$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->$table->find($search_array)->sort(array("addedAt" => -1));
		$record = iterator_to_array($cursor);
		return $record;*/
	 }
	 
	 
	 function getRecordById($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("_id" => new \MongoId($id));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	
	 }
	 
	 function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$mongo->$db->$table->insert($save); 
		$newDocID = $save['_id'];
		return $newDocID;
	 }
	 
	 function getUpdateRecord($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array("_id" => new \MongoId($id));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	
	 }
	 
	 function _updateRecord($id, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		if(isset($save['userPassword']) && $save['userPassword'] != ""){
			$save['userPassword'] = $this->saltPassword($save['userPassword']);	
		}
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	 }
	 
	//Active Listing
	function getActiveListing(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
			"isActive" => "1",
			"deletedAt" => "");
		$cursor = $mongo->$db->$table->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		return $paginated_ids;
	}
	
	//update project status
	function updateProjectStatus($projectId, $projectStatus){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$save['projectStatus'] = $projectStatus;
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("_id" => new \MongoId($projectId)), $newdata);
		// set pk
		return "success";
	}
	
	//getBrandByProjectId
	function getBrandByProjectId($projectId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
			"_id" => new \MongoId($projectId),
			"isActive" => "1",
			"deletedAt" => "");
		$cursor = $mongo->$db->$table->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		if(!empty($paginated_ids)){
			foreach($paginated_ids as $key => $paginated_ids){}
			$brandId = $paginated_ids['fkBrandId']->{'$id'};
			$brandSearch['_id'] = new \MongoId($brandId);
			$brandSearch['isActive'] = "1";
			$brandSearch['deletedAt'] = "";
			$paginated_ids = $mongo->$db->stm_brand->find($brandSearch);
			$paginated_ids = iterator_to_array($paginated_ids);
		}
		return $paginated_ids;
	}
	
	//get records by client id
	function getRecordsByClientId($clientId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkClientId'] = new \MongoId($clientId);
		$search_array['archivedAt'] = "";
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
		$pipeline = array(
			array(
				'$match' => $search_array
			),
			array(
				'$lookup' => array('from' => "stm_user",
					'localField' => "fkCreatedById",
					'foreignField' => "_id",
					'as' => "createdArray",
				),
			),
			array(
				'$lookup' => array('from' => "stm_brand",
					'localField' => "fkBrandId",
					'foreignField' => "_id",
					'as' => "brandArray",
				),
			),
		);
		$cursor = $c->aggregate($pipeline);
		return $cursor;
	
	 }
	
}
