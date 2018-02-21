@include(DIR_ADMIN.'header')

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
                                            <h3>User Modules</h3>
                                            <!--<p>Will be followed for all Departments</p>-->
                                        </div>
                                        <form name="roleForm" class="form-horizontal" method="post">
                                        	<div class="form-group" id="error_clientName">
                                                <div class="row mrgn-all-none">
                                                    <label for="clientName" class="col-sm-2 control-label">Client Name</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="clientName" class="form-control" id="branclientNamedName" placeholder="Client Name" value="{!! $data[$id]['clientName']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_clientEmail">
                                                <div class="row mrgn-all-none">
                                                    <label for="clientEmail" class="col-sm-2 control-label">Client Email</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="clientEmail" class="form-control" id="clientEmail" placeholder="Client Email" value="{!! $data[$id]['clientEmail']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_clientPhone">
                                                <div class="row mrgn-all-none">
                                                    <label for="clientPhone" class="col-sm-2 control-label">Client Phone</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="clientPhone" class="form-control" id="clientPhone" placeholder="Client Phone" value="{!! $data[$id]['clientPhone']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_clientCompany">
                                                <div class="row mrgn-all-none">
                                                    <label for="clientCompany" class="col-sm-2 control-label">Client Company</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="clientCompany" class="form-control" id="clientCompany" placeholder="Client Company" value="{!! $data[$id]['clientCompany']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_clientAddress">
                                                <div class="row mrgn-all-none">
                                                    <label for="clientAddress" class="col-sm-2 control-label">Client Address</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="clientAddress" class="form-control" id="clientAddress" placeholder="Client Address" value="{!! $data[$id]['clientAddress']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_fkBrandId">
                                                <div class="row mrgn-all-none">
                                                    <label for="fkBrandId" class="col-sm-2 control-label">Tagged Brands</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<select class="form-control chosen-select" id="fkBrandId" name="fkBrandId[]" multiple>
                                                            @if($brands > 0)
                                                            	@foreach($brands as $key => $brand)
                                                                	{{--*/ $sel = (in_array($key, $taggedBrands))? "selected='selected'" : "" /*--}}
                                                                	<option value="{!! $key !!}" {!! $sel !!}>{!! $brand['brandName'] !!}</option>
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
                                                            <option value="">Select Status</option>
                                                            <option value="1" {!! $data[$id]['isActive'] == '1' ? 'selected="selected"' : '' !!}>Active</option>
                                                            <option value="0" {!! $data[$id]['isActive'] == '0' ? 'selected="selected"' : '' !!}>InActive</option>
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
<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="roleForm"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		//$("div[id^=error_]").removeClass("has-error").addClass("test");
		$("div[id^=error_]").removeClass("has-error");
		// validate form
		return jsonValidate('{!! $id !!}',$(this));
	});
	//CKEDITOR.replace('cat_content');
	
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
	$(".chosen-select").chosen();
});
</script>
@include(DIR_ADMIN.'footer')