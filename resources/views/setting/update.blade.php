@include(DIR_ADMIN.'header')
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
            <li><a class="link-effect" href="{!! URL::to(DIR_ADMIN.$module) !!}">Listing</a></li>
            <li>{!! $page_action !!}</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- END Page Header --> 
    
    <!-- Page Content -->
    <div class="content"> 
      
      <!-- Session Messages --> 
      @include(DIR_ADMIN.'flash_message') 
      <!-- Tab View -->
      <div class="row">
        <form name="data_form" class="form" method="post">
          <div class="block block-bordered">
            <div class="block-content">
            <div class="row push-20-t">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="col-xs-6 has-info">
                      <div class="form-material">
                        <input readonly="readonly" name="key" class="form-control input-lg" type="text" placeholder="Key" value="{!! $data->{"key"}; !!}" />
                        <label for="mega-firstname">Key</label>
                      </div>
                    </div>
                    <div class="col-xs-6 has-info">
                      <div class="form-material">
                        <input readonly="readonly" name="hint" class="form-control input-lg" type="text" placeholder="Description" value="{!! $data->description; !!}" />
                        <label for="mega-firstname">Hint</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row push-20-t">
                <div class="col-sm-12">
                  <div class="form-group">
                    <div class="col-xs-6">
                      <div class="form-material">
                        <input name="value" class="form-control input-lg" type="text" placeholder="Enter Value" value="{!! $data->value !!}">
                        <label for="mega-firstname"><span class="text-danger">*</span> Value</label>
                        <div id="error_msg_value" class="help-block text-right animated fadeInDown hide" style="color:red"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row push-10-t push-10">
                <div class="form-group">
                  <div class="col-xs-12">
                    <button class="btn btn-default pull-right" type="submit"><i class="fa fa-check"></i> Submit</button>
                    <i id="loading" class="fa fa-2x fa-asterisk fa-spin text-success pull-right" style="margin-right:10px; display:none;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
          <input type="hidden" name="do_post" value="1" />
        </form>
      </div>
      <!-- END Tab View --> 
      <!-- END Full Table --> 
    </div>
    <!-- END Page Content --> 
  </main>
  <!--Dialog Box HTML Start --> 
  <!--<div id="dialog_confirm" title="Confirmation" style="display:none;">Are you really want to delete?</div>--> 
  
</div>

<!-- Page Script --> 
<script type="text/javascript">
$(function() {
	// default form submit/validate
	$('form[name="data_form"]').submit(function(e) {
		e.preventDefault();
		// hide all errors
		$("div[id^=error_msg_]").removeClass("show").addClass("hide");
		// validate form
		return jsonValidate('{!! $data->{$pk} !!}',$(this),'i[id=loading]');
	});
});

</script> 
@include(DIR_ADMIN.'footer') 