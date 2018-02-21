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

class UserDesignation extends Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_userDesignation';
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
        //$this->__fields = array($this->primaryKey, 'admin_group_id', 'username', 'email', 'password', 'remember_login_token', 'is_active','remember_login_token_created_at', 'forgot_password_hash', 'forgot_password_hash_created_at', 'status', 'last_login_at', 'brand_id', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	
	/**
     * Get Listing
     * 
     */
    
	public function ajaxListing($search_array){
		$search_array['deletedAt'] = "";
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		
		$c = $mongo->$db->selectCollection("stm_userDesignation");
		if(!empty($search_array)){
			 $pipeline = array(
				array(
					'$match' => $search_array
				),
				array(
					'$lookup' => array('from' => "stm_userRole",
						'localField' => "fkRoleId",
						'foreignField' => "_id",
						'as' => "roleArray",
					),
				),
			
			);
		}else{
			$pipeline = array(
				'$lookup' => array('from' => "stm_userRole",
					'localField' => "fkRoleId",
					'foreignField' => "_id",
					'as' => "roleArray",
				),
			);
		}
		
		$cursor = $c->aggregate($pipeline);
		//$paginated_ids = iterator_to_array($cursor);
		return $cursor;
	}
	
	//Insert Record
	function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$mongo->$db->stm_userDesignation->insert($save);
		$newDocID = $save['_id'];
		return $newDocID;
	}
	
	//GetUpdateRecord
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
	
	//UpdateRecord
	function _updateRecord($id, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$newdata = array('$set' => $save);
		$mongo->$db->stm_userDesignation->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	}
	
	//Active Listing
	function getActiveListing(){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$search_array = array(
			"isActive" => "1",
			"deletedAt" => "");
		$cursor = $mongo->$db->stm_userDesignation->find($search_array);
		$paginated_ids = iterator_to_array($cursor);
		return $paginated_ids;
	}
	
	//Records by Role Id
	function getDesignationsByRoleId($roleId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = 'stm_userDesignation';
		$search_array = array("fkRoleId" => new \MongoId($roleId));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		if(!empty($record)){
			return $record;
		}else{
			return 'error';
		}
	}
}
