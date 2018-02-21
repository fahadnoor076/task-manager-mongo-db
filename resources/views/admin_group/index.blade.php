{{--*/
// extra models
$admin_module_model = $model_path."AdminModule";
$admin_module_model = new $admin_module_model;
/*--}}
@include(DIR_ADMIN.'header')
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.css') !!}">
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed"> @include(DIR_ADMIN.'side_overlay')
  @include(DIR_ADMIN.'sidebar')
  @include(DIR_ADMIN.'nav_header')
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
    <div class="content"> 
      
      <!-- Session Messages --> 
      @include(DIR_ADMIN.'flash_message') 
      <!-- Buttons -->
      <div class="col-sm-12"> <a href="{!! URL::to(DIR_ADMIN.$module.'/add') !!}" class="btn btn-primary push-5-r push-10 push-10-t pull-right" type="button"><i class="fa fa-plus"></i> Add {!! $s_title !!}</a></div>
      <!-- Full Table -->
      <div class="block">
        <div class="block-content">
          <form name="listing_form" method="post">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center" style="width: 10%;">#</th>
                  <th style="width: 40%;">Name</th>
                  <th class="hidden-xs" style="width: 20%;">Created At</th>
                  <th class="hidden-xs" style="width: 20%;">Updated At</th>
                  <th class="hidden-xs" style="width: 10%; text-align:center;" align="center">Options</th>
                </tr>
              </thead>
              <tbody>
              @if (isset($raw_ids[0])) {{--*/ $i = 0 /*--}} 
              <!-- found records --> 
              @foreach($raw_ids as $raw_id)  {{--*/ $i++ /*--}}
              {{--*/ $record = $model->get($raw_id->{$pk}) /*--}}
              <tr id="{!! $record->{$pk} !!}" class="js-animation-object animated">
                <td class="text-center">{!! $i !!}</td>
                <td>{!! $record->name !!}
                  </th>
                <td class="hidden-xs">{!! date(DATE_TIME_FORMAT_ADMIN,strtotime($record->created_at)) !!}</td>
                <td class="hidden-xs">{!! $record->updated_at ? date(DATE_TIME_FORMAT_ADMIN,strtotime($record->updated_at)) : "never" !!}</td>
                <td class="hidden-xs" align="center"><div class="btn-group"> <a class="btn btn-xs btn-default" type="button" href="{!! URL::to(DIR_ADMIN.$module.'/update/'.$record->{$pk}) !!}" data-toggle="tooltip" title="" data-original-title="Update"><i class="fa fa-pencil"></i></a> @if ($record->{$pk} > 1) <a class="btn btn-xs btn-default action_del_item" type="button" data-toggle="tooltip" title="" data-item_id="{!! $record->{$pk} !!}" data-form="listing_form" data-original-title="Delete"><i class="fa fa-times"></i></a> @endif </div></td>
              </tr>
              @endforeach
              @else 
              <!-- no records -->
              <tr>
                <td style="width: 100%;" colspan="5" align="center">No records found</td>
              </tr>
              @endif
                </tbody>
              
            </table>
            <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
            <input type="hidden" name="delete_ids[]" value="" />
            <input type="hidden" name="do_delete" value="" />
          </form>
        </div>
      </div>
      
      <!-- END Full Table --> 
    </div>
    <!-- END Page Content --> 
  </main>
  <!--Dialog Box HTML Start --> 
  <!--<div id="dialog_confirm" title="Confirmation" style="display:none;">Are you really want to delete?</div>--> 
  
</div>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.js') !!}"></script> 
<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
@include(DIR_ADMIN.'footer') 