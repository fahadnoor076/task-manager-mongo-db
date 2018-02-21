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
                                            <div class="form-group" id="error_userModuleName">
                                                <div class="row mrgn-all-none">
                                                    <label for="userModuleName" class="col-sm-2 control-label">Module Name</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="text" name="userModuleName" class="form-control" id="userModuleName" placeholder="Module Name" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_parentId">
                                            	<div class="row mrgn-all-none">
                                            		<label for="parentId" class="col-sm-2 control-label">Parent Module</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="parentId" name="parentId">
                                                            <option value="">Select Parent</option>
                                                            <option value="0">::No Parent::</option>
                                                            @if(!empty($parentModules))
                                                            	@foreach($parentModules as $key => $module)
                                                                	<option value="{!! $key !!}">{!! $module['userModuleName'] !!}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                        		</div>
                                            </div>
                                            <div class="form-group" id="error_iconClass">
                                                <div class="row mrgn-all-none">
                                                    <label for="iconClass" class="col-sm-2 control-label">Icon Class</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<input type="text" name="iconClass" class="form-control" id="iconClass" placeholder="Icon Class" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_isActive">
                                                <div class="row mrgn-all-none">
                                                    <label for="isActive" class="col-sm-2 control-label">Is Active</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="isActive" name="isActive">
                                                            <option value="">Select Status</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">InActive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_showInMenu">
                                                <div class="row mrgn-all-none">
                                                    <label for="showInMenu" class="col-sm-2 control-label">Show In Menu</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <select class="form-control" id="showInMenu" name="showInMenu">
                                                            <option value="">Select One</option>
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_orderId">
                                                <div class="row mrgn-all-none">
                                                    <label for="orderId" class="col-sm-2 control-label">Navigation Order</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <input type="number" step="0.01" min="1" name="orderId" class="form-control" id="orderId" placeholder="Navigation Order" value="1" />
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
		return jsonValidate('{!! $route_action !!}',$(this));
	});
	//CKEDITOR.replace('cat_content');
	
});
</script> 
@include(DIR_ADMIN.'footer')