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

class UserPrivilege extends Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_userPrivilege';
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
    //protected $fillable = ['designationId', 'previlegeId', 'permission', 'isActive', 'addedAt', 'updatedAt', 'deletedAt'];

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
        $this->__fields = array($this->primaryKey, 'privilegeName', 'privilegeSlug', 'isActive', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	
	/**
     * Get Listing
     * 
     */
    
	public function ajaxListing(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
			'isActive' => 1,
			'deletedAt' => ''
		);
		$cursor = $mongo->$db->$table->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		return $paginated_ids;
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
     * Add Record
     * 
     */
	function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$mongo->$db->stm_userModulePermission->insert($save);
		#$newDocID = $save['_id'];
		//return $newDocID;
		return TRUE;
	
	}
	
	/**
     * Update Record
     * 
     */
	function _updateRecord($fkDesignationId, $fkModuleId, $save){
		$return = NULL;
		//Get Record to Delete
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
				'fkDesignationId' => $fkDesignationId,
				'fkModuleId' => $fkModuleId,
				'deletedAt' => '');
		$cursor = $mongo->$db->stm_userModulePermission->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		if(sizeof($paginated_ids) != 0) {
			foreach($paginated_ids as $key => $value){
			}
			//$record = $this->get($key);
			//$record['deletedAt'] = date("Y-m-d H:i:s");
			$save["updatedAt"] = date("Y-m-d H:i:s");
			$save["deletedAt"] = "";
			$record_id = $this->updateRecordById($key,(array)$save);
			
		} else {
			// add new
			$save["addedAt"] = date("Y-m-d H:i:s");
			$save["updatedAt"] = "";
			$save["deletedAt"] = "";
			$return = $this->_addRecord($save);
		}
		return $return;
		//return $paginated_ids;
		
		
		
		
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$newdata = array('$set' => $save);
		$mongo->$db->stm_userModulePermission->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	
	}
	
	function updateRecordById($id, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$newdata = array('$set' => $save);
		$mongo->$db->stm_userModulePermission->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	}
	
	
	/**
     * ckeck admin permissions on given module id
     * 
     * @param string $module
     * @param string $action
     * @param int $group_id
     * @return boolean (TRUE | FALSE)
     */
    function checkAccess($privilege, $designationId) {
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
			"privilegeSlug" => $privilege,
			"isActive" => 1);
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		//return $record;
		
		foreach($record as $key => $value){
		}
		
		$privilegeId = $key;
		$action = "permission";
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
			$action => 1,
			"fkDesignationId" => new \MongoId($designationId),
			"fkPrivilegeId" => new \MongoId($privilegeId));
		$cursor = $mongo->$db->stm_userPrivilegeStatus->find($search_array);
		$record = iterator_to_array($cursor);
		return sizeof($record) > 0 ? TRUE : FALSE;
    }
	
	
	
	/**
     * Redirect un authenticated admin user to access module
     * 
     * @param string $module
     * @param string $action
     * @param int $group_id
     */
    function checkPrivilegeAuth($privilege, $action, $designationId = 1) {
		if ($this->checkAccess($privilege, $action, $designationId) === FALSE) {
			\Session::put(ADMIN_SESS_KEY.'error_msg', 'You are not allowed to access chosen option');
			\Session::save();
			//$url_redirect = \URL::previous();
			$url_redirect = \URL::previous() == \URL::current() ? \URL::to(DIR_ADMIN) : \URL::previous();
			header("location:" . $url_redirect);
			exit;
        } 
    }
	
	
	/**
     * ckeck admin permissions on given module id
     * 
     * @param string $module_id
     * @param string $group_id
     * @param int $columns
     * @return object $result
     */
	function removePermissions($designationId) {	
		$return = NULL;
		
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
			'fkDesignationId' => new \MongoId($designationId),
			'deletedAt' => ''
		);
		$cursor = $mongo->$db->stm_userModulePermission->find($search_array);
		$raw_ids = iterator_to_array($cursor);
		// remove old if exists
		if(($cursor->count()) > 0) {
			/*$record = $this->get($raw_ids[0]->{$this->primaryKey});
			$record->deleted_at = date("Y-m-d H:i:s");
			$this->set($record->{$this->primaryKey},(array)$record);*/
			foreach($raw_ids as $key => $raw_id) {
				$mongo = mongoInitialization();
				$db = MASTER_DB_NAME;
				$mongo->$db->stm_userModulePermission->remove(array('_id' => new \MongoId($key)));
			}
		}
		return $return;
	}
	
}
