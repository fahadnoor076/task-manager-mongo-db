<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class PLAttachment extends Base {
	
	use SoftDeletes;
    public $table = 'pl_attachment';
    public $timestamps = true;
	public $primaryKey;
    protected $dates = ['deleted_at'];
	
	public function __construct()
	{
		// set tables and keys
        $this->__table = $this->table;
		$this->primaryKey = 'attachment_id';
		$this->__keyParam = $this->primaryKey.'-';
		$this->hidden = array();
		
        // set fields
        $this->__fields   = array($this->primaryKey, 'attachment_type_id', 'title', 'content', 'thumb', 'data_packet', 'created_at', 'updated_at', 'deleted_at');
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
			$pModel = $this->__modelPath."PLAttachmentType";
			$pModel = new $pModel;
			try{
				// entity 1
				$e1 = $pModel->getData($data->attachment_type_id);
				// unset unrequired
				unset($data->attachment_type_id, $data->updated_at, $data->deleted_at);
				// set new
				$data->attachment_type = $e1;
				
			} catch(Exception $e) {
				//unset($data);
			}
		}
		return $data;
    }
	
}
