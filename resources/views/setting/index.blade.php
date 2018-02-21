@include(DIR_ADMIN.'header') 
<!--<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/dropzonejs/dropzone.min.css') !!}">-->
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed"> 
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.css') !!}" />
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
              <li class="active"> <a href="#tab-step1" data-toggle="tab" aria-expanded="true">Main {!! $s_title !!}</a> </li>
              <li class=""> <a href="#tab-step2" data-toggle="tab" aria-expanded="false">Other {!! $p_title !!}</a> </li>
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
                <form name="main_setting" method="post" enctype="multipart/form-data">
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="site_name" class="form-control input-lg" type="text" placeholder="App/Site Name" value="{!! isset($config->site_name)? $config->site_name : '' !!}" />
                            <label for="mega-firstname"><span class="text-danger">*</span> App/Site Name </label>
                            <div id="error_msg_site_name" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="form-material"> 
                            <!-- DropzoneJS Container --> 
                            <!--<div class="dropzone" id="upload_logo" action="base_forms_pickers_more.html"></div>--> 
                            <a href="javascript:;" class="img-container" id="upload_logo"> <img id="site_logo" width="100" src="{!! URL::to(config('constants.LOGO_PATH').$config->site_logo) !!}" alt="-"></a>
                            <div id="elfinder"></div>
                            <label for="mega-firstname"><span class="text-danger">*</span> Logo</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="site_slogan" class="form-control input-lg" type="text" placeholder="Site/App Slogan" value="{!! isset($config->site_slogan)? $config->site_slogan : '' !!}" />
                            <label for="mega-firstname">Site/App Slogan</label>
                            <div id="error_msg_site_slogan" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="app_description" class="form-control input-lg" type="text" placeholder="App Description" value="{!! isset($config->app_description)? $config->app_description : '' !!}" />
                            <label for="mega-firstname">App Description</label>
                            <div id="error_msg_app_description" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="meta_keywords" class="form-control input-lg" type="text" placeholder="Meta Keywords" value="{!! isset($config->meta_keywords)? $config->meta_keywords : '' !!}" />
                            <label for="mega-firstname">Meta Keyword (for web)</label>
                            <div id="error_msg_meta_keywords" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="meta_description" class="form-control input-lg" type="text" placeholder="Meta Description" value="{!! isset($config->meta_description)? $config->meta_description : '' !!}" />
                            <label for="mega-firstname">Meta Description (for web)</label>
                            <div id="error_msg_meta_description" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--<div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="android_app_url" class="form-control input-lg" type="text" placeholder="Android App URL" value="{!! isset($config->android_app_url)? $config->android_app_url : '' !!}" />
                            <label for="mega-firstname">Android App URL</label>
                            <div id="error_msg_android_app_url" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="ios_app_url" class="form-control input-lg" type="text" placeholder="iOS App URL" value="{!! isset($config->ios_app_url)? $config->ios_app_url : '' !!}" />
                            <label for="mega-firstname">iOS App URL </label>
                            <div id="error_msg_ios_app_url" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>-->
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
                  <input type="hidden" name="site_logo" value="{!! $config->site_logo; !!}" />
                  <input type="hidden" name="do_post_main" value="1" />
                  <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                </form>
              </div>
              <!-- END Tab 1 --> 
              
              <!-- Tab 2 -->
              <div class="tab-pane fade fade-up" id="tab-step2">
                <div class="block-content">
                  <table class="js-table-sections table table-hover js-dataTable-full" id="mydatatable">
                    <thead>
                      <tr>
                        <th class="text-center"></th>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Hint</th>
                        <th align="center">Options</th>
                      </tr>
                    </thead>
                    <tfoot style="display: table-header-group;">
                      <tr>
                        <th class="text-center"></th>
                        <th><input type="text" name="key" value="" /></th>
                        <th><input type="text" name="value" value="" /></th>
                        <th></th>
                        <th class="hidden-xs" align="center"><a href="javascript:;" id="grid_search" class="btn btn-xs btn-success" type="button"><i class="fa fa-search"></i> Search</a><a id="grid_reset" href="javascript:;" class="btn btn-xs push-10-t btn-danger" type="button"><i class="glyphicon glyphicon-refresh"></i> Reset</a></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <!-- END Tab 2 --> 
              
            </div>
            
            <!-- END Steps Content --> 
            
            <!-- Steps Navigation --> 
            <!--<div class="block-content block-content-mini block-content-full border-t">
                <div class="row">
                  <div class="col-xs-6">
                    <button class="wizard-prev btn btn-default disabled" type="button"><i class="fa fa-arrow-left"></i> Previous</button>
                  </div>
                  <div class="col-xs-6 text-right">
                    <button class="wizard-next btn btn-default" type="button" style="display: inline-block;">Next <i class="fa fa-arrow-right"></i></button>
                    <button class="wizard-finish btn btn-primary" type="submit" style="display: none;"><i class="fa fa-check"></i> Submit</button>
                  </div>
                </div>
              </div>--> 
            <!-- END Steps Navigation --> 
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
@include(DIR_ADMIN.'elfinder') 

