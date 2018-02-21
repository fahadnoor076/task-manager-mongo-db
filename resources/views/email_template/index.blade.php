@include(DIR_ADMIN.'header')
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.css') !!}" />
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
      
      <!-- Full Table -->
      <div class="block">
        <div class="block-content"> 
          <!-- Action Buttons -->
          <div class="form-group">
            <br clear="all" />
          </div>
          <form name="listing_form" method="post">
            <table class="js-table-sections table table-hover js-dataTable-full" id="mydatatable">
              <thead>
                <tr>
                  <th class="text-center"><!--<label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                      <input type="checkbox" id="check_all" name="check_all" />
                      <span></span></label>--></th>
                  <th class="text-center">Hint</th>
                  <th>Key</th>
                  <th>Subject</th>
                  <th>Updated at</th>
                  <th class="text-center">Options</th>
                </tr>
              </thead>
              <tfoot style="display: table-header-group;">
                <tr>
                  <th class="text-center"></th>
                  <th class="text-center"></th>
                  <th><input type="text" name="key" value="" /></th>
                  <th><input type="text" name="subject" value="" /></th>
                  <th class="hidden-xs"><input class="js-datepicker form-control" type="text" id="updated_at" name="updated_at" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy"></th>
                  <th class="hidden-xs" align="center"><a href="javascript:;" id="grid_search" class="btn btn-xs btn-success" type="button"><i class="fa fa-search"></i> Search</a><a id="grid_reset" href="javascript:;" class="btn btn-xs push-10-t btn-danger" type="button"><i class="glyphicon glyphicon-refresh"></i> Reset</a></th>
                </tr>
              </tfoot>
            </table>
            <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
          </form>
        </div>
      </div>
      
      <!-- END Full Table --> 
    </div>
    <!-- END Page Content --> 
  </main>
  <!--Dialog Box HTML Start --> 
  <!--<div id="dialog_confirm" title="Confirmation" style="display:none;">Are you really want to delete?</div>--> 
  
</div>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.js') !!}"></script> 
<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<!-- Custom JS Code --> 
<script type="text/javascript">
$(document).ready(function() {
	// ini date picker
	App.initHelper('datepicker');
	
	// data grid generation
	$('#mydatatable').DataTable().destroy();
	var dg = $("#mydatatable").DataTable({
		processing: true,
		serverSide: true,
		//paging: false,
		searching: false,
		bStateSave: false, // save datatable state(pagination, sort, etc) in cookie.
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
				width : '5%',
				targets: 0
			}, {
				data: "hint",
				orderable: false,
				className: 'text-center',
				width : '5%',
				targets: 1
			}, {
				data: "key",
				orderable: true,
				width : '30%',
				targets: 2
			}, {
				data: "subject",
				orderable: true,
				width : '30%',
				targets: 3
			},  {
				data: "updated_at",
				orderable: true,
				width : '20%',
				className: 'text-center',
				targets: 4
			}, {
				data: "options",
				orderable: false,
				width : '10%',
				className: 'text-center',
				targets: 5
			}
		],
		order: [
			[2, "asc"]
		]
	});
	// add search to datatable
	dgSearch(dg);
	// add select actions to datatable
	//dgSelectActions(dg);
	
});

</script> 
@include(DIR_ADMIN.'footer') 