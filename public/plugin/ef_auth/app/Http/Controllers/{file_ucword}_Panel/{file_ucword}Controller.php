<?php

namespace App\Http\Controllers\{wildcard_ucword}_Panel;

use App\Http\Controllers\Controller;
use View;
use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Models\EFEntityPlugin;
// models
//use App\Http\Models\Vendor;

class {file_ucword}Controller extends Controller {
	
	private $_assign_data = array(
		's_title' => '{wildcard_title}', // singular title
		'p_title' => '{wildcard_plural_title}', // plural title
		'page_action' => 'Listing', // default page action
		'parent_nav' => '{wildcard_identifier}-', // parent navigation id
		'err_msg' => '',
		'succ_msg' => '',
		
	);
	private $_module = "{wildcard_identifier}"; // (db module name, directory name, active nav id, routing name)
	private $_pk; // (primary key of module table : extracted from module i.e: {module_name}_id)
	private $_model = "{wildcard_ucword}"; // name of primary model
	private $_json_data = array();
	private $_layout = "";
	
	// entity vars
	private $_entity_session_identifier, $_entity_dir, $_entity_pk, $_entity_model;
	private $_unAuthRoutes = array();
	private $_entity_id = "{base_entity_id}";
	private $_plugin_identifier = "{plugin_identifier}";
	private $_plugin_config = array();

    /**
     * Prevent Unauthorized User
     */
    public function __construct(Request $request) {
		////$this->middleware('auth');
		// construct parent
		parent::__construct();
		// set model path for views
		$this->_assign_data["model_path"] = "App\Http\Models\\";
		// init current module model
		$this->_model = $this->_assign_data["model_path"].$this->_model;
		$this->_model = $this->_assign_data["model"] = new $this->_model;
		$this->__models['entity_plugin_model'] = new EFEntityPlugin;
		// default nav id
		$this->_assign_data["active_nav"] = $this->_assign_data["parent_nav"].$this->_module;
		// set primary key
		//$this->_pk = $this->_assign_data["pk"] = $this->_module."_id";
		$this->_pk = $this->_assign_data["pk"] = "{wildcard_pk}";
		// unAuth routes
		$this->_unAuthRoutes = array(
			config('pl_{wildcard_identifier}.DIR_PANEL')."login",
			config('pl_{wildcard_identifier}.DIR_PANEL')."logout",
			config('pl_{wildcard_identifier}.DIR_PANEL')."forgot_password",
			config('pl_{wildcard_identifier}.DIR_PANEL')."confirm_forgot"
		);
		// entity vars
		$this->_entity_session_identifier = config('pl_{wildcard_identifier}.SESS_KEY');
		$this->_entity_dir = config('pl_{wildcard_identifier}.DIR_PANEL');
		$this->_entity_pk = "{wildcard_pk}";
		$this->_entity_model = $this->_assign_data["model_path"]."{wildcard_ucword}";
		$this->_entity_model = $this->_assign_data["entity_model"] = new $this->_entity_model;
		// set module name
		$this->_assign_data["module"] = $this->_module;
		// assign meta from parent constructor
		$this->_assign_data["_meta"] = $this->__meta;		
		// set dir path
		$this->_assign_data["p_dir"] = $this->_entity_dir;
		$this->_assign_data["dir"] = $this->_assign_data["p_dir"].$this->_module."/";
		// check auth - except in non-authorization module
		//if(!in_array($request->path(),$this->_unAuthRoutes) && !$request->is(config('pl_{wildcard_identifier}.DIR_PANEL')."confirm_forgot/*")) {
		if(!in_array($request->path(),$this->_unAuthRoutes)) {
			$this->_entity_model->checkAuth($request, "master");
		}
		// plugin config
		$this->_plugin_config = $this->__models['entity_plugin_model']->getPluginSchema($this->_entity_id, $this->_plugin_identifier);
		// set defaults
		$this->_assign_data["plugin_features"] = isset($this->_plugin_config->features) ? $this->_plugin_config->features : array();	
    }

