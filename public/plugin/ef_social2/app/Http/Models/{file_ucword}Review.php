<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class {wildcard_ucword}Review extends Base {
	
	use SoftDeletes;
    public $table = '{wildcard_identifier}_review';
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
        $this->__fields   = array($this->primaryKey, '{target_pk}', '{wildcard_pk}', "reference_data", 'review', 'status', 'created_at', 'updated_at', 'deleted_at');
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
				$e1 = $pModel->getMiniData($data->{target_pk});
				// unset unrequired
				unset($data->{target_pk}, $data->updated_at, $data->deleted_at);
				// set new
				$data->{target_identifier} = $e1;
				
			} catch(Exception $e) {
				//unset($data);
			}
		}
		return $data;
    }
	
}
