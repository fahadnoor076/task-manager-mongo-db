<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class PLPostScheduleMap extends Base {
	
	use SoftDeletes;
    public $table = 'pl_post_schedule_map';
    public $timestamps = true;
	public $primaryKey;
    protected $dates = ['deleted_at'];
	
	public function __construct()
	{
		// set tables and keys
        $this->__table = $this->table;
		$this->primaryKey = 'post_schedule_map_id';
		$this->__keyParam = $this->primaryKey.'-';
		$this->hidden = array();
		
        // set fields
        $this->__fields   = array($this->primaryKey, 'post_schedule_type_id', 'post_id', 'publish_at', 'status', 'created_at', 'updated_at', 'deleted_at');
	}
	
}