    /**
     * Return data to vendor listing page
     * 
     * @return type Array()
     */
    public function index(Request $request) {
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Ajax Listing
     * 
     * @return json 
     */
    public function ajaxListing(Request $request) {
        // datagrid params : sorting/order
        $search_value = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';
        $dg_order = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : '';
        $dg_sort = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : '';
        $dg_columns = isset($_REQUEST['columns']) ? $_REQUEST['columns'] : '';
        // default ordering
        if ($dg_order == "" && $dg_sort == "") {
            $dg_order = "a.created_at";
            $dg_sort = "ASC";
        } else {
            // fix invalid column
            $dg_order = $dg_order == 0 ? 1 : $dg_order;
            // get column field name
            $dg_order = $dg_columns[$dg_order]["data"];
			// fix joined column name
			$dg_order = str_replace("|",".",$dg_order);
        }
		
		// perform select actions
		$this->_selectActions($request);

        // init output
        $records = array();
        $records["data"] = array();

        /*
		// init query
        $query = $this->_model->select("a.".$this->_pk);
		$query->from("vendor AS a");
        $query->join("vendor_group AS ag", "ag.vendor_group_id", "=", "a.vendor_group_id");
		$query->whereNull("a.deleted_at");
		
		// apply search
        $query = $this->_searchParams($request, $query);
		

        // get total records count
        $total_records = $query->count(); // total records
        //$total_records = count($query->get()); // total records
        // datagrid settings
        $dg_limit = intval($_REQUEST['length']);
        $dg_limit = $dg_limit < 0 ? $total_records : $dg_limit;
        $dg_start = intval($_REQUEST['start']);
        $dg_draw = intval($_REQUEST['draw']);
        $dg_end = $dg_start + $dg_limit;
        $dg_end = $dg_end > $total_records ? $total_records : $dg_end;


        // get records
        $query = $this->_model->select("a.".$this->_pk,"ag.name AS vendor_group");
        $query->from("vendor AS a");
        $query->join("vendor_group AS ag", "ag.vendor_group_id", "=", "a.vendor_group_id");
        $query->whereNull("a.deleted_at");
		$query->where("a.".$this->_pk,">", 1);
		
        // apply search
        $query = $this->_searchParams($request, $query);
		
		$query->take($dg_limit); // limit
        $query->skip($dg_start); // offset
        $query->orderBy($dg_order, $dg_sort); // ordering
        $paginated_ids = $query->get();
		*/
		$paginated_ids = array();
        // if records
        if (isset($paginated_ids[0])) {
			// statuses
			$statuses = config('pl_{wildcard_identifier}.STATUSES');

            // collect records
			$i=0;
            foreach ($paginated_ids as $paginated_id) {
				$id_record = $this->_model->get($paginated_id->{$this->_pk});
				
				// status html
				$status = "";
				// options html
				$options = '<div class="btn-group">';
				// selectbox html
				$checkbox = '<label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">';
				// manage options
				if($id_record->{$this->_pk} > 1) {
					// edit
					$options .= '<a class="btn btn-xs btn-default" type="button" href="'.\URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module.'/update/'.$id_record->{$this->_pk}).'" data-toggle="tooltip" title="Update" data-original-title="Update"><i class="fa fa-pencil"></i></a>';
					/*// delete
					$options .= '<a class="btn btn-xs btn-default action_del_item" type="button" data-toggle="tooltip" title="" data-item_id="'.$id_record->{$this->_pk}.'" data-form="listing_form" data-original-title="Delete"><i class="fa fa-times"></i></a>';*/
					$checkbox .= '<input type="checkbox" id="check_id_'.$id_record->{$this->_pk}.'" name="check_ids[]" value="'.$id_record->{$this->_pk}.'" />';
					// status html
					if($id_record->status == 1) {
						$status = '<a id="status'.$id_record->{$this->_pk}.'" class="btn btn-xs btn-success" type="button">'.$statuses[$id_record->status].'</a>';
					} else {
						$status = '<a id="status'.$id_record->{$this->_pk}.'" class="btn btn-xs btn-danger" type="button">'.$statuses[$id_record->status].'</a>';
					}
				}
				$options .= '</div>';
				$checkbox .= '<span></span> </label>';
				
				// collect data
                $records["data"][] = array(
                    "ids" => $checkbox,
					//"vendor_group" => $paginated_id->vendor_group,
                    "a|username" => $id_record->username,
                    "a|email" => $id_record->email,
					"a|status" => $status,
                    "a|created_at" => date(config('pl_{wildcard_identifier}.DATE_FORMAT'),strtotime($id_record->created_at)),
                    "options" => $options
                );
				
				// increament
				$i++;

            }
        }


        $records["draw"] = $dg_draw;
        $records["recordsTotal"] = $total_records;
        $records["recordsFiltered"] = $total_records;

        echo json_encode($records);
    }
	
