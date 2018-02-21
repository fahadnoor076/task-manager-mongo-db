@include(config('pl_{wildcard_identifier}.DIR_PANEL').'header') 
{{--*/
// generate queue_id
$queue_id = \Session::get(config('pl_{wildcard_identifier}.SESS_KEY')."auth")->{wildcard_pk}.'-'.uniqid();
/*--}}
<!-- <link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/dropzonejs/dropzone.min.css') !!}"> -->
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed"> 
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.css') !!}" />
@include(config('pl_{wildcard_identifier}.DIR_PANEL').'side_overlay')
  @include(config('pl_{wildcard_identifier}.DIR_PANEL').'sidebar')
  @include(config('pl_{wildcard_identifier}.DIR_PANEL').'nav_header')
  <main id="main-container"> 
    <!-- Page Header --> 
    <!-- Success Alert --> 
    <!-- END Success Alert -->
    <div class="content bg-gray-lighter">
      <div class="row items-push">
        <div class="col-sm-7">
          <h1 class="page-heading"> {!! $p_title !!} </h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
          <ol class="breadcrumb push-10-t">
            <li>{!! $p_title !!}</li>
            <li><a class="link-effect" href="javascript:;">{!! $page_action !!}</a></li>
          </ol>
        </div>
      </div>
    </div>
    <!-- END Page Header --> 
    
    <!-- Page Content -->
    <section class="content content-boxed"> 
      <!-- Section Content --> 
      <!--@if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{!! $error !!}</li>
          @endforeach
        </ul>
      </div>
      @endif-->
      <!-- Session Messages --> 
      @include(config('pl_{wildcard_identifier}.DIR_PANEL').'flash_message')
    <div class="block block-bordered">
            <div class="block-content">  
          <form name="main_setting" method="post" enctype="multipart/form-data">
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6">
                          <div class="form-material">
                            <input name="name" class="form-control input-lg" type="text" placeholder="Name" value="{!! \Session::get(config('pl_{wildcard_identifier}.SESS_KEY')."auth")->name !!}" />
                            <label for="mega-firstname"><span class="text-danger">*</span> Name </label>
                            <div id="error_msg_name" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                          </div>
                        </div>
                        <div class="col-xs-6">
                          <div class="form-material"> 
                            <!-- DropzoneJS Container --> 
                            <!--<div class="dropzone" id="upload_logo" action="base_forms_pickers_more.html"></div>--> 
                            <a href="javascript:;" class="img-container" id="upload_logo"> <img id="site_logo" width="100" src="{!! URL::to(config('pl_{wildcard_identifier}.DIR_IMG').\Session::get(config('pl_{wildcard_identifier}.SESS_KEY')."auth")->{wildcard_pk}.'/'.\Session::get(config('pl_{wildcard_identifier}.SESS_KEY')."auth")->image) !!}" alt="-"></a>
                            <div id="elfinder"></div> 
                            <label for="mega-firstname"><span class="text-danger">*</span> Logo</label>
                          </div>
                        </div>
                    </div>
                  </div>
                  <div class="row push-20-t">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-xs-6 pull-right">
                          <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-check"></i> Update</button>
                        </div>
                        <br clear="all" />
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="site_logo" value="{!! Session::get(config('pl_{wildcard_identifier}.SESS_KEY')."auth")->image !!}" />
                  <input type="hidden" name="do_post" value="1" />
                  <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                </form>
                </div>
                </div>
      <!-- END Section Content --> 
    </section>
    <!-- END Page Content --> 
  </main>
  <!--Dialog Box HTML Start --> 
  <!--<div id="dialog_confirm" title="Confirmation" style="display:none;">Are you really want to delete?</div>--> 
  
</div>
<!-- Session Messages --> 
@include(DIR_ADMIN.'elfinder') 
<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/jquery-validation/jquery.validate.min.js"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/pages/base_forms_wizard.js"></script> 
<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.js') !!}"></script> 
<!-- <script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/dropzonejs/dropzone.min.js"></script> -->

<script type="text/javascript">

var el_commands = [
  'open', 'reload', 'getfile', 'quicklook', 
  'download','duplicate', 'rename', 'mkdir', 'mkfile', 'upload', 'copy', 
  'cut', 'paste', 'edit', 'extract', 'search', 'info', 'view', 'help',
  'resize', 'sort'
];

$(function() {
  
  // default form submit/validate (main)
  $('form[name="main_setting"]').submit(function(e) {
    e.preventDefault();
    // hide all errors
    $("div[id^=error_msg_]").removeClass("show").addClass("hide");
    // validate form
    return jsonValidate('{!! $module !!}',$(this));
  });
  

  // upload logo action
  $("a#upload_logo").click(function(e) {
    e.preventDefault();
    var fm = $('<div/>').dialogelfinder({
      url : '{!! $module !!}/logo_browser?_token={!! csrf_token() !!}',
      lang : 'en',
      width : 840,
      commands : el_commands, 
      destroyOnClose : true,
      getFileCallback : function(files, fm) {
        //console.log(files);
        $("img#site_logo").prop("src","{!! URL::to(config('pl_{wildcard_identifier}.DIR_IMG').\Session::get(config('pl_{wildcard_identifier}.SESS_KEY')."auth")->{wildcard_pk}) !!}/"+files.name);
        $("input[name=site_logo]").val(files.name);
      },
      commandsOptions : {
        getfile : {
          oncomplete : 'close',
          folders : false
        }
      }
    }).dialogelfinder('instance');
    
  });


  // by default
    //initDropZone("image");
    
  // bulk dropzone
  // dropzoneObj = $("div#myDrop2").dropzone({
  //       url: '../../ajax/multi_uploader?_token={!! csrf_token() !!}&reserve_name=1',
  //       maxFilesize: 25,
  //       acceptedFiles: "image/*",
  //       maxFiles: 1,
  //       accept: function(file, done) {
  //         console.log("uploaded");
  //         done();
  //       },
  //       init: function() {
  //         this.on("maxfilesexceeded", function(file){
  //             alert("No more files please!");
  //         });
  //       }
  //   });
});

// function initDropZone(uploader_type) {
    
//     dropzoneObj = $("div#myDrop").dropzone({
//         url: '../../ajax/multi_uploader?_token={!! csrf_token() !!}',
//         maxFilesize: 25,
//         params:{queue_id: '{!! $queue_id !!}'},
//         acceptedFiles: "image/*",
//         maxFiles: 1,
//         accept: function(file, done) {
//           console.log("uploaded");
//           done();
//         },
//         init: function() {
//           this.on("maxfilesexceeded", function(file){
//               alert("No more files please!");
//           });
//         }
//     });
// }

// function progressHandler(event){
//     $("#progress").css('display', 'block');
//   var percent = (event.loaded / event.total) * 100;
    
//   $("#progress_bar").text(Math.round(percent)+ "%");
//     $("#progress_bar").css('width', Math.round(percent)+ "%");
// }

// function completeHandler(event){
//      //location.reload();
//      $("#progress").css('display', 'none');
//      // validate form
//      jsonValidate('{!! $route_action !!}','form[name="data_form"]');
// }

  </script> 
@include(config('pl_{wildcard_identifier}.DIR_PANEL').'footer') 