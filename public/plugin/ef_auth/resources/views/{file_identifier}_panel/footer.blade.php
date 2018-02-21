<!-- Footer -->
<footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
  <div class="pull-right"> </div>
  <div class="pull-right"> <a class="font-w600" href="<?php echo url("/"); ?>" target="_blank">{!! $_meta->site_name !!}</a> &copy; <span class="js-year-copy"></span> </div>
</footer>
<!-- END Footer -->
</div>
<div id="raw_div"></div>
<!-- END Page Container --> 

<!-- Apps Modal --> 
<!-- Opens from the button in the header -->
<div class="modal fade" id="apps-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-sm modal-dialog modal-dialog-top">
    <div class="modal-content"> 
      <!-- Apps Block -->
      <div class="block block-themed block-transparent">
        <div class="block-header bg-primary-dark">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
          <h3 class="block-title">Apps</h3>
        </div>
        <div class="block-content">
          <div class="row text-center">
            <div class="col-xs-6"> <a class="block block-rounded" href="index.html">
              <div class="block-content text-white bg-default"> <i class="si si-speedometer fa-2x"></i>
                <div class="font-w600 push-15-t push-15">Backend</div>
              </div>
              </a> </div>
            <div class="col-xs-6"> <a class="block block-rounded" href="frontend_home.html">
              <div class="block-content text-white bg-modern"> <i class="si si-rocket fa-2x"></i>
                <div class="font-w600 push-15-t push-15">Frontend</div>
              </div>
              </a> </div>
          </div>
        </div>
      </div>
      <!-- END Apps Block --> 
    </div>
  </div>
</div>
<!-- END Apps Modal --> 

<!-- Normal Modal -->

<div class="modal" id="modal_delete_item" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-popout">
    <div class="modal-content">
      <div class="block block-themed block-transparent remove-margin-b">
        <div class="block-header bg-success">
          <ul class="block-options">
            <li>
              <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
            </li>
          </ul>
          <h3 class="block-title">Confirmation!</h3>
        </div>
        <div class="block-content">
          <p>Are you sure you want to delete.</p>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="form" value="" />
        <input type="hidden" id="module_url" value="" />
        <input type="hidden" id="item_id" value="" />
        <button title="close" id="btn_close" class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
        <button title="ok" id="btn_ok" class="btn btn-sm btn-success" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Delete</button>
      </div>
    </div>
  </div>
</div>
<!-- END Normal Modal --> 

<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'bootbox.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'custom.js') !!}"></script>
<script src="{!! URL::to(config('constants.JS_URL').'core.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/bootstrap.min.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/jquery.slimscroll.min.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/jquery.scrollLock.min.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/jquery.appear.min.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/jquery.countTo.min.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/jquery.placeholder.min.js') !!}"></script> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/js.cookie.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/masked-inputs/jquery.maskedinput.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/jquery-mousewheel/jquery.mousewheel.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/jquery-ui/globalize.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/jquery-ui/jquery-ui.js') !!}"></script>
<script src="{!! URL::to(config('constants.JS_URL').'jquery.doubleScroll.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'app.js') !!}"></script> 
<!--<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'dataTables.bootstrap.js') !!}"></script> --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL')) !!}/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
@if(Request::url() == URL::to(DIR_ADMIN.'/game/dashboard/').'/'.\Request::segment(4)) 
<!--<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_comp_charts.js') !!}"></script>--> 
@endif
<script>
$(function() {
	// set current navigation
	setAdminNav('{!! isset($active_nav) ? $active_nav : "dashboard"; !!}');
});
</script>
</body></html>