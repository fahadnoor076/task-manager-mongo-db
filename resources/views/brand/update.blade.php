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
                                            <div class="form-group" id="error_brandName">
                                                <div class="row mrgn-all-none">
                                                    <label for="brandName" class="col-sm-2 control-label">Brand Name</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="brandName" class="form-control" id="brandName" placeholder="Brand Name" value="{!! $data[$id]['brandName']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_brandUrl">
                                                <div class="row mrgn-all-none">
                                                    <label for="brandUrl" class="col-sm-2 control-label">Brand URL</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="brandUrl" class="form-control" id="brandUrl" placeholder="Brand URL" value="{!! $data[$id]['brandUrl']; !!}" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_startTime">
                                                <div class="row mrgn-all-none">
                                                    <label for="startTime" class="col-sm-2 control-label">Shift Start Time</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<select class="form-control" id="startTime" name="startTime">
                                                            <option value="">Select Start Time</option>
                                                            <option value="12" {!! $data[$id]['startTime'] == '12' ? 'selected="selected"' : '' !!}>12:00pm</option>
                                                            <option value="20" {!! $data[$id]['startTime'] == '20' ? 'selected="selected"' : '' !!}>08:00pm</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_endTime">
                                                <div class="row mrgn-all-none">
                                                    <label for="endTime" class="col-sm-2 control-label">Shift End Time</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<select class="form-control" id="endTime" name="endTime">
                                                            <option value="">Select End Time</option>
                                                            <option value="21" {!! $data[$id]['endTime'] == '21' ? 'selected="selected"' : '' !!}>09:00pm</option>
                                                            <option value="29" {!! $data[$id]['endTime'] == '29' ? 'selected="selected"' : '' !!}>05:00am</option>
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
@include(DIR_ADMIN.'footer')