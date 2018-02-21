{{--*/
// extra models
$admin_module_model = $model_path."AdminModule";
$admin_module_model = new $admin_module_model;

$admin_module_permission_model = $model_path."AdminModulePermission";
$admin_module_permission_model = new $admin_module_permission_model;
/*--}}
@include(DIR_ADMIN.'header')
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed"> @include(DIR_ADMIN.'side_overlay')
  @include(DIR_ADMIN.'sidebar')
  @include(DIR_ADMIN.'nav_header')
  <main id="main-container"> 
    <!-- Page Header --> 
    <!-- Success Alert --> 
    <!-- END Success Alert -->
    <div class="content bg-gray-lighter">
      <div class="row items-push">
        <div class="col-sm-7">
          <h1 class="page-heading"> {!! $p_title !!} </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
          <ol class="breadcrumb push-10-t">
            <li>{!! $p_title !!}</li>
            <li><a class="link-effect" href="{!! URL::to(DIR_ADMIN.$module) !!}">Listing</a></li>
            <li>{!! $page_action !!}</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- END Page Header --> 
    
    <!-- Page Content -->
    <div class="content"> 
      
      <!-- Session Messages --> 
      @include(DIR_ADMIN.'flash_message') 
      <!-- Tab View -->
      <div class="row">
        <form name="data_form" class="form" method="post">
          <div class="col-lg-12"> 
            <!-- Simple Classic Progress Wizard (.js-wizard-simple class is initialized in js/pages/base_forms_wizard.js) --> 
            <!-- For more examples you can check out http://vadimg.com/twitter-bootstrap-wizard-example/ -->
            <div class="js-wizard-simple block"> 
              <!-- Step Tabs -->
              <ul class="nav nav-tabs nav-justified">
                <li class="active"> <a href="#tab-step1" data-toggle="tab" aria-expanded="true">{!! $s_title !!}</a> </li>
                <li class=""> <a href="#tab-step2" data-toggle="tab" aria-expanded="false">Permissions</a> </li>
              </ul>
              <!-- END Step Tabs --> 
              
              <!-- Steps Progress -->
              <div class="block-content block-content-mini block-content-full border-b">
                <div class="wizard-progress progress progress-mini remove-margin-b">
                  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 33.3333%;"></div>
                </div>
              </div>
              <!-- END Steps Progress --> 
              
              <!-- Tab Content -->
              <div class="block-content tab-content push-30"> 
                <!-- Tab 1 -->
                <div class="tab-pane fade fade-up active in" id="tab-step1">
                  <div class="form-group">
                    <div class="col-sm-8 col-sm-offset-2">
                      <div class="form-material">
                        <input class="form-control" type="text" id="name" name="name" placeholder="Enter Group Name">
                        <label  for="name"><span class="text-danger">*</span> Name</label>
                        <div id="error_msg_name" class="help-block text-right animated fadeInDown hide" style="color:red">Please enter Role Name</div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- END Tab 1 --> 
                
                <!-- Tab 2 -->
                <div class="tab-pane fade fade-up" id="tab-step2">
                  <div class="block-content">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="text-center" style="width: 10%;">#</th>
                          <th style="width: 50%;">Module</th>
                          <th class="hidden-xs" style="width: 10%;"> <label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                              <input name="view_master" type="checkbox">
                              <span></span> <strong>View</strong> </label></th>
                          <th class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                              <input name="add_master" type="checkbox">
                              <span></span> <strong>Add</strong> </label></th>
                          <th class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                              <input name="update_master" type="checkbox">
                              <span></span> <strong>Update</strong> </label></th>
                          <th class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                              <input name="delete_master" type="checkbox">
                              <span></span> <strong>Delete</strong> </label></th>
                        </tr>
                      </thead>
                      <tbody>
                      {{--*/ $raw_ids = $admin_module_model->select("admin_module_id")
                      ->where("is_active","=",1)
                      ->whereNull("deleted_at")
                      ->where("parent_id","=", 0)
                      ->orderBy("name","ASC")
                      ->get() /*--}}
                      
                      @if (isset($raw_ids[0])) {{--*/ $i=0 /*--}}
                      
                      
                      @foreach($raw_ids as $raw_id) {{--*/ $i++ /*--}} 
                      <!-- get record --> 
                      {{--*/ $record = $admin_module_model->get($raw_id->admin_module_id) /*--}} 
                      <!-- check if has childs --> 
                      {{--*/ $raw_child_ids = $admin_module_model->select("admin_module_id")
                      ->where("is_active","=",1)
                      ->whereNull("deleted_at")
                      ->where("parent_id","=", $record->admin_module_id)
                      ->orderBy("name","ASC")
                      ->get() /*--}} 
                      <!-- check child --> 
                      @if(isset($raw_child_ids[0])) {{--*/ $j=0 /*--}} 
                      <!-- has child-->
                      <tr>
                        <td class="text-center" style="width: 10%;"><strong>{!! $i !!}</strong></td>
                        <td style="width: 90%;" colspan="4"><strong>{!! $record->name !!}</strong></td>
                      </tr>
                      <!-- childs loop --> 
                      @foreach($raw_child_ids as $raw_child_id) {{--*/ $j++ /*--}} 
                      <!-- get child record --> 
                      {{--*/ $c_record = $admin_module_model->get($raw_child_id->admin_module_id) /*--}}
                      <tr>
                        <td class="text-center" style="width: 10%;">{!! $i !!}. {!! $j !!}
                          <input type="hidden" name="modules[]" value="{!! $c_record->admin_module_id !!}" /><input type="hidden" name="parents[]" value="{!! $c_record->parent_id !!}" /></td>
                        <td style="width: 50%;">{!! $c_record->name !!}</td>
                        <td class="hidden-xs" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="view_{!! $c_record->admin_module_id !!}" name="view_{!! $c_record->admin_module_id !!}">
                            <span></span> </label></td>
                        <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="add_{!! $c_record->admin_module_id !!}" name="add_{!! $c_record->admin_module_id !!}">
                            <span></span> </label></td>
                        <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="update_{!! $c_record->admin_module_id !!}" name="update_{!! $c_record->admin_module_id !!}">
                            <span></span> </label></td>
                        <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="delete_{!! $c_record->admin_module_id !!}" name="delete_{!! $c_record->admin_module_id !!}">
                            <span></span> </label></td>
                      </tr>
                      @endforeach
                      @else 
                      <!-- no child -->
                      <tr>
                        <td class="text-center" style="width: 10%;"><strong>{!! $i !!}</strong>
                          <input type="hidden" name="modules[]" value="{!! $record->admin_module_id !!}" /><input type="hidden" name="parents[]" value="{!! $record->parent_id !!}" /></td>
                        <td style="width: 50%;"><strong>{!! $record->name !!}</strong></td>
                        <td class="hidden-xs" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="view_{!! $record->admin_module_id !!}" name="view_{!! $record->admin_module_id !!}">
                            <span></span> </label></td>
                        <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="add_{!! $record->admin_module_id !!}" name="add_{!! $record->admin_module_id !!}">
                            <span></span> </label></td>
                        <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="update_{!! $record->admin_module_id !!}" name="update_{!! $record->admin_module_id !!}">
                            <span></span> </label></td>
                        <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                            <input type="checkbox" id="delete_{!! $record->admin_module_id !!}" name="delete_{!! $record->admin_module_id !!}">
                            <span></span> </label></td>
                      </tr>
                      @endif
                      @endforeach
                        
                      @else
                      <!-- no records -->
                      <tr>
                        <td style="width: 100%;" colspan="5" align="center">No records found</td>
                      </tr>
                      @endif
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- END Tab 2 --> 
                
              </div>
              <!-- END Steps Content --> 
              
              <!-- Steps Navigation -->
              <div class="block-content block-content-mini block-content-full border-t">
                <div class="row">
                  <div class="col-xs-6">
                    <button class="wizard-prev btn btn-default disabled" type="button"><i class="fa fa-arrow-left"></i> Previous</button>
                  </div>
                  <div class="col-xs-6 text-right">
                    <button class="wizard-next btn btn-default" type="button" style="display: inline-block;">Next <i class="fa fa-arrow-right"></i></button>
                    <button class="wizard-finish btn btn-primary pull-right" type="submit" style="display: none;"><i class="fa fa-check"></i> Submit</button>
                    <i id="loading" class="fa fa-2x fa-asterisk fa-spin text-success pull-right" style="margin-right:10px; display:none;"></i>
                  </div>
                </div>
              </div>
              <!-- END Steps Navigation --> 
            </div>
            <!-- END Simple Classic Progress Wizard --> 
          </div>
          <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
          <input type="hidden" name="do_post" value="1" />
        </form>
      </div>
      <!-- END Tab View --> 
      <!-- END Full Table --> 
    </div>
    <!-- END Page Content --> 
  </main>
  <!--Dialog Box HTML Start --> 
  <!--<div id="dialog_confirm" title="Confirmation" style="display:none;">Are you really want to delete?</div>--> 
  
</div>

<!-- Page JS Plugins --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/jquery-validation/jquery.validate.min.js"></script> 

<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/pages/base_forms_wizard.js"></script> 
<!-- Custom JS Code --> 
<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="data_form"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_msg_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $route_action !!}',$(this),'i[id=loading]');
	});
	
	// master checkbox controls
	$('input[name$="_master"]').change(function() {
		var sel = $(this).attr('name').replace('_master','');
		if($(this).prop('checked')) {
			$('input[name^="'+sel+'_"]').prop('checked',true);
			// mark views checked on other master control checked
			if($(this).attr('name')!='view_master') {
				$('input[name^="view_"]').prop('checked',true);
			}
		}
		else {
			$('input[name^="'+sel+'_"]').prop('checked',false);
		}
	});
	
	// all controls
	$('input[id^="add_"], input[id^="update_"], input[id^="delete_"]').change(function () {
		if($(this).prop('checked')) {
			elemData = $(this).attr('id').split('_');
			$('input[id="view_'+elemData[1]+'"]').prop('checked',true);
		}
	});
	
});

</script> 
@include(DIR_ADMIN.'footer') 