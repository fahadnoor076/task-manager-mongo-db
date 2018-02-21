@include(DIR_ADMIN.'header')

{{--*/
// statuses
$admin_statuses = config("constants.ADMIN_STATUSES");
/*--}}
<style>
.error{
	color: #f00;
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
                            	<h3>{!! $module !!} Segments</h3>
                                 	<!--<p></p>-->
                           	</div>
                            
                            <div class="typography-widget col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <h3>{!! $project['projectName'] !!}</h3>
                                        <p>Priority: <b>{!! ucfirst($project['projectPriority']) !!}</b></p>
                                    </div>
                                    <div class="des-style">
                                        <dl> <dt class="mrgn-b-xs">Project Description</dt>
                                            <dd>{!! $project['projectDescription'] !!}</dd>
                                            
                                            <!--<dt class="mrgn-b-xs">Euismod</dt>
                                            <dd>Donec id elit non mi porta gravida at eget metus.</dd>-->
                                        </dl>
                                    </div>
                                    <hr>
                                    <div class="des-style-2">
                                        <dl class="dl-horizontal">
                                        	<dt class="mrgn-b-xs">Client</dt>
                                            	<dd>{!! $client['clientName'] !!}</dd>
                                            <dt class="mrgn-b-xs">Start Date</dt>
                                            	<dd>{!! $project['addedAt'] !!}</dd>
                                            <dt class="mrgn-b-xs">Created By</dt>
                                            	<dd>{!! $createdBy['userName'] !!}</dd>
                                            @if(isset($privileges['project-price']) && $privileges['project-price'] != 0)
                                            	<dt class="mrgn-b-xs">Project Cost</dt>
                                                    <dd>${!! $project['projectTotalCost'] !!}</dd>
                                                <dt class="mrgn-b-xs">Pending Cost</dt>
                                                    <dd>${!! $project['projectPendingCost'] !!}</dd>
                                            @endif
                                            @if(isset($privileges['project-mark-complete']) && $privileges['project-mark-complete'] != 0)
                                            	<dt class="mrgn-b-xs">Project Status</dt>
                                                    <dd>
                                                    	<div class="col-sm-12 col-md-2">
                                                        	<select class="form-control" name="projectStatus" id="projectStatus">
                                                            	<!-- Calling Helper Function -->
                                                            	{{--*/ $projectStatus = projectStatus(); /*--}}
                                                            	@if(isset($projectStatus) && $projectStatus != "")
                                                                	@foreach($projectStatus as $key => $value)
                                                                    	{!! $sel = ($project['projectStatus'] == $key)? "selected='selected'" : "" !!}
                                                                    	<option value="{!! $key !!}" {!! $sel !!}>{!! $value !!}</option>
                                                                    @endforeach
                                                                @endif
                                                                <!--<option value="active" {!! $sel = ($project['projectStatus'] == 'active') ? "selected='selected'" : "" !!}>Active</option>
                                                                <option value="complete" {!! $sel = ($project['projectStatus'] == 'complete') ? "selected='selected'" : "" !!}>Complete</option>
                                                                <option value="halt" {!! $sel = ($project['projectStatus'] == 'halt') ? "selected='selected'" : "" !!}>Halt</option>
                                                                <option value="refund-cbd" {!! $sel = ($project['projectStatus'] == 'refund-cbd') ? "selected='selected'" : "" !!}>Refund CBD</option>
                                                                <option value="refund-delay" {!! $sel = ($project['projectStatus'] == 'refund-delay') ? "selected='selected'" : "" !!}>Refund Delay</option>
                                                                <option value="refund-quality" {!! $sel = ($project['projectStatus'] == 'refund-quality') ? "selected='selected'" : "" !!}>Refund Quality</option>-->
                                                            </select>
                                                        </div>
                                                    </dd>
                                            @endif
                                            
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            
                            
                      <div class="bst-users-listing clearfix">
                        <div class="row">
                        	@if($projectSegments)
                            	@foreach($projectSegments as $key => $segment)
                                	{{--*/ $segmentSlug = strtolower($segment['segmentArray'][0]['departmentName']) /*--}}
                                	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                <div class="bst-block fw-light">
                                    <div class="clearfix">
                                        <div class="thumb-wid pull-left mrgn-r-md"> <span style="font-size:24px; font-weight: bold;">{!! $segment['segmentArray'][0]['departmentName'] !!}</span> </div>
                                        <div class="thumb-content pull-left">
                                            <h6 class="fw-bold base-dark">{!! $project['projectName'] !!} <span class="label label-xs label-danger mrgn-l-xs">{!! $project['projectPriority'] !!}</span></h6>
                                            <p><span><i class="fa fa-user-secret mrgn-r-xs" aria-hidden="true"></i></span>{!! $client['clientName'] !!}</p>
                                            <p><span><i class="fa fa-calendar mrgn-r-xs" aria-hidden="true"></i></span> {!! ($segment['segmentArray'][0]['updatedAt'] != "")? date("d M, Y H:i:s", strtotime($segment['segmentArray'][0]['updatedAt'])) : date("d M, Y H:i:s", strtotime($segment['segmentArray'][0]['addedAt'])) !!}</p>
                                            {{--*/ $segmentVal = $segmentSlug."Slug"; /*--}}
                                            @if(!isset($segment[$segmentVal]))
                                            	<a href="#{!! $segmentSlug !!}-specs" class="btn btn-xs btn-outline-primary font-xs mrgn-b-xs" data-toggle="modal">Enter Specs</a>
                                            @else
                                            	<a href="#{!! $segmentSlug !!}-specs" class="btn btn-xs btn-outline-primary font-xs mrgn-b-xs" data-toggle="modal">Update Specs</a>
                                            <a href="{!! URL::to(config('/').'segment-details/'.$segment['_id']->{'$id'}) !!}" class="btn btn-xs btn-outline-success font-xs mrgn-b-xs">Details</a>
                                            @endif </div>
                                        <div class="action-links">
                                            <ul class="list-inline">
                                                <li> <a class="text-success" href="javascript:;"><i class="fa fa-star" aria-hidden="true"></i></a> </li>
                                                <li>
                                                    <div class="dropdown"> <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa fa-chevron-down"></i></a>
                                                        <ul class="dropdown-menu dropdown-arrow dropdown-menu-right">
                                                            <li>
                                                                <a href="javascript:;"> <i class="fa fa-eye"></i> <span class="mrgn-l-sm">View</span> </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;"> <i class="fa fa-pencil"></i> <span class="mrgn-l-sm">Edit </span> </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:;"> <i class="fa fa-trash-o"></i> <span class="mrgn-l-sm">Delete</span> </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                                    <!-- Modal Specs Form -->
                                    <div id="{!! $segmentSlug !!}-specs" class="modal fade" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title">{!! ucfirst($segmentSlug)." Specifications" !!}</h4> </div>
                                                    <form name="specsForm" class="modal-form" method="post">
                                                    <div class="modal-body">
                                                    	@if($segmentSlug == 'logo')
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h4>*Exact name to be appeared on Logo:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="logoName" class="form-control" value="{!! (isset($segment['logoName'])) ? $segment['logoName'] : ""; !!}"> 
                                                                        <div class="error" id="error_logoName"></div>
                                                                    </div>
                                                                    <h4>Slogan (if any):</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="logoSlogan" class="form-control" value="{!! (isset($segment['logoSlogan'])) ? $segment['logoSlogan'] : ""; !!}">
                                                                        <div class="error" id="error_logoSlogan"></div>
                                                                    </div>
                                                                    <h4>*Preferred Style of Logo:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="logoStylePreferred" class="form-control" value="{!! (isset($segment['logoStylePreferred'])) ? $segment['logoStylePreferred'] : ""; !!}">
                                                                        <div class="error" id="error_logoStylePreferred"></div>
                                                                    </div>
                                                                    <h4>*Look and Feel:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="logoLookAndFeel" class="form-control" value="{!! (isset($segment['logoLookAndFeel'])) ? $segment['logoLookAndFeel'] : ""; !!}">
                                                                        <div class="error" id="error_logoLookAndFeel"></div>
                                                                    </div>
                                                                    <h4>*No. of Concepts:</h4>
                                                                    <div class="form-group">
                                                                        <input type="number" min="1" max="10" name="logoConcepts" class="form-control" value="{!! (isset($segment['logoConcepts'])) ? $segment['logoConcepts'] : ""; !!}">
                                                                        <div class="error" id="error_logoConcepts"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h4>Any additional comments:</h4>
                                                                    <div class="form-group">
                                                                        <textarea name="logoAdditionalComments" class="form-control">{!! (isset($segment['logoAdditionalComments'])) ? $segment['logoAdditionalComments'] : ""; !!}</textarea>
                                                                        <div class="error" id="error_logoAdditionalComments"></div>
                                                                    </div>
                                                                    <h4>*Industry:</h4>
                                                                    <div class="form-group">
                                                                        <select name="logoIndustry" class="form-control">
                                                                        {{--*/ $industries = logoIndustries(); /*--}}
                                                                        @if(isset($industries) && $industries > 0)
                                                                            @foreach($industries as $ind_key => $value)
                                                                                {!! $sel = (isset($segment['logoIndustry']) && $segment['logoIndustry'] == $ind_key) ? "selected='selected'" : ""; !!}
                                                                                <option value="{!! $ind_key !!}" {!! $sel !!}>{!! $value !!}</option>
                                                                            @endforeach
                                                                        @endif
                                                                        </select>
                                                                        <div class="error" id="error_logoIndustry"></div>
                                                                    </div>
                                                                    <h4>A brief description about your business:</h4>
                                                                    <div class="form-group">
                                                                        <textarea name="logoBusinessDescription" class="form-control">{!! (isset($segment['logoBusinessDescription'])) ? $segment['logoBusinessDescription'] : ""; !!}</textarea>
                                                                        <div class="error" id="error_logoBusinessDescription"></div>
                                                                    </div>
                                                                    <h4>*Your Target Audience:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="logoTargetAudience" class="form-control" value="{!! (isset($segment['logoTargetAudience'])) ? $segment['logoTargetAudience'] : ""; !!}">
                                                                        <div class="error" id="error_logoTargetAudience"></div>
                                                                    </div>
                                                                    <!--<h4>Upload Reference Material:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"> </div>-->
                                                                </div>
                                                            </div>
                                                        @elseif($segmentSlug == 'website')
                                                        	<div class="row">
                                                                <div class="col-md-6">
                                                                    <h4>*Business Name:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteBusinessName" class="form-control" value="{!! (isset($segment['websiteBusinessName'])) ? $segment['websiteBusinessName'] : ""; !!}"> 
                                                                        <div class="error" id="error_websiteBusinessName"></div>
                                                                    </div>
                                                                    <h4>Slogan (if any):</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteSlogan" class="form-control" value="{!! (isset($segment['websiteSlogan'])) ? $segment['websiteSlogan'] : ""; !!}">
                                                                        <div class="error" id="error_websiteSlogan"></div>
                                                                    </div>
                                                                    <h4>*No. of Concepts:</h4>
                                                                    <div class="form-group">
                                                                        <input type="number" min="1" max="10" name="websiteConcepts" class="form-control" value="{!! (isset($segment['websiteConcepts'])) ? $segment['websiteConcepts'] : ""; !!}">
                                                                        <div class="error" id="error_websiteConcepts"></div>
                                                                    </div>
                                                                    <h4>*Look and Feel:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteLookAndFeel" class="form-control" value="{!! (isset($segment['websiteLookAndFeel'])) ? $segment['websiteLookAndFeel'] : ""; !!}">
                                                                        <div class="error" id="error_websiteLookAndFeel"></div>
                                                                    </div>
                                                                    <h4>*Specific Websites you like:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteLike" class="form-control" value="{!! (isset($segment['websiteLike'])) ? $segment['websiteLike'] : ""; !!}">
                                                                        <div class="error" id="error_websiteLike"></div>
                                                                    </div>
                                                                    <h4>*Navigation Menu Preference:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteNavMenuPreference" class="form-control" value="{!! (isset($segment['websiteNavMenuPreference'])) ? $segment['websiteNavMenuPreference'] : ""; !!}">
                                                                        <div class="error" id="error_websiteNavMenuPreference"></div>
                                                                    </div>
                                                                    <h4>Existing Domain Name (if any):</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteExistingDomain" class="form-control" value="{!! (isset($segment['websiteExistingDomain'])) ? $segment['websiteExistingDomain'] : ""; !!}">
                                                                        <div class="error" id="error_websiteExistingDomain"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h4>*Domain Preference:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteDomainPreference" class="form-control" value="{!! (isset($segment['websiteDomainPreference'])) ? $segment['websiteDomainPreference'] : ""; !!}">
                                                                        <div class="error" id="error_websiteDomainPreference"></div>
                                                                    </div>
                                                                    
                                                                    <h4>Any additional comments:</h4>
                                                                    <div class="form-group">
                                                                        <textarea name="websiteAdditionalComments" class="form-control">{!! (isset($segment['websiteAdditionalComments'])) ? $segment['websiteAdditionalComments'] : ""; !!}</textarea>
                                                                        <div class="error" id="error_websiteAdditionalComments"></div>
                                                                    </div>
                                                                    <h4>*Industry:</h4>
                                                                    <div class="form-group">
                                                                        <select name="websiteIndustry" class="form-control">
                                                                        {{--*/ $industries = websiteIndustries(); /*--}}
                                                                        @if(isset($industries) && $industries > 0)
                                                                            @foreach($industries as $ind_key => $value)
                                                                                {!! $sel = (isset($segment['websiteIndustry']) && $segment['websiteIndustry'] == $ind_key) ? "selected='selected'" : ""; !!}
                                                                                <option value="{!! $ind_key !!}" {!! $sel !!}>{!! $value !!}</option>
                                                                            @endforeach
                                                                        @endif
                                                                        </select>
                                                                        <div class="error" id="error_websiteIndustry"></div>
                                                                    </div>
                                                                    <h4>*A brief description about your business:</h4>
                                                                    <div class="form-group">
                                                                        <textarea name="websiteBusinessDescription" class="form-control">{!! (isset($segment['websiteBusinessDescription'])) ? $segment['websiteBusinessDescription'] : ""; !!}</textarea>
                                                                        <div class="error" id="error_websiteBusinessDescription"></div>
                                                                    </div>
                                                                    <h4>*Your Target Audience:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="websiteTargetAudience" class="form-control" value="{!! (isset($segment['websiteTargetAudience'])) ? $segment['websiteTargetAudience'] : ""; !!}">
                                                                        <div class="error" id="error_websiteTargetAudience"></div>
                                                                    </div>
                                                                    <!--<h4>Upload Reference Material:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"> </div>-->
                                                                </div>
                                                            </div>
                                                        @elseif($segmentSlug == 'video')
                                                        	<div class="row">
                                                                <div class="col-md-6">
                                                                    <h4>*Business Name:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="videoBusinessName" class="form-control" value="{!! (isset($segment['videoBusinessName'])) ? $segment['videoBusinessName'] : ""; !!}"> 
                                                                        <div class="error" id="error_videoBusinessName"></div>
                                                                    </div>
                                                                    <h4>Slogan (if any):</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="videoSlogan" class="form-control" value="{!! (isset($segment['videoSlogan'])) ? $segment['videoSlogan'] : ""; !!}">
                                                                        <div class="error" id="error_videoSlogan"></div>
                                                                    </div>
                                                                    <h4>*No. of Concepts:</h4>
                                                                    <div class="form-group">
                                                                        <input type="number" min="1" max="10" name="videoConcepts" class="form-control" value="{!! (isset($segment['videoConcepts'])) ? $segment['videoConcepts'] : ""; !!}">
                                                                        <div class="error" id="error_videoConcepts"></div>
                                                                    </div>
                                                                    <h4>Current Website Address:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="videoWebsiteAddress" class="form-control" value="{!! (isset($segment['videoWebsiteAddress'])) ? $segment['videoWebsiteAddress'] : ""; !!}">
                                                                        <div class="error" id="error_videoWebsiteAddress"></div>
                                                                    </div>
                                                                    <h4>*Preferred Style of Animation:</h4>
                                                                    <div class="form-group">
                                                                        <select name="videoAnimationStyle" class="form-control">
                                                                        {{--*/ $styles = videoAnimationStyles(); /*--}}
                                                                        @if(isset($styles) && $styles > 0)
                                                                            @foreach($styles as $stylekey => $value)
                                                                                {!! $sel = (isset($segment['videoAnimationStyle']) && $segment['videoAnimationStyle'] == $stylekey) ? "selected='selected'" : ""; !!}
                                                                                <option value="{!! $stylekey !!}" {!! $sel !!}>{!! $value !!}</option>
                                                                            @endforeach
                                                                        @endif
                                                                        </select>
                                                                        <div class="error" id="error_videoAnimationStyle"></div>
                                                                    </div>
                                                                    <h4>*Primary Use of the Video:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="videoPrimaryUse" class="form-control" value="{!! (isset($segment['videoPrimaryUse'])) ? $segment['videoPrimaryUse'] : ""; !!}">
                                                                        <div class="error" id="error_videoPrimaryUse"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h4>Any additional comments:</h4>
                                                                    <div class="form-group">
                                                                        <textarea name="videoAdditionalComments" class="form-control">{!! (isset($segment['videoAdditionalComments'])) ? $segment['videoAdditionalComments'] : ""; !!}</textarea>
                                                                        <div class="error" id="error_videoAdditionalComments"></div>
                                                                    </div>
                                                                    <h4>*Industry:</h4>
                                                                    <div class="form-group">
                                                                        <select name="videoIndustry" class="form-control">
                                                                        {{--*/ $industries = videoIndustries(); /*--}}
                                                                        @if(isset($industries) && $industries > 0)
                                                                            @foreach($industries as $ind_key => $value)
                                                                                {!! $sel = (isset($segment['videoIndustry']) && $segment['videoIndustry'] == $ind_key) ? "selected='selected'" : ""; !!}
                                                                                <option value="{!! $ind_key !!}" {!! $sel !!}>{!! $value !!}</option>
                                                                            @endforeach
                                                                        @endif
                                                                        </select>
                                                                        <div class="error" id="error_videoIndustry"></div>
                                                                    </div>
                                                                    <h4>*A brief description about your business:</h4>
                                                                    <div class="form-group">
                                                                        <textarea name="videoBusinessDescription" class="form-control">{!! (isset($segment['videoBusinessDescription'])) ? $segment['videoBusinessDescription'] : ""; !!}</textarea>
                                                                        <div class="error" id="error_videoBusinessDescription"></div>
                                                                    </div>
                                                                    <h4>*Your Target Audience:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" name="videoTargetAudience" class="form-control" value="{!! (isset($segment['videoTargetAudience'])) ? $segment['videoTargetAudience'] : ""; !!}">
                                                                        <div class="error" id="error_videoTargetAudience"></div>
                                                                    </div>
                                                                    <!--<h4>Upload Reference Material:</h4>
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"> </div>-->
                                                                </div>
                                                            </div>
                                                        @else
                                                        
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                    	<input type="hidden" name="orderOf" value="{!! $segmentSlug; !!}" />
                                                    	<input type="hidden" name="segmentId" value="{!! $segment['fkSegmentId']->{'$id'}; !!}" />
                                                        <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
                                                        <input type="hidden" name="do_post" value="1" />
                                                        <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
                                                        <button type="submit" class="btn btn-success">Save changes</button>
                                                    </div>
                                                </form>
                                                <form action="http://localhost/beast/demo/jquery/dropzone.html" class="dropzone dz-clickable" id="dropzone_accepted_files">
                                                	<div class="dz-default dz-message"><span>Upload reference material</span></div>
                                                </form>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Specs Form End -->
                                @endforeach
                            @endif
                            <!-- ChatBox -->
                            <div class="clearfix"></div>
                            <div class="user-chat-wrap bst-block overflow-wrapper">
                            	<h1>Project Board</h1>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3"  style="overflow-y:scroll; max-height:800px;">
                                        <div class="chat-list-sidebar bst-block-no-gutter clearfix">
                                            <div class="search-form pad-all-lg">
                                                <form action="javascript:;" method="post">
                                                    <div class="input-group">
                                                        <input class="form-control" placeholder="Search message" type="text"> <span class="input-group-addon">
                                                        <input type="submit" value="&#xf002;">
                                                    </span> </div>
                                                </form>
                                            </div>
                                            <div>
                                                <ul class="bst-large-tabs nav nav-tabs tabs-left">
                                                    <li class="active">
                                                        <a href="#tab-panel-1" data-toggle="tab">
                                                            <h4 class="mrgn-b-md">Favourite</h4>
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-1.jpg" width="95" height="95" alt="Chat person image"> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Alex Wood</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">2:30</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab-panel-2" data-toggle="tab">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-10.jpg" width="95" height="95" alt="Chat person image"> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Branda South</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">1:00</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab-panel-2" data-toggle="tab">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-2.jpg" width="95" height="95" alt="Chat person image"> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Rose Wilson</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">12:3</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab-panel-2" data-toggle="tab">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-3.jpg" width="95" height="95" alt="Chat person image"> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Bobby Merchant</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">3:30</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="unread-msg">
                                                        <a href="#tab-panel-2" data-toggle="tab">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-4.jpg" width="95" height="95" alt="Chat person image"> <span class="badge badge-success pos-absolute pos-abs-right-top">2</span> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Bob Davidson</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">2:10</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab-panel-2" data-toggle="tab">
                                                            <h4 class="mrgn-b-md">History</h4>
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-5.jpg" width="95" height="95" alt="Chat person image"> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Rosy Benedict</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">4:00</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab-panel-2" data-toggle="tab">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left">
                                                                    <div class="display-b pos-relative"> <img class="img-responsive img-circle" src="assets/img/user-6.jpg" width="95" height="95" alt="Chat person image"> </div>
                                                                </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="clearfix">
                                                                        <div class="thumb-content pull-left">
                                                                            <h6>Tobias White</h6> </div>
                                                                        <div class="thumb-wid pull-right text-right">5:29</div>
                                                                    </div>
                                                                    <p class="mrgn-all-none">"Life is 10% what happens to you and 90% how you react to it." Charles R. Swindoll</p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9" style="overflow-y:scroll; max-height:800px;">
                                        <div class="chat-window pad-l-lg  clearfix">
                                            <div class="tab-content mrgn-b-lg">
                                                <div class="tab-pane active" id="tab-panel-1">
                                                    <div class="bst-messages-list">
                                                        <div class="mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left"> <img class="img-responsive img-circle" src="assets/img/user-7.jpg" width="95" height="95" alt="message reciever image"> </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                        <p class="mrgn-all-none">This would be amazing. I think it can be a revolutionary change.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Loriss Jobs</span> <span class="pull-right display-ib">7:45</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sent-msg mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-l-sm pull-right"> <img class="img-responsive img-circle" src="assets/img/user-8.jpg" width="95" height="95" alt="message reciever image"> </div>
                                                                <div class="thumb-content">
                                                                    <div class="pad-all-md mrgn-b-xs pos-relative msg-wrap fw-light arrow-box-right">
                                                                        <p class="mrgn-all-none">For the last decade, our “Expect Innovations” has focused on these issues in a competitive environment that emphasizes the value we derive from our similarities and our differences. Expect Innovations says that, “Together, we can create and maintain the innovations and latest discoveries + inclusive environment that can create huge differences using today's techniques and achieve a lot more than this.”</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Russe Finn</span> <span class="pull-right display-ib">2:30</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left"> <img class="img-responsive img-circle" src="assets/img/user-9.jpg" width="95" height="95" alt="message reciever image"> </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                        <p>Hey everyone! Check out the latest updates and innovations. It is just unbelievable!</p> <img class="img-responsive mrgn-b-md" src="assets/img/chat-message-image-1.png" width="367" height="276" alt="msg image"> <img class="img-responsive" src="assets/img/chat-message-image-2.png" width="367" height="276" alt="msg image"> </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Aliena Dradford</span> <span class="pull-right display-ib">2:30</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab-panel-2">
                                                    <div class="bst-messages-list">
                                                        <div class="mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left"> <img class="img-responsive img-circle" src="assets/img/user-10.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                        <p class="mrgn-all-none">Veniam odio, nisi expedita ullam sequi, neque repellendus.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Winny Hosuton</span> <span class="pull-right display-ib">9:56</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sent-msg mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-l-sm pull-right"> <img class="img-responsive img-circle" src="assets/img/user-1.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap pos-relative fw-light arrow-box-right">
                                                                        <p class="mrgn-all-none">Consectetur adipisicing elit. Sed deleniti, rerum delectus iusto odit sint ad nulla molestiae eveniet assumenda tempore, eius nisi earum a. Veniam odio, nisi expedita ullam sequi, neque repellendus quidem quasi eius atque laudantium eaque corporis illo obcaecati voluptate mollitia deleniti. Atque necessitatibus ipsam, doloribus fugit.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Loura Specy</span> <span class="pull-right display-ib">7:10</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left"> <img class="img-responsive img-circle" src="assets/img/user-3.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                        <p class="mrgn-all-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Joy Roberts</span> <span class="pull-right display-ib">7:20</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sent-msg mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-right"> <img class="img-responsive img-circle" src="assets/img/user-4.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content">
                                                                    <div class="bg-primary pad-all-md mrgn-b-xs msg-wrap pos-relative fw-light arrow-box-right">
                                                                        <p class="mrgn-all-none">Consectetur adipisicing elit. Tempora laboriosam obcaecati enim totam ratione odit, ipsa ad rerum hic nobis.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Brad Bill</span> <span class="pull-right display-ib">7:03</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left"> <img class="img-responsive img-circle" src="assets/img/user-3.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                        <p class="mrgn-all-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Joy Roberts</span> <span class="pull-right display-ib">7:20</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sent-msg mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-right"> <img class="img-responsive img-circle" src="assets/img/user-4.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content">
                                                                    <div class="bg-primary pad-all-md mrgn-b-xs msg-wrap pos-relative fw-light arrow-box-right">
                                                                        <p class="mrgn-all-none">Consectetur adipisicing elit. Tempora laboriosam obcaecati enim totam ratione odit, ipsa ad rerum hic nobis.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Brad Bill</span> <span class="pull-right display-ib">7:03</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-left"> <img class="img-responsive img-circle" src="assets/img/user-3.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content pull-left">
                                                                    <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                        <p class="mrgn-all-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Joy Roberts</span> <span class="pull-right display-ib">7:20</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="sent-msg mrgn-b-lg">
                                                            <div class="clearfix">
                                                                <div class="thumb-wid mrgn-r-sm pull-right"> <img class="img-responsive img-circle" src="assets/img/user-4.jpg" alt="message reciever image" width="95" height="95"> </div>
                                                                <div class="thumb-content">
                                                                    <div class="bg-primary pad-all-md mrgn-b-xs msg-wrap pos-relative fw-light arrow-box-right">
                                                                        <p class="mrgn-all-none">Consectetur adipisicing elit. Tempora laboriosam obcaecati enim totam ratione odit, ipsa ad rerum hic nobis.</p>
                                                                    </div>
                                                                    <div class="clearfix"> <span class="pull-left base-dark display-ib">Brad Bill</span> <span class="pull-right display-ib">7:03</span> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- tab-content closed -->
                                            <div class="send-msg-form col-sm-12 col-xs-12 col-md-12 col-lg-12 pull-right pad-all-none">
                                                <form action="javascript:;" method="post">
                                                    <div class="form-group clearfix">
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-11 pad-all-none">
                                                            <textarea class="pad-all-sm form-control mrgn-b-sm" name="message" placeholder="Write a message"></textarea>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-1 pad-all-none">
                                                            <input class="btn btn-primary btn-lg btn-block" name="send_message" value="Send" type="submit"> </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="file-field-label">
                                                            <input type="file" name="send_file"> <span><i class="fa fa-paperclip mrgn-r-sm" aria-hidden="true"></i>
                                                            </span> Attach Files </label>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- chat-window closed -->
                                    </div>
                                </div>
                                <!-- row closed -->
                   			</div>
	
                            <!-- Chatbox End -->
                        </div>
                    </div>
                
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
        

<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="specsForm"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		//$("div[id^=error_]").removeClass("has-error");
		// validate form
		//return jsonValidate('{!! $route_action !!}',$(this));
		return jsonValidate('{!! $id !!}',$(this));
	});
	//CKEDITOR.replace('cat_content');
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
	$(".chosen-select").chosen();
	$('#projectStatus').change(function(){
		if(confirm("Are you sure want to change the Status?")){
			var projectStatus = $(this).val();
			var projectId = '{!! $id !!}';
			$.ajax({
				type:"POST",
				url:'../update-status/'+projectId,
				data:{projectStatus:projectStatus},
				success:function(d)
				{
					if(d == "success"){
						location.reload();
					}else{
						alert("An error occured!");
						return false;
					}
				}			
			});
			
		}else{
			return false;
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

/*var photo_counter = 0;
Dropzone.options.realDropzone = {

    uploadMultiple: false,
    parallelUploads: 100,
    maxFilesize: 8,
    previewsContainer: '#dropzonePreview',
    previewTemplate: document.querySelector('#preview-template').innerHTML,
    addRemoveLinks: true,
    dictRemoveFile: 'Remove',
    dictFileTooBig: 'Image is bigger than 8MB',

    // The setting up of the dropzone
    init:function() {

        this.on("removedfile", function(file) {

            $.ajax({
                type: 'POST',
                url: 'delete-image',
                data: {id: file.name, _token: $('#csrf-token').val()},
                dataType: 'html',
                success: function(data){
                    var rep = JSON.parse(data);
                    if(rep.code == 200)
                    {
                        photo_counter--;
                        $("#photoCounter").text( "(" + photo_counter + ")");
                    }

                }
            });

        } );
    },
    error: function(file, response) {
        if($.type(response) === "string")
            var message = response; //dropzone sends it's own error messages in string
        else
            var message = response.message;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        return _results;
    },
    success: function(file,done) {
        photo_counter++;
        $("#photoCounter").text( "(" + photo_counter + ")");
    }
}*/
</script>
@include(DIR_ADMIN.'footer')