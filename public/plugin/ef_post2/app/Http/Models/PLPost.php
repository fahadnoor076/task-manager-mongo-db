<?php namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes AS SoftDeletes;

// models
#use App\Http\Models\Setting;

class PLPost extends Base {
	
	use SoftDeletes;
    public $table = 'pl_post';
    public $timestamps = true;
	public $primaryKey;
    protected $dates = ['deleted_at'];
	
	public function __construct()
	{
		// set tables and keys
        $this->__table = $this->table;
		$this->primaryKey = 'post_id';
		$this->__keyParam = $this->primaryKey.'-';
		$this->hidden = array();
		
        // set fields
        $this->__fields   = array($this->primaryKey, 'post_type_id', 'post_feedback_type_id', 'title', 'search_term', 'content', 'data_packet', 'location', 'latitude', 'longitude', 'count_like', 'count_share', 'count_comment', 'count_view', 'is_comment_enabled', 'is_like_enabled', 'is_share_enabled', 'status', 'created_at', 'updated_at', 'deleted_at');
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
			$e1Model = $this->__modelPath."PLPostType";
			$e1Model = new $e1Model;
			$e2Model = $this->__modelPath."PLPostFeedbackType";
			$e2Model = new $e2Model;
			
			try{
				// entity 1
				$e1 = $e1Model->getData($data->post_type_id);
				// entity 2
				$e2 = $e2Model->getData($data->post_feedback_type_id);
				// unset unrequired
				unset($data->post_type_id, $data->post_feedback_type_id, $data->updated_at, $data->deleted_at);
				// set entities
				$data->post_type = $e1;
				$data->post_feedback_type = $e2;
				
			} catch(Exception $e) {
				//unset($data);
			}
		}
		return $data;
    }
	
}