	/**
     * Search Params
     * @param $query query
     * @return query
     */
    private function _searchParams($request, $query) {
		// search
        // - vendor group
        if($request->vendor_group != "") {
			$q = trim($request->vendor_group);
			$query->where('ag.name', 'like', "%$q%");
		}
		// - username
		if($request->username != "") {
			$q = trim($request->username);
			$query->where('a.username', 'like', "%$q%");
		}
		// - email
		if($request->email != "") {
			$q = trim($request->email);
			$query->where('a.email', 'like', "%$q%");
		}
		// - email
		if($request->is_active != "") {
			$q = trim($request->is_active);
			$query->where('a.is_active', '=', $q);
		}
		// - created_at
		if($request->created_at != "") {
			$q = trim($request->created_at);
			$query->where('a.created_at', 'like', date("Y-m-d", strtotime($q))." %");
		}
		return $query;
    }
	

    /**
     * Add
     * 
     * @return view
     */
    public function add(Request $request) {
        //Checking module Authentication
        // page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_add($request);
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Add (private)
     * 
     * @return view
     */
    private function _add(Request $request) {
        // filter params
		$request->username = trim($request->username);
		$request->email = trim($request->email);
		$request->vendor_group_id = intval($request->vendor_group_id);
		$request->password = $request->password;
		
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email|unique:{wildcard_identifier},email'));
		
		// get all modules
		if ($request->username == "") {
			$field_name = "username";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Username";
		} else if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->vendor_group_id == 0) {
			$field_name = "vendor_group_id";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please select Role";
		} else if ($request->password == "") {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Password";
		} else {
			// set record
			$save['username'] = $request->username;
			$save['email'] = $request->email;
			//$save['password'] = bcrypt($request->password);
			$save['password'] = $request->password;
			//$save['vendor_group_id'] = $request->vendor_group_id;
			$save["created_at"] = date("Y-m-d H:i:s");
			
			// insert
			//$record_id = $this->_model->put($save);
			$record_id = $this->_model->generateNew($save);
			
			// set session msg
			\Session::put(VENDOR_SESS_KEY.'success_msg', 'record has been added');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module);
		}
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * Update
     * 
     * @return view
     */
    public function update(Request $request, $id) {
        // page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		
		// get record
		$this->_assign_data["data"] = $this->_model->get($id);
		
		// redirect on invalid record
		if($this->_assign_data["data"] == FALSE) {
			// set session msg
			\Session::put(VENDOR_SESS_KEY.'error_msg', 'Invalid record selection');
			// redirect
			return redirect(\URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module));
		}
		
