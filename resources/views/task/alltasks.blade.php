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
<div class="bst-main-wrapper pad-all-md"> @include(DIR_ADMIN.'sidebar')
  <div class="bst-content-wrapper">
    <div class="bst-content">
      <div class="bst-page-bar mrgn-b-md breadcrumb-double-arrow">
        <ul class="breadcrumb">
          <li class="breadcrumb-item text-capitalize">
            <h3>{!! $p_title !!}</h3>
          </li>
          <li class="breadcrumb-item active"><a href="javascript:;">{!! $page_action !!}</a></li>
        </ul>
      </div>
      <!-- Session Messages --> 
      @include(DIR_ADMIN.'flash_message')
      <div class="table-style">
        <div class="bst-block">
          <div class="bst-block-title mrgn-b-lg">
            <h3 class="text-capitalize">{!! $module !!}</h3>
            {{--*/ $taskStatus = getTaskStatus(); /*--}}
          </div>
          <div class="bst-block-content">
          	<ul class="nav nav-tabs mrgn-b-lg">
                <li class="active"><a href="{!! URL::to(config('/').'task/all-tasks/') !!}" aria-expanded="true">All Tasks </a>
                	<span class="badge badge-danger pos-absolute pos-abs-right-top alltasksCount" style="right:-6px; top:-6px;"></span>
                </li>
                <li class=""> <a href="{!! URL::to(config('/').'task/all-due-tasks/') !!}" aria-expanded="false">All Due Tasks </a>
                	<span class="badge badge-danger pos-absolute pos-abs-right-top allduetasksCount" style="right:-6px; top:-6px;">{!! $allduetasksCount; !!}</span>
                </li>
                <li class=""> <a href="{!! URL::to(config('/').'task/due-today-tasks/') !!}" aria-expanded="false">Tasks Due Today </a>
                	<span class="badge badge-danger pos-absolute pos-abs-right-top duetodaytasksCount" style="right:-6px; top:-6px;">{!! $duetodaytasksCount; !!}</span>
                </li>
                <li class=""> <a href="{!! URL::to(config('/').'task/clarification-tasks/') !!}" aria-expanded="false">Clarifications </a>
                	<span class="badge badge-danger pos-absolute pos-abs-right-top clarificationtasksCount" style="right:-6px; top:-6px;">{!! $clarificationtasksCount; !!}</span>
                </li>
                <li class=""> <a href="{!! URL::to(config('/').'task/overdue-tasks/') !!}" aria-expanded="false">Tasks Overdue </a>
                	<span class="badge badge-danger pos-absolute pos-abs-right-top overduetasksCount" style="right:-6px; top:-6px;">{!! $overduetasksCount; !!}</span>
                </li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active fade in">
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
                                <th>ProjectName</th>
                                <th>Segment</th>
                                <th>TaskStatus</th>
                                <th>Priority</th>
                                <th>Brand</th>
                                <th>DueDate</th>
                                <th>AssignedTo</th>
                                <th>AssignedBy</th>
                                <th>Options</th>
                              </tr>
                            </thead>
                            <tfoot style="display: table-header-group;">
                              <tr>
                                <th class="text-center"></th>
                                <th><input type="text" class="form-control" name="projectName" /></th>
                                <th><select class="form-control" name="segmentName">
                                		<option value="">::Select Segment::</option>
                                        @if(!empty($segments))
                                        	@foreach($segments as $key => $segment)
                                            	@if($segment['departmentSlug'] != "sales")
                                            		<option value="{!! $key; !!}">{!! $segment['departmentName']; !!}</option>
                                                @endif
                                        	@endforeach
                                        @endif
                                	</select></th>
                                <th></th>
                               	<th><select class="form-control" name="taskPriority">
                                    <option value="">::Select Priority::</option>
                                    <option value="1">Prospect</option>
                                    <option value="2">High Paid</option>
                                    <option value="3">Confirm Upsell</option>
                                    <option value="4">Regular</option>
                                  </select></th>
                                <th><select class="form-control" name="fkBrandId">
                                    	<option value="">::Select Brand::</option>
                                        @if($brands > 0)
                                        	@foreach($brands as $key => $brand)
                                            	<option value="{!! $key !!}">{!! $brand['brandName'] !!}</option>
                                    		@endforeach
                                        @endif                   
                                  </select></th>
                                <th><input type="text" name="taskDueDate" class="form-control datepicker mrgn-b-xs" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" /></th>
                                <th></th>
                                <th><input type="text" class="form-control" name="taskAssignedBy" /></th>
                                <th class="hidden-xs" align="center"> <a href="javascript:;" id="grid_search" class="btn btn-xs btn-success" type="button"><i class="fa fa-search"></i> Search</a> <a id="grid_reset" href="javascript:;" class="btn btn-xs push-10-t btn-danger" type="button"><i class="glyphicon glyphicon-refresh"></i> Reset</a> </th>
                              </tr>
                            </tfoot>
                          </table>
                          <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                        </form>
                      </div>
                    </div>
                </div>
                <div id="tab-2" class="tab-pane fade">
                    
                </div>
                <div id="tab-3" class="tab-pane fade">
                    
                </div>
                <div id="tab-4" class="tab-pane fade">
                    
                </div>
                <div id="tab-5" class="tab-pane fade">
                    
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
	$('form[name="config"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_msg_]").removeClass("show").addClass("hide");
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
			url: "../{!! $module !!}/alltasks-ajax/listing?_token=<?php echo csrf_token(); ?>", // ajax source
			type: "POST",
			data : function(d) {
				for (var attrname in dg_ajax_params) { d[attrname] = dg_ajax_params[attrname]; }
			}
		},
		drawCallback: function (settings) {
			$('.alltasksCount').html(settings._iRecordsTotal);
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
				data: "projectName",
				orderable: true,
				responsivePriority: 2,
				className: 'text-center',
				targets: 1
			}, {
				data: "segmentName",
				orderable: true,
				responsivePriority: 3,
				className: 'text-center',
				targets: 2
			}, {
				data: "taskStatus",
				orderable: true,
				responsivePriority: 6,
				className: 'text-center',
				targets: 3
			}, {
				data: "taskPriority",
				orderable: true,
				responsivePriority: 8,
				className: 'text-center',
				targets: 4
			}, {
				data: "taskBrand",
				orderable: true,
				responsivePriority: 7,
				className: 'text-center',
				targets: 5
			}, {
				data: "taskDueDate",
				orderable: true,
				responsivePriority: 4,
				className: 'text-center',
				targets: 6
			}, {
				data: "taskAssignedTo",
				orderable: true,
				responsivePriority: 5,
				className: 'text-center',
				targets: 7
			}, {
				data: "taskAssignedBy",
				orderable: true,
				responsivePriority: 9,
				className: 'text-center',
				targets: 8
			}, {
				data: "options",
				orderable: false,
				responsivePriority: 1,
				className: 'text-center',
				targets: 9
			}
		],
		order: [
			[1, "asc"],
			[6, "asc"],
			[7, "desc"]
		]
		
	});
	
	dgSearch(dg);
	// add select actions to datatable
	dgSelectActions(dg);
	
});

</script>