<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/jquery-validation/jquery.validate.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/pages/base_forms_wizard.js"></script> 
<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.js') !!}"></script> 
<!--<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/dropzonejs/dropzone.min.js"></script>--> 

<script type="text/javascript">

var el_commands = [
	'open', 'reload', 'getfile', 'quicklook', 
	'download','duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy', 
	'cut', 'paste', 'edit', 'extract', 'search', 'info', 'view', 'help',
	'resize', 'sort'
];

$(function() {
	
	// default form submit/validate (main)
	$('form[name="main_setting"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_msg_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $module !!}',$(this),'i[id=loading]');
	});
	
	// upload logo action
	$("a#upload_logo").click(function(e) {
		e.preventDefault();
		var fm = $('<div/>').dialogelfinder({
			url : '{!! $module !!}/logo_browser?_token={!! csrf_token() !!}',
			lang : 'en',
			width : 840,
			commands : el_commands, 
			destroyOnClose : true,
			getFileCallback : function(files, fm) {
				//console.log(files);
				$("img#site_logo").prop("src","{!! URL::to(config('constants.LOGO_PATH')); !!}/"+files.name);
				$("input[name=site_logo]").val(files.name);
			},
			commandsOptions : {
				getfile : {
					oncomplete : 'close',
					folders : false
				}
			}
		}).dialogelfinder('instance');
		
	});
	
	// activate tab
	$("a[href=#tab-{!! $tab !!}]").trigger("click");
	
	// data grid generation
	$('#mydatatable').DataTable().destroy();
	var dg = $("#mydatatable").DataTable({
		processing: true,
		serverSide: true,
		//paging: false,
		searching: false,
		bStateSave: true, // save datatable state(pagination, sort, etc) in cookie.
		ajax: {
			url: "{!! $module !!}/ajax/listing?_token=<?php echo csrf_token(); ?>", // ajax source
			type: "POST",
			data : function(d) {
				for (var attrname in dg_ajax_params) { d[attrname] = dg_ajax_params[attrname]; }
			}
		},
		drawCallback: function (settings) {
			// other functionality
			// Initialize Popovers
			$('[data-toggle="popover"], .js-popover').popover({
				container: 'body',
				animation: true,
				trigger: 'hover'
			});
		},
		lengthMenu: [
			[10, 20, 50, 100, - 1],
			[10, 20, 50, 100, "All"] // change per page values here
		],
		pageLength: 10, // default record count per page
		columnDefs : [
			{
				data: "ids",
				orderable: false,
				className: 'text-center',
				width : "5%",
				targets: 0
			}, {
				data: "key",
				orderable: true,
				width : "25%",
				targets: 1
			}, {
				data: "value",
				orderable: true,
				width : "40%",
				className: 'text-center',
				targets: 2
			}, {
				data: "description",
				orderable: false,
				width : "10%",
				targets: 3
			}, {
				data: "options",
				orderable: false,
				width : "20%",
				className: 'text-center',
				targets: 4
			}
		],
		order: [
			[1, "asc"]
		]
	});
	// add search to datatable
	dgSearch(dg);
	// add select actions to datatable
	dgSelectActions(dg);
});
</script> 
@include(DIR_ADMIN.'footer') 