{{--*/
// extra models
$user_module_model = $model_path."UserModule";
$user_module_model = new $user_module_model;

$user_module_permission_model = $model_path."UserModulePermission";
$user_module_permission_model = new $user_module_permission_model;

$user_privilege_model = $model_path."UserPrivilege";
$user_privilege_model = new $user_privilege_model;
/*--}}

{{--*/ $raw_ids = $user_module_model->ajaxListing(); /*--}}
{{--*/ $prev_ids = $user_privilege_model->ajaxListing(); /*--}}

@include(DIR_ADMIN.'header')
<div class="bst-wrapper">
    @include(DIR_ADMIN.'side_overlay')
    <div class="bst-main">
        <div class="fyt-main-top"></div>
        <div class="bst-main-wrapper pad-all-md">
            @include(DIR_ADMIN.'sidebar')
            <div class="bst-content-wrapper">
                <div class="bst-content">
                    <div class="bst-page-bar mrgn-b-md breadcrumb-double-arrow">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item text-capitalize">
                                <h3>{!! $p_title !!}</h3> </li>
                            <li class="breadcrumb-item text-capitalize"><a href="{!! URL::to(DIR_ADMIN.$module) !!}">Listing</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:;">{!! $page_action !!}</a></li>
                        </ul>
                    </div>
                    <!-- Session Messages --> 
                     @include(DIR_ADMIN.'flash_message')
                    <div class="form-validation-style">
                    <form name="dataForm" class="form-horizontal" method="post">
                        <div class="bst-block">
                            <div class="horizontal-form-style">
                                <div class="bst-block-title mrgn-b-lg">
                                    <h3>User Roles</h3>
                                    <p>Will be followed for all Departments</p>
                                </div>
                            
                            
                            
                                <!-- START -->
                                <div class="js-wizard-simple block"> 
                                  <!-- Step Tabs -->
                                  <ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#tab-step1" data-toggle="tab" aria-expanded="true" class="tab_progress">{!! $s_title !!}</a> </li>
                                    <li class=""> <a href="#tab-step2" data-toggle="tab" aria-expanded="false" class="tab_progress">Module Permissions</a> </li>
                                    <li class=""> <a href="#tab-step3" data-toggle="tab" aria-expanded="false" class="tab_progress">Application Permissions</a> </li>
                                  </ul>
                                  <!-- END Step Tabs --> 
              
                                  <!-- Steps Progress -->
                                  <div class="block-content block-content-mini block-content-full border-b">
                                    <div class="wizard-progress progress progress-mini remove-margin-b">
                                      <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 50.00%;"></div>
                                    </div>
                                  </div>
                                  <!-- END Steps Progress --> 
                              
                                  <!-- Tab Content -->
                                  <div class="block-content tab-content push-30"> 
                                    <!-- Tab 1 -->
                                    <div class="tab-pane fade fade-up active in" id="tab-step1">
                                      <div class="form-group">
                                        <div class="col-sm-14 col-sm-offset-2">
                                          <div class="form-material">
                                          <!--<form name="dataForm" class="form-horizontal" method="post">-->
                                            <div class="form-group" id="error_userDesignationName">
                                                <div class="row mrgn-all-none">
                                                    <label for="userDesignationName" class="col-sm-2 control-label">Designation Name</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="userDesignationName" class="form-control" id="userDesignationName" placeholder="Role Name" value="{!! $data[$id]['userDesignationName'] !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userRoleId">
                                                <div class="row mrgn-all-none">
                                                    <label for="userRoleId" class="col-sm-2 control-label">User Role</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="userRoleId" name="userRoleId">
                                                            <option value="">Select Role</option>
                                                            @if($roles > 0)
                                                                @foreach($roles as $key => $role)
                                                                	{{--*/ $sel = $data[$id]['fkRoleId'] == $key ? 'selected="selected"' : '' /*--}}
                                                                    <option value="{!! $key !!}" {!! $sel !!}>{!! $role['userRoleName'] !!}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_isActive">
                                                <div class="row mrgn-all-none">
                                                    <label for="isActive" class="col-sm-2 control-label">Is Active</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="isActive" name="isActive">
                                                            <option value="">Select Status</option>
                                                            <option value="1" {!! $data[$id]['isActive'] == "1" ? 'selected="selected"' : '' !!}>Active</option>
                                                            <option value="0" {!! $data[$id]['isActive'] == "0" ? 'selected="selected"' : '' !!}>In-Active</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--<div class="form-group">
                                                <div class="row mrgn-all-none">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>-->
                                        <!--</form>-->
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
                                      @if (sizeof($raw_ids) != 0) {{--*/ $i=0 /*--}}
                                      	@foreach($raw_ids as $raw_id) {{--*/ $i++ /*--}}
                                        	<!-- get record --> 
                                          {{--*/ $record = $user_module_model->getRecordById($raw_id['_id']) /*--}}
                                          <!-- check if has childs -->
                                          {{--*/ $raw_child_ids = $user_module_model->getChildrenByParentId($raw_id['_id']) /*--}}
                                          <!-- check child --> 
                                          @if(sizeof($raw_child_ids) > 0) {{--*/ $j=0 /*--}} 
                                              <!-- has child-->
                                              <tr>
                                                <td class="text-center" style="width: 10%;"><strong>{!! $i !!}</strong></td>
                                                <td style="width: 90%;" colspan="4"><strong>{!! $record[$raw_id['_id']->{'$id'}]['userModuleName'] !!}</strong></td>
                                              </tr>
                                              <!-- childs loop --> 
                                              @foreach($raw_child_ids as $raw_child_id) {{--*/ $j++ /*--}} 
                                              <!-- get child record --> 
                                              {{--*/ $c_record = $user_module_model->getRecordById($raw_child_id['_id']) /*--}}
                                              <!-- check permissions -->
                                              {{--*/ $view_checked = $user_module_permission_model->checkAccess($c_record[$raw_child_id['_id']->{'$id'}]['userModuleSlug'], "view", $id) ? 'checked="checked"' : "" /*--}}
                                              {{--*/ $add_checked = $user_module_permission_model->checkAccess($c_record[$raw_child_id['_id']->{'$id'}]['userModuleSlug'], "add", $id) ? 'checked="checked"' : "" /*--}}
                                              {{--*/ $update_checked = $user_module_permission_model->checkAccess($c_record[$raw_child_id['_id']->{'$id'}]['userModuleSlug'], "update", $id) ? 'checked="checked"' : "" /*--}}
                                              {{--*/ $delete_checked = $user_module_permission_model->checkAccess($c_record[$raw_child_id['_id']->{'$id'}]['userModuleSlug'], "delete", $id) ? 'checked="checked"' : "" /*--}}
                                              
                                              <tr>
                                                <td class="text-center" style="width: 10%;">{!! $i !!}. {!! $j !!}
                                                  <input type="hidden" name="modules[]" value="{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" /><input type="hidden" name="parents[]" value="{!! $c_record[$raw_child_id['_id']->{'$id'}]['parentId'] !!}" /></td>
                                                <td style="width: 50%;">{!! $c_record[$raw_child_id['_id']->{'$id'}]['userModuleName'] !!}</td>
                                                <td class="hidden-xs" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="view_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="view_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $view_checked !!}>
                                                    <span></span> </label></td>
                                                <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="add_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="add_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $add_checked !!}>
                                                    <span></span> </label></td>
                                                <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="update_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="update_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $update_checked !!}>
                                                    <span></span> </label></td>
                                                <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="delete_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="delete_{!! $c_record[$raw_child_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $delete_checked !!}>
                                                    <span></span> </label></td>
                                              </tr>
                                              @endforeach
                                          	@else
                                            <!-- check permissions -->
                                          	  {{--*/ $view_checked = $user_module_permission_model->checkAccess($record[$raw_id['_id']->{'$id'}]['userModuleSlug'], "view", $id) ? 'checked="checked"' : "" /*--}}
                                              {{--*/ $add_checked = $user_module_permission_model->checkAccess($record[$raw_id['_id']->{'$id'}]['userModuleSlug'], "add", $id) ? 'checked="checked"' : "" /*--}}
                                              {{--*/ $update_checked = $user_module_permission_model->checkAccess($record[$raw_id['_id']->{'$id'}]['userModuleSlug'], "update", $id) ? 'checked="checked"' : "" /*--}}
                                              {{--*/ $delete_checked = $user_module_permission_model->checkAccess($record[$raw_id['_id']->{'$id'}]['userModuleSlug'], "delete", $id) ? 'checked="checked"' : "" /*--}} 
                                          	<!-- no child -->
                                              <tr>
                                                <td class="text-center" style="width: 10%;"><strong>{!! $i !!}</strong>
                                                  <input type="hidden" name="modules[]" value="{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" /><input type="hidden" name="parents[]" value="{!! $record[$raw_id['_id']->{'$id'}]['parentId'] !!}" {!! $view_checked !!} /></td>
                                                <td style="width: 50%;"><strong>{!! $record[$raw_id['_id']->{'$id'}]['userModuleName'] !!}</strong></td>
                                                <td class="hidden-xs" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="view_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="view_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $view_checked !!} />
                                                    <span></span> </label></td>
                                                <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="add_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="add_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $add_checked !!} />
                                                    <span></span> </label></td>
                                                <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="update_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="update_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $update_checked !!} />
                                                    <span></span> </label></td>
                                                <td class="text-center" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                    <input type="checkbox" id="delete_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" name="delete_{!! $record[$raw_id['_id']->{'$id'}]['_id']->{'$id'} !!}" {!! $delete_checked !!} />
                                                    <span></span> </label></td>
                                              </tr>
                                          	@endif
                                          @endforeach
                                      @endif
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <!-- END Tab 2 --> 
                                
                                <!-- Tab 3 -->
                                <div class="tab-pane fade fade-up" id="tab-step3">
                                  <div class="block-content">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th class="text-center" style="width: 10%;">#</th>
                                          <th style="width: 50%;">Privilege Name</th>
                                          <th class="hidden-xs" style="width: 10%;"> <label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                              <input name="permission_master" type="checkbox">
                                              <span></span> <strong>Permission</strong> </label></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      	@if(!empty($prev_ids))
                                        	{{--*/ $i = 0; /*--}}
                                        	@foreach($prev_ids as $key => $privilege)
                                            	{{--*/ $i++; /*--}}
                                                {{--*/ $p_record = $user_privilege_model->getRecordById($key) /*--}}
                                                
                                                {{--*/ $perm_checked = $user_privilege_model->checkAccess($privilege['privilegeSlug'], $id) ? 'checked="checked"' : "" /*--}}
                                            	<tr>
                                                    <td class="text-center" style="width: 10%;"><strong>{!! $i !!}</strong>
                                                    <input type="hidden" name="perms[]" value="{!! $p_record[$key]['_id']->{'$id'} !!}" />
                                                    <td style="width: 50%;"><strong>{!! $privilege['privilegeName']; !!}</strong></td>
                                                    <td class="hidden-xs" style="width: 10%;"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                        <input type="checkbox" id="permission_{!! $key !!}" name="permission_{!! $key !!}" {!! $perm_checked !!}>
                                                        <span></span> </label></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <!-- END Tab 3 -->
                                
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
                               
                    </div>
                </div>
            </div>
            <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
            <input type="hidden" name="do_post" value="1" />
            </form
        ></div>
    </div>
</div>

<script type="text/javascript">
/*$(function() {
	// default form submit/validate
	$('form[name="dataForm"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		return jsonValidate('{!! $route_action !!}',$(this));
	});
	//CKEDITOR.replace('cat_content');
	
});*/

$(function() {
	// default form submit/validate
	$('form[name="dataForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		return jsonValidate('{!! $id !!}',$(this),'i[id=loading]');
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

$(document).ready(function(){
	$('.progress-bar-info').css('width', "33.3%");
	$('.tab_progress').click(function(){
		var anch = $(this).attr('href');
		$('.nav-justified li').removeClass('active');
		var width = $('.progress-bar-info').css('width');
		if(anch == '#tab-step2'){
			$('.progress-bar-info').css('width', "66.6%");
			$('.tab_progress').parent('li').eq(1).addClass('active');
			$('#tab-step1').removeClass('active in');
			$('#tab-step2').addClass('active in');
			$('#tab-step3').removeClass('active in');
			$('.wizard-next').removeClass("disabled", false);
			$('.wizard-prev').removeClass("disabled", false);
			$('.wizard-finish').css('display', 'none');
			$('.wizard-next').css('display', 'inline-block');
		}else if(anch == "#tab-step3"){
			$('.progress-bar-info').css('width', "100%");
			$('.tab_progress').parent('li').eq(2).addClass('active');
			$('#tab-step1').removeClass('active in');
			$('#tab-step2').removeClass('active in');
			$('#tab-step3').addClass('active in');
			$('.wizard-next').removeClass("disabled", false);
			$('.wizard-prev').removeClass("disabled", false);
			$('.wizard-finish').css('display', 'inline-block');
			$('.wizard-next').css('display', 'none');
		}else if(anch == "#tab-step1"){
			$('.progress-bar-info').css('width', "33.3%");
			$('.tab_progress').parent('li').eq(0).addClass('active');
			$('#tab-step1').addClass('active in');
			$('#tab-step2').removeClass('active in');
			$('#tab-step3').removeClass('active in');
			$('.wizard-next').removeClass("disabled", false);
			$('.wizard-prev').addClass("disabled", true);
			$('.wizard-finish').css('display', 'none');
			$('.wizard-next').css('display', 'inline-block');
		}
	});
	
	$('.wizard-next').click(function(){
		var width = $('.progress-bar-info').css('width');
		if(width == "33.3%"){
			$('.nav-justified li').removeClass('active');
			$('.tab_progress').parent('li').eq(1).addClass('active');
			$('#tab-step1').removeClass('active in');
			$('#tab-step2').addClass('active in');
			$('#tab-step3').removeClass('active in');
			$('.progress-bar-info').css('width', "66.6%");
			//$('.wizard-next').addClass("disabled", true);
			$('.wizard-prev').removeClass("disabled", false);
			//$('.wizard-finish').css('display', 'inline-block');
			//$('.wizard-next').css('display', 'none');
		}else if(width == "66.6%"){
			$('.nav-justified li').removeClass('active');
			$('.tab_progress').parent('li').eq(2).addClass('active');
			$('#tab-step1').removeClass('active in');
			$('#tab-step2').removeClass('active in');
			$('#tab-step3').addClass('active in');
			$('.progress-bar-info').css('width', "100%");
			//$('.wizard-next').addClass("disabled", true);
			$('.wizard-prev').removeClass("disabled", false);
			$('.wizard-finish').css('display', 'inline-block');
			$('.wizard-next').css('display', 'none');
		}
	});
	
	$('.wizard-prev').click(function(){
		var width = $('.progress-bar-info').css('width');
		if(width == "100%"){
			$('.nav-justified li').removeClass('active');
			$('.tab_progress').parent('li').eq(1).addClass('active');
			$('#tab-step3').removeClass('active in');
			$('#tab-step2').addClass('active in');
			$('#tab-step1').removeClass('active in');
			$('.progress-bar-info').css('width', "66.6%");
			$('.wizard-next').removeClass("disabled", false);
			//$('.wizard-prev').addClass("disabled", true);
			$('.wizard-finish').css('display', 'none');
			$('.wizard-next').css('display', 'inline-block');
		}else if(width == "66.6%"){
			$('.nav-justified li').removeClass('active');
			$('.tab_progress').parent('li').eq(0).addClass('active');
			$('#tab-step3').removeClass('active in');
			$('#tab-step2').removeClass('active in');
			$('#tab-step1').addClass('active in');
			$('.progress-bar-info').css('width', "33.3%");
			$('.wizard-next').removeClass("disabled", false);
			$('.wizard-prev').addClass("disabled", true);
			$('.wizard-finish').css('display', 'none');
			$('.wizard-next').css('display', 'inline-block');
		}
		
	});
});
</script> 
@include(DIR_ADMIN.'footer')