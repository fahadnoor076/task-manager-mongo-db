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
                                <li class="breadcrumb-item active"><a href="javascript:;">{!! $page_action !!}</a></li>
                            </ul>
                        </div>
                        <!-- Session Messages --> 
     					 @include(DIR_ADMIN.'flash_message')
                        <div class="form-validation-style">
                        <div class="bst-block">
                                    <div class="horizontal-form-style">
                                        <div class="bst-block-title mrgn-b-lg">
                                            <h3>Change Password</h3>
                                            <!--<p>Will be followed for all Departments</p>-->
                                        </div>
                                        <form name="data_form" class="form-horizontal" method="post">
                                            <div class="form-group" id="error_current_password">
                                                <div class="row mrgn-all-none">
                                                    <label for="current_password" class="col-sm-2 control-label">Current Password</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	 <input class="form-control" type="password" id="current_password" name="current_password" placeholder="Current Password" value="" />
                                                         <div id="error_msg_current_password" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_new_password">
                                                <div class="row mrgn-all-none">
                                                    <label for="new_password" class="col-sm-2 control-label">New Password</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	 <input class="form-control" type="password" id="new_password" name="new_password" placeholder="New Password" value="" />
                                                         <div id="error_msg_new_password" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_confirm_password">
                                                <div class="row mrgn-all-none">
                                                    <label for="confirm_password" class="col-sm-2 control-label">Confirm Password</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	 <input class="form-control" type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" value="" />
                                                         <div id="error_msg_confirm_password" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row mrgn-all-none">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="submit" class="btn btn-primary">Change Password</button>
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
	$('form[name="data_form"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		$("div[id^=error_msg_]").removeClass("show").addClass("hide");
		//$("div[id^=error_]").removeClass("has-error");
		// validate form
		return jsonValidate('{!! $route_action !!}',$(this));
	});
	//CKEDITOR.replace('cat_content');
	
});
</script> 
@include(DIR_ADMIN.'footer')