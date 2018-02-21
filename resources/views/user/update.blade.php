@include(DIR_ADMIN.'header')

{{--*/
// statuses
$admin_statuses = config("constants.ADMIN_STATUSES");
/*--}}

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
                        <div class="bst-block">
                                    <div class="horizontal-form-style">
                                        <div class="bst-block-title mrgn-b-lg">
                                            <h3>Users</h3>
                                            <p>Salsoft Technologies</p>
                                        </div>
                                        <form name="dataForm" class="form-horizontal" method="post">
                                        	<div class="form-group" id="error_userName">
                                                <div class="row mrgn-all-none">
                                                    <label for="userName" class="col-sm-2 control-label">User Name</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                       <input type="text" class="form-control" id="userName" name="userName" value="{!! $data[$id]['userName'] !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userEmail">
                                                <div class="row mrgn-all-none">
                                                    <label for="userEmail" class="col-sm-2 control-label">User Email</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" class="form-control" id="userEmail" name="userEmail" value="{!! $data[$id]['userEmail'] !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userPassword">
                                                <div class="row mrgn-all-none">
                                                    <label for="userPassword" class="col-sm-2 control-label">User New Password</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" class="form-control" id="userPassword" name="userPassword" placeholder="Set New Password or else leave Empty" value="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userContactPersonal">
                                                <div class="row mrgn-all-none">
                                                    <label for="userContactPersonal" class="col-sm-2 control-label">User Phone</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="tel" class="form-control" id="userContactPersonal inputTel" name="userContactPersonal" placeholder="0345-1234567" value="{!! $data[$id]['userContactPersonal'] !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userContactEmergency">
                                                <div class="row mrgn-all-none">
                                                    <label for="userContactEmergency" class="col-sm-2 control-label">Emergency Contact</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="tel"  class="form-control" id="userContactEmergency inputTel" name="userContactEmergency" placeholder="0333-1234567" value="{!! $data[$id]['userContactEmergency'] !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_fkRoleId">
                                                <div class="row mrgn-all-none">
                                                    <label for="fkRoleId" class="col-sm-2 control-label">User Role</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                       <select class="form-control" id="fkRoleId" name="fkRoleId">
                                                            <option value="">::Select Role::</option>
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
                                            <div class="form-group" id="error_fkDesignationId">
                                            	<div class="row mrgn-all-none">
                                            		<label for="fkDesignationId" class="col-sm-2 control-label">User Designation</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="fkDesignationId" name="fkDesignationId">
                                                            <option value="">::Select Designation::</option>
                                                            @if($designations > 0)
                                                            	@foreach($designations as $key => $designation)
                                                                	{{--*/ $sel = $data[$id]['fkDesignationId'] == $key ? 'selected="selected"' : '' /*--}}
                                                                	<option value="{!! $key !!}" {!! $sel !!}>{!! $designation['userDesignationName'] !!}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                        		</div>
                                            </div>
                                            <div class="form-group" id="error_fkDepartmentId">
                                            	<div class="row mrgn-all-none">
                                            		<label for="fkDepartmentId" class="col-sm-2 control-label">Department</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="fkDepartmentId" name="fkDepartmentId">
                                                            <option value="">::Select Department::</option>
                                                            @if($departments > 0)
                                                            	@foreach($departments as $key => $department)
                                                                	{{--*/ $sel = $data[$id]['fkDepartmentId'] == $key ? 'selected="selected"' : '' /*--}}
                                                                	<option value="{!! $key; !!}" {!! $sel; !!}>{!! $department['departmentName'] !!}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                        		</div>
                                            </div>
                                            <div class="form-group" id="error_fkBrandId">
                                            	<div class="row mrgn-all-none">
                                            		<label for="fkBrandId" class="col-sm-2 control-label">Brand</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="fkBrandId" name="fkBrandId[]" multiple="multiple">
                                                            <option value="">::Select Brand::</option>
                                                            @if($brands > 0)
                                                            	@foreach($brands as $key => $brand)
                                                                	{{--*/ $sel = in_array($key, $userBrands) ? 'selected="selected"' : '' /*--}}
                                                                	<option value="{!! $key !!}" {!! $sel; !!}>{!! $brand['brandName'] !!}</option>
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
                                                            <option value="">::Select Status::</option>
                                                            <option value="1" {!! $data[$id]['isActive'] == '1' ? 'selected="selected"' : '' !!}>Active</option>
                                                            <option value="0" {!! $data[$id]['isActive'] == '0' ? 'selected="selected"' : '' !!}>In-Active</option>
                                                        </select>
                                                    </div>
                                        		</div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row mrgn-all-none">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
          									<input type="hidden" name="do_post" value="1" />
                                        </form>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="dataForm"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		//return jsonValidate('{!! $route_action !!}',$(this));
		return jsonValidate('{!! $id !!}',$(this));
	});
	//CKEDITOR.replace('cat_content');
});

$(document).ready(function() {
    $('#fkRoleId').change(function(){
		if($(this).val() != ""){
			roleId = $(this).val();
			$.ajax({
				url:'../get-designations',
				type:'POST',
				data:{'roleId': roleId}
			}).done(function(data){
				if(data != 'error'){
					$('#fkDesignationId').html('');
					$('#fkDesignationId').append('<option value="">::Select Designation::</option>');
					$.each(data, function(key, value){
						$('#fkDesignationId').append('<option value="'+key+'">'+value.userDesignationName+'</option>');
						$('.designation_slug').val(value.userDesignationSlug);
						if(value.userDesignationSlug == 'super_admin' || value.userDesignationSlug == 'admin'){
							$('#error_fkDepartmentId').fadeOut();
							$('#error_fkBrandId').fadeOut();
						}else{
							$('#error_fkDepartmentId').fadeIn();
							$('#error_fkBrandId').fadeIn();
						}
					});
				}else{
					$('#fkDesignationId').html('');
					$('#fkDesignationId').append('<option value="">::Select Designation::</option>');
				}							
			});
		}
	});
	
	$('#fkDepartmentId').change(function(){
		if($(this).val() != ""){
			departmentId = $(this).val();
			$.ajax({
				url:'../get-brands-by-department',
				type:'POST',
				data:{'departmentId': departmentId}
			}).done(function(data){
				if(data != 'error'){
					$('#fkBrandId').html('');
					$('#fkBrandId').append('<option value="">::Select Brand::</option>');
					$.each(data, function(key, value){
						$('#fkBrandId').append('<option value="'+key+'">'+value.brandName+'</option>');
					});
				}else{
					$('#fkBrandId').html('');
					$('#fkBrandId').append('<option value="0" selected="selected">::No Brand::</option>');
				}							
			});
		}
	});
});
</script> 
@include(DIR_ADMIN.'footer')