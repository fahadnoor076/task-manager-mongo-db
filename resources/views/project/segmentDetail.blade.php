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
              <li class="breadcrumb-item text-capitalize"><a href="{!! URL::to(DIR_ADMIN.$module.'/detail/'.$project['_id']->{'$id'}) !!}">Detail</a></li>
              <li class="breadcrumb-item active"><a href="javascript:;">{!! $page_action !!}</a></li>
            </ul>
            <div class="text-right"> <a href="#addTask" class="btn btn-primary" data-toggle="modal">+ Add Task</a> </div>
          </div>
          <!-- Session Messages --> 
          @include(DIR_ADMIN.'flash_message')
          <div class="form-validation-style">
            <div class="bst-block">
              <div class="horizontal-form-style">
                <div class="bst-block-title mrgn-b-lg">
                  <h3>{!! $module !!} Segment Details</h3>
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
                        
                        <!--<dt class="mrgn-b-xs">Euismod</dt>
                                            <dd>Donec id elit non mi porta gravida at eget metus.</dd>-->
                      </dl>
                    </div>
                    <hr>
                    <h3>Segment Details</h3>
                    @if(isset($segment['orderOf']) && $segment['orderOf'] == 'logo')
                    <div class="des-style-2">
                      <dl class="dl-horizontal">
                        <dt class="mrgn-b-xs">Exact name to be appeared on logo:</dt>
                        <dd>{!! $segment['logoName'] !!}</dd>
                        <dt class="mrgn-b-xs">Slogan(if any):</dt>
                        <dd>{!! $segment['logoSlogan'] !!}</dd>
                        <dt class="mrgn-b-xs">Logo style preferred:</dt>
                        <dd>{!! $segment['logoStylePreferred'] !!}</dd>
                        <dt class="mrgn-b-xs">Look & Feel:</dt>
                        <dd>{!! $segment['logoLookAndFeel'] !!}</dd>
                        <dt class="mrgn-b-xs">No. of concepts:</dt>
                        <dd>{!! $segment['logoConcepts'] !!}</dd>
                        <dt class="mrgn-b-xs">Additional comments:</dt>
                        <dd>{!! $segment['logoAdditionalComments'] !!}</dd>
                        <dt class="mrgn-b-xs">Logo industry:</dt>
                        {{--*/ $industries = logoIndustries(); /*--}}
                        @if($industries != "")
                        @foreach($industries as $key => $value)
                        @if($key == $segment['logoIndustry'])
                        {{--*/ $industry = $value; /*--}}
                        @endif
                        @endforeach
                        @endif
                        <dd>{!! $industry !!}</dd>
                        <dt class="mrgn-b-xs">Business description: </dt>
                        <dd>{!! $segment['logoBusinessDescription'] !!}</dd>
                        <dt class="mrgn-b-xs">Target audience:</dt>
                        <dd>{!! $segment['logoTargetAudience'] !!}</dd>
                      </dl>
                    </div>
                    @elseif(isset($segment['orderOf']) && $segment['orderOf'] == 'website')
                    <div class="des-style-2">
                      <dl class="dl-horizontal">
                        <dt class="mrgn-b-xs">Business name:</dt>
                        <dd>{!! $segment['websiteBusinessName'] !!}</dd>
                        <dt class="mrgn-b-xs">Slogan(if any):</dt>
                        <dd>{!! $segment['websiteSlogan'] !!}</dd>
                        <dt class="mrgn-b-xs">No. of concepts:</dt>
                        <dd>{!! $segment['websiteConcepts'] !!}</dd>
                        <dt class="mrgn-b-xs">Look & Feel:</dt>
                        <dd>{!! $segment['websiteLookAndFeel'] !!}</dd>
                        <dt class="mrgn-b-xs">Websites you like:</dt>
                        <dd>{!! $segment['websiteLike'] !!}</dd>
                        <dt class="mrgn-b-xs">Navigation menu preference:</dt>
                        <dd>{!! $segment['websiteNavMenuPreference'] !!}</dd>
                        <dt class="mrgn-b-xs">Existing domain:</dt>
                        <dd>{!! $segment['websiteExistingDomain'] !!}</dd>
                        <dt class="mrgn-b-xs">Domain preference:</dt>
                        <dd>{!! $segment['websiteDomainPreference'] !!}</dd>
                        <dt class="mrgn-b-xs">Additional comments:</dt>
                        <dd>{!! $segment['websiteAdditionalComments'] !!}</dd>
                        <dt class="mrgn-b-xs">Website industry:</dt>
                        {{--*/ $industries = websiteIndustries(); /*--}}
                        @if($industries != "")
                        @foreach($industries as $key => $value)
                        @if($key == $segment['websiteIndustry'])
                        {{--*/ $industry = $value; /*--}}
                        @endif
                        @endforeach
                        @endif
                        <dd>{!! $industry !!}</dd>
                        <dt class="mrgn-b-xs">Business description: </dt>
                        <dd>{!! $segment['websiteBusinessDescription'] !!}</dd>
                        <dt class="mrgn-b-xs">Target audience:</dt>
                        <dd>{!! $segment['websiteTargetAudience'] !!}</dd>
                      </dl>
                    </div>
                    @elseif(isset($segment['orderOf']) && $segment['orderOf'] == 'video')
                    <div class="des-style-2">
                      <dl class="dl-horizontal">
                        <dt class="mrgn-b-xs">Business name:</dt>
                        <dd>{!! $segment['videoBusinessName']; !!}</dd>
                        <dt class="mrgn-b-xs">Slogan(if any):</dt>
                        <dd>{!! $segment['videoSlogan']; !!}</dd>
                        <dt class="mrgn-b-xs">No. of concepts:</dt>
                        <dd>{!! $segment['videoConcepts']; !!}</dd>
                        <dt class="mrgn-b-xs">Website address:</dt>
                        <dd>{!! $segment['videoWebsiteAddress']; !!}</dd>
                        <dt class="mrgn-b-xs">Animation you like:</dt>
                        {{--*/ $animations = videoAnimationStyles(); /*--}}
                        @if($animations != "")
                        @foreach($animations as $key => $value)
                        @if($key == $segment['videoAnimationStyle'])
                        {{--*/ $animation = $value; /*--}}
                        @endif
                        @endforeach
                        @endif
                        <dd>{!! $animation; !!}</dd>
                        <dt class="mrgn-b-xs">Primary use of video:</dt>
                        <dd>{!! $segment['videoPrimaryUse'] !!}</dd>
                        <dt class="mrgn-b-xs">Additional comments:</dt>
                        <dd>{!! $segment['videoAdditionalComments'] !!}</dd>
                        <dt class="mrgn-b-xs">Video industry:</dt>
                        {{--*/ $industries = videoIndustries(); /*--}}
                        @if($industries != "")
                        @foreach($industries as $key => $value)
                        @if($key == $segment['videoIndustry'])
                        {{--*/ $industry = $value; /*--}}
                        @endif
                        @endforeach
                        @endif
                        <dd>{!! $industry !!}</dd>
                        <dt class="mrgn-b-xs">Business description: </dt>
                        <dd>{!! $segment['videoBusinessDescription'] !!}</dd>
                        <dt class="mrgn-b-xs">Target audience:</dt>
                        <dd>{!! $segment['videoTargetAudience'] !!}</dd>
                      </dl>
                    </div>
                    @else
                    @endif </div>
                </div>
                <div class="bst-users-listing clearfix">
                  <div class="row">
                    <div class="bst-block">
                      <div class="bst-block-title mrgn-b-md">
                        <div class="caption">
                          <h3 class="text-capitalize">Segment Tasks</h3>
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
                                {{--*/ $count = 0; /*--}}
                                @foreach($tasks as $task) 
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
                            <tr> {{--*/ $count++; /*--}}
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
                                @endif</td>
                              <td>{!! "Assigned By"; !!}</td>
                              <td><a class="btn btn-outline-success btn-xs" type="button" href="{!! URL::to(config('/')); !!}/task/detail/{!! $task['_id']->{'$id'}; !!}" data-toggle="tooltip" title="Task Details" data-original-title="Task Details"><span><i class="fa fa-eye" aria-hidden="true"></i></span> Details</a></td>
                            </tr>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Specs Form -->
<div id="addTask" class="modal fade" aria-hidden="true">
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
            <input type="hidden" name="taskDueDate" value="{!! ($duedate != "")? $duedate : date('Y-m-d H:i:s' ,strtotime(date('Y-m-d')." 05:00:00" . "+1 days")); !!}" />
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
                
                                        @if($segmentUsers > 0)
                                            @foreach($segmentUsers as $key => $user)
                                                
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
          <input type="hidden" name="segmentId" value="{!! $segment['_id']->{'$id'}; !!}" />
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