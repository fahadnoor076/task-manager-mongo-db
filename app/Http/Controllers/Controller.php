<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

// extra libs
use Illuminate\Http\Request;

use Session;

// load models
use App\Http\Models\Conf;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;
	
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
		$this->__models['conf_model'] = new Conf;
		
		#$detail = $this->__models['conf_model']->getBy('key','site');
		#$detail = json_decode($detail->value);
		#$this->__meta = $detail;
		
		// request headers
		$headers = apache_request_headers();
		// get language
		$lang_i = "language"; // index
		$lang = isset($headers[ucfirst($lang_i)]) ? $headers[ucfirst($lang_i)] : "en";
		
		// set locale
		app()->setLocale($lang);
	}
	
	
	/**
	 * Parse API response
	 *
	 * @return Response
	*/
	protected function __ApiResponse(Request $request, $api_data)
	{
		// we need message params in all requests
		if(!isset($api_data["message"])) {
			$api_data["message"] = "";
		}
		//$api_data["t_message"] = trans('api_errors.invalid_user_request');
		
		// we need to have bool (0/1) for response
		$api_data["response"] = $api_data["response"] == "success" ? 0 : 1;
		$api_data["error"] = $api_data["response"];
		unset($api_data["response"]);
		
		// kick user if not valid - or removed
		if($api_data['message'] == trans('api_errors.invalid_user_request')) {
			$api_data['kick_user'] = 1;
		}
		
		// parse for devices
		if(\Session::token() != $request->input('_token')) {
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			return $api_data;
		}
		
		// target element
		$data['targetElem'] = 'pre[id=response]';
		// view page
		$data['prettyPrint'] = json_encode($api_data);
		return $data;		
	}
	
	
	/**
     * check Param
	 * @param string pk
     * @param string $model
     * @param string $param
	 * @param string $param_def_value
	 * @return count
     */
    protected function __checkParamCount($request, $pk, $model, $param, $param_def_value = "") {
		return $this->__models[$model]
			->where($pk,"=",$request->input($param,$param_def_value))
			->whereNull("deleted_at")
			->count();
	}
}