		// redirect on invalid record
		if($this->_assign_data["data"]->{$this->_pk} == 1) {
			// set session msg
			\Session::put(VENDOR_SESS_KEY.'error_msg', 'Cannot update vendor record');
			// redirect
			return redirect(\URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module));
		}
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_update($request, $this->_assign_data["data"]);
		}
		
        $view = View::make($this->_assign_data["dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * Update (private)
     * 
     * @return view
     */
    private function _update(Request $request, $data) {
        // filter params
		$request->username = trim($request->username);
		$request->email = trim($request->email);
		$request->vendor_group_id = intval($request->vendor_group_id);
		$request->is_active = intval($request->is_active);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email|unique:{wildcard_identifier},email,'.$data->{$this->_pk}.','.$this->_pk));
		
		// get all modules
		if ($request->username == "") {
			$field_name = "username";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Username";
		} else if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->vendor_group_id == 0) {
			$field_name = "vendor_group_id";
			$this->_json_data['focusElem'] = "select[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please select Role";
		} else {
			$save = (array)$data;
			// set record
			// set record
			$save['username'] = $request->username;
			//$save['vendor_group_id'] = $request->vendor_group_id;
			$save['email'] = $request->email;
			$save['is_active'] = $request->is_active;
			$save["updated_at"] = date("Y-m-d H:i:s");
			
			
			// update
			$this->_model->set($save[$this->_pk], $save);
			// set pk
			$record_id = $save[$this->_pk];
			
			// set session msg
			\Session::put(VENDOR_SESS_KEY.'success_msg', 'record has been updated');
			
			//redirect
			$this->_json_data['redirect'] = \URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').$this->_module);
		}
		// return json
		return $this->_json_data;
    }



    /**
     * Select Action
     * 
     * @return query
     */
    private function _selectActions($request) {
		$request->select_action = trim($request->select_action);
		$request->checked_ids = is_array($request->checked_ids) ? $request->checked_ids : array();
		
        if($request->select_action != "" && isset($request->checked_ids[0])) {
			foreach($request->checked_ids as $checked_id) {
				$record = $this->_model->get($checked_id);
				// if valid record
				if($record !== FALSE) {
					// if delete
					if($request->select_action == "delete") {
						$this->_model->remove($record->{$this->_pk});
						/*$record->deleted_at = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);*/
					}
					// set status
					$statuses = config('pl_{wildcard_identifier}.STATUSES');
					// if valid status
					$valid_key = array_search($request->select_action,$statuses);
					if($valid_key !== FALSE) {
						$record->status = $valid_key;
						$record->updated_at = date("Y-m-d H:i:s");
						$this->_model->set($record->{$this->_pk},(array)$record);
					}
					
				}
			}
			
		}
		
    }
	
	
	/**
     * login
     * 
     * @return type Array()
     */
    public function login(Request $request) {
		// check remember me
		$this->_entity_model->checkCookieAuth();
		// redirect logged
		$this->_entity_model->redirectLogged();
		
		// page action
		$this->_assign_data["page_action"] = ucfirst(__FUNCTION__);
		$this->_assign_data["route_action"] = strtolower(__FUNCTION__);
		// validate post form
		if($request->do_post == 1) {
			return $this->_login($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].__FUNCTION__, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _login(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// filter params
		$request->email = strip_tags($request->email);
		$request->remember = intval(strip_tags($request->remember));
		$request->password = $request->password;
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email'));
		
		// record
		$record = $this->_entity_model->checkLogin($request->email, $request->password);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->password == "") {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Password";
		} else if ($record === FALSE) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Invalid login provided";
		} else if ($record->status == 0) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Your account is inactive, Please contact Vendoristrator.";
		} else if ($record->status == 2) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Cannot login. Account is baned by Vendoristrator.";
		} else {
			// set record
			$save = (array)$record;
			$save["last_login_at"] = date("Y-m-d H:i:s");
			$save["remember_login_token"] = NULL;
			
			// remember me
			if($request->remember > 0) {
				$remember_login_token = $this->_entity_model->setRememberToken((object)$save);
				$save["remember_login_token"] = $remember_login_token;
			}
			
			// save
			$this->_entity_model->set($save[$this->_entity_pk], $save);
			
			// set login session
			$redirect_url = $this->_entity_model->setLoginSession((object)$save);
			
			//redirect
			$this->_json_data['redirect'] = $redirect_url;
		}
		// return json
		return $this->_json_data;
    }
	
	/**
     * forgotPassword
     * 
     * @return type Array()
     */
    public function forgotPassword(Request $request) {
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
		// page action
		$this->_assign_data["page_action"] = "Forgot Password";
		$this->_assign_data["route_action"] = $view_file;
		// validate post form
		if($request->do_post == 1) {
			return $this->_forgotPassword($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].$view_file, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _forgotPassword(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// filter params
		$request->email = strip_tags($request->email);
		$request->remember = intval(strip_tags($request->remember));
		$request->password = $request->password;
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email'));
		
		// record
		$record = $this->_entity_model->getBy("email",$request->email);
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($record === FALSE) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Sorry, no such email exists in our system";
		} /*else if ($record->status <> 1) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Cannot login. Account is either inactive or baned by Vendoristrator.";
		}*/ else {
			// forgot password
			$this->_entity_model->forgotPassword($record);
			
			//redirect
			$this->_json_data['redirect'] =  \URL::to($this->_entity_dir."login");
		}
		// return json
		return $this->_json_data;
    }
	
	
	/**
     * confirmForgot
     * 
     * @return type Array()
     */
    public function confirmForgot(Request $request) {
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
		// filter params
		$request->merge(array_map('strip_tags', $request->all()));
		$request->merge(array_map('trim', $request->all()));
		
		// page action
		$this->_assign_data["page_action"] = "Reset Password";
		$this->_assign_data["route_action"] = $view_file;
		
		// check params
		$raw_record = $this->_entity_model->select($this->_entity_pk)
			->where("email","=", $request->email)
			->where("forgot_password_hash","=", $request->code)
			->whereNull("deleted_at")
			->get();
		$raw_id = isset($raw_record[0]) ? $raw_record[0]->{$this->_entity_pk} : 0;
		$record = $this->_entity_model->get($raw_id);
		
		// assign params
		$this->_assign_data["code"] = $request->code;
		$this->_assign_data["email"] = $request->email;
		$this->_assign_data["record"] = $record;
		
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_confirmForgot($request);
		}
		
        $view = View::make($this->_assign_data["p_dir"].$view_file, $this->_assign_data);
        return $view;
    }
	
	/**
     * _login (private)
     * 
     * @return view
     */
    private function _confirmForgot(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// check params
		$raw_record = $this->_entity_model->select($this->_entity_pk)
			->where("email","=", $request->email)
			->where("forgot_password_hash","=", $request->code)
			->whereNull("deleted_at")
			->get();
		$raw_id = isset($raw_record[0]) ? $raw_record[0]->{$this->_entity_pk} : 0;
		$record = $this->_entity_model->get($raw_id);
		
		// validator
		$valid_email = Validator::make(array('email' => $request->email), array('email' => 'required|email'));
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		// get all modules
		if ($valid_email->fails()) {
			$field_name = "email";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = $valid_email->errors()->first();
		} else if ($request->code == "") {
			$field_name = "code";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Confirmation Code";
		} else if ($record === FALSE) {
			$field_name = "code";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Invalid Confirmation Link. Please contact Vendoristrator";
		} else if ($request->password == "") {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter New Password";
		} else if (strlen($request->password) < 6) {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "New Password must be at-least 6 character long";
		} else if ($request->confirm_password == "") {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please confirm Password";
		} else if ($request->password != $request->confirm_password) {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Passwords do not match";
		} else {
			// set data
			$this->_entity_model->recoverPasswordSuccess($record, $request->password);
			
			// session message
			\Session::put($this->_entity_session_identifier.'success_msg', "Please login with your new password");
			
			//redirect
			$this->_json_data['redirect'] =  \URL::to($this->_entity_dir."login");
		}
		// return json
		return $this->_json_data;
    }

	
	/**
     * Return data to vendor listing page
     * 
     * @return type redirect
     */
    public function logout(Request $request) {
		$this->_entity_model->logout();
    }

    /**
     * changePassword
     * 
     * @return view
     */
    public function changePassword(Request $request) {
		// view file
		$view_file = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', __FUNCTION__));
		
		// override passwords
		$this->_assign_data["p_title"] = $this->_assign_data["s_title"] = "Change Password";
		$this->_assign_data["route_action"] = $view_file;
		
		// validate post form
		if($request->do_post == 1) {
			return $this->_changePassword($request);
		}
		
		$view = View::make(config('pl_{wildcard_identifier}.DIR_PANEL').$view_file, $this->_assign_data);
        return $view;
    }
	
	/**
     * _changePassword (private)
     * 
     * @return view
     */
    private function _changePassword(Request $request) {
		// trim/escape all
		//$request->merge(array_map('strip_tags', $request->all())); // - pass may have special chars
		$request->merge(array_map('trim', $request->all()));
		
		// default errors class
		$this->_json_data['removeClass'] = "hide";
		$this->_json_data['addClass'] = "show";
		
		
		// get all modules
		if ($request->password == "") {
			$field_name = "password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter Password";
		} else if ($request->new_password == "") {
			$field_name = "new_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please enter New Password";
		} else if (strlen($request->new_password) < 6) {
			$field_name = "new_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "New Password must be at-least 6 character long";
		} else if ($request->confirm_password == "") {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Please confirm Password";
		} else if ($request->new_password != $request->confirm_password) {
			$field_name = "confirm_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Passwords do not match";
		} else if ($this->_entity_model->saltPassword($request->password) != \Session::get($this->_entity_session_identifier."auth")->password) {
			$field_name = "new_password";
			$this->_json_data['focusElem'] = "input[name=" . $field_name . "]";
			$this->_json_data['targetElem'] = "div[id=error_msg_" . $field_name . "]";
			$this->_json_data['text'] = "Your current Password is invalid";
		} else {
			// set data
			$record = $this->_entity_model->get(\Session::get($this->_entity_session_identifier."auth")->{$this->_entity_pk});
			$save = (array)$record;
			$save["password"] = $this->_entity_model->saltPassword($request->new_password);
			$save["updated_at"] = date("Y-m-d H:i:s");
			$this->_entity_model->set($save[$this->_entity_pk],$save);
			
			// set login session
			$this->_entity_model->setLoginSession((object)$save);
			
			// session message
			\Session::put($this->_entity_session_identifier.'success_msg', "Successfully updated your Password");
			
			//redirect
			$this->_json_data['redirect'] =  \URL::to($this->_entity_dir."change_password");
		}
		// return json
		return $this->_json_data;
    }
}
