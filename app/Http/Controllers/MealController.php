<?php

namespace App\Http\Controllers;

// extra libs
use Illuminate\Http\Request;

use Session;

use View;
use DB;
// models
use App\Http\Models\Meal;

class MealController extends Controller
{
    #use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	
	protected $__meta;
	protected $__models = array();
	
	
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Request $request)
	{
		
		
		// init models
		#$this->__models['conf_model'] = new Conf;
		
		#$detail = $this->__models['conf_model']->getBy('key','site');
		#$detail = json_decode($detail->value);
		#$this->__meta = $detail;
		
		// request headers
		#$headers = apache_request_headers();
		// get language
		#$lang_i = "language"; // index
		#$lang = isset($headers[ucfirst($lang_i)]) ? $headers[ucfirst($lang_i)] : "en";
		
		// set locale
		#app()->setLocale($lang);
	}
	
	public function index(){
		//INSERT
		/*$connection = new \MongoClient();
		$db = $connection->selectDB("tm_mongo");
		$collection = $db->selectCollection("Users");
		$document = array( 
		   "Username" => 'Ahsan Jawaid', 
		   "Designation" => 'Software Engineer',
		   "Phone" => '03412878731',
		   "Email" => 'ahsan.jawaid@salsoft.net',
		);      
		$collection->insert($document);
		echo "Record Inserted!";
		exit;*/
		
		//SELECT
		$mongo = new \MongoClient();
		// the following two lines are equivalent
		//$collection = $mongo->selectDB("tm_mongo")->selectCollection("Users");
		//$collection = $mongo->tm_mongo->Users;
		$db = "tm_mongo";
		//$m = new \MongoClient();
		//$db = $m->selectDB('tm_mongo');
		//$collection = $db->selectCollection("Users");
		//$collection = new MongoCollection($db, 'Users');
		
		$cursor = $mongo->$db->Users->find();
		$array = iterator_to_array($cursor);
		echo "<pre>";
		print_r($array);
		exit;
		
		//UPDATE
		/*$mongo = new \MongoClient();
		$db = "tm_mongo";
		$cursor = $mongo->$db->Users;
		//$cursor->insert(array("firstname" => "Bob", "lastname" => "Jones" ));
		$newdata = array('$set' => array("Username" => "Ahsan Jawaid Naqvi"));
		$cursor->update(array("Username" => "Ahsan Jawaid"), $newdata);
		
		var_dump($cursor->findOne(array("Username" => "Ahsan Jawaid")));
		exit;*/
		
		//DELETE
		//$id = '4b3f272c8ead0eb19d000000';
		/*$mongo = new \MongoClient();
		$db = "tm_mongo";
		$cursor = $mongo->$db->Users->remove(array('Username' => "Ahsan Jawaid"));
		
		// will work:
		//$collection->remove(array('_id' => new MongoId($id)), true);
		echo "Removed";
		exit;*/
		
		
			
	}
	
}
