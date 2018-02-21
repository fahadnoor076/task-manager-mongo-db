
<!-- Footer -->
<footer class="bst-footer pad-all-md">
    <div class="footer-wrapper bg-reverse pad-all-md">
        <div class="row footer-area">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10">
                <p class="mrgn-b-none"><a class="font-w600" href="<?php echo url("/"); ?>" target="_blank">{!! APP_NAME !!}</a> &copy; {!! date('Y'); !!}</p>
                <h3 class="h5 push-50-t animated fadeInUp" data-toggle="appear" data-class="animated fadeInUp">Crafted with <i class="fa fa-heart text-city" style="color:indianred;"></i> by <a href="javascript:;">PHP Team</a></h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 text-right">
                <a class="footer-logo display-ib" href="<?php echo url("/"); ?>"><h2 style="font-weight:bold;">S a l s o f t</h2><!--<img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'footer-logo.png') !!}" width="129" height="16" alt="footer logo">--></a>
            </div>
        </div>
    </div>
    <a href="#" id="back-top" class="to-top scrolled"> <span class="to-top-icon"></span> </a>
</footer>
</div>
{{--*/ $bname = basename($_SERVER['REQUEST_URI']);
#$bname = explode('.',$bname); /*--}}
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'bootbox.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'custom.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/js.cookie.min.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'app.js') !!}"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'core/bootstrap.min.js') !!}"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'vendor.js') !!}" type="text/javascript"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins.js') !!}" type="text/javascript"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'beast.js') !!}" type="text/javascript"></script>
<script src="{!! URL::to(config('/').'resources/assets/plugin/bootstrap-table/js/bootstrap-table.min.js') !!}" type="text/javascript"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'chosen.jquery.js') !!}" type="text/javascript"></script>

<script src="{!! URL::to(config('/').'resources/assets/plugin/full-calendar/js/fullcalendar.min.js') !!}" type="text/javascript"></script>

<script>
$(function() {
	// set current navigation
	setAdminNav('{!! isset($bname) ? $bname : "dashboard"; !!}');
});
</script>

</body>

</html>