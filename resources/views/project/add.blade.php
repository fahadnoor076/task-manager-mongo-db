@include(DIR_ADMIN.'header')

{{--*/
// statuses
$admin_statuses = config("constants.ADMIN_STATUSES");
/*--}}
<style>
.error{
	color:#f00;	
}
</style>
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
                                            <h3>Add {!! $module !!}</h3>
                                            <!--<p>Will be followed for all Departments</p>-->
                                        </div>
                                        <form name="dataForm" class="form-horizontal" method="post">
                                        	<!-- CLIENT -->
                                            <div class="clientDiv">
                                                <div class="form-group" id="error_fkClientId">
                                                    <div class="row mrgn-all-none">
                                                        <label for="fkClientId" class="col-sm-2 control-label">Account</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="text-left">
                                                               <select class="form-control" id="fkClientId" name="fkClientId">
                                                                    <option value="">::Select Account::</option>
                                                                    @if($clients > 0)
                                                                        @foreach($clients as $key => $client)
                                                                            @if(!empty($brandClients))
                                                                                @if(in_array($client['_id'], $brandClients))
                                                                                    <option value="{!! $key !!}">{!! $client['clientName'] !!}</option>
                                                                                @endif
                                                                            @else
                                                                                <option value="{!! $key !!}">{!! $client['clientName'] !!}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="text-right">
                                                                <span>OR</span>
                                                                <a href="#addClient" class="btn btn-default" data-toggle="modal">Add Client</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                        	</div>
                                            <!-- CLIENT END -->
                                        	<!-- INVOICE -->
                                            <div class="invoiceDiv">
                                                <div class="form-group" id="error_invoiceId">
                                                    <div class="row mrgn-all-none">
                                                        <label for="invoiceId" class="col-sm-2 control-label">Select Invoice</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                        	<div class="text-left">
                                                               <select class="form-control" id="invoiceId" name="invoiceId">
                                                                    <option value="">::Select Invoice::</option>
                                                                    @if(isset($invoices) && $invoices > 0)
                                                                        @foreach($invoices['result'] as $invoice)
                                                                            <option value="{!! $invoice['_id']; !!}">{!! $invoice['invoiceNumber']; !!}</option>
                                                                        @endforeach
                                                                    @endif
                                                               </select>
                                                            </div>
                                                            <div class="text-right">
                                                                <span>OR</span>
                                                                <a href="#invoiceAdd" class="btn btn-default" data-toggle="modal">Add Invoice</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        	</div>
                                            <!-- INVOICE END -->
                                            <div class="projectDiv">
                                                <div class="form-group" id="error_projectName">
                                                    <div class="row mrgn-all-none">
                                                        <label for="projectName" class="col-sm-2 control-label">Project Name</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                           <input type="text" class="form-control" id="projectName" name="projectName" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_userEmail">
                                                    <div class="row mrgn-all-none">
                                                        <label for="userEmail" class="col-sm-2 control-label">Project Description</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <textarea class="form-control" name="projectDescription"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_userPassword">
                                                    <div class="row mrgn-all-none">
                                                        <label for="userPassword" class="col-sm-2 control-label">Project Priority</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="form-control" name="projectPriority">
                                                                <option value="">::Select Priority::</option>
                                                                <option value="prospect">Prospect</option>
                                                                <option value="confirm-upsell">Confirm Upsell</option>
                                                                <option value="high-priority">High Priority</option>
                                                                <option value="regular">Regular</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_projectTotalCost">
                                                    <div class="row mrgn-all-none">
                                                        <label for="projectTotalCost" class="col-sm-2 control-label">Project Total Cost</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">$</div>
                                                                <input type="number" class="form-control" id="projectTotalCost" name="projectTotalCost" placeholder="Total Cost">
                                                                <div class="input-group-addon">.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_projectPendingCost">
                                                    <div class="row mrgn-all-none">
                                                        <label for="projectPendingCost" class="col-sm-2 control-label">Project Pending Cost</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">$</div>
                                                                <input type="number" class="form-control" id="projectPendingCost" name="projectPendingCost" placeholder="Pending Cost">
                                                                <div class="input-group-addon">.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_fkBrandId">
                                                    <div class="row mrgn-all-none">
                                                        <label for="fkBrandId" class="col-sm-2 control-label">Brand</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="form-control" name="fkBrandId">
                                                                <option value="">::Select Brand::</option>
                                                                @if($brands > 0)
                                                                    @foreach($brands as $key => $brand)
                                                                        @if(!empty($brandsTagged))
                                                                            @if(in_array($key, $brandsTagged))
                                                                                <option value="{!! $key !!}">{!! $brand['brandName'] !!}</option>
                                                                            @endif
                                                                        @else
                                                                            <option value="{!! $key !!}">{!! $brand['brandName'] !!}</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_fkSegmentIds">
                                                    <div class="row mrgn-all-none">
                                                        <label for="fkSegmentIds" class="col-sm-2 control-label">Project Segments</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="form-control chosen-select" id="fkSegmentIds" name="fkSegmentIds[]" data-placeholder="Select Segment(s)" multiple>
                                                                @if($segments > 0)
                                                                    @foreach($segments as $key => $segment)
                                                                        @if($segment['departmentSlug'] != "sales")
                                                                            <option value="{!! $key !!}">{!! $segment['departmentName'] !!}</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="error_isActive" style="display:none;">
                                                    <div class="row mrgn-all-none">
                                                        <label for="isActive" class="col-sm-2 control-label">Is Active</label>
                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                            <select class="form-control" id="isActive" name="isActive">
                                                                <option value="">::Select Status::</option>
                                                                <option value="1" selected="selected">Active</option>
                                                                <option value="0">In-Active</option>
                                                            </select>
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
                                        	</div>
                                        </form>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Modal Specs Form -->
<div id="addClient" class="modal fade" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Add Client</h4>
        </div>
        <form name="clientForm" class="modal-form" method="post">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>*Client Name:</h4>
                        <div class="form-group">
                        <input type="text" name="clientName" class="form-control" value="">
                        <div class="error" id="error_clientName"></div>
                        </div>
                        <h4>*Client Email:</h4>
                        <div class="form-group">
                        <input type="text" name="clientEmail" class="form-control" value="">
                        <div class="error" id="error_clientEmail"></div>
                        </div>
                        <h4>*Client Phone:</h4>
                        <div class="form-group">
                        <input type="text" name="clientPhone" class="form-control" value="">
                        <div class="error" id="error_clientPhone"></div>
                        </div>
                        <h4>*Client Company:</h4>
                        <div class="form-group">
                        <input type="text" name="clientCompany" class="form-control" value="">
                        <div class="error" id="error_clientCompany"></div>
                        </div>
                        <h4>Client Address:</h4>
                        <div class="form-group">
                        <input type="text" name="clientAddress" class="form-control" value="">
                        <div class="error" id="error_clientAddress"></div>
                        </div>
                        <h4>*Tagged Brands:</h4>
                        <div class="form-group">
                        <select class="form-control" id="fkBrandId" name="fkBrandId[]" multiple>
                            @if($brands > 0)
                                @foreach($brands as $key => $brand)
                                    @if(!empty($brandsTagged))
                                        @if(in_array($key, $brandsTagged))
                                            <option value="{!! $key !!}">{!! $brand['brandName'] !!}</option>
                                        @endif
                                    @else
                                        <option value="{!! $key !!}">{!! $brand['brandName'] !!}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        <div class="error" id="error_fkBrandId"></div>
                        </div>
                        <h4>Is Active:</h4>
                        <div class="form-group">
                        <select class="form-control" name="isActive">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">InActive</option>
                        </select>
                        <div class="error" id="error_isActive"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
                <input type="hidden" name="client_post" value="1" />
                <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
                <button type="submit" class="btn btn-success">Save changes</button>
            </div>
        </form>
    </div>
    </div>
</div>
<!-- Modal Specs Form End -->

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
            <div class="form-group">
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
              <input type="text" name="invoicePaidAmount" class="form-control invoicePaidAmount" >
              <div class="error" id="error_invoicePaidAmount"></div>
            </div>
            <h4>Description:</h4>
            <div class="form-group">
              <textarea name="invoiceDescription" class="form-control"></textarea>
              <div class="error" id="error_invoiceDescription"></div>
            </div>
            <!-- Assign to Users -->
            <h4>Status:</h4>
            <div class="form-group" style="display:none;">
              <select class="form-control" id="invoiceStatus" name="invoiceStatus">
              	<option value="0">InActive</option>
                <option value="1" selected="selected">Active</option>                                                               
              </select>
              <div class="error" id="error_invoiceStatus"></div>
            </div>
            <!-- Assign to Users --> 
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="clientId" id="modalClientId" value="">
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
<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="dataForm"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		//return jsonValidate('{!! $route_action !!}',$(this));
		return jsonValidate('{!! $route_action !!}',$(this));
	});
	$('form[name="clientForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		return jsonValidate('{!! $route_action !!}',$(this));
	});
	$('form[name="invoiceForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		return jsonValidate('{!! $route_action !!}',$(this));
	});
	
	//CKEDITOR.replace('cat_content');
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
	$(".chosen-select").chosen();
	
	$('.invoiceDiv').css('display', 'none');
	
	$('.projectDiv').css('display', 'none');
	
	$('#fkClientId').on('change', function(){
		$('.invoiceDiv').css('display', 'none');
		var clientId = $(this).val();
		if(clientId != ""){
			$('#modalClientId').val(clientId);
			$.ajax({
			  type: "POST",
			  url: "account-invoices",
			  data: {"clientId":clientId},
			}).done(function(data) {
				if(Object.keys(data.invoices).length > 0){
					$('.invoiceDiv').css('display', 'block');
					$('#invoiceId').html('');
					$("#invoiceId").append('<option value="">::Select Invoice::</option>');
					$(data.invoices).each(function(key, value){
						$("#invoiceId").append('<option value="'+value._id.$id+'">'+value.invoiceNumber+'</option>');
					});
				}else{
					$('.invoiceDiv').css('display', 'block');
					$('#invoiceId').html('');
					$("#invoiceId").append('<option value="">::No Invoice::</option>');
				}
			});
		}
	});
	
	$('#invoiceId').on('change', function(){
		$('.projectDiv').css('display', 'none');
		var invoice = $(this).val();
		if(invoice != ""){
			$.ajax({
			  type: "POST",
			  url: "invoice-details",
			  data: {"invoiceId":invoice},
			}).done(function(data) {
				$('.projectDiv').css('display', 'block');
				$('#projectTotalCost').val(data.invoice.invoiceTotalAmount);
				$('#projectTotalCost').attr('readonly', true);
				var paidAmount = data.invoice.invoicePaidAmount;
				var pendingAmount = data.invoice.invoiceTotalAmount - paidAmount;
				$('#projectPendingCost').val(pendingAmount);
				$('#projectPendingCost').attr('readonly', true);
				//Client
				$("#fkClientId option").each(function(){
					if($(this).val()==data.invoice.fkClientId.$id){
						$(this).attr("selected","selected");    
					}
				});
				//Segments
				$("#fkSegmentIds option").html('');
				$(data.invoice.invoiceArray).each(function(key, value){
					$("#fkSegmentIds").append('<option value="'+value.fkSegmentId.$id+'" selected="selected">'+value.segmentType+'</option>');
					/*$("#fkSegmentIds option").each(function(){
						if($(this).val()==value.fkSegmentId.$id){
							$(this).attr("selected","selected");    
						}
					});*/
				});
				$('#fkSegmentIds').trigger('chosen:updated');
			});
		}
	});
	
	$('.invoiceType').on('change', function(){
		var clientId = $('#modalClientId').val();
		if(clientId != ""){
			if($(this).val() == "1"){
				$('.projectId').html('');
				$('.projectId').append('<option value="0">::No Projects::</option>');
			}else{
				$.ajax({
				  type: "POST",
				  url: "client-projects",
				  data: {"clientId":clientId},
				}).done(function(data) {
					if(Object.keys(data.projectsArray).length > 0){
						$('.projectId').html('');
						$('.projectId').append('<option value="">::Select Project::</option>');
						$(data.projectsArray).each(function(key, value){
							$(".projectId").append('<option value="'+value._id.$id+'">'+value.projectName+'</option>');
						});
					}else{
						$('.projectId').html('');
						$('.projectId').append('<option value="">::No Project::</option>');
					}
				});	
			}
		}
		
	});
	
	$('.invoiceSegments').on('change', function(){
		var departments = '{!! json_encode($segments); !!}';
		departments = JSON.parse(departments);
		$.each(departments, function(key, value){
			$('.'+value['departmentSlug']+'Div').css('display', 'none');
            /*if($('.segmentsInfo').find('.'+value['departmentSlug']+'Div').is(":visible")){
                $('.'+value['departmentSlug']+'SegmentAmount').val();
            }*/
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
        $.each(departments, function(key, value){
            if($('.segmentsInfo').find('.'+value['departmentSlug']+'Div').is(":hidden")){
                $('.'+value['departmentSlug']+'SegmentAmount').val(0);
            }
        });
        $('.segmentAmount').trigger('blur');
	});
	$('.segmentAmount').on('blur', function(){
		var amount = newAmount = parseFloat(0);
		var departments = '{!! json_encode($segments); !!}';
		departments = JSON.parse(departments);
		$.each(departments, function(key, value){
			if(value['departmentSlug'] != "sales"){
			    if($('.segmentsInfo').find('.'+value['departmentSlug']+'Div').is(":visible")){
                    newAmount = $('.'+value['departmentSlug']+'SegmentAmount').val();
                    amount += parseFloat(newAmount);
                }
			}
		});
		$('.invoiceTotalAmount').val(amount);
	});
	
	$('.invoicePaidAmount').on('blur', function(){
		$('#error_invoicePaidAmount').html('');
		var total = $('.invoiceTotalAmount').val();
		if(total != ""){
			var paid = $('.invoicePaidAmount').val();
			if(parseInt(paid) > parseInt(total)){
				$('#error_invoicePaidAmount').html('Total amount should not be less than Paid amount!');
				$('#invoiceAdd').find('.btn-success').prop('disabled', true);
			}else{
                $('#error_invoicePaidAmount').html('');
                $('#invoiceAdd').find('.btn-success').prop('disabled', false);
            }
		}
	});
});
var config = {
  '.chosen-select'           : {},
  '.chosen-select-deselect'  : {allow_single_deselect:true},
  '.chosen-select-no-single' : {disable_search_threshold:10},
  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
  '.chosen-select-width'     : {width:"95%"}
}
</script>
@include(DIR_ADMIN.'footer')