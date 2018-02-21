@include(config('pl_{wildcard_identifier}.DIR_PANEL').'header') 
<!-- Page JS Plugins --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/jquery-validation/jquery.validate.min.js') !!}"></script> 

<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_forms_validation.js') !!}"></script>
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
@include(config('pl_{wildcard_identifier}.DIR_PANEL').'side_overlay')
@include(config('pl_{wildcard_identifier}.DIR_PANEL').'sidebar')
@include(config('pl_{wildcard_identifier}.DIR_PANEL').'nav_header') 

<!-- Main Container -->
<main id="main-container"> 
  <!-- Page Header -->
  <div class="content bg-gray-lighter">
    <div class="row items-push">
      <div class="col-sm-7">
        <h1 class="page-heading"> Change Password </h1>
      </div>
      <div class="col-sm-5 text-right hidden-xs">
        <ol class="breadcrumb push-10-t">
          <li>{wildcard_ucword}</li>
          <li><a class="link-effect" href="javascript:;">Change Password</a></li>
        </ol>
      </div>
    </div>
  </div>
  <!-- END Page Header --> 
  
  <!-- Page Content -->
  <div>
    <section class="content content-boxed"> 
      <!-- Section Content --> 
      <!--@if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{!! $error !!}</li>
          @endforeach
        </ul>
      </div>
      @endif-->
      <!-- Session Messages --> 
      @include(config('pl_{wildcard_identifier}.DIR_PANEL').'flash_message')
      
      <div class="row items-push push-30">
        <div class="col-md-6 col-md-offset-3">
          <div class="block pad-40">
            <form name="data_form" class="js-validation-bootstrap form-horizontal" action="" method="post">
              <div class="form-group">
                <div class="col-xs-12">
                  <div class="form-material form-material-primary"> </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-12">
                  <div class="form-material form-material-primary">
                    <input class="form-control" type="password" name="password" placeholder="Current Password" value="">
                    <label><span class="text-danger">*</span> Current Password</label>
                    <div id="error_msg_password" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-12">
                  <div class="form-material form-material-primary">
                    <input class="form-control" type="password" name="new_password" placeholder="New Password" value="">
                    <label><span class="text-danger">*</span> New Password</label>
                    <div id="error_msg_new_password" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-12">
                  <div class="form-material form-material-primary">
                    <input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" value="">
                    <label><span class="text-danger">*</span> Confirm Password</label>
                    <div id="error_msg_confirm_password" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6 col-sm-offset-3">
                  <button class="btn btn-block btn-success" type="submit"><i class="pull-right"></i>Update Password</button>
                </div>
              </div>
              <input type="hidden" id="token" name="_token" value="{!! csrf_token() !!}" />
              <input type="hidden" name="do_post" value="1" />
            </form>
          </div>
        </div>
      </div>
      <!-- END Section Content --> 
    </section>
  </div>
  <!-- END Page Content --> 
</main>
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
<!-- END Main Container --> 
@include(config('pl_{wildcard_identifier}.DIR_PANEL').'footer')