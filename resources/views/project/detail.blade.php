@include(DIR_ADMIN.'header')

{{--*/
// statuses
$admin_statuses = config("constants.ADMIN_STATUSES");
/*--}}
<style>
.dragula-container {
    width: 100% !important;
	display:block;
	min-height: 60px;
}
.dragula-container div {
    display: inline-block;
}
.dragula-wrapper{
	background-color:#fff;
	color:#646464;
}
.error{
	color: #f00;
}
.send-msg-form input[type=button] {
    height: 55px !important;
    padding: 0 !important;
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}
.tooltip-arrow{
	display:none;
}
.tooltip-inner{
	display:none;	
}
.clearfix{
	padding-top:10px;
}
.nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
	display:none;
}
.timeline-layout{
	padding: 0px 0px 0px 75px !important;	
}
<!-- Dragula -->


</style>
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
                  <dl>
                    <dt class="mrgn-b-xs">Project Description</dt>
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
                        </select>
                      </div>
                    </dd>
                    @endif
                  </dl>
                </div>
              </div>
            </div>
            <div class="bst-users-listing clearfix">
              <div class="row"> @if($projectSegments)
              <div class="default-pills-tab">
              	<ul class="nav nav-pills mrgn-b-lg">
                                            
                {{--*/ $count = 0; /*--}}
                @foreach($projectSegments as $key => $segment)
                {{--*/ $segmentSlug = strtolower($segment['segmentArray'][0]['departmentName']); $count++; /*--}}
                            
                
        				<li {!! ($count == 1) ? "class='active'" : ""; !!}>
                        <a data-toggle="tab" href="#tab-{!! $count; !!}" aria-expanded="false">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                      <div class="bst-block fw-light">
                        <div class="clearfix">
                          <div class="thumb-wid pull-left mrgn-r-md"> <span style="font-size:24px; font-weight: bold;">{!! $segment['segmentArray'][0]['departmentName'] !!}</span> </div>
                          <div class="thumb-content pull-left">
                            <h6 class="fw-bold base-dark">{!! $project['projectName'] !!} <span class="label label-xs label-danger mrgn-l-xs">{!! $project['projectPriority'] !!}</span></h6>
                            <p><span><i class="fa fa-user-secret mrgn-r-xs" aria-hidden="true"></i></span>{!! $client['clientName'] !!}</p>
                            <p><span><i class="fa fa-calendar mrgn-r-xs" aria-hidden="true"></i></span> {!! ($segment['segmentArray'][0]['updatedAt'] != "")? date("d M, Y H:i:s", strtotime($segment['segmentArray'][0]['updatedAt'])) : date("d M, Y H:i:s", strtotime($segment['segmentArray'][0]['addedAt'])) !!}</p>
                            {{--*/ $segmentVal = $segmentSlug."Slug"; /*--}}
                            @if(!isset($segment[$segmentVal])) <a href="#{!! $segmentSlug !!}-specs" class="btn btn-xs btn-outline-primary font-xs mrgn-b-xs" data-toggle="modal">Enter Specs</a> @else <a href="#{!! $segmentSlug !!}-specs" class="btn btn-xs btn-outline-primary font-xs mrgn-b-xs" data-toggle="modal">Update Specs</a> <a href="{!! URL::to(config('/').'segment-details/'.$segment['fkSegmentId']->{'$id'}) !!}" class="btn btn-xs btn-outline-success font-xs mrgn-b-xs">Details</a> @endif </div>
                          <div class="action-links">
                            <ul class="list-inline">
                              <li> <a class="text-success" href="javascript:;"><i class="fa fa-star" aria-hidden="true"></i></a> </li>
                              <li>
                                <div class="dropdown"> <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa fa-chevron-down"></i></a>
                                  <ul class="dropdown-menu dropdown-arrow dropdown-menu-right">
                                    <li> <a href="javascript:;"> <i class="fa fa-eye"></i> <span class="mrgn-l-sm">View</span> </a> </li>
                                    <li> <a href="javascript:;"> <i class="fa fa-pencil"></i> <span class="mrgn-l-sm">Edit </span> </a> </li>
                                    <li> <a href="javascript:;"> <i class="fa fa-trash-o"></i> <span class="mrgn-l-sm">Delete</span> </a> </li>
                                  </ul>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                   </a>
                  </li>
                
                
                <!-- Modal Specs Form -->
                <div id="{!! $segmentSlug !!}-specs" class="modal fade" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{!! ucfirst($segmentSlug)." Specifications" !!}</h4>
                      </div>
                      <form name="specsForm" class="modal-form" method="post" enctype="multipart/form-data">
                        <div class="modal-body"> 
                        
                        @if($segmentSlug == 'logo')
                          <div class="row">
                            <div class="col-md-6">
                              <h4>*Phase:</h4>
                              <div class="form-group">
                              	<select name="phaseId" class="form-control">
                                	@if($phases > 0)
                                    	@foreach($phases as $key => $phase)
                                        	@if($phase['fkDepartmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                        		<option value="{!! $key !!}">{!! $phase['phaseName']; !!}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div class="error" id="error_phaseId"></div>
                              </div>
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
                            </div>
                            <div class="col-md-6">
                              <h4>*No. of Concepts:</h4>
                              <div class="form-group">
                                <input type="number" min="1" max="10" name="logoConcepts" class="form-control" value="{!! (isset($segment['logoConcepts'])) ? $segment['logoConcepts'] : ""; !!}">
                                <div class="error" id="error_logoConcepts"></div>
                              </div>
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
                              <h4>*Phase:</h4>
                              <div class="form-group">
                              	<select name="phaseId" class="form-control">
                                	@if($phases > 0)
                                    	@foreach($phases as $key => $phase)
                                        	@if($phase['fkDepartmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                        		<option value="{!! $key !!}">{!! $phase['phaseName']; !!}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div class="error" id="error_phaseId"></div>
                              </div>
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
                              <h4>*Phase:</h4>
                              <div class="form-group">
                              	<select name="phaseId" class="form-control">
                                	@if($phases > 0)
                                    	@foreach($phases as $key => $phase)
                                        	@if($phase['fkDepartmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                        		<option value="{!! $key !!}">{!! $phase['phaseName']; !!}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div class="error" id="error_phaseId"></div>
                              </div>
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
                        	<div class="row">
                                <div class="col-md-6">
                                    <div class="form-group span_image">
                                        <label class="file-field-label">
                                            <input type="file" name="postFiles[]" id="fileUpload" multiple style="display:none;" />
                                            <span>
                                                <i class="fa fa-paperclip mrgn-r-sm" aria-hidden="true"></i>
                                            </span> Attach Files
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Thumbnail Display -->
                        <div class="row imageThumbnails"></div>
                        <!-- Image Thumbnail Display End -->
                        
                        <div class="modal-footer">
                          <input type="hidden" name="orderOf" value="{!! $segmentSlug; !!}" />
                          <input type="hidden" name="segmentId" value="{!! $segment['fkSegmentId']->{'$id'}; !!}" />
                          <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
                          <input type="hidden" name="do_post" value="1" />
                          <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
                          <button type="submit" class="btn btn-success">Save changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <!-- Modal Specs Form End --> 
                @endforeach
                </ul>
                @endif 
                
                @if($projectSegments)
                <div class="tab-content">
                {{--*/ $count = 0; /*--}}
                    @foreach($projectSegments as $key => $segment)
                    {{--*/ $segmentSlug = strtolower($segment['segmentArray'][0]['departmentName']); $count++; /*--}}
                    <div id="tab-{!! $count; !!}" class="tab-pane fade in {!! ($count == 1)? "active" : ""; !!}">
                        <!-- ChatBox -->
                        <div class="clearfix"></div>
                        <div class="user-chat-wrap bst-block overflow-wrapper">
                        	<ul class="nav nav-tabs mrgn-b-lg">
                                <li class="active"> <a data-toggle="tab" href="#tab-1{!! $count; !!}" aria-expanded="true">{!! $segment['segmentArray'][0]['departmentName']." Segment Board";  !!} </a> </li>
                                <li class=""> <a data-toggle="tab" href="#tab-2{!! $count; !!}" aria-expanded="false">Segment Tasks </a> </li>
                                <li class=""> <a data-toggle="tab" href="#tab-3{!! $count; !!}" aria-expanded="false">Segment Users </a> </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1{!! $count; !!}" class="tab-pane fade active in">
                                    <h1>Segment Board of {!! $segment['segmentArray'][0]['departmentName']  !!}</h1>
                                      <div class="row">
                                        <!-- Segment Chat -->
                                        <div class="text-right" style="margin: 0px 0px 0px 0px;"><button type="button" class="btn btn-default refresh_page"> <i class="fa fa-refresh fa-lg"></i> Refresh </button></div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y:scroll; max-height:800px;">
                                          <div class="chat-window pad-l-lg  clearfix">
                                            <div class="tab-content mrgn-b-lg">
                                              <div class="tab-pane active" id="tab-panel-1">
                                                <div class="bst-messages-list" id="bst-messages-list{!! $count; !!}">
                                                
                                                    <div class="row">
                                                    
                                                    </div>
                                                    
                                                  <h5 class="mrgn-b-lg"><span class="display-ib mrgn-r-sm"><i class="fa fa-comment" aria-hidden="true"></i></span>Segment Timeline</h5> 
                                                  
                                                <!-- Bootcamp Snippet -->
                                                <div class="container-fluid">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                          
                                                            <div class="timeline timeline-line-dotted">
                                                                <!--<span class="timeline-label">
                                                                    <a href="#" class="btn btn-default" title="More...">
                                                                        <i class="fa fa-fw fa-history"></i>
                                                                    </a>
                                                                </span>-->
                                                                
                                                                <!-- Segment Specs Div -->
                                                                <div class="timeline-item">
                                                                   <div class="timeline-point timeline-point-success" style="color:#F00; font-size: 30px; padding-top:10px;">
                                                                        S
                                                                    </div>
                                                                    <div class="timeline-event">
                                                                    	<div class="clearfix"></div>
                                                                    	<h5 class="mrgn-b-lg"><span class="display-ib mrgn-r-sm"><i class="fa fa-search-plus" aria-hidden="true"></i></span>Segment Specifications</h5>
                                                                        @if(isset($segment['orderOf']))
                                                                          @if($segment['orderOf'] == 'logo')
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Exact name to be appeared on logo:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoName'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Slogan(if any):</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoSlogan'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Logo style preferred:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoStylePreferred'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Look & Feel:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoLookAndFeel'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>No. of concepts:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoConcepts'] !!}</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Additional comments:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoAdditionalComments'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Logo industry:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                    {{--*/ $industries = logoIndustries(); /*--}}
                                                                                    @if($industries != "")
                                                                                        @foreach($industries as $key => $value)
                                                                                            @if($key == $segment['logoIndustry'])
                                                                                                {{--*/ $industry = $value; /*--}}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                        <p>{!! $industry; !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Business description:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoBusinessDescription'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Target audience:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['logoTargetAudience'] !!}</p>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            
                                                                          @elseif($segment['orderOf'] == 'website')
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Business name:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteBusinessName'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Slogan(if any):</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteSlogan'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>No. of concepts:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteConcepts'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Look & Feel:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteLookAndFeel'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Websites you like:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteLike'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Navigation menu preference:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteNavMenuPreference'] !!}</p>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Existing domain:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteExistingDomain'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Domain preference:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteDomainPreference'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Additional comments:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteAdditionalComments'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Website industry:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                    {{--*/ $industries = websiteIndustries(); /*--}}
                                                                                    @if($industries != "")
                                                                                        @foreach($industries as $key => $value)
                                                                                            @if($key == $segment['websiteIndustry'])
                                                                                                {{--*/ $industry = $value; /*--}}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                        <p>{!! $industry; !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Business description:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteBusinessDescription'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Target audience:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['websiteTargetAudience'] !!}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                          
                                                                          @elseif($segment['orderOf'] == 'video')
                                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Business name:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoBusinessName'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Slogan(if any):</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoSlogan'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>No. of concepts:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoConcepts'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Website address:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoWebsiteAddress'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Animation you like:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        {{--*/ $styles = videoAnimationStyles(); /*--}}
                                                                                        @if(isset($styles) && $styles > 0)
                                                                                            @foreach($styles as $stylekey => $value)
                                                                                                @if($stylekey == $segment['videoAnimationStyle'])
                                                                                                    {{--*/ $animationstyle = $value; /*--}}
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif
                                                                                        <p>{!! $animationstyle; !!}</p>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Primary use of video:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoPrimaryUse'] !!}</p>
                                                                                    </div><
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Additional comments:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoAdditionalComments'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Video industry:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                    {{--*/ $industries = videoIndustries(); /*--}}
                                                                                    @if($industries != "")
                                                                                        @foreach($industries as $key => $value)
                                                                                            @if($key == $segment['videoIndustry'])
                                                                                                {{--*/ $industry = $value; /*--}}
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                        <p>{!! $industry; !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Business description:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoBusinessDescription'] !!}</p>
                                                                                    </div>
                                                                                    <div class="timeline-heading">
                                                                                        <h4>Target audience:</h4>
                                                                                    </div>
                                                                                    <div class="timeline-body">
                                                                                        <p>{!! $segment['videoTargetAudience'] !!}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                          
                                                                          @endif
                                                                          <div class="clearfix"></div>
                                                                            <div class="timeline-heading">
                                                                                <h4>Reference Material:</h4>
                                                                            </div>
                                                                            <!-- reference files -->
                                                                            @if(isset($segment['fileArray'][0]) && !empty($segment['fileArray'][0]))
                                                                                <div class="row" style="margin:2px;">
                                                                                    @foreach($segment['fileArray'] as $taskFile)
                                                                                        @if($taskFile['fkSegmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                                                            {{--*/ $ext = pathinfo($taskFile['fileName'], PATHINFO_EXTENSION); /*--}}
                                                                                            <!-- File Extension Check -->
                                                                                            @if($ext == 'jpg' || $ext == 'png')
                                                                                                {{--*/ $tFile = URL::to(config('constants.ADMIN_RESOURCE_URL')."taskfiles/".$taskFile['directoryPath'].$taskFile['fileName']); /*--}}
                                                                                            @elseif($ext == 'docx')
                                                                                                {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/docx-icon.png"; /*--}}
                                                                                            @elseif($ext == 'pdf')
                                                                                                {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/pdf-icon.png"; /*--}}
                                                                                            @elseif($ext == 'ai' || $ext == 'eps')
                                                                                                {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/ai-icon.png"; /*--}}
                                                                                            @elseif($ext == 'txt')
                                                                                                {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/txt-icon.png"; /*--}}
                                                                                            @else
                                                                                                {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/rar-icon.png"; /*--}}
                                                                                            @endif
                                                                                            <!-- File Extension Check End -->
                                                                                            
                                                                                            @if(file_exists('./resources/assets/taskfiles/'.$taskFile['directoryPath'].$taskFile['fileName']))
                                                                                                {{--*/ $file = $tFile; /*--}}
                                                                                            @else
                                                                                                {{--*/ $file = URL::to(config('constants.ADMIN_IMG_URL')."180x180.jpg"); /*--}}
                                                                                            @endif
                                                                                            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                                                <div class="overlay-wrap text-center mrgn-b-md">
                                                                                                    <img class="img-responsive display-ib" src="{!! $file !!}" width="20" height="20" alt="Task File">
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                    
                                                                                </div>
                                                                            @endif
                                                                            <!-- reference files end -->
                                                                        @else
                                                                        <div class="timeline-heading">
                                                                            <h4 style="color:#F00;">SPECS NOT ENTERED!</h4>
                                                                        </div>
                                                                        @endif
                                                                        
                                                                        <div class="timeline-footer">
                                                                            <p class="text-right" style="text-align:right;">{!! date('M d Y, H:i:s', strtotime($segment['addedAt'])); !!}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Segment Specs Div -->
                                                                
                                                                <!-- '<span class="square-50 bg-default img-circle base-reverse mrgn-r-md"></span>' -->
                                                                
                                                                @if(!empty($segmentChat))
                                                                {{--*/ $dateArr = array(); /*--}}
                                                                @foreach($segmentChat['result'] as $key => $comment)
                                                                    @if($comment['fkSegmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                                    {{--*/ $username = substr($comment['userArray'][0]['userName'],0,1); /*--}}
                                                                    {{--*/ $userImage = $username; /*--}}
                                                                    @if($comment['userArray'][0]['userProfileImage'] != "")
                                                                        {{--*/ $profileImage = URL::to(config('/').DIR_PROFILE_IMG.'/'.$comment['userArray'][0]['userProfileImage']); /*--}
                                                                        {{--*/ $userImage= '<img class="profile-dp" src="'.$profileImage.'" width="95" height="95" alt="Sender Image">'; /*--}}
                                                                    @endif
                                                                    <!-- DATE -->
                                                                    @if(!in_array(date('M d, Y', strtotime($comment['addedAt'])), $dateArr))
                                                                        <span class="timeline-label">
                                                                            <span class="label label-primary">{!! date('M d, Y', strtotime($comment['addedAt'])); !!}</span>
                                                                        </span>
                                                                    	{{--*/ array_push($dateArr, date('M d, Y', strtotime($comment['addedAt']))); /*--}}
                                                                    @endif
                                                                    <!-- DATE END -->
                                                                        
                                                                    <!--if($comment['fkAddedById']->{'$id'} == $userDetail->{'_id'}->{'$id'})-->
                                                                    @if(isset($comment['userArray'][0]['fkDepartmentId']->{'$id'}))
                                                                    	@if($comment['userArray'][0]['fkDepartmentId']->{'$id'} == $salesDept['_id']->{'$id'})
                                                                    		<div class="timeline-item-new timeline-item">
                                                                            <div class="timeline-point timeline-point-success" style="font-size: 30px; padding-top:10px;">
                                                                                {!! $userImage; !!}
                                                                            </div>
                                                                            <div class="timeline-event">
                                                                                <div class="timeline-heading">
                                                                                    <h4>{!! $comment['userArray'][0]['userName']; !!}</h4>
                                                                                </div>
                                                                                <div class="timeline-body">
                                                                                    <p>{!! $comment['postDesc']; !!}</p>
                                                                                    @if(!empty($comment['fileArray'][0]))
                                                                                        <div class="row">
                                                                                            {{--*/ $attachments = 0; /*--}}
                                                                                            {{--*/ $attachments = count($comment['fileArray']); /*--}}
                                                                                            <div class="clearfix" style="padding:12px 0px 6px 16px;">
                                                                                                <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'update-request-icon.png'); !!}" alt="Attachments" title="Attachments" width="25" />
                                                                                                {!! $attachments !!} Attachment(s)
                                                                                            </div>
                                                                                            @foreach($comment['fileArray'] as $taskFile)
                                                                                            	{{--*/ $ext = pathinfo($taskFile['fileName'], PATHINFO_EXTENSION); /*--}}
                                                                                                <!-- File Extension Check -->
                                                                                                @if($ext == 'jpg' || $ext == 'png')
                                                                                                    {{--*/ $tFile = URL::to(config('constants.ADMIN_RESOURCE_URL')."taskfiles/".$taskFile['directoryPath'].$taskFile['fileName']); /*--}}
                                                                                                @elseif($ext == 'docx')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/docx-icon.png"; /*--}}
                                                                                                @elseif($ext == 'pdf')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/pdf-icon.png"; /*--}}
                                                                                                @elseif($ext == 'ai' || $ext == 'eps')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/ai-icon.png"; /*--}}
                                                                                                @elseif($ext == 'txt')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/txt-icon.png"; /*--}}
                                                                                                @else
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/rar-icon.png"; /*--}}
                                                                                                @endif
                                                                                                <!-- File Extension Check End -->
                                                                                                
                                                                                                @if(file_exists('./resources/assets/taskfiles/'.$taskFile['directoryPath'].$taskFile['fileName']))
                                                                                                    {{--*/ $file = $tFile; /*--}}
                                                                                                @else
                                                                                                    {{--*/ $file = URL::to(config('constants.ADMIN_IMG_URL')."180x180.jpg"); /*--}}
                                                                                                @endif
                                                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                                                    <div class="overlay-wrap text-center mrgn-b-md">
                                                                                                        <img class="img-responsive display-ib" src="{!! $file; !!}" width="20" height="20" alt="Task File">
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endforeach
                                                                                            
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="timeline-footer">
                                                                                    <p class="text-right" style="text-align:right !important;">{!! date('d M, Y g:i a', strtotime($comment['addedAt'])); !!}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        <div style="clear:both;"></div>
                                                                    @else
                                                                    	<div class="timeline-item">
                                                                            <div class="timeline-point timeline-point-success" style="font-size: 30px; padding-top:10px; color:#06F !important;">
                                                                                {!! $userImage; !!}
                                                                            </div>
                                                                            <div class="timeline-event">
                                                                                <div class="timeline-heading">
                                                                                    <h4>{!! $comment['userArray'][0]['userName']; !!}</h4>
                                                                                </div>
                                                                                <div class="timeline-body">
                                                                                    <p>{!! $comment['postDesc']; !!}</p>
                                                                                    @if(!empty($comment['fileArray'][0]))
                                                                                        <div class="row">
                                                                                            {{--*/ $attachments = 0; /*--}}
                                                                                            {{--*/ $attachments = count($comment['fileArray']); /*--}}
                                                                                            <div class="clearfix" style="padding:12px 0px 6px 16px;">
                                                                                                <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'update-request-icon.png'); !!}" alt="Attachments" title="Attachments" width="25" />
                                                                                                {!! $attachments !!} Attachment(s)
                                                                                            </div>
                                                                                            @foreach($comment['fileArray'] as $taskFile)
                                                                                                {{--*/ $ext = pathinfo($taskFile['fileName'], PATHINFO_EXTENSION); /*--}}
                                                                                                <!-- File Extension Check -->
                                                                                                @if($ext == 'jpg' || $ext == 'png')
                                                                                                    {{--*/ $tFile = URL::to(config('constants.ADMIN_RESOURCE_URL')."taskfiles/".$taskFile['directoryPath'].$taskFile['fileName']); /*--}}
                                                                                                @elseif($ext == 'docx')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/docx-icon.png"; /*--}}
                                                                                                @elseif($ext == 'pdf')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/pdf-icon.png"; /*--}}
                                                                                                @elseif($ext == 'ai' || $ext == 'eps')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/ai-icon.png"; /*--}}
                                                                                                @elseif($ext == 'txt')
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/txt-icon.png"; /*--}}
                                                                                                @else
                                                                                                    {{--*/ $tFile = URL::to(config("constants.ADMIN_IMG_URL"))."/avatars/rar-icon.png"; /*--}}
                                                                                                @endif
                                                                                                <!-- File Extension Check End -->
                                                                                                
                                                                                                @if(file_exists('./resources/assets/taskfiles/'.$taskFile['directoryPath'].$taskFile['fileName']))
                                                                                                    {{--*/ $file = $tFile; /*--}}
                                                                                                @else
                                                                                                    {{--*/ $file = URL::to(config('constants.ADMIN_IMG_URL')."180x180.jpg"); /*--}}
                                                                                                @endif
                                                                                                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                                                                    <div class="overlay-wrap text-center mrgn-b-md">
                                                                                                        <img class="img-responsive display-ib" src="{!! $file !!}" width="20" height="20" alt="Task File">
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endforeach
                                                                                            
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="timeline-footer">
                                                                                    <p class="text-right" style="text-align:right !important;">{!! date('d M, Y g:i a', strtotime($comment['addedAt'])); !!}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div style="clear:both;"></div> 
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="pad-all-md mrgn-b-xs pos-relative msg-wrap fw-light arrow-box-right">
                                                                <p class="mrgn-all-none">No comments yet! Start conversation?</p>
                                                            </div>
                                                        @endif
                                                                
                                                            </div>
                                                        </div>
                                                        
                                            
                                                        
                                                    </div><!--main col end-->
        										</div><!--main container end-->
                                                <!-- Bootcamp Snippet End -->
                                                  
                                                </div>
                                              </div>
                                            </div>
                                            <!-- tab-content closed -->
                                          </div>
                                          <!-- chat-window closed --> 
                                        </div>
                                      </div>
                                      <!-- row closed -->
                                </div>
                                <div id="tab-2{!! $count; !!}" class="tab-pane fade">
                                	<div class="bst-users-listing clearfix">
                                    	<div class="left-tab bst-block">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <h3 class="text-capitalize">Segment Tasks</h3> </div>
                                    <div class="row">
                                        <div class="col-xs-2 col-sm-2 col-md-2">
                                            <ul class="nav nav-tabs tabs-left">
                                            	@if($phases > 0)
                                                	{{--*/ $c = 0; /*--}}
                                                	@foreach($phases as $key => $phase)
                                                    	@if($phase['fkDepartmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                    		{{--*/ $c++;
                                                        	$sel = ($c == 1)? "active": ""; /*--}}
                                                    		<li class="{!! $sel; !!}"> <a data-toggle="tab" href="#tab-{!! $phase['phaseSlug'].$c; !!}" aria-expanded="false"> {!! $phase['phaseName']; !!} </a> </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                        <div class="col-xs-10 col-sm-10 col-md-10">
                                            <div class="tab-content">
                                            	@if($phases > 0)
                                                	{{--*/ $ca = 0; /*--}}
                                                	@foreach($phases as $key => $phase)
                                                    	@if($phase['fkDepartmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                    		{{--*/ $ca++;
                                                        	$sela = ($ca == 1)? "active in": ""; /*--}}
                                                            <div id="tab-{!! $phase['phaseSlug'].$ca; !!}" class="tab-pane fade {!! $sela; !!}">
                                                                <div class="row">
                                                                    <div class="bst-block">
                                                                      <div class="bst-block-title mrgn-b-md">
                                                                        <div class="caption">
                                                                          <h3 class="text-capitalize">Segment Tasks</h3>
                                                                          @if(!isset($segment['orderOf']))
                                                                            <div class="alert alert-danger display-hide" style="display: block;">
                                                                                <button class="close" data-close="alert"></button> Segment Specs not entered!
                                                                                <div class="text-right" style="float:right; margin-top:-7px;">
                                                                                    <a href="#{!! $segmentSlug; !!}-specs" class="btn btn-default" data-toggle="modal">Enter Specs</a>
                                                                                </div>
                                                                            </div>
                                                                          @else
                                                                            <div class="text-right" style="float:right; margin-top:-44px;"><a href="#{!! $segmentSlug; !!}TaskAdd" class="btn btn-primary" data-toggle="modal">+ Add Task</a></div>
                                                                          @endif
                                                                        </div>
                                                                      </div>
                                                                      <div class="bst-block-content">
                                                                        <div class="table-responsive">
                                                                          <table class="table table-striped table-hover table-bordered">
                                                                            <thead>
                                                                              <tr>
                                                                                <th>Task</th>
                                                                                <th>Segment</th>
                                                                                <th>Priority / Brand</th>
                                                                                <th>Deadline</th>
                                                                                <th>Assigned to</th>
                                                                                <th>Assigned By</th>
                                                                                <th>Action</th>
                                                                              </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @if(!empty($tasks))
                                                                                @foreach($tasks['result'] as $task)
                                                                                    @if($task['fkSegmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'} && ($task['fkPhaseId']->{'$id'} == $phase['_id']->{'$id'}))
                                                                                        <!-- Task Users --> 
                                                                                        {{--*/ $task_id = $task['_id']->{'$id'}; /*--}}
                                                                                        {{--*/ $taskusers = $taskUserAssignmentModel->getTaskListing($task_id); /*--}}
                                                                                        @if(isset($taskusers['result'][0]))
                                                                                            @foreach($taskusers as $taskuser)
                                                                                                @if(is_array($taskuser))
                                                                                                    {{--*/ $userArray = array(); /*--}}
                                                                                                    @foreach($taskuser as $taskuser)
                                                                                                        {{--*/ $userName = $taskuser['userArray'][0]['userName']; /*--}}
                                                                                                        {{--*/ array_push($userArray, $userName); /*--}}
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @endif 
                                                                                        <!-- Task Users End -->
                                                                                        <tr>
                                                                                          {{--*/ $status = 'success'; $priority = ''; /*--}}
                                                                                          {{--*/ $priorities = taskPriorities(); /*--}}
                                                                                          @if($priorities != "")
                                                                                              @foreach($priorities as $skey => $value)
                                                                                                  @if($task['taskPriority'] == $skey)
                                                                                                      {{--*/ $priority = $value; /*--}}
                                                                                                      @if($skey == 1)
                                                                                                        {{--*/ $status = 'danger'; /*--}}
                                                                                                      @elseif($skey == 2)
                                                                                                        {{--*/ $status = 'warning'; /*--}}
                                                                                                      @elseif($skey == 3)
                                                                                                        {{--*/ $status = 'primary'; /*--}}
                                                                                                      @endif
                                                                                                  @endif
                                                                                              @endforeach
                                                                                          @endif
                                                                                          <td>{!! $project['projectName']; !!}</td>
                                                                                          <td>{!! (isset($segment['orderOf']))?ucfirst($segment['orderOf']):"-"; !!}</td>
                                                                                          <td><span class="label label-xs label-{!! $status; !!} mrgn-l-xs">{!! $priority; !!}</span> / {!! $brand['brandName']; !!}</td>
                                                                                          <td>{!! $task['taskDueDate']; !!}</td>
                                                                                          <td>@if(!empty($userArray))
                                                                                            @foreach($userArray as $key => $user) <span class="label label-default btn-rounded">{!! $user !!}</span> @endforeach
                                                                                            @endif
                                                                                            <a href="#userAdd{!! $count; !!}" class="btn" data-toggle="modal"><i class="fa fa-plus fa-lg"></i></a></td>
                                                                                          {{--*/ $assignedBy = $userModel->get($task['fkCreatedById']->{'$id'}); /*--}}
                                                                                          <td>{!! (isset($assignedBy['userName']))? $assignedBy['userName'] : "Unidentified"; !!}</td>
                                                                                          <td><a class="btn btn-outline-success btn-xs" type="button" href="{!! URL::to(config('/')); !!}/task/detail/{!! $task['_id']->{'$id'}; !!}" data-toggle="tooltip" title="Project Details" data-original-title="Project Details"><span><i class="fa fa-eye" aria-hidden="true"></i></span> Details</a>
                                                                                          <!--<a href="javascript:;"><i class="fa fa-cog fa-lg base-dark" aria-hidden="true"></i></a> <a href="javascript:;" class="mrgn-l-sm"><i class="fa fa-angle-down fa-lg base-dark" aria-hidden="true"></i></a>--></td>
                                                                                        </tr>
                                                                                      <!-- Modal Add User -->
                                                                                    <div class="modal fade" id="userAdd{!! $count; !!}" aria-hidden="true">
                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                                    <h4 class="modal-title">Users</h4> </div>
                                                                                                <form name="addUserForm" class="modal-form" method="post">
                                                                                                    <div class="modal-body">
                                                                                                        <h4>Assign to:</h4>
                                                                                                    <div class="form-group">
                                                                                                      <select class="selectize-remove-btn selectized" id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>
                                                                                                            {{--*/ $taskUsers = $userModel->getUsersByDepartmentId($segment['fkSegmentId']->{'$id'});
                                                                                                            		$usel = ''; /*--}}
                                                                                                            @if(isset($taskUsers) && $taskUsers > 0)
                                                                                                                @foreach($taskUsers as $key => $user)
                                                                                                                    @if(!empty($userArray))
                                                                                                                    	{{--*/ $usel = (in_array($user['userName'], $userArray))? "selected='selected'" : ""; /*--}}
                                                                                                                    @endif
                                                                                                                    <option value="{!! $key !!}" {!! $usel; !!}>{!! $user['userName'] !!}</option>
                                                                                                                @endforeach
                                                                                                            @endif         
                                                                                                      </select>
                                                                                                      <div class="error" id="error_fkUserId"></div>
                                                                                                    </div>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <input type="hidden" name="fkProjectId" value="{!! $segment['fkProjectId']->{'$id'}; !!}" />
                                                                                                        <input type="hidden" name="fkSegmentId" value="{!! $segment['fkSegmentId']->{'$id'}; !!}" />
                                                                                                        <input type="hidden" name="fkPhaseId" value="{!! $phase['_id']->{'$id'}; !!}" />
                                                                                                        <input type="hidden" name="fkTaskId" value="{!! $task['_id']->{'$id'}; !!}" />
                                                                                                        <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
                                                                                                        <input type="hidden" name="orderOf" value="{!! $task['orderOf']; !!}" />
                                                                                                        <input type="hidden" name="do_task_user_post" value="1" />
                                                                                                        <button type="button" class="btn btn-outline-inverse" data-dismiss="modal">Close</button>
                                                                                                        <button type="submit" class="btn btn-success">Save changes</button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                            <!-- /.modal-content -->
                                                                                        </div>
                                                                                        <!-- /.modal-dialog -->
                                                                                    </div>
                                                                                    <!-- Modal Add User End -->
                                                                                    @endif	<!-- End If Segment Match -->
                                                                                 @endforeach
                                                                                @else
                                                                                <tr>
                                                                                  <td colspan="7" style="text-align:center;">No tasks available!</td>
                                                                                </tr>
                                                                                @endif
                                                                            </tbody>
                                                                          </table>
                                                                        </div>
                                                                      </div>
                                                                    </div>
                                                                  </div>
                                                            </div>
                                                            <!-- Modal Task Form -->
                                                            <div id="{!! $segmentSlug; !!}TaskAdd" class="modal fade" aria-hidden="true">
                                                              <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                  <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title">Add New Task</h4>
                                                                  </div>
                                                                  <form name="taskForm" class="modal-form" method="post">
                                                                    <div class="modal-body">
                                                                      <div class="row">
                                                                        <h4>Segment: <b>{!! (isset($segment['orderOf']))? ucfirst($segment['orderOf']): "-"; !!}</b></h4>
                                                                        <div class="clearfix"></div>
                                                                        <h4>Task Priority</h4>
                                                                        <div class="form-group">
                                                                          <select name="taskPriority" class="form-control">
                                                                            {{--*/ $priorities = taskPriorities(); /*--}}
                                                                            @if($priorities != "")
                                                                                @foreach($priorities as $key => $value)
                                                                                    <option value="{!! $key; !!}">{!! $value; !!}</option>
                                                                                @endforeach
                                                                            @endif
                                                                                                
                                                                          </select>
                                                                          <div class="error" id="error_logoSlogan"></div>
                                                                        </div>
                                                                        @if(isset($privileges['task-due-date']) && $privileges['task-due-date'] == 1)
                                                                        <h4>Due Date:</h4>
                                                                        {{--*/ $duedate = getDueDate($brand['endTime']); /*--}}
                                                                        <div class="form-group">
                                                                          <input type="text" name="taskDueDate" class="form-control input-group date datetimepicker" data-date="{!! date('Y-m-d ').'H:i:s'; !!}" data-date-format="yyyy-mm-dd HH:ii p" required="required" value="{!! ($duedate != "")? $duedate : date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days")); !!}">
                                                                          <div class="error" id="error_taskTeamDueDate"></div>
                                                                        </div>
                                                                        @else
                                                                        <input type="hidden" name="taskDueDate" value="{!! (isset($duedate) && $duedate != "")? $duedate : date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days")); !!}" />
                                                                        @endif
                                                                        <h4>Description(if any):</h4>
                                                                        <div class="form-group">
                                                                          <textarea name="taskDescription" class="form-control"></textarea>
                                                                          <div class="error" id="error_taskDescription"></div>
                                                                        </div>
                                                                        <!-- Assign to Users -->
                                                                        <h4>Assign to:</h4>
                                                                        <div class="form-group">
                                                                          <select class="form-control " id="fkUserId" name="fkUserId[]" data-placeholder="" multiple>
                                                                                {{--*/ $taskUsers = $userModel->getUsersByDepartmentId($segment['fkSegmentId']->{'$id'}); /*--}}
                                                                                @if(isset($taskUsers) && $taskUsers > 0)
                                                                                    @foreach($taskUsers as $key => $user)
                                                                                        <option value="{!! $key !!}">{!! $user['userName'] !!}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                                                
                                                                          </select>
                                                                          <div class="error" id="error_fkUserId"></div>
                                                                        </div>
                                                                        <!-- Assign to Users --> 
                                                                      </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                      <input type="hidden" name="orderOf" value="{!! (isset($segment['orderOf']))? $segment['orderOf']:""; !!}" />
                                                                      <input type="hidden" name="projectId" value="{!! $segment['fkProjectId']->{'$id'}; !!}" />
                                                                      <input type="hidden" name="segmentId" value="{!! $segment['fkSegmentId']->{'$id'}; !!}" />
                                                                      <input type="hidden" name="phaseId" value="{!! $phase['_id']->{'$id'}; !!}" />
                                                                      <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
                                                                      <input type="hidden" name="do_task_post" value="1" />
                                                                      <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
                                                                      <button type="submit" class="btn btn-success">Save changes</button>
                                                                    </div>
                                                                  </form>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            <!-- Modal Task Form End -->
                                                            
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    
                                      
                                    </div>
                                    
                                </div>
                                <div id="tab-3{!! $count; !!}" class="tab-pane fade">
                                	<!--<div class="col-xs-12 col-md-6 col-lg-9">-->
                                    	<div class="text-right">
                                            <a href="#{!! $segmentSlug; !!}SegmentUserAdd" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus-square fa-lg"></i> User </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="bst-users-listing clearfix">
                                            <div class="row">
                                            	{{--*/ $userArr = array(); /*--}}
                                                @if(isset($segmentUsers['result'][0]))
                                                    @foreach($segmentUsers as $segmentuser)
                                                        @if(is_array($segmentuser))
                                                            {{--*/ $userArray = array(); $usersCount = count($segmentuser); /*--}}
                                                            @if($usersCount > 6)
                                                            	{{--*/ $usersCount = 6; /*--}}
                                                            @endif
                                                            @foreach($segmentuser as $segmentuser)
                                                                @if($segmentuser['fkSegmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                                	{{--*/ array_push($userArr, $segmentuser['fkUserId']->{'$id'}); /*--}}
                                                                    {{--*/ $username = substr($segmentuser['userArray'][0]['userName'],0,1); /*--}}
                                                                    {{--*/ $buttons = buttonsArray(); /*--}}
                                                                    {{--*/ $randArr = rand(1,$usersCount) /*--}}
                                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                                                        <div class="bst-block fw-light">
                                                                            <div class="clearfix">
                                                                                {{--*/ $userImage = '<span class="square-50 bg-'.$buttons[$randArr].' img-circle base-reverse mrgn-r-md">'.$username.'</span>'; /*--}}
                                                                                @if($segmentuser['userArray'][0]['userProfileImage'] != "")
                                                                                    {{--*/ $profileImage = URL::to(config('/').DIR_PROFILE_IMG.'/'.$segmentuser['userArray'][0]['userProfileImage']); /*--}
                                                                                    {{--*/ $userImage= '<img class="img-responsive img-circle" src="'.$profileImage.'" width="95" height="95" alt="message reciever image">'; /*--}}
                                                                                @endif
                                                                                <div class="thumb-wid pull-left mrgn-r-md"> {!! $userImage; !!} </div>
                                                                                <div class="thumb-content pull-left">
                                                                                    <h6 class="fw-bold base-dark">{!! $segmentuser['userArray'][0]['userName']; !!} <span class="label label-xs label-primary mrgn-l-xs">Admin</span></h6>
                                                                                    <p><span><i class="fa fa-map-marker mrgn-r-xs" aria-hidden="true"></i></span>Salsoft Technologies, Pk</p>
                                                                                    {{--*/ $designation = $userDesignationModel->getUpdateRecord($segmentuser['userArray'][0]['fkDesignationId']->{'$id'}); /*--}}
                                                                                    @if(!empty($designation))
                                                                                        @foreach($designation as $dk => $designation)
                                                                                        @endforeach
                                                                                    @endif
                                                                                    <p><span><i class="fa fa-black-tie mrgn-r-xs" aria-hidden="true"></i>{!! $designation['userDesignationName']; !!}</span></p> <a href="#/" class="btn btn-xs btn-outline-primary font-xs mrgn-b-xs">Send Message</a> <a href="#/" class="btn btn-xs btn-outline-success font-xs mrgn-b-xs">Follow</a> </div>
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
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        
                                        <!-- Dragula -->
                                        <div class="bst-block">
                                            <div class="bst-block-title mrgn-b-lg">
                                                <h3>Segment Users</h3>
                                                <!--<p><span class="note-box bg-danger">Note !</span>Copying stuff from only one of the containers and sorting on the other one? No problem!</p>-->
                                            </div>
                                            <div class="bst-block-content">
                                                <div class="dragula-wrapper mrgn-b-lg pad-all-md">
                                                	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <h1>Active Users:</h1>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div id='dragula-lft-event' class='dragula-container'>
                                                    	
                                                            @if(isset($segmentUsers['result'][0]))
                                                                @foreach($segmentUsers['result'] as $segmentuser)
                                                                    @if($segmentuser['fkSegmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                                        {{--*/ array_push($userArr, $segmentuser['fkUserId']->{'$id'}); /*--}}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            
                                                            @if(isset($allUsers))
                                                                @foreach($allUsers as $akey => $auser)
                                                                    @if(in_array($akey, $userArr))
                                                                    @else
                                                                    	<div data-class="not_added" data-userid="{!! $akey; !!}" data-segment="{!! $segment['fkSegmentId']->{'$id'}; !!}" data-project="{!! $segment['fkProjectId']->{'$id'}; !!}">{!! $auser['userName']; !!}</div>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                                        	<h1>Segment</h1>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                        	<h1>Users on Segment</h1>
                                                        </div>
                                                        
                                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" id="display">
                                                        	<h3>Logo</h3>
                                                       	</div>
                                                        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                                                        	<div id='dragula-rgt-event' class='dragula-container'>@if(isset($allUsers))
                                                                @foreach($allUsers as $key => $user)
                                                                    @if(in_array($key, $userArr))
                                                                    	<div data-class="added" data-userid="{!! $key; !!}" data-segment="{!! $segment['fkSegmentId']->{'$id'}; !!}" data-project="{!! $segment['fkProjectId']->{'$id'}; !!}">{!! $user['userName']; !!}</div>
                                                                    @endif
                                                                @endforeach
                                                            @endif</div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                            			</div>
                                        <!-- Dragula End -->
                                    <!--</div>-->
                                    
                                    <!-- Add User Segment -->
                                    <!-- <div class="col-xs-12 col-md-3 col-lg-3">
                                        <div class="bst-block">
                                            <div class="bst-block-title mrgn-b-lg">
                                                <h3 class="text-capitalize">Segment Users</h3>
                                                <div class="text-right" style="float: right;margin: -30px -10px 0px 0px;">
                                                	<a href="#{!! $segmentSlug; !!}SegmentUserAdd" class="btn btn-success" data-toggle="modal"><i class="fa fa-plus-square fa-lg"></i> User </a>
                                                </div>
                                            </div>
                                            <div class="content">
                                            	@if(isset($segmentUsers['result'][0]))
                                                    @foreach($segmentUsers as $segmentuser)
                                                        @if(is_array($segmentuser))
                                                            {{--*/ $userArray = array(); $usersCount = count($segmentuser); /*--}}
                                                            @if($usersCount > 6)
                                                            	{{--*/ $usersCount = 6; /*--}}
                                                            @endif
                                                            @foreach($segmentuser as $segmentuser)
                                                                @if($segmentuser['fkSegmentId']->{'$id'} == $segment['fkSegmentId']->{'$id'})
                                                                    {{--*/ $username = substr($segmentuser['userArray'][0]['userName'],0,1); /*--}}
                                                                    {{--*/ $buttons = buttonsArray(); /*--}}
                                                                    {{--*/ $randArr = rand(1,$usersCount) /*--}}
                                                                    {{--*/ $userImage = '<span class="square-50 bg-'.$buttons[$randArr].' img-circle base-reverse mrgn-r-md">'.$username.'</span>'; /*--}}
                                                                    @if($segmentuser['userArray'][0]['userProfileImage'] != "")
                                                                        {{--*/ $profileImage = URL::to(config('/').DIR_PROFILE_IMG.'/'.$segmentuser['userArray'][0]['userProfileImage']); /*--}
                                                                        {{--*/ $userImage= '<img class="img-responsive img-circle" src="'.$profileImage.'" width="95" height="95" alt="message reciever image">'; /*--}}
                                                                    @endif
                                                                    {!! $userImage.$segmentuser['userArray'][0]['userName']; !!}
                                                                    {{--*/ $designation = $userDesignationModel->getUpdateRecord($segmentuser['userArray'][0]['fkDesignationId']->{'$id'}); /*--}}
                                                                    @if(!empty($designation))
                                                                        @foreach($designation as $dk => $designation)
                                                                        @endforeach
                                                                        <p style="float:left;margin:-20px 0px 0px 73px; font-size:10px;">
                                                                            <span><i class="fa fa-black-tie mrgn-r-xs" aria-hidden="true"></i>{!! $designation['userDesignationName']; !!}</span>
                                                                        </p>
                                                                    @endif
                                                                    <div class="clearfix"></div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            
                                            </div>
                                        </div>
                                    </div> -->
                                    <!-- Add User Segment End -->
                                    
                                    
                                    <!-- Modal Segment User Add -->
                                    <div id="{!! $segmentSlug; !!}SegmentUserAdd" class="modal fade" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Add Segment Users</h4>
                                          </div>
                                          <form name="sgUserForm" class="modal-form" method="post">
                                            <div class="modal-body" style="min-height:200px;">
                                            {{--*/ $deptUsers = $departmentDetails; /*--}}
                                              <select class="selectize-remove-btn selectized" id="fkUserId" name="fkUserId[]" data-placeholder="Select Segment Users" multiple="multiple">
                                              	@if(isset($allUsers))
                                                	@foreach($allUsers as $key => $auser)
                                                    	{{--*/ $sel = (in_array($key, $userArr)) ? "selected='selected'" : "" ; /*--}}
                                                    	<option value="{!! $key; !!}" {!! $sel; !!}>{!! $auser['userName']; !!}</option>
                                                    @endforeach
                                                @endif
                                           	  </select>
                                              <div class="error" id="error_logoName"></div>
                                            </div>
                                            <div class="modal-footer">
                                              <input type="hidden" name="fkProjectId" value="{!! $segment['fkProjectId']->{'$id'}; !!}" />
                                              <input type="hidden" name="fkSegmentId" value="{!! $segment['fkSegmentId']->{'$id'}; !!}" />
                                              <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
                                              <input type="hidden" name="do_sguser_post" value="1" />
                                              <button type="button" data-dismiss="modal" class="btn btn-outline-inverse">Close</button>
                                              <button type="submit" class="btn btn-success">Submit</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <!-- Modal Segment User Add End -->
                                </div>
                            </div> 
                        </div>
                        
                        <!-- Chatbox End -->
                    </div>
                    @endforeach
                </div>
                @endif                 
                
            </div>
                 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<form method="post" id="tag_user">
	<input type="hidden" name="userid" id="taguserid" value="" />
    <input type="hidden" name="segmentid" id="tagsegmentid" value="" />
    <input type="hidden" name="projectid" id="tagprojectid" value="" />
    <input type="hidden" name="_token" id="csrf-token" value="{!! csrf_token(); !!}" />
</form>
<script type="text/javascript">
$(function() {
	// default form submit/validate
	/*$('form[name="specsForm"]').submit(function(e) {
		e.preventDefault();
		//CKEDITOR.instances.cat_content.destroy();
		//CKEDITOR.replace('cat_content');
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		//$("div[id^=error_]").removeClass("has-error");
		// validate form
		//return jsonValidate('{!! $route_action !!}',$(this));
		return jsonValidate('{!! $id !!}',$(this));
	});*/
	
	$('form[name="taskForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		return jsonValidate('{!! $id !!}',$(this));
	});
	
	$('form[name="sgUserForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		return jsonValidate('{!! $id !!}',$(this));
	});
	
	$('form[name="addUserForm"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		return jsonValidate('{!! $id !!}',$(this));
	});
	
	
	$('.submitComment').click(function(e) {
		e.preventDefault();
		$("div[id^=error_]").removeClass("has-error");
		var formid = $(this).data('id');
		var userImage = $('.userImage'+formid).val();
		var src = "{!! URL::to(config('/').DIR_PROFILE_IMG."/"); !!}";
		src = src+"/"+userImage;
		var userName = $('.userName'+formid).val();
		var timeStamp = $('.timeStamp'+formid).val();
		var comment = $('.segmentComment'+formid).val();
		if(comment == ""){
			$("div[id^=error_]").addClass("has-error");
			return false;
		}else{
			$.ajax({
				type:"POST",
				url:'../segment-comment-submit',
				data:$('#chatForm'+formid).serialize(),
				success:function(d)
				{
					if(d == "success"){
						$('#bst-messages-list'+formid).append('<div class="sent-msg mrgn-b-lg"><div class="clearfix"><div class="thumb-wid mrgn-l-sm pull-right"> <img class="img-responsive img-circle" src="'+src+'" width="95" height="95" alt="message sender image"> </div><div class="thumb-content"><div class="pad-all-md mrgn-b-xs pos-relative msg-wrap fw-light arrow-box-right"><p class="mrgn-all-none">'+comment+'</p></div><div class="clearfix"> <span class="pull-left base-dark display-ib">'+userName+'</span> <span class="pull-right display-ib">'+timeStamp+'</span> </div></div></div></div>');
						$('#chatForm'+formid)[0].reset();
					}else{
						alert("An error occured!");
						return false;
					}
				}			
			});
		}
	});
});

