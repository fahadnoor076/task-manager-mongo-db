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

class ClientBrand extends Base implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_clientBrand';
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
        $this->__fields = array($this->primaryKey, 'fkClientId', 'fkBrandId', 'isActive', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	/**
     * Records Listing
     * @param Search array
     * @return object $data
     */
	 function getBrandsListing($clientId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkClientId'] = new \MongoId($clientId);
		$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		if(!empty($record)){
			$brandsArray = array();
			foreach($record as $key => $value){
				$brandId = $value['fkBrandId'];
				$table = "stm_brand";
				$search_array = array("_id" => new \MongoId($brandId));
				$cursor = $mongo->$db->$table->find($search_array);
				$record = iterator_to_array($cursor);
				foreach($record as $key => $brand){
				}
				array_push($brandsArray, array('brandId' => $key, 'brandName' => $brand['brandName']));
			}
			return $brandsArray;
			
		}else{
			return "error";
		}
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
	 
	 function _removeRecord($clientId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$cursor = $mongo->$db->$table->remove(array('fkClientId' => new \MongoId($clientId)));
		return "done";
	 }
	 
	 function _updateRecordByClientId($clientId, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("fkClientId" => new \MongoId($clientId)), $newdata);
		// set pk
		return "done";
	 }
	 
	 function getClientByBrandArray($brandsArray){
		$brandsNewArr = array();
		foreach($brandsArray as $brandId){
			array_push($brandsNewArr, new \MongoId($brandId));
		}
		$brandsArray = array();
		$search_array['fkBrandId'] = array('$in' => $brandsNewArr);
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['deletedAt'] = "";
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		if(!empty($record)){
			$clientsArray = array();
			foreach($record as $key => $value){
				array_push($brandsArray, $value['fkClientId']);
			}
			return $brandsArray;
			
		}else{
			return "error";
		}
	 }
	
}
