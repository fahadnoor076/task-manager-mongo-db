<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class {wildcard_ucword}Comment extends Base {
	
	use SoftDeletes;
    public $table = '{wildcard_identifier}_comment';
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
        $this->__fields   = array($this->primaryKey, 'actor_id', 'actor_type', 'target_id', 'comment', 'parent_id', 'status', 'created_at', 'updated_at', 'deleted_at');
	}
	
}
