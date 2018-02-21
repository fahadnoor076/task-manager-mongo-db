@include(DIR_ADMIN.'header')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css" />
<!-- Datatables Buttons -->
<link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
<style>
.nav-pills{
	display:block;
}
.error{
	color:#f00;
}
</style>

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
                                <li class="breadcrumb-item active"><a href="{!! URL::to(config('/').'account'); !!}">Listing</a></li>
                                <li class="breadcrumb-item"><a href="javascript:;">{!! $page_action !!}</a></li>
                            </ul>
                        </div>
                        <!-- Session Messages --> 
     					 @include(DIR_ADMIN.'flash_message')
                        
            <div class="form-validation-style">
        <div class="bst-block">
          <div class="horizontal-form-style">
            <div class="bst-block-title mrgn-b-lg">
              <h3>Account Details</h3>
              <!--<p></p>--> 
            </div>
            <div class="typography-widget col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="bst-block">
                <div class="bst-block-title mrgn-b-lg">
                  <h3>{!! $data['clientName']; !!}</h3>
                </div>
                
                <div class="des-style">
                	
                   	<dl class="dl-horizontal">
                        <dt class="mrgn-b-xs">Name:</dt>
                        <dd>{!! $data['clientName']; !!}</dd>
                        <dt class="mrgn-b-xs">Email:</dt>
                        <dd>{!! $data['clientEmail']; !!}</dd>
                        <dt class="mrgn-b-xs">Phone:</dt>
                        <dd>{!! $data['clientPhone']; !!}</dd>
                        <dt class="mrgn-b-xs">Company:</dt>
                        <dd>{!! $data['clientCompany']; !!}</dd>
                        <dt class="mrgn-b-xs">Address:</dt>
                        <dd>{!! $data['clientAddress']; !!}</dd>
                        <dt class="mrgn-b-xs">Added Date:</dt>
                        <dd>{!! date('M d, Y H:i:s', strtotime($data['addedAt'])); !!}</dd>
                 	</dl>
                </div>
                <hr>
                
              </div>
            </div>
            <div class="bst-users-listing clearfix">
              <div class="row">               <div class="default-pills-tab">
              	<ul class="nav nav-pills mrgn-b-lg">
                	@if($projects > 0)
                    	@foreach($projects as $project)
                        	@if(is_array($project))
                            	{{--*/ $pjCount = count($project); /*--}}
                            	@foreach($project as $project)
                                	<li>
                                    	<a href="{!! URL::to('/project/detail/'.$project['_id']->{'$id'}); !!}">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                            <div class="bst-block fw-light">
                                                <div class="clearfix"></div>
                                                <div class="thumb-wid pull-left mrgn-r-md">
                                                	{{--*/ $buttons = buttonsArray(); /*--}}
                                                    {{--*/ $randArr = rand(1,$pjCount) /*--}}
                                                	{{--*/ $pjname = strtoupper(substr($project['projectName'],0,2)); /*--}}
                                                	<span class="square-50 {!! "btn-".$buttons[$randArr]; !!} img-circle base-reverse mrgn-r-md">{!! $pjname; !!}</span>
                                                    <!--<span style="font-size:24px; font-weight: bold;">{!! $project['projectName'] !!}</span>-->
                                                </div>
                                                <div class="thumb-content pull-left">
                                                    <h6 class="fw-bold base-dark">{!! $project['projectName']; !!} <span class="label label-xs label-{!! $buttons[$randArr]; !!} mrgn-l-xs">{!! $project['projectPriority']; !!}</span></h6>
                                                    <p><span><i class="fa fa-user-secret mrgn-r-xs" aria-hidden="true"></i></span>{!! $project['createdArray'][0]['userName'] !!}</p>
                                                    <p><span><i class="fa fa-calendar mrgn-r-xs" aria-hidden="true"></i></span>{!! date('M d, Y H:i:s', strtotime($project['addedAt'])); !!}</p>
                                            </div>
                                        </div>
                                      </div>
                                      </a>
                                  </li>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                        
                                </ul>
                 
                
                        <div class="clearfix"></div>
                        <div class="user-chat-wrap bst-block overflow-wrapper">
                        	<ul class="nav nav-tabs mrgn-b-lg">
                                <li class="active"> <a data-toggle="tab" href="#tab-1" aria-expanded="true">Opportunities</a> </li>
                                <li class=""> <a data-toggle="tab" href="#tab-2" aria-expanded="false">Invoices</a> </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane fade active in">
                                    <div class="bst-users-listing clearfix">
                                      <div class="row">
                                        <div class="bst-block">
                                          <div class="bst-block-title mrgn-b-md">
                                            <div class="caption">
                                              	<h3 class="text-capitalize">Opportunities</h3>
                                                <div class="text-right" style="float:right; margin:-40px -200px 0px 0px;">
                                                	<a href="#opportunityAdd" class="btn btn-primary" data-toggle="modal">+ Add Opportunity</a>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="bst-block-content">
                                            <div class="table-responsive">
                                              <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                  <tr>
                                                  	<th></th>
                                                    <th>Project</th>
                                                    <th>Amount</th>
                                                    <th>Description</th>
                                                    <th>Added Date</th>
                                                    <th>Added By</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                @if(!empty($opportunities))
                                                	{{--*/ $count = 0; /*--}}
                                                    @foreach($opportunities['result'] as $opportunity)
                                                    	{{--*/ ($opportunity['opportunityStatus'] == 0) ? $status = '<span class="label label-xs label-danger mrgn-l-xs">InActive</span>' : $status = '<span class="label label-xs label-success mrgn-l-xs">Active</span>'; /*--}}
                                                        {{--*/ $count++; /*--}}
                                                        <tr>
                                                          <td>{!! $count; !!}</td>
                                                          <td>{!! $opportunity['projectArray'][0]['projectName']; !!}</td>
                                                          <td>{!! "$".$opportunity['opportunityAmount']; !!}</td>
                                                          <td>{!! $opportunity['opportunityDescription']; !!}</td>
                                                          <td>{!! date('M d, Y H:i:s', strtotime($opportunity['addedAt'])); !!}</td>
                                                          <td>{!! $opportunity['userArray'][0]['userName']; !!}</td>
                                                          <td>{!! $status; !!}</td>
                                                          <td>
                                                          	<div class="btn-group">
                                                          		<a class="btn btn-outline-inverse btn-xs" type="button" href="#update{!! $count; !!}" data-toggle="modal" title="Update"><span><i class="fa fa-pencil" aria-hidden="true"></i></span> Edit</a>
                                                                <a class="btn btn-outline-danger btn-xs deleteOpportunity" data-id="{!! $opportunity['_id']->{'$id'}; !!}" type="button" href="javascript:;" title="Delete"><span><i class="fa fa-times" aria-hidden="true"></i></span> Delete</a>
                                                              </div>
                                                         </td>
                                                        </tr>
                                                        <!-- Modal Task Form -->
                                                        <div id="update{!! $count; !!}" class="modal fade" aria-hidden="true">
                                                          <div class="modal-dialog">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                <h4 class="modal-title">Update Opportunity</h4>
                                                              </div>
                                                              <form name="opportunityForm" class="modal-form" method="post">
                                                                <div class="modal-body">
                                                                  <div class="row">
                                                                    <h4>Select Project</h4>
                                                                    <div class="form-group">
                                                                      <select name="projectId" class="form-control">
                                                                        <option value="">::Select Project::</option>
                                                                        @if($projects > 0)
                                                                            @foreach($projects as $project)
                                                                                @if(is_array($project))
                                                                                    @foreach($project as $project)
                                                                                    	{{--*/ $sel = ($opportunity['fkProjectId'] == $project['_id']->{'$id'}) ? "selected='selected'" : ""; /*--}}
                                                                                        <option value="{!! $project['_id']->{'$id'} !!}" {!! $sel; !!}>{!! $project['projectName']; !!}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            @endforeach
                                                                        @endif                                                                                          
                                                                      </select>
                                                                      <div class="error" id="error_projectId"></div>
                                                                    </div>
                                                                    <h4>Amount:</h4>
                                                                    <div class="form-group">
                                                                      <input type="text" name="opportunityAmount" class="form-control" value="{!! $opportunity['opportunityAmount']; !!}" >
                                                                      <div class="error" id="error_opportunityAmount"></div>
                                                                    </div>
                                                                    <h4>Description:</h4>
                                                                    <div class="form-group">
                                                                      <textarea name="opportunityDescription" class="form-control">{!! $opportunity['opportunityDescription']; !!}</textarea>
                                                                      <div class="error" id="error_opportunityDescription"></div>
                                                                    </div>
                                                                    <!-- Assign to Users -->
                                                                    <h4>Status:</h4>
                                                                    <div class="form-group">
                                                                      <select class="form-control" id="opportunityStatus" name="opportunityStatus">
                                                                        <option value="0" {!! ($opportunity['opportunityStatus'] == 0)? "selected='selected'" : ""; !!}>InActive</option>
                                                                        <option value="1" {!! ($opportunity['opportunityStatus'] == 1)? "selected='selected'" : ""; !!}>Active</option>                                                               
                                                                      </select>
                                                                      <div class="error" id="error_opportunityStatus"></div>
                                                                    </div>
                                                                    <!-- Assign to Users --> 
                                                                  </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                  <input type="hidden" name="_id" value="{!! $opportunity['_id']->{'$id'}; !!}">
                                                                  <input type="hidden" name="clientId" value="{!! $id; !!}">
                                                                  <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}">
                                                                  <input type="hidden" name="opportunity_update" value="1">
                                                                  <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
                                                                  <button type="submit" class="btn btn-success">Save changes</button>
                                                                </div>
                                                              </form>
                                                            </div>
                                                          </div>
                                                        </div>
                                                        <!-- Modal Task Form End -->
                                                     @endforeach
                                                    @else
                                                    <tr>
                                                      <td colspan="7" style="text-align:center;">No opportunities available!</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                                <div id="tab-2" class="tab-pane fade">
                                	<div class="bst-users-listing clearfix">
                                      <div class="row">
                                        <div class="bst-block">
                                          <div class="bst-block-title mrgn-b-md">
                                            <div class="caption">
                                              <h3 class="text-capitalize">Invoices</h3>
                                              <div class="text-right" style="float:right; margin-top:-44px;"><a href="#invoiceAdd" class="btn btn-primary" data-toggle="modal">+ Add Invoice</a></div>
                                            </div>
                                          </div>
                                          <div class="bst-block-content">
                                            <div class="table-responsive">
                                              <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                  <tr>
                                                  	<th></th>
                                                    <th>Type</th>
                                                    <th>Account</th>
                                                    <th>Project</th>
                                                    <th>Invoice#</th>
                                                    <th>Segments</th>
                                                    <th>Total Amount</th>
                                                    <th>Paid Amount</th>
                                                    <th>Added By</th>
                                                    <th>Added Date</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                @if(isset($invoices) && $invoices > 0)
                                                	{{--*/ $count = 0; /*--}}
                                                	@foreach($invoices['result'] as $invoice)
                                                    	{{--*/ $count++; /*--}}
                                                    	<tr>
                                                        	{{--*/ $types = getInvoiceTypes(); /*--}}
                                                            @if(isset($types))
                                                            	@foreach($types as $key => $type)
                                                                	@if($key == $invoice['invoiceType'])
                                                                    	{{--*/ $invtype = $type; /*--}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            <th>{!! $count; !!}</th>
                                                            <th>{!! $invtype !!}</th>
                                                            <th>{!! $data['clientName']; !!}</th>
                                                            <th>{!! (isset($invoice['projectArray'][0]['projectName']))? $invoice['projectArray'][0]['projectName'] : "::No projects::" ; !!}</th>
                                                            <th>{!! $invoice['invoiceNumber']; !!}</th>
                                                            <th>
                                                            	@if(isset($invoice['invoiceArray'][0]))
                                                                	@foreach($invoice['invoiceArray'] as $invoiceSegment)
                                                                    	{!! "<div>"; !!}
                                                                    	{!! "<b>".ucfirst($invoiceSegment['segmentType']) ."</b>" !!}
                                                                        {!! "<span><i style='font-weight:500;'>Amount:</i> ".$invoiceSegment['segmentAmount'] ."</span>" !!}
                                                                        {!! "<span><i style='font-weight:500;'>Desc:</i> ".$invoiceSegment['segmentDescription'] ."</span>" !!}
                                                                        {!! "</div>"; !!}
                                                                    @endforeach
                                                                @else
                                                                	{!! "No Segments"; !!}
                                                                @endif
                                                            </th>
                                                            <th>{!! $invoice['invoiceTotalAmount']; !!}</th>
                                                            <th>{!! $invoice['invoicePaidAmount']; !!}</th>
                                                            <th>{!! $invoice['createdArray'][0]['userName']; !!}</th>
                                                            <th>{!! $invoice['addedAt']; !!}</th>
                                                      	</tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                                 
                
            </div>
                 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
                </div>
            </div>
        </div>
<!-- Modal Opportunity Form -->
<div id="opportunityAdd" class="modal fade" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add Opportunity</h4>
      </div>
      <form name="opportunityForm" class="modal-form" method="post">
        <div class="modal-body">
          <div class="row">
            <h4>Select Project</h4>
            <div class="form-group">
              <select name="projectId" class="form-control">
              	<option value="">::Select Project::</option>
              	@if($projects > 0)
                	@foreach($projects as $project)
                    	@if(is_array($project))
                        	@foreach($project as $project)
                    			<option value="{!! $project['_id']->{'$id'} !!}">{!! $project['projectName']; !!}</option>
                            @endforeach
                        @endif
                    @endforeach
                @endif                                                                                          
              </select>
              <div class="error" id="error_projectId"></div>
            </div>
            <h4>Amount:</h4>
            <div class="form-group">
              <input type="text" name="opportunityAmount" class="form-control" >
              <div class="error" id="error_opportunityAmount"></div>
            </div>
            <h4>Description:</h4>
            <div class="form-group">
              <textarea name="opportunityDescription" class="form-control"></textarea>
              <div class="error" id="error_opportunityDescription"></div>
            </div>
            <!-- Assign to Users -->
            <h4>Status:</h4>
            <div class="form-group">
              <select class="form-control" id="opportunityStatus" name="opportunityStatus">
              	<option value="0">InActive</option>
                <option value="1">Active</option>                                                               
              </select>
              <div class="error" id="error_opportunityStatus"></div>
            </div>
            <!-- Assign to Users --> 
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="clientId" value="{!! $id; !!}">
          <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}">
          <input type="hidden" name="opportunity_post" value="1">
          <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
          <button type="submit" class="btn btn-success">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Opportunity Form End -->

<!-- Modal Invoice Form -->
<div id="invoiceAdd" class="modal fade" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add Invoice</h4>
      </div>
      <form name="invoiceForm" class="modal-form" method="post" novalidate>
        <div class="modal-body">
          <div class="row">
            <h4>Invoice #:</h4>
            <div class="form-group">
              <input type="text" name="invoiceNumber" class="form-control" >
              <div class="error" id="error_invoiceNumber"></div>
            </div>
            <h4>Type:</h4>
            <div class="form-group">
              <select name="invoiceType" class="form-control invoiceType">
              	<option value="">::Select Type::</option>
                {{--*/ $types = getInvoiceTypes(); /*--}}
                @if(is_array($types) && $types != "")
                	@foreach($types as $key => $type)
                    	<option value="{!! $key; !!}">{!! $type; !!}</option>
                    @endforeach
                @endif                                                                                      
              </select>
              <div class="error" id="error_invoiceType"></div>
            </div>
            <h4>Select Project</h4>
            <div class="form-group projectDiv">
              <select name="projectId" class="form-control projectId">
              	<option value="0">::No Projects::</option>                                                                                      
              </select>
              <div class="error" id="error_projectId"></div>
            </div>
            <h4>Segments:</h4>
            <div class="form-group">
              <select name="invoiceSegments[]" class="selectize-remove-btn selectized invoiceSegments" multiple="multiple">
              	<option value="">::Select Type::</option>
                @if(isset($segments) && $segments > 0)
                	@foreach($segments as $key => $segment)
                    	@if($segment['departmentSlug'] != "sales")
                    		<option value="{!! $key; !!}">{!! $segment['departmentName']; !!}</option>
                        @endif
                    @endforeach
                @endif                                                                                      
              </select>
              <div class="error" id="error_invoiceSegments"></div>
            </div>
            
            <div class="segmentsInfo">
            	@if(isset($segments) && $segments > 0)
                	@foreach($segments as $key => $segment)
                    	@if($segment['departmentSlug'] != "sales")
                        	<div class="{!! $segment['departmentSlug']."Div"; !!}" style="display:none;">
                            	<hr />
                                <h4 style="text-align:center; font-size:22px;">Segment Details of {!! $segment['departmentName']; !!}</h4>
                                <!--<div class="form-group">
                                  <p class="{!! $segment['departmentSlug']."Segment"; !!}">{!! $segment['departmentName']; !!}</p>
                                </div>-->
                                <h4>Segment Amount:</h4>
                                <div class="form-group">
                                  <input type="number" name="{!! $segment['departmentSlug'].'SegmentAmount'; !!}" class="form-control segmentAmount {!! $segment['departmentSlug'].'SegmentAmount'; !!}" min="1" max="99999" value="0" />
                                  <div class="error" id="error_{!! $segment['departmentSlug'].'SegmentAmount'; !!}"></div>
                                </div>
                                <h4>Segment Description:</h4>
                                <div class="form-group">
                                  <textarea name="{!! $segment['departmentSlug'].'SegmentDescription'; !!}" class="form-control"></textarea>
                                  <div class="error" id="error_{!! $segment['departmentSlug'].'SegmentDescription'; !!}"></div>
                                </div>
                            	<hr />
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
            
            <h4>Total Amount:</h4>
            <div class="form-group">
              <input type="number" name="invoiceTotalAmount" class="form-control invoiceTotalAmount" value="0" readonly="readonly" min="1" max="9999">
              <div class="error" id="error_invoiceTotalAmount"></div>
            </div>
            <h4>Paid Amount:</h4>
            <div class="form-group">
              <input type="text" name="invoicePaidAmount" class="form-control" >
              <div class="error" id="error_invoicePaidAmount"></div>
            </div>
            <h4>Description:</h4>
            <div class="form-group">
              <textarea name="invoiceDescription" class="form-control"></textarea>
              <div class="error" id="error_invoiceDescription"></div>
            </div>
            <!-- Assign to Users -->
            <h4>Status:</h4>
            <div class="form-group">
              <select class="form-control" id="invoiceStatus" name="invoiceStatus">
              	<option value="0">InActive</option>
                <option value="1">Active</option>                                                               
              </select>
              <div class="error" id="error_invoiceStatus"></div>
            </div>
            <!-- Assign to Users --> 
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="clientId" value="{!! $id; !!}">
          <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}">
          <input type="hidden" name="invoice_post" value="1">
          <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
          <button type="submit" class="btn btn-success">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Invoice Form End -->
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
	$('form[name="opportunityForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $id !!}',$(this));
	});
	
	$('form[name="invoiceForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $id !!}',$(this));
	});
	
	$('.deleteOpportunity').click(function(){
		var id = $(this).data('id');
		if(confirm("Are you sure about deleting this?")){
			$.ajax({
			  type: "POST",
			  url: "../remove-opportunity",
			  data: {"id":id},
			}).done(function() {
				alert("Record removed successfully!");
			 	location.reload();
			});
		}
	});
	
	$('.invoiceType').on('change', function(){
		var projects = '{!! json_encode($projectsArray); !!}';
		projects = JSON.parse(projects);
		var type = $(this).val();
		if(type == 1){
			$('.projectId').html('');
			$('.projectId').append('<option value="0">::No Projects::</option>');
		}else{
			$('.projectId').html('');
			$('.projectId').append('<option value="">::Select Project::</option>');
			/*$.each( projects, function( key, value ) {
			  alert( key + ": " + value );
			});*/
			Object.keys(projects).forEach(function(key){
				$('.projectId').append('<option value="'+key+'">'+projects[key]+'</option>');
			});
		}
	});
	
	$('.invoiceSegments').on('change', function(){
		var departments = '{!! json_encode($segments); !!}';
		departments = JSON.parse(departments);
		$.each(departments, function(key, value){
			$('.'+value['departmentSlug']+'Div').css('display', 'none');
		});
		var segments = $(this).val();
		segments = segments.toString();
		if(segments.match(/,/)){
			var sgArr = segments.split(',');
			for(var i=0;i<=sgArr.length-1;i++){
				var sgKey = sgArr[i];
				var sgName = departments[sgKey]['departmentSlug'];
				$('.'+sgName+'Div').css('display', 'block');
			}
		}else{
			var sgKey = $(this).val();
			var sgName = departments[sgKey]['departmentSlug'];
			$('.'+sgName+'Div').css('display', 'block');
		}
		
	});
	
	$('.segmentAmount').on('change', function(){
		var amount = newAmount = parseInt(0);
		var departments = '{!! json_encode($segments); !!}';
		departments = JSON.parse(departments);
		$.each(departments, function(key, value){
			if(value['departmentSlug'] != "sales"){
				newAmount = $('.'+value['departmentSlug']+'SegmentAmount').val();
			}
			amount += parseInt(newAmount);
		});
		/*var logoAmount = $('logoSegmentAmount').val();
		var logoAmount = $('websiteSegmentAmount').val();
		var logoAmount = $('videoSegmentAmount').val();
		if(amount == ""){amount = 0}
		var invoiceAmount = $('.invoiceTotalAmount').val();
		if(invoiceAmount == ""){invoiceAmount = 0}
		invoiceAmount = parseInt(invoiceAmount) + parseInt(amount);*/
		$('.invoiceTotalAmount').val(amount);
	});
	
});

</script>