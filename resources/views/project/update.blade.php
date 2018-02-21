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
                                            <h3>{!! $p_title !!}</h3>
                                            <!--<p>Salsoft Technologies</p>-->
                                        </div>
                                        <form name="dataForm" class="form-horizontal" method="post">
                                        	<div class="form-group" id="error_projectName">
                                                <div class="row mrgn-all-none">
                                                    <label for="projectName" class="col-sm-2 control-label">Project Name</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                       <input type="text" class="form-control" id="projectName" name="projectName" value="{!! $data[$id]['projectName'] !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userEmail">
                                                <div class="row mrgn-all-none">
                                                    <label for="userEmail" class="col-sm-2 control-label">Project Description</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<textarea class="form-control" name="projectDescription">{!! $data[$id]['projectDescription'] !!}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_userPassword">
                                                <div class="row mrgn-all-none">
                                                    <label for="userPassword" class="col-sm-2 control-label">Project Priority</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<select class="form-control" name="projectPriority">
                                                        	<option value="">::Select Priority::</option>
                                                            <option value="prospect" {!! ($data[$id]['projectPriority'] == 'prospect')? "selected='selected'" : "" !!}>Prospect</option>
                                                            <option value="confirm-upsell" {!! ($data[$id]['projectPriority'] == 'confirm-upsell')? "selected='selected'" : "" !!}>Confirm Upsell</option>
                                                            <option value="high-priority" {!! ($data[$id]['projectPriority'] == 'high-priority')? "selected='selected'" : "" !!}>High Priority</option>
                                                            <option value="regular" {!! ($data[$id]['projectPriority'] == 'regular')? "selected='selected'" : "" !!}>Regular</option>
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
                                                            <input type="number" class="form-control" id="InputAmount-1" name="projectTotalCost" placeholder="Total Cost" value="{!! $data[$id]['projectTotalCost'] !!}">
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
                                                            <input type="number" class="form-control" id="InputAmount-1" name="projectPendingCost" placeholder="Pending Cost" value="{!! $data[$id]['projectPendingCost'] !!}">
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
                                                                	{!! $sel = ($data[$id]['fkBrandId']->{'$id'} == $key)? "selected='selected'" : "" !!}
                                                                	@if(!empty($brandsTagged))
                                                                    	@if(in_array($key, $brandsTagged))
                                                                        	<option value="{!! $key; !!}" {!! $sel; !!}>{!! $brand['brandName']; !!}</option>
                                                                        @endif
                                                                    @else
                                                                    	<option value="{!! $key; !!}" {!! $sel; !!}>{!! $brand['brandName']; !!}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_fkClientId">
                                                <div class="row mrgn-all-none">
                                                    <label for="fkClientId" class="col-sm-2 control-label">Client</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<div class="text-left">
                                                           <select class="form-control" id="fkClientId" name="fkClientId">
                                                                <option value="">::Select Client::</option>
                                                                @if($clients > 0)
                                                                    @foreach($clients as $key => $client)
                                                                        {!! $sel = ($data[$id]['fkClientId']->{'$id'} == $key)? "selected='selected'" : "" !!}
                                                                        @if(!empty($brandClients))
                                                                            @if(in_array($client['_id'], $brandClients))
                                                                                <option value="{!! $key !!}" {!! $sel !!}>{!! $client['clientName'] !!}</option>
                                                                            @endif
                                                                        @else
                                                                            <option value="{!! $key !!}" {!! $sel !!}>{!! $client['clientName'] !!}</option>
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
                                            <div class="form-group" id="error_fkSegmentIds">
                                                <div class="row mrgn-all-none">
                                                    <label for="fkSegmentIds" class="col-sm-2 control-label">Project Segments</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<select class="form-control chosen-select" id="fkSegmentIds" name="fkSegmentIds[]" data-placeholder="Select Segment(s)" multiple>
                                                            @if($segments > 0)
                                                                @foreach($segments as $key => $segment)
                                                                	@if($segment['departmentSlug'] != "sales")
                                                                    	{!! $sel = (in_array($key, $projectSegments))? "selected='selected'" : "" !!}
                                                                        <option value="{!! $key !!}" {!! $sel !!}>{!! $segment['departmentName'] !!}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_isActive">
                                            	<div class="row mrgn-all-none">
                                            		<label for="isActive" class="col-sm-2 control-label">Is Active</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="isActive" name="isActive">
                                                            <option value="">::Select Status::</option>
                                                            <option value="1" {!! ($data[$id]['isActive'] == '1')? "selected='selected'" : "" !!}>Active</option>
                                                            <option value="0" {!! ($data[$id]['isActive'] == '0')? "selected='selected'" : "" !!}>In-Active</option>
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
		return jsonValidate('{!! $id !!}',$(this));
	});
	$('form[name="clientForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		//return jsonValidate('{!! $route_action !!}',$(this));
		return jsonValidate('{!! $id !!}',$(this));
	});
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
	$(".chosen-select").chosen();
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