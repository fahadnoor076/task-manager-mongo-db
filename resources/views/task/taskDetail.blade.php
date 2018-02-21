@include(DIR_ADMIN.'header')

{{--*/
// statuses
$admin_statuses = config("constants.ADMIN_STATUSES");
$token = csrf_token();
/*--}}
<style>
.error{
	color: #f00;
}
.arrow-box-right {
    position: relative !important;
    background: #eee !important;
    border: 1px solid #9e9e9e !important;
}
.sent-msg .arrow-box-right p {
    color: #646464 !important;
}
.sent-msg .arrow-box-right:after {
    border-left-color: #eee !important;
}
.brdrd{
	border: 1px solid #eee;
	border-radius:8px;	
}
</style>
<div class="bst-wrapper"> @include(DIR_ADMIN.'side_overlay')
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
            <!--<div class="text-right"> <a href="#addTask" class="btn btn-primary" data-toggle="modal">+ Add Task</a> </div>-->
          </div>
          <!-- Session Messages --> 
          @include(DIR_ADMIN.'flash_message')
          <div class="form-validation-style">
            <div class="bst-block">
              <div class="horizontal-form-style">
                <div class="bst-block-title mrgn-b-lg">
                  <h3>{!! ucfirst($segment['orderOf'])." ".$module !!} Details</h3>
                  <!--<p></p>--> 
                </div>
                <div class="typography-widget col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <div class="bst-block">
                    <div class="bst-block-title mrgn-b-lg">
                      <h3>Project Name: {!! $project['projectName'] !!}</h3>
                      <p>Priority: <b>{!! $project['projectPriority'] !!}</b></p>
                    </div>
                    <div class="des-style">
                      <dl>
                        <dt class="mrgn-b-xs">Project Description</dt>
                        <dd>{!! $project['projectDescription'] !!}</dd>
                        <!-- Task Status -->
                        {{--*/ $taskStatusArr = array(); $taskStatus = ""; /*--}}
                        @if($data['orderOf'] == 'logo')
                            {{--*/ $taskStatusArr = logoTaskStatus(); /*--}}
                        @elseif($data['orderOf'] == 'website')
                            {{--*/ $taskStatusArr = websiteTaskStatus(); /*--}}
                        @elseif($data['orderOf'] == 'video')
                            {{--*/ $taskStatusArr = videoTaskStatus(); /*--}}
                        @else
                            {{--*/ $taskStatusArr = mobileTaskStatus(); /*--}}
                        @endif
                        
                        @if(!empty($taskStatusArr))
                            @foreach($taskStatusArr as $key => $status)
                                @if($key == $data['taskStatus'])
                                    {{--*/ $taskStatus = $status; /*--}}
                                @endif
                            @endforeach
                        @endif
                        <!-- Task Status End -->
                        <dt class="mrgn-b-xs">Task Status</dt>
                        <dd>{!! $taskStatus; !!}</dd>
                      </dl>
                    </div>
                    <hr>
                    <div class="timeline timeline-line-dotted">
                        <div class="timeline-item">
                        	<div class="timeline-event" style="overflow-x:hidden; overflow-y:scroll; height:530px;">
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
                                    @else
                                        <p>No files uploaded!</p>
                                    @endif
                                    <!-- reference files end -->
                               @endif
                            </div>
                        </div>
                        
                        <div class="timeline-item timeline-item-new" style="margin-top:0px;">
                        	<div class="timeline-event" style="overflow-x:hidden; overflow-y:scroll; height:530px;">
                            	<div class="clearfix"></div>
                                <h5 class="mrgn-b-lg"><span class="display-ib mrgn-r-sm"><i class="fa fa-folder-open-o" aria-hidden="true"></i></span>Task Files</h5>
                                <!-- Task Files -->
                                <div class="bst-block-content">
                                    <div class="table-responsive" style="max-height:475px;">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>File</th>
                                                    <th>AddedBy</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(!empty($taskFiles))
                                                    {{--*/ $count = 0; /*--}}
                                                    @foreach($taskFiles['result'] as $key => $file)
                                                        {{--*/ $count++; /*--}}
                                                        <tr>
                                                            <td>{!! $count; !!}</td>
                                                            {{--*/  $ext = pathinfo($file['fileName'], PATHINFO_EXTENSION); /*--}}
                                                            @if($ext == 'jpg' || ($ext == 'png' || $ext == 'jpeg'))
                                                                {{--*/ $fileName = URL::to(config('constants.ADMIN_RESOURCE_URL')).'/taskfiles/'.$file['directoryPath'].$file['fileName']; /*--}}
                                                            @elseif($ext == 'eps' || $ext == 'ai')
                                                                {{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/ai-icon.png'; /*--}}
                                                            @elseif($ext == 'docx' || $ext == 'doc')
                                                                {{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/docx-icon.png'; /*--}}
                                                            @elseif($ext == 'pdf')
                                                                {{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/pdf-icon.png'; /*--}}
                                                            @elseif($ext == 'txt')
                                                                {{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/txt-icon.png'; /*--}}
                                                            @elseif($ext == 'rar')
                                                                {{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/rar-icon.png'; /*--}}
                                                            @endif
                                                            <td><a class="{!! 'file'.$count; !!}" href="#basic" data-toggle="modal" onclick="popupImg({!! $count; !!});" data-title="{!! $file['fileName']; !!}" data-src="{!! $fileName; !!}"><img class="img-responsive mrgn-b-md" src="{!! $fileName; !!}" width="50" height="50" alt="{!! $file['fileName']; !!}" title="{!! $file['fileName']; !!}" /></a></td>
                                                            <td>{!! $file['userArray'][0]['userName']; !!}</td>
                                                            <td>{!! date('M d, Y H:i:s', strtotime($file['addedAt'])); !!}</td>
                                                            <td><a download="{!! $file['fileName']; !!}" href="{!! URL::to(config('constants.ADMIN_RESOURCE_URL')).'/taskfiles/'.$file['directoryPath'].$file['fileName']; !!}" title="Download"><i class="fa fa-download fa-lg base-dark" aria-hidden="true"></i></a></td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr><td><p>No Files(s) found!</p></td></tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- task Files End -->
                            </div>
                        </div>
                    </div>
               	</div>
                <div class="clearfix"></div>
                <div class="bst-users-listing clearfix">
                  <div class="row">
                    <div class="bst-block" style="min-height:1000px; height:auto;">
                      <div class="bst-block-title mrgn-b-md">
                        <div class="caption">
                          <h3 class="text-capitalize">Task Chat</h3>
                        </div>
                      </div>
                      <div class="bst-block-content">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 brdrd">
                            <div class="chat-window pad-l-lg  clearfix">
                                <div class="tab-content mrgn-b-lg">
                                    <div class="bst-messages-list" style="height:600px; overflow-x:scroll;">
                                    	<!-- Task Description -->
                                        @if(isset($data['taskDescription']))
                                            <div class="sent-msg mrgn-b-lg">
                                                <div class="clearfix">
                                                    <div class="thumb-wid mrgn-l-sm pull-right"> <span class="square-50 bg-danger img-circle base-reverse mrgn-r-md">TD</span> </div>
                                                    <div class="thumb-content">
                                                        <div class="pad-all-md mrgn-b-xs pos-relative msg-wrap fw-light arrow-box-right">
                                                            <span class="error" style="font-size:12px;">(Task Description)</span>
                                                            <p class="mrgn-all-none">{!! $data['taskDescription']; !!}</p>
                                                                                                                            </div>
                                                        <div class="clearfix"> <span class="pull-left base-dark display-ib">{!! $data['userArray'][0]['userName']; !!}</span> <span class="pull-right display-ib">{!! date('M d, Y H:i:s', strtotime($data['addedAt'])); !!}</span> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <!-- Task Description End -->
                                    	@if(isset($taskChat[0]) && !empty($taskChat[0]))
                                        	@foreach($taskChat as $taskChat)
                                            	{{--*/ $taskType = ""; /*--}}
                                                @if($taskChat['postType'] == 1)
                                                	{{--*/ $taskType = "(Clarification)"; /*--}}
                                                @elseif($taskChat['postType'] == 2)
                                                	{{--*/ $taskType = "(Post Design)"; /*--}}
                                                @elseif($taskChat['postType'] == 3)
                                                	{{--*/ $taskType = "(Revision)"; /*--}}
                                                @elseif($taskChat['postType'] == 4)
                                                	{{--*/ $taskType = "(Redraw)"; /*--}}
                                                @elseif($taskChat['postType'] == 8)
                                                	{{--*/ $taskType = "(Final Files)"; /*--}}
                                                @endif
                                            	{{--*/ $username = substr($taskChat['userArray'][0]['userName'],0,1); /*--}}
                                                {{--*/ $userImage = '<span class="square-50 bg-default img-circle base-reverse mrgn-r-md">'.$username.'</span>'; /*--}}
                                                @if($taskChat['userArray'][0]['userProfileImage'] != "")
                                                    {{--*/ $profileImage = URL::to(config('/').DIR_PROFILE_IMG.'/'.$taskChat['userArray'][0]['userProfileImage']); /*--}
                                                    {{--*/ $userImage= '<img class="img-responsive img-circle" src="'.$profileImage.'" width="95" height="95" alt="User Image">'; /*--}}
                                                @endif
                                            	@if($taskChat['fkAddedById']->{'$id'} != $userId)
                                                	<div class="sent-msg mrgn-b-lg">
                                                        <div class="clearfix">
                                                            <div class="thumb-wid mrgn-l-sm pull-right"> {!! $userImage; !!} </div>
                                                            <div class="thumb-content">
                                                                <div class="pad-all-md mrgn-b-xs pos-relative msg-wrap fw-light arrow-box-right">
                                                                	<span class="error" style="font-size:12px;">{!! $taskType; !!}</span>
                                                                    <p class="mrgn-all-none">{!! $taskChat['postDesc']; !!}</p>
                                                                    @if(!empty($taskChat['fileArray'][0]))
                                                                    	<div class="row">
                                                                        	{{--*/ $attachments = 0; /*--}}
                                                                        	{{--*/ $attachments = count($taskChat['fileArray']); /*--}}
                                                                        	<div class="clearfix" style="padding:12px 0px 6px 16px;">
                                                                            	<img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'update-request-icon.png'); !!}" alt="Attachments" title="Attachments" width="25" />
                                                                            	{!! $attachments !!} Attachment(s)
                                                                            </div>
                                                                        	@foreach($taskChat['fileArray'] as $taskFile)
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
                                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                                    <div class="overlay-wrap text-center mrgn-b-md">
                                                                                        <img class="img-responsive display-ib" src="{!! $file !!}" width="150" height="150" alt="Task File">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                    		
                                                                    	</div>
                                                                    @endif
                                                                </div>
                                                                <div class="clearfix"> <span class="pull-left base-dark display-ib">{!! $taskChat['userArray'][0]['userName']; !!}</span> <span class="pull-right display-ib">{!! date('M d, Y H:i:s',strtotime($taskChat['addedAt'])); !!}</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                	<div class="mrgn-b-lg">
                                                        <div class="clearfix">
                                                            <div class="thumb-wid mrgn-r-sm pull-left"> {!! $userImage; !!} </div>
                                                            <div class="thumb-content pull-left">
                                                                <div class="pad-all-md mrgn-b-xs msg-wrap arrow-box pos-relative">
                                                                	<span class="error" style="font-size:12px;">{!! $taskType; !!}</span>
                                                                    <p class="mrgn-all-none">{!! $taskChat['postDesc']; !!}</p>
                                                                    @if(!empty($taskChat['fileArray'][0]))
                                                                    	<div class="row">
                                                                        	{{--*/ $attachments = 0; /*--}}
                                                                        	{{--*/ $attachments = count($taskChat['fileArray']); /*--}}
                                                                        	<div class="clearfix" style="padding:12px 0px 6px 16px;">
                                                                            	<img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'update-request-icon.png'); !!}" alt="Attachments" title="Attachments" width="25" />
                                                                            	{!! $attachments !!} Attachment(s)
                                                                            </div>
                                                                        	@foreach($taskChat['fileArray'] as $taskFile)
                                                                            	@if(file_exists('./resources/assets/taskfiles/'.$taskFile['directoryPath'].$taskFile['fileName']))
                                                                        			{{--*/ $file = URL::to(config('constants.ADMIN_RESOURCE_URL')."taskfiles/".$taskFile['directoryPath'].$taskFile['fileName']); /*--}}
                                                                        		@else
                                                                                	{{--*/ $file = URL::to(config('constants.ADMIN_IMG_URL')."180x180.jpg"); /*--}}
                                                                                @endif
                                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                                    <div class="overlay-wrap text-center mrgn-b-md">
                                                                                        <img class="img-responsive display-ib" src="{!! $file !!}" width="150" height="150" alt="Task File">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                    		
                                                                    	</div>
                                                                    @endif
                                                                </div>
                                                                <div class="clearfix"> <span class="pull-left base-dark display-ib">{!! $taskChat['userArray'][0]['userName']; !!}</span> <span class="pull-right display-ib">{!! date('M d, Y H:i:s',strtotime($taskChat['addedAt'])); !!}</span> </div>
                                                            </div>
                                                        </div>
                                                    </div>                                         
                                            	@endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!-- tab-content closed -->
                                <!-- Buttons -->
                                <div class="social-icon mrgn-b-lg">
                                {{--*/ $postDesignArr = array('2','7','10','13','17','22','25','28','32','37','40','43','47','52','55','58'); /*--}}
                                
                                {{--*/ $finalFilesArr = array('4','9','12','15','19','24','27','30','34','39','42','45','49','54','57','60'); /*--}}
                                
                                {{--*/ $revisionRedrawArr = array('4','12','15','19','27','30','34','42','45','49','57','60'); /*--}}
                                
                                {{--*/ $taskApproveArr = array('3','8','11','14','18','23','26','29','33','38','41','44','48','53','56','59'); /*--}}
                                
                                {{--*/ $taskRejectArr = array('3','8','11','14','18','23','26','29','33','38','41','44','48','53','56','59','5','20','35','50'); /*--}}
                                
                                {{--*/ $taskProcessArr = array('5','20','35','50'); /*--}}
                                @if(isset($privileges['task-comment']) && $privileges['task-comment'] == 1)
                                    <button type="button" class="btn btn-outline-primary mrgn-b-xs pst_addComment"> <i class="fa fa-comment fa-lg"></i> Add Comment </button>
                                @endif
                                @if(isset($privileges['ask-final-files']) && $privileges['ask-final-files'] == 1)
                                	@if(in_array($data['taskStatus'], $finalFilesArr))
                                    	<button type="button" class="btn btn-outline-inverse mrgn-b-xs pst_askFiles"> <i class="fa fa-paper-plane fa-lg"></i> Ask for final files </button>
                                    @else
                                    	<button type="button" class="btn btn-outline-inverse mrgn-b-xs" disabled="disabled"> <i class="fa fa-paper-plane fa-lg"></i> Ask for final files </button>
                                    @endif
                                @endif
                                @if(isset($privileges['revision-redraw']) && $privileges['revision-redraw'] == 1)
                                	@if(in_array($data['taskStatus'], $revisionRedrawArr))
                                    	<button type="button" class="btn btn-outline-warning mrgn-b-xs pst_revisionRedraw"> <i class="fa fa fa-pencil-square-o fa-lg"></i> Revision/Redraw </button>
                                    @else
                                    	<button type="button" class="btn btn-outline-warning mrgn-b-xs" disabled="disabled"> <i class="fa fa fa-pencil-square-o fa-lg"></i> Revision/Redraw </button>
                                    @endif
                                @endif
                                @if(isset($privileges['task-approve']) && $privileges['task-approve'] == 1)
                                	@if(in_array($data['taskStatus'], $taskApproveArr))
                                   		<button type="button" class="btn btn-outline-success mrgn-b-xs pst_approve"> <i class="fa fa-thumbs-o-up fa-lg"></i> Approve </button>
                                    @else
                                    	<button type="button" class="btn btn-outline-success mrgn-b-xs" disabled="disabled"> <i class="fa fa-thumbs-o-up fa-lg"></i> Approve </button>
                                    @endif
                                @endif
                                @if(isset($privileges['task-reject']) && $privileges['task-reject'] == 1)
                                	@if(in_array($data['taskStatus'], $taskRejectArr))
                                    	<button type="button" class="btn btn-outline-danger mrgn-b-xs pst_reject"> <i class="fa fa-thumbs-o-down fa-lg"></i> Reject </button>
                                    @else
                                    	<button type="button" class="btn btn-outline-danger mrgn-b-xs" disabled="disabled"> <i class="fa fa-thumbs-o-down fa-lg"></i> Reject </button>
                                    @endif
                                @endif
                                @if(isset($privileges['task-processed']) && $privileges['task-processed'] == 1)
                                	@if(in_array($data['taskStatus'], $taskProcessArr))
                                    	<button type="button" class="btn btn-outline-base mrgn-b-xs pst_proceed"> <i class="fa fa-handshake-o fa-lg"></i> Processed </button>
                                    @else
                                    	<button type="button" class="btn btn-outline-base mrgn-b-xs" disabled="disabled"> <i class="fa fa-handshake-o fa-lg"></i> Processed </button>
                                    @endif
                                @endif
                                @if(isset($privileges['post-design']) && $privileges['post-design'] == 1)
                                	@if(in_array($data['taskStatus'], $postDesignArr))
                                    	<button type="button" class="btn btn-outline-primary mrgn-b-xs pst_postDesign"> <i class="fa fa-external-link fa-lg"></i> Post design </button>
                                    @else
                                    	<button type="button" class="btn btn-outline-primary mrgn-b-xs" disabled="disabled"> <i class="fa fa-external-link fa-lg"></i> Post design </button>
                                    @endif
                                @endif
                                @if(isset($privileges['view-designs']) && $privileges['view-designs'] == 1)
                                    <button type="button" class="btn btn-outline-success mrgn-b-xs pst_viewDesigns"> <i class="fa fa-eye fa-lg"></i> Veiw all designs </button>
                                @endif
                                    
                                </div>
                                <!-- Buttons End -->
                                <div class="send-msg-form col-sm-12 col-xs-12 col-md-12 col-lg-12 pull-right pad-all-none">
                                    {!! Form::open(array('files'=>true, 'name'=>'post_form', 'id'=>'post_form', 'enctype'=>'multipart/form-data', 'style'=>'display:none;')); !!}
                                        <div class="form-group clearfix">
                                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-11 pad-all-none">
                                            	<h3 class="postTitle"></h3>
                                                <textarea class="pad-all-sm form-control mrgn-b-sm postDesc" name="postDesc" placeholder="Write a message"></textarea>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-1 pad-all-none" style="margin-top:33px;">
                                            	<input type="hidden" name="do_task_post" value="1" />
                                                <input type="hidden" name="projectId" value="{!! $data['fkProjectId']; !!}" />
                                                <input type="hidden" name="segmentId" value="{!! $data['fkSegmentId']; !!}" />
                                                <input type="hidden" name="phaseId" value="{!! $data['fkPhaseId']; !!}" />
                                                <input type="hidden" name="taskId" value="{!! $data['_id']; !!}" />
                                                <input type="hidden" name="brandId" value="{!! $data['fkBrandId']; !!}" />
                                            	<input type="hidden" name="taskType" class="taskType" value="">
                                                <input type="hidden" name="orderOf" value="{!! $data['orderOf']; !!}">
                                                <input type="hidden" name="_token" value="{!! $token; !!}">
                                                <input class="btn btn-primary btn-lg btn-block" name="send_message" value="Send" type="submit">
                                            </div>
                                            <div class="revisionDiv clearfix"></div>
                                            <div class="form-group span_image">
                                                <label class="file-field-label">
                                                    <input type="file" name="postFiles[]" id="fileUpload" multiple />
                                                    <span>
                                                        <i class="fa fa-paperclip mrgn-r-sm" aria-hidden="true"></i>
                                                    </span> Attach Files
                                                </label>
                                            </div>
                                        </div>
                                    {!! Form::close(); !!}
                                    
                                    <!-- Image Thumbnail Display -->
                                    <div class="row imageThumbnails"></div>
                                    <!-- Image Thumbnail Display End --> 
                                    
                                </div>
                            </div>
                            <!-- chat-window closed -->
                        </div>
                      </div>
                      <!-- Task Files -->
                      <!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 brdrd" style="height:600px; overflow-x:scroll;">
                      	  <div class="bst-block-title mrgn-b-md">
                            <div class="caption">
                              <h3 class="text-capitalize">Task Files</h3>
                            </div>
                          </div>
                      	<div class="bst-block-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                        	<th>#</th>
                                            <th>File</th>
                                            <th>AddedBy</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	@if(!empty($taskFiles))
                                        	{{--*/ $count = 0; /*--}}
                                        	@foreach($taskFiles['result'] as $key => $file)
                                            	{{--*/ $count++; /*--}}
                                            	<tr>
                                                	<td>{!! $count; !!}</td>
                                                    {{--*/  $ext = pathinfo($file['fileName'], PATHINFO_EXTENSION); /*--}}
                                                    @if($ext == 'jpg' || ($ext == 'png' || $ext == 'jpeg'))
                                                    	{{--*/ $fileName = URL::to(config('constants.ADMIN_RESOURCE_URL')).'/taskfiles/'.$file['directoryPath'].$file['fileName']; /*--}}
                                                    @elseif($ext == 'eps' || $ext == 'ai')
                                                    	{{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/ai-icon.png'; /*--}}
                                                    @elseif($ext == 'docx' || $ext == 'doc')
                                                    	{{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/docx-icon.png'; /*--}}
                                                    @elseif($ext == 'pdf')
                                                    	{{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/pdf-icon.png'; /*--}}
                                                    @elseif($ext == 'txt')
                                                    	{{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/txt-icon.png'; /*--}}
                                                    @elseif($ext == 'rar')
                                                    	{{--*/ $fileName = URL::to(config('constants.ADMIN_IMG_URL')).'/avatars/rar-icon.png'; /*--}}
                                                    @endif
                                                    <td><a class="{!! 'file'.$count; !!}" href="#basic" data-toggle="modal" onclick="popupImg({!! $count; !!});" data-title="{!! $file['fileName']; !!}" data-src="{!! $fileName; !!}"><img class="img-responsive mrgn-b-md" src="{!! $fileName; !!}" width="50" height="50" alt="{!! $file['fileName']; !!}" title="{!! $file['fileName']; !!}" /></a></td>
                                                    <td>{!! $file['userArray'][0]['userName']; !!}</td>
                                                    <td>{!! date('M d, Y H:i:s', strtotime($file['addedAt'])); !!}</td>
                                                    <td><a download="{!! $file['fileName']; !!}" href="{!! URL::to(config('constants.ADMIN_RESOURCE_URL')).'/taskfiles/'.$file['directoryPath'].$file['fileName']; !!}" title="Download"><i class="fa fa-download fa-lg base-dark" aria-hidden="true"></i></a></td>
                                                </tr>
                                            @endforeach
                                        @else
                                        	<tr><td><p>No Files(s) found!</p></td></tr>
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                      </div>-->
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

<!-- Image Popup -->
<div class="modal fade" id="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modalTitle"></h4> </div>
            <div class="modal-body">
                <img class="img-responsive mrgn-b-md" id="modalSrc" src="" width="650" alt="" title="">
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Image Popup End -->
<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="taskForm"]').submit(function(e) {
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
	/*$('form[name="post_form"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_]").removeClass("show").addClass("hide");
		return jsonValidate('{!! $id !!}',$(this));
	});*/
});

