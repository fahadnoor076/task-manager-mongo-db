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

class UserBrand extends Eloquent implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_userBrand';
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
    #protected $hidden = ['password', 'remember_login_token'];
	
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
        $this->__fields = array($this->primaryKey, 'fkUserId', 'fkBrandId', 'is_active', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	
	/**
     * Add Brand User.
     *
     * @var array
     */
	function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$mongo->$db->$table->insert($save); 
		$recordId = $save['_id'];
		return $recordId;
	}
	 
	
	/**
     * Add Brand User.
     *
     * @var array
     */
	function checkRecord($search_array){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	
	/**
     * Add Brand User.
     *
     * @var array
     */
	function _updateRecord($id, $update){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$newdata = array('$set' => $update);
		$mongo->$db->$table->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return "success";
	}
	
	
	/**
     * Add Brand User.
     *
     * @var array
     */
	function _deleteRecord($userId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$save['deletedAt'] = date('Y-m-d H:i:s');
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("fkUserId" => new \MongoId($userId)), $newdata, array('multiple' => true));
		// set pk
		return "success";
	}
	
	
	/**
     * Get Listing
     *
     * @var array
     */
	function getRecordsByUserId($userId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkUserId'] = new \MongoId($userId);
		$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		return $record;
	}
	
	
	/**
     * Get Brands Listing
     *
     * @var array
     */
	function getBrandByUserId($userId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkUserId'] = new \MongoId($userId);
		$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		$brandsArr = array();
		if(!empty($record)){
			foreach($record as $key => $value){
				array_push($brandsArr, $value['fkBrandId']);
			}
		}
		return $brandsArr;
	}
}
