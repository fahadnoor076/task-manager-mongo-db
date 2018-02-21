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

class UserModule extends Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_userModule';
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
	
	 public function __construct() {
        // set tables and keys
        $this->__table = $this->table;
        //$this->primaryKey = $this->__table . '_id';
		$this->primaryKey = '_id';
        $this->__keyParam = $this->primaryKey . '-';
        $this->hidden = array();
		// set entity vars
		$this->_entity_identifier = $this->table;

        // set fields
        //
        $this->__fields = array($this->primaryKey, 'parentId', 'userModuleName', 'userModuleSlug', 'iconClass', 'isActive', 'showInMenu','orderId', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	
	/**
     * Get CRUD Listing
     * 
     */
    
	public function ajaxAllListing($search_array){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->stm_userModule->find($search_array)->sort(array("orderId" => 1));
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	
	/**
     * Get Listing
     * 
     */
    
	public function ajaxListing(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"deletedAt" => "",
						"isActive" => "1",
						"parentId" => "0",
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	/**
     * Get By Id
     * 
     */
	public function getRecordById($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
						"_id" => new\MongoID($id),
						"deletedAt" => "",
					);
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	
	/**
     * Get By Id
     * 
     */
	public function getChildrenByParentId($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"deletedAt" => "",
						"isActive" => "1",
						"parentId" => new \MongoId($id),
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array)->sort(array("orderId" => 1));
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	
	/**
     * Get Module & Module Permissions
     * 
     */
	public function getModules(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"isActive" => "1",
						"showInMenu" => "1",
						"parentId" => "0",
						"deletedAt" => "",
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array)->sort(array("orderId" => 1));
		$records = iterator_to_array($cursor);
		return $records;
		
		/*$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"userModuleSlug" => "user",
						"parentId" => "0",
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array);
		$records = iterator_to_array($cursor);
		foreach($records as $key => $value){
		}
		
		if($cursor->count() > 0){
			#$search_array['_id'] == new \MongoId($key);
			$search_array['isActive'] = "1";
			$search_array['showInMenu'] = "1";
			$search_array['deletedAt'] = "";
			$c = $mongo->$db->selectCollection("stm_userModule");
			$pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_userModulePermission",
						'localField' => "_id",
						'foreignField' => "fkModuleId",
						'as' => "roleArray",
					),
				),
				
			);
			
			
			$cursor = $c->aggregate($pipeline);
			echo "<pre>";print_r($cursor);exit;
			#return $cursor;
		}*/
		
	}
	
	
	/**
     * Get Module Permissions
     * 
     */
	public function getModulePermission($id, $designationId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"fkModuleId" => new \MongoId($id),
						"fkDesignationId" => new \MongoId($designationId),
						"deletedAt" => "",
					);
		$cursor = $mongo->$db->stm_userModulePermission->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	
	/**
     * Get Module Permissions By Parent Id
     * 
     */
	public function checkModulePermissionByParentId($parentId, $designationId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$getChild = $mongo->$db->stm_userModule->find(array('parentId' => new \MongoId($parentId), 'deletedAt' => ''));
		$getChild = iterator_to_array($getChild);
		$resp = 'error';
		if(!empty($getChild)){
        	foreach($getChild as $key => $value){
				$search_array = array(
					"fkModuleId" => new \MongoId($value['_id']->{'$id'}),
					"fkDesignationId" => new \MongoId($designationId),
					"deletedAt" => "",
				);
				$cursor = $mongo->$db->stm_userModulePermission->find($search_array);
				$record = iterator_to_array($cursor);
				if(!empty($record)){
        			foreach($record as $key => $crecord){
						if($crecord['viewPermission'] == '1'){
							$resp = 'success';
						}
					}
				}
			}
		}
		return $resp;
	}
	
	/**
     * Get Module Children Count
     * 
     */
	function getChildCountByParent($parendId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"parentId" => new \MongoId($parendId),
						"isActive" => "1",
						"showInMenu" => "1",
						"deletedAt" => "",
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array);
		#$record = iterator_to_array($cursor);
		return $cursor->count();
	}
	
	/**
     * Get Module Children
     * 
     */
	function getChildsByParent($parendId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"parentId" => new \MongoId($parendId),
						"isActive" => "1",
						"deletedAt" => "",
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array);
		$cursor = $cursor->sort(array('orderId' => 1));
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	/**
     * Get Parent Module
     * 
     */
	function getAllParentModules(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
						"parentId" => "0",
						"isActive" => "1",
						"deletedAt" => "",
					);
		$cursor = $mongo->$db->stm_userModule->find($search_array);
		$cursor = $cursor->sort(array('orderId' => 1));
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	/**
     * Add Module
     * 
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
     * Get Update Module
     * 
     */
	public function getUpdateRecord($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
			"_id" => new \MongoId($id),
			"deletedAt" => "");
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	/**
     * Update Module
     * 
     */
	public function _updateRecord($id, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	}
	
}
