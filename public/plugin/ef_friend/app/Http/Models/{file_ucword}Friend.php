<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class {wildcard_ucword}Friend extends Base {
	
	use SoftDeletes;
    public $table = '{wildcard_identifier}_friend';
    public $timestamps = true;
	public $primaryKey;
    protected $dates = ['deleted_at'];
	
	public function __construct()
	{
		// set tables and keys
        $this->__table = $this->table;
		$this->primaryKey = $this->__table.'_id';
		$this->__keyParam = $this->primaryKey.'-';
		$this->hidden = array();
		
        // set fields
        $this->__fields   = array($this->primaryKey, '{wildcard_pk}', 'target_{wildcard_pk}', 'status', "tracking_id", 'created_at', 'updated_at', 'deleted_at');
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
			$pModel = $this->__modelPath."{wildcard_ucword}";
			$pModel = new $pModel;
			try{
				// entity 1
				$e1 = $pModel->getMiniData($data->{wildcard_pk});
				// entity 2
				$e2 = $pModel->getMiniData($data->target_{wildcard_pk});
				// unset unrequired
				unset($data->{wildcard_pk},$data->target_{wildcard_pk}, $data->updated_at, $data->deleted_at);
				// set new
				$data->{wildcard_identifier} = $e1;
				$data->target_{wildcard_identifier} = $e2;
				
			} catch(Exception $e) {
				//unset($data);
			}
		}
		return $data;
    }
	
}
