@include(DIR_ADMIN.'header') 
<!--<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/dropzonejs/dropzone.min.css') !!}">-->
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed"> 
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.css') !!}" />
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/buttons.dataTables.min.css') !!}" />
@include(DIR_ADMIN.'side_overlay')
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
            <li><a class="link-effect" href="javascript:;">{!! $page_action !!}</a></li>
          </ol>
        </div>
      </div>
    </div>
    <!-- END Page Header --> 
    
    <!-- Page Content -->
    <div class="content"> 
      
      <!-- Session Messages --> 
      @include(DIR_ADMIN.'flash_message') 
      <!-- Buttons --> 
      <!--<div class="col-sm-12"> <a href="{!! URL::to(DIR_ADMIN.$module.'/add') !!}" class="btn btn-primary push-5-r push-10 push-10-t pull-right" type="button"><i class="fa fa-plus"></i> Add {!! $s_title !!}</a></div>-->
      
      <div class="row">
        <div class="col-lg-12"> 
          <!-- Simple Classic Progress Wizard (.js-wizard-simple class is initialized in js/pages/base_forms_wizard.js) --> 
          <!-- For more examples you can check out http://vadimg.com/twitter-bootstrap-wizard-example/ -->
          <div class="js-wizard-simple block"> 
            <!-- Step Tabs -->
            <ul class="nav nav-tabs nav-justified">
              <li class="active"> <a href="#tab-step1" data-toggle="tab" aria-expanded="true">Query</a> </li>
              <li class=""> <a href="#tab-step2" data-toggle="tab" aria-expanded="false">Results</a> </li>
            </ul>
            <!-- END Step Tabs --> 
            
            <!-- Steps Progress -->
            <div class="block-content block-content-mini block-content-full border-b">
              <div class="wizard-progress progress progress-mini remove-margin-b">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
              </div>
            </div>
            <!-- END Steps Progress --> 
            
            <!-- Tab Content -->
            <div class="block-content tab-content push-30"> 
              <!-- Tab 1 -->
              <div class="tab-pane fade fade-up active in" id="tab-step1">
                <form name="main" method="post" enctype="multipart/form-data">
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-12">
                          <div class="form-material">
                            <textarea rows="10" name="statement" class="form-control input-lg" type="text" placeholder="Enter SQL Statement"></textarea>
                            <label for="mega-firstname">SQL Statement</label>
                            <div id="error_msg_statement" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6 pull-right">
                          <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-check"></i> Submit</button>
                          <i id="loading" class="fa fa-2x fa-asterisk fa-spin text-success pull-right" style="margin-right:10px; display:none;"></i>
                        </div>
                        <br clear="all" />
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="do_post" value="1" />
                  <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                </form>
              </div>
              <!-- END Tab 1 --> 
              
              <!-- Tab 2 -->
              <div class="tab-pane fade fade-up" id="tab-step2">
                <div class="block-content">
                </div>
              </div>
              <!-- END Tab 2 --> 
              
            </div>
            
            <!-- END Steps Content --> 
            
          </div>
          <!-- END Simple Classic Progress Wizard --> 
        </div>
      </div>
      <!-- END Full Table --> 
    </div>
    <!-- END Page Content --> 
  </main>
  <!--Dialog Box HTML Start --> 
  <!--<div id="dialog_confirm" title="Confirmation" style="display:none;">Are you really want to delete?</div>--> 
  
</div>
<!-- Session Messages -->

<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/jquery-validation/jquery.validate.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/pages/base_forms_wizard.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.js') !!}"></script> 
<!-- Datatabales extra -->
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/dataTables.buttons.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/buttons.flash.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/jszip.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/pdfmake.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/vfs_fonts.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/buttons.html5.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/extra/buttons.print.min.js') !!}"></script>
<script type="text/javascript">
$(function() {
	
	// default form submit/validate (main)
	$('form[name="main"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_msg_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $module !!}',$(this), "#loading");
	});
	
});
</script> 
@include(DIR_ADMIN.'footer') 