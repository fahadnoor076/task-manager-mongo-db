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
                                                       <p>{!! $data[$id]['userEmail'] !!}</p>
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
                                            <div class="form-group" id="error_profileImage">
                                                <div class="row mrgn-all-none">
                                                    <label for="profileImage" class="col-sm-2 control-label">Profile Image</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <span class="btn btn-success fileinput-button input_button">
                                                        	<i class="glyphicon glyphicon-plus"></i>
                                                        	<span>Choose File</span>
                                                        </span>
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
                                        <form action="{!! URL::to(config('/')); !!}" class="dropzone" id="dropzone_single_file" style="display:block;">
                                            <input name="profileImage" id="profileImage" type="file" />
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
   $('.input_button').click(function(){
	   $('#profileImage').click();
   });
});
</script> 
@include(DIR_ADMIN.'footer')