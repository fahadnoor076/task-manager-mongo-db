{{--*/
// extra models
// - temp (will be removed after custom login/forgot/recover
//$conf_model = "App\Http\Models\Conf";
//$conf_model = new $conf_model;
//$_meta = isset($_meta) ? $_meta : json_decode($conf_model->getBy('key','site')->value);
/*--}}

@include(DIR_ADMIN.'header')
<!-- Login Content -->
<div class="bst-wrapper">
        <div class="bst-main login-bg"> <!--<a class="fixed-btn" href="index.html"><i class="fa fa-arrow-left mrgn-r-xs"></i>Back To Home</a>-->
            <div class="login-form-wrapper mrgn-b-lg">
                <div class="container-fluid">
                    <div class="row">
                    @include(DIR_ADMIN.'flash_message')
                        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-5 center-block">
                            <div class="bst-form-block bst-full-block overflow-wrappper">
                                <div class="login-bar"> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'login-bars.png') !!}" class="img-responsive" alt="login bar" width="743" height="7"> </div>
                                <div class="bst-block-title text-center">
                                    <div class="mrgn-b-lg">
                                        <a href="index.html"> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'beast-logo-blue.png') !!}" alt="login logo" class="img-responsive display-ib" width="300" height="42"> </a>
                                    </div>
                                    <div class="login-top mrgn-b-lg">
                                        <div class="mrgn-b-md">
                                            <h2 class="text-capitalize base-dark font-2x fw-normal">Forget Password</h2> </div>
                                        <p>Please provide your account’s email and we will send you your password.</p>
                                    </div>
                                </div>
                                <div class="bst-block-content">
                                    <form class="login-form" name="data_form" action="javascript:;" method="post">
                                        <div class="form-group has-feedback">
                                            <input class="form-control" name="email" id="user-id" type="email" placeholder="User Name" > <span class="glyphicon glyphicon-user form-control-feedback fa-lg" aria-hidden="true"></span> 
                                            <div id="error_msg_email" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                                        </div>
                                        <div class="mrgn-b-lg">
                                            <button type="submit" class="btn btn-success btn-block font-2x">Sign In</button>
                                        </div>
                                        <!--<div class="text-center">
                                            <h5 class="base-dark">Don't have an account ? <a href="javascript:;" class="text-primary">Sign Up</a></h5>
                                        </div>
                                        <div class="text-center"> <a class="back-home-btn" href="index.html"><i class="fa fa-long-arrow-left mrgn-r-xs"></i>Back To Home</a> </div>-->
                                        <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
            							<input type="hidden" name="do_post" value="1" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- END Login Content --> 
<!-- Reminder Footer -->
<?php /*?><div class="push-5-t text-center animated fadeInUp"> <small class="text-muted font-w600"><span class="js-year-copy"></span> &copy; {!! APP_NAME !!}</small> </div><?php */?>
<script>
// default form submit/validate
$('form[name="data_form"]').submit(function(e) {
	e.preventDefault();
	// hide all errors
	$("div[id^=error_msg_]").removeClass("show").addClass("hide");
	// validate form
	return jsonValidate('{!! $route_action !!}',$(this));
});
</script>
<!-- END Reminder Footer --> 
@include(DIR_ADMIN.'footer')