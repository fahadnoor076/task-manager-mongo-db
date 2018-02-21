<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

class EmailTemplate extends Base {

    use SoftDeletes;
    public $table = 'stm_emailTemplate';
    public $timestamps = true;
	public $primaryKey;
    protected $dates = ['deletedAt'];
	
	public function __construct()
	{
		// set tables and keys
        $this->__table = $this->table;
		$this->primaryKey = '_id';
		$this->__keyParam = $this->primaryKey.'-';
		$this->hidden = array();
		
        // set fields
         $this->__fields   = array($this->primaryKey, 'key', 'hint', 'subject', 'body', 'wildcards', 'addedAt', 'updatedAt', 'deletedAt');
	}
    
}
