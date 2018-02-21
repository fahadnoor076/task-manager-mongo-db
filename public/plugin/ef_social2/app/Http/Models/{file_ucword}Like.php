<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class {wildcard_ucword}Like extends Base {
	
	use SoftDeletes;
    public $table = '{wildcard_identifier}_like';
    public $timestamps = true;
	public $primaryKey;
    protected $dates = ['deleted_at'];
	
	public function __construct()
	{
		// set tables and keys
        $this->__table = $this->table;
		$this->primaryKey = $this->table.'_id';
		$this->__keyParam = $this->primaryKey.'-';
		$this->hidden = array();
		
        // set fields
        $this->__fields   = array($this->primaryKey, '{wildcard_pk}', "activity_type_id", 'actor_type', "actor_id", 'created_at', 'updated_at', 'deleted_at');
		
	}
	
	
	/**
     * getData
     * @param int pk_id
     * @return Query
     */
    public function getData($pk_id = 0) {
		$data = $this->get($pk_id);
		
		if($data !== FALSE) {
			// load models
			$pModel = $this->__modelPath."{target_ucword}";
			$pModel = new $pModel;
			try{
				// entity 1
				$e1 = $pModel->getMiniData($data->actor_id);
				// unset unrequired
				unset($data->actor_id, $data->updated_at, $data->deleted_at);
				// set new
				$data->{target_identifier} = $e1;
				
			} catch(Exception $e) {
				//unset($data);
			}
		}
		return $data;
    }
	
}
