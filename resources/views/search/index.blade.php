@include('admin.header') 
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.css') !!}">
<div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
@include('admin.side_overlay')
    @include('admin.sidebar')
    @include('admin.nav_header') 

<!-- Main Container -->
<main id="main-container"> 
  <!-- Page Header -->
  <div class="content bg-gray-lighter">
    <div class="row items-push">
      <div class="col-sm-7">
        <h1 class="page-heading"> Search & Reporting </h1>
      </div>
      <!--<div class="col-sm-5 text-right hidden-xs">
        <ol class="breadcrumb push-10-t">
          <li>Users</li>
          <li><a class="link-effect" href="javascript:;">Users Listing</a></li>
        </ol>
      </div>--> 
    </div>
  </div>
  <!-- END Page Header --> 
  
  <!-- Page Content -->
  <div class="content s-reporting"> 
    <!-- Filters -->
    <form name="generate_report" class="form-horizontal push-10-t push-10" method="post">
      <div class="col-sm-3">
		<div class="row">
			<h4 class="block-title block-header bg-success text-white">Game level</h4>
			<div class="block block-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Game</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game Three
					</div>
					
				</div>
			</div>
			<div class="blockblock-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Level as per game </h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game Level One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game Level Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game Level Three
					</div>
					
				</div>
			</div>
			<div class="block">
				<div class="block-content pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Game Level One
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row top-space">
			<h4 class="block-title block-header bg-success text-white">Location</h4>
			<div class="block block-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Territory</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Territory One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Territory Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Territory Three
					</div>
					
				</div>
			</div>
			<div class="block block-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Country</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> United States
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Germany
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> France
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Mexico
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Colombia
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> United kingdom
					</div>
				</div>
			</div>
			<div class="block block-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">State</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> State One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> State Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> State Three
					</div>
				</div>
			</div>
			<div class="block block-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">City</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> City One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> City Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> City Three
					</div>
				</div>
			</div>
			<div class="block block-opt-hidden remove-margin-b">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Retailer</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Retailer One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Retailer Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Retailer Three
					</div>
				</div>
			</div>
			<div class="block block-opt-hidden">
				<div class="block-header block-bordered-b">
					<ul class="block-options">
						<li>
							<button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-down"></i></button>
						</li>
					</ul>
					<h3 class="block-title">Store</h3>
				</div>
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Store One
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Store Two
						</label>
					</div>
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Store Three
					</div>
				</div>
			</div>
		</div>
		
		<div class="row top-space">
			<h4 class="block-title block-header bg-success text-white">User centered</h4>
			<div class="block  remove-margin">
				<div class="block-content block-bordered-b clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Full Name
						</label>
					</div>
				</div>
			</div>
			<div class="block remove-margin">
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Email
						</label>
					</div>
				</div>
			</div>
			<div class="block remove-margin">
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Date of Birth 
						</label>
					</div>
				</div>
			</div>
			<div class="blockremove-margin">
				<div class="block-content block-bordered-b pad-10 clearfix">
					<div class="col-xs-12">
						<label class="css-input css-checkbox css-checkbox-info">
							<input type="checkbox"><span></span> Date of sign-up/ registration 
						</label>
					</div>
				</div>
			</div>
		</div>
	  </div>
	  
      <input type="hidden" name="order" value="" />
      <input type="hidden" name="sort" value="ASC" />
      <input type="hidden" name="page_no" value="1" />
    </form>
    <!-- Results -->
    <div class="col-sm-9">
      <div class="block block-bordered col-sm-12">
        <div class="block-header bg-gray-lighter">
          <ul class="block-options">
            <!--<li>
            <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
          </li>--> 
            <!--<li>
            <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
          </li>-->
          </ul>
          <h3 class="block-title">Results</h3>
        </div>
        <div class="block-content" id="report_results" style="overflow-x:auto;"> 
          <!-- content here --> 
        </div>
      </div>
    </div>
    <!-- END Table Sections --> 
    
  </div>
  <!-- END Page Content --> 
</main>
<!-- END Main Container --> 

<!-- Page JS Plugins --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/datatables/jquery.dataTables.min.js') !!}"></script> 
<!-- Page JS Code --> 
<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<script type="text/javascript" src="<?php echo url("/")."/public/"; ?>js/core.js"></script> 
<script type="text/javascript">
//var base_admin_url = "<?php echo url("/"); ?>/admin/";
$(document).ready(function() {
	// form
	/* var data_form = "form[name=generate_report]";
	// form submit event
	$(data_form).submit(function(e) {
		e.preventDefault();
		jsonValidate("search/generateReport",this);
	});
	
	// load countries
	$("input[name='territories[]']").change(function(e) {
		jsonValidate("search/load/countries",data_form);
	}); */
});
</script> 
@include('admin.footer')