function popupImg(id){
	var title = $('.file'+id).data('title');
	var src = $('.file'+id).data('src');
	$('#modalTitle').html(title);
	$('#modalSrc').attr('src', src);
	$('#modalSrc').attr('alt', title);
	$('#modalSrc').attr('title', title);
}

</script> 
<script type="text/javascript">
$(document).ready(function(){
	
	var chat = $(".bst-messages-list");
	chat.scrollTop($(".thumb-content").last().offset().top);
	
	//$('.bst-messages-list').animate({scrollTop: $(".thumb-content").last().offset().top}, 'slow');
	
	$(".chosen-select").chosen();
	
	$('.span_image').click(function(){
		$('#image_form').css('display', 'block');
	});
	
	$('.pst_addComment').click(function(){
		var taskType = $('.taskType').val();
		var status = $('#post_form').css('display');
		$('.revisionDiv').html('');
		if(status == 'none' || taskType != 1){
			$('#post_form').hide();
			$('.taskType').val(1);
			$('.postTitle').html('Ask for Clarification');
			$('#post_form').fadeIn('slow');
			$('#image_form').fadeIn('slow');
		}else{
			$('.taskType').val('');
			$('#post_form').fadeOut('slow');
			$('#image_form').fadeOut('slow');
		}
		
	});
	$('.pst_askFiles').click(function(){
		//Askforfiles
		$('.postDesc').val('Asking for Final Files');
		$('.taskType').val(8);
		$('#post_form').submit();
	});
	$('.pst_revisionRedraw').click(function(){
		//revision redraw popup show
		var taskType = $('.taskType').val();
		var status = $('#post_form').css('display');
		if(status == 'none' || (taskType != 3 || taskType != 4)){
			$('#post_form').hide();
			$('.taskType').val(3);
			$('.postTitle').html('Add Revision/Redraw');
			$('.revisionDiv').append('<label for="revision"><input type="radio" name="revisionType" id="revision" value="0" checked="checked" onclick="revisionStatus(3);"><span class="mrgn-l-xs">Revision</span></label><label for="redraw"><input type="radio" name="revisionType" id="redraw" value="1" onclick="revisionStatus(4);"><span class="mrgn-l-xs">Redraw</span></label>');
			$('#post_form').show();
			$('.span_image').show();
		}else{
			$('.revisionDiv').html('');
			$('.taskType').val('');
			$('#post_form').hide();
			$('.span_image').hide();
		}
	});
	$('.pst_approve').click(function(){
		//Approve
		$('.postDesc').val('Design has been Approved!');
		$('.taskType').val(5);
		$('#post_form').submit();
	
	});
	$('.pst_reject').click(function(){
		var taskType = $('.taskType').val();
		var status = $('#post_form').css('display');
		$('.revisionDiv').html('');
		if(status == 'none' || taskType != 6){
			$('#post_form').hide();
			$('.taskType').val(6);
			$('.postTitle').html('Reject Reason');
			$('#post_form').show();
			$('.span_image').show();
		}else{
			$('.taskType').val('');
			$('#post_form').hide();
			$('.span_image').hide();
		}
	});
	$('.pst_proceed').click(function(){
		//Processed
		$('.postDesc').val('Design has been Processed!');
		$('.taskType').val(7);
		$('#post_form').submit();
	
	
	});
	$('.pst_postDesign').click(function(){
		var taskType = $('.taskType').val();
		var status = $('#post_form').css('display');
		$('.revisionDiv').html('');
		if(status == 'none' || taskType != 2){
			$('#post_form').hide();
			$('.taskType').val(2);
			$('.postTitle').html('Post your Design');
			$('#post_form').show();
			$('.span_image').show();
		}else{
			$('.taskType').val('');
			$('#post_form').hide();
			$('.span_image').hide();
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

var image_url = '{!! URL::to(config("constants.ADMIN_IMG_URL")); !!}';

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

function revisionStatus(id){
	$('.taskType').val(id);
}
</script> 
@include(DIR_ADMIN.'footer')