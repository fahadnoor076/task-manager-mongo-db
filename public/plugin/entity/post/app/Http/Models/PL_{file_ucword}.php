<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
// models
use App\Http\Models\Setting;
use App\Http\Models\Conf;
use App\Http\Models\EmailTemplate;

class PL{wildcard_ucword} extends Base {

        /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'pl_{wildcard_identifier}';
	public $primaryKey = '{wildcard_pk}';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    
    // enitity vars
	private $_entity_identifier, $_entity_session_identifier, $_entity_dir, $_entity_pk, $_entity_salt_pattren, $_entity_model;

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
    protected $hidden = [];
	
	 public function __construct() {
        // set tables and keys
        $this->__table = $this->table;
        //$this->primaryKey = $this->__table . '_id';
        $this->__keyParam = $this->primaryKey . '-';
        $this->hidden = array();
		// entity vars
		$this->_entity_identifier = "{wildcard_identifier}";
		$this->_entity_dir = config('pl_{wildcard_identifier}.DIR_PANEL');
		$this->_entity_pk = "{wildcard_pk}";
		$this->_entity_model = $this;

        // set fields
        $this->__fields = array($this->primaryKey,'{wildcard_identifier}_type_id','title','search_term','content','data_packet','location','latitude','longitude','count_like','count_share','count_comment','count_view','created_at','updated_at','deleted_at');
    }
	
	
	/**
	* Get Data
	* @param integer $pk
	* @return Object
	*/
	function getData($id = 0) {
		// init target
		$data = $this->get($id);
		
		if($data !== FALSE) {
			// unset unrequired
			unset($data->deleted_at);
		}
		return $data;
	}


}