//File Thumbnails
$("#fileUpload").change(function(){
	readURL(this);
});
	
	
function readURL(input) {
	if (input.files && input.files[0]) {
		$('.imageThumbnails').html('');
		$.each(input.files, function (i) {
			$('.imageThumbnails').append('<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4" id="imageDiv'+i+'"><div class="overlay-wrap text-center mrgn-b-md"><img id="blah'+i+'" class="img-responsive display-ib" src="" width="650" height="650" alt=""><div class="hover-overlay pos-center primary-tp-layer"><div class="center-holder"><a href="javascript:;" class="btn btn-white btn-block" onclick="removeImg('+i+');">Remove</a></div></div></div></div>');
			$.each(input.files[i], function (key, val) {
				//alert(key + "****" + val);
				if(key == 'type' && (val == 'image/jpeg' || val == 'image/png')){
					var reader = new FileReader();
					reader.onload = function (e) {
						$('#blah'+i).attr('src', e.target.result);
					}
					
					reader.readAsDataURL(input.files[i])
				}
				if(key == 'type' && val == 'application/pdf'){
					$('#blah'+i).attr('src', image_url+"/avatars/pdf-icon.png");
				}
				if(key == 'type' && (val == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || val == 'application/docx')){
					$('#blah'+i).attr('src', image_url+"/avatars/docx-icon.png");
				}
				if(key == 'type' && (val == 'application/postscript' || val == 'application/ai')){
					$('#blah'+i).attr('src', image_url+"/avatars/ai-icon.png");
				}
				if(key == 'type' && val == 'text/plain'){
					$('#blah'+i).attr('src', image_url+"/avatars/txt-icon.png");
				}
				if(key == 'type' && val == ''){
					$('#blah'+i).attr('src', image_url+"/avatars/rar-icon.png");
				}
			});
		});
		
		return false;
		
	}
}
function removeImg(id){
	$('#imageDiv'+id).remove();
}
</script> 
<script type="text/javascript">
$(document).ready(function(){
	//Dragula Script
	
	
	
	/*function $(id) {
	  return document.getElementById(id);
	}*/
	
	dragula([document.getElementById('dragula-lft-event'), document.getElementById('dragula-rgt-event')],{
          revertOnSpill: true,
    }).on('drop', function(el){
		  el.className += ' temp_drag';
		  var userid = $('.temp_drag').data('userid');
		  var segment = $('.temp_drag').data('segment');
		  var project = $('.temp_drag').data('project');
		  $('#taguserid').val(userid);
		  $('#tagsegmentid').val(segment);
		  $('#tagprojectid').val(project);
		  var clsname = $('.temp_drag').data('class');
		  if(clsname == 'added'){
			  $('.temp_drag').attr('data-class', 'not_added');
		  	  el.className = 'ex-moved';
			  updateUserSegmentInverse();
		  }else{
			  $('.temp_drag').attr('data-class', 'added');
			  el.className = 'ex-moved';
			  updateUserSegment();
		  }
	});//-- end of dragular
	
	//Tag User
	function updateUserSegment(){
		$.ajax({
			type:"POST",
			url:'../user-tag',
			data:$('#tag_user').serialize(),
			success:function(d)
			{
				if(d == "success"){
					alert("User added successfully");
					location.reload();
				}else{
					alert("An error occured!");
					return false;
				}
			}			
		});
	};
	
	//remove Tagged User
	function updateUserSegmentInverse(){
		$.ajax({
			type:"POST",
			url:'../remove-user-tag',
			data:$('#tag_user').serialize(),
			success:function(d)
			{
				if(d == "success"){
					alert("User removed successfully");
					location.reload();
				}else{
					alert("An error occured!");
					return false;
				}
			}			
		});
	};

	
	$('.refresh_page').click(function(){
		location.reload();
	});
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