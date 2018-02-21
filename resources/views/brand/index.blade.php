@include(DIR_ADMIN.'header')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css" />
<!-- Datatables Buttons -->
<link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />

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
                            @if($perm_add)
                            <div class="text-right">
                            	<a href="{!! URL::to(DIR_ADMIN.$module.'/add') !!}" class="btn btn-primary">+ Add Brand</a>
                            </div>
                            @endif
                        </div>
                        <!-- Session Messages --> 
     					 @include(DIR_ADMIN.'flash_message')
                        <div class="table-style">
                        <div class="bst-block">
                            <div class="bst-block-title mrgn-b-lg">
                                <!--<h3 class="text-capitalize">Bootstrap Table with all extension</h3>--> </div>
                            <div class="bst-block-content">
                                <div class="bootstrap-table">
                                	<div class="fixed-table-container">
                                    	<div class="col-sm-3">
                                          <select class="form-control" name="select_action" size="1">
                                            <option value="">Options</option>
                                            @if($perm_del)
                                            <option value="delete">Delete</option>
                                            @endif
                                          </select>
                                        </div>
                                        <form name="listing_form" method="post">
                                          <!-- js-table-sections table table-hover js-dataTable-full --> <!-- ui celled table nowrap  mydatatable-->
                                            <table class="display responsive nowrap js-table-sections table table-hover js-dataTable-full" id="mydatatable" width="100%">
                                              <thead>
                                                <tr>
                                                  <th class="text-center" width="5%"><label class="css-input css-checkbox css-checkbox-rounded css-checkbox-sm css-checkbox-primary">
                                                      <input type="checkbox" id="check_all" name="check_all" />
                                                      <span></span></label></th>
                                                  <th>BrandName</th>
                                                  <th>BrandUrl</th>
                                                  <th>StartTime</th>
                                                  <th>EndTime</th>
                                                  <th>IsActive</th>
                                                  <th>Added Date</th>
                                                  <th>Options</th>
                                                </tr>
                                              </thead>
                                              <tfoot style="display: table-header-group;">
                                                <tr>
                                                  <th class="text-center"></th>
                                                  <th><input type="text" name="brandName" class="search_keyword" /></th>
                                                  <th></th>
                                                  <th><input type="text" name="startTime" class="search_keyword" /></th>
                                                  <th><input type="text" name="endTime" class="search_keyword" /></th>
                                                  <th><select name="isActive" class="form-control search_keyword">
                                                  		<option value="">::Select Status::</option>
                                                        <option value="1">Active</option>
                                                        <option value="2">InActive</option>
                                                      </select></th>
                                                  <th></th>
                                                  <th class="hidden-xs" align="center">
                                                  	<a href="javascript:;" id="grid_search" class="btn btn-xs btn-success" type="button"><i class="fa fa-search"></i> Search</a>
                                                    <a id="grid_reset" href="javascript:;" class="btn btn-xs push-10-t btn-danger" type="button"><i class="glyphicon glyphicon-refresh"></i> Reset</a>
                                                  </th>
                                                </tr>
                                              </tfoot>
                                            </table>
                                   		 	<input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                                  		</form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@include(DIR_ADMIN.'footer')

<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script> 

<!-- Datatables Buttons -->
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>-->

<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/magnific-popup/magnific-popup.min.js') !!}"></script>

<script type="text/javascript">
$(document).ready(function() {
	// ini date picker
	// default form submit/validate (config)
	$('form[name="listing_form"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $module !!}',$(this));
	});
	
	// number spinner
	$( ".js-spinner-number" ).spinner({
		min: 1,
		max: 1000,
		step: 1,
		start: 1
	});
	
	// data grid generation
	$('#mydatatable').DataTable().destroy();
	var dg = $("#mydatatable").DataTable({
		//dom: 'Bfrtip',
		dom: 'lpiBfrtip',
		//bScrollCollapse: true,
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		],
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
			// Init page helpers (Magnific Popup plugin)
			//$('.img-thumb').magnificPopup({type: 'image'});
		},
		lengthMenu: [
			[10, 20, 50, 100, - 1],
			[10, 20, 50, 100, "All"] // change per page values here
		],
		pageLength: 20, // default record count per page
		columnDefs : [
			{
				data: "ids",
				orderable: false,
				responsivePriority: 0,
				width: '5%',
				className: 'text-center',
				targets: 0
			}, {
				data: "brandName",
				orderable: true,
				responsivePriority: 2,
				className: 'text-center',
				targets: 1
			}, {
				data: "brandUrl",
				orderable: true,
				responsivePriority: 3,
				className: 'text-center',
				targets: 2
			}, {
				data: "startTime",
				orderable: true,
				responsivePriority: 4,
				className: 'text-center',
				targets: 3
			}, {
				data: "endTime",
				orderable: true,
				responsivePriority: 5,
				className: 'text-center',
				targets: 4
			}, {
				data: "isActive",
				orderable: false,
				responsivePriority: 6,
				className: 'text-center',
				targets: 5
			}, {
				data: "addedAt",
				orderable: true,
				responsivePriority: 7,
				targets: 6
			}, {
				data: "options",
				orderable: false,
				responsivePriority: 1,
				className: 'text-center',
				targets: 7
			}
		],
		order: [
			[1, "asc"]
		]
	});
	
	dgSearch(dg);
	// add select actions to datatable
	dgSelectActions(dg);
	
	
	//Reload Datatables
	/*function RefreshTable(tableId, urlData)
	{
	  $.getJSON(urlData, null, function( json )
	  {
		table = $(tableId).dataTable();
		oSettings = table.fnSettings();
	
		table.fnClearTable(this);
	
		/*for (var i=0; i<json.aaData.length; i++)
		{
		  table.oApi._fnAddData(oSettings, json.aaData[i]);
		}*/
	
		/*oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();
		table.fnDraw();
	  });
	}*/
	
	/*function AutoReload()
	{
	  RefreshTable('#mydatatable', '{!! $module !!}/ajax/listing?_token=<?php //echo csrf_token(); ?>');
	
	  setTimeout(function(){AutoReload();}, 30000);
	}*/
	
	 // setTimeout(function(){AutoReload();}, 30000);
	
});

</script>