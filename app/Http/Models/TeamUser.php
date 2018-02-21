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

class TeamUser extends Base implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'stm_teamUser';
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
        $this->__fields = array($this->primaryKey, 'fkTeamId', 'fkUserId', 'isLead', 'isActive', 'addedAt', 'updatedAt', 'deletedAt');
    }
	
	/**
     * Records Listing
     * @param Search array
     * @return object $data
     */
	 function getTeamListing($teamId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkTeamId'] = new \MongoId($teamId);
		$search_array['isLead'] = 0;
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
		$pipeline = array(
			array(
				'$match' => $search_array
			),
			array(
				'$lookup' => array('from' => "stm_user",
					'localField' => "fkUserId",
					'foreignField' => "_id",
					'as' => "userArray",
				),
			),
		);
		
		$cursor = $c->aggregate($pipeline);
		return $cursor;
	 }
	 
	 
	 function getTeamLead($teamId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array = array(
			"fkTeamId" => new \MongoId($teamId),
			"isLead" => 1);
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		if(!empty($record)){
			foreach($record as $key => $value){
			}
			$leadId = $value['fkUserId'];
			$table = "stm_user";
			$search_array = array("_id" => new \MongoId($leadId));
			$cursor = $mongo->$db->$table->find($search_array);
			$record = iterator_to_array($cursor);
			return $record;
		}else{
			return "error";
		}
		/*$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = $this->__table;
		$search_array['fkTeamId'] = new \MongoId($teamId);
		$search_array['isLead'] = 1;
		$search_array['deletedAt'] = "";
		$c = $mongo->$db->selectCollection($table);
		$pipeline = array(
			array(
				'$match' => $search_array
			),
			array(
				'$lookup' => array('from' => "stm_user",
					'localField' => "fkUserId",
					'foreignField' => "_id",
					'as' => "userArray",
				),
			),
		);
		
		$cursor = $c->aggregate($pipeline);
		return $cursor;*/
	 }
	 
	 
	 function getDepartmentUsersByTeam($teamId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME;
		$table = "stm_team";
		$search_array = array("_id" => new \MongoId($teamId));
		$cursor = $mongo->$db->$table->find($search_array);
		$record = iterator_to_array($cursor);
		if(!empty($record)){
			foreach($record as $key => $value){
			}
			$departmentId = $value['fkDepartmentId'];
			$table = "stm_user";
			$search_array = array("fkDepartmentId" => new \MongoId($departmentId));
			$cursor = $mongo->$db->$table->find($search_array);
			$record = iterator_to_array($cursor);
			return $record;
		}else{
			return "error";
		}
	 }
	
	 
	 function _addRecord($save){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$mongo->$db->$table->insert($save); 
		$newDocID = $save['_id'];
		return $newDocID;
	 }
	 
	 function _removeRecords($teamId){
		$mongo = mongoInitialization();
		$db = MASTER_DB_NAME; 
		$table = $this->__table;
		$cursor = $mongo->$db->$table->remove(array('fkTeamId' => new \MongoId($teamId)));
		return "done";
	 }
	
}
