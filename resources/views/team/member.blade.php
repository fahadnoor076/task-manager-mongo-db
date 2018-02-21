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
                                            <h3>{!! $p_title !!} Management</h3>
                                            <!--<p>Will be followed for all Departments</p>-->
                                        </div>
                                        {{--*/ $teamLeadId = (isset($teamLead ))? $teamLead : ""; /*--}}
                                        <form name="roleForm" class="form-horizontal" method="post">
                                            <div class="form-group" id="error_teamLeadId">
                                                <div class="row mrgn-all-none">
                                                    <label for="teamLeadId" class="col-sm-2 control-label">Team Lead</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<div class="select-box">
                                            				<div class="selectbox-wrap mrgn-b-lg">
                                                				<div class="selectbox">
                                                                    <select class="form-control" id="teamLeadId" name="teamLeadId">
                                                                        <option value="">::Select Team Lead::</option>
                                                                        @if($departmentMembers > 0)
                                                                            @foreach($departmentMembers as $key => $member)
                                                                                {{--*/ $sel = ($teamLeadId == $key)? "selected='selected'" : "" /*--}}
                                                                                <option value="{!! $key !!}" {!! $sel !!}>{!! $member['userName'] !!}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                               </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="error_fkUserId">
                                                <div class="row mrgn-all-none">
                                                    <label for="fkUserId" class="col-sm-2 control-label">Team Members</label>
                                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                                    	<select class="form-control chosen-select" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>
                                                            <option value="">::Select Team Members::</option>
                                                            @if($departmentMembers > 0)
                                                                @foreach($departmentMembers as $key => $member)
                                                                	@if($teamLeadId != $key)
                                                                        {!! $sel = (in_array($key, $teamMembers))? "selected='selected'" : "" !!}
                                                                        <option value="{!! $key !!}" {!! $sel !!}>{!! $member['userName'] !!}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
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
var config = {
  '.chosen-select'           : {},
  '.chosen-select-deselect'  : {allow_single_deselect:true},
  '.chosen-select-no-single' : {disable_search_threshold:10},
  '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
  '.chosen-select-width'     : {width:"95%"}
}
</script>
@include(DIR_ADMIN.'footer')