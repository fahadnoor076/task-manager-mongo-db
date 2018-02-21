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

class ProjectOpportunity extends Base implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_projectOpportunity';
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
        $this->__fields = array($this->primaryKey, 'fkProjectId', 'fkClientId', 'fkCreatedById', 'opportunityAmount', 'opportunityDescription', 'opportunityStatus', 'isActive', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	/**
     * Records Listing
     * @param Search array
     * @return object $data
     */
	 function getActiveListingByClientId($clientId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkClientId'] = new \MongoId($clientId);
		$search_array['isActive'] = 1;
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
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
		);
		
		$cursor = $c->aggregate($pipeline);
		return $cursor;
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
     * Update Records
     * @param Search array
     * @return object $data
     */
	 function _updateRecord($id, $save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	 }
	 
	 /**
     * Delete Records
     * @param Search array
     * @return object $data
     */
	 function _removeRecord($id){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$save['deletedAt'] = date('Y-m-d H:i:s');
		$newdata = array('$set' => $save);
		$mongo->$db->$table->update(array("_id" => new \MongoId($id)), $newdata);
		// set pk
		return $id;
	 }
	 
}
