<!-- Sidebar -->

<nav id="sidebar"> 
  <!-- Sidebar Scroll Container -->
  <div id="sidebar-scroll"> 
    <!-- Sidebar Content --> 
    <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
    <div class="sidebar-content"> 
      <!-- Side Header -->
      
      <div class="side-header side-content bg-white-op custom-bg-logo"> 
        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
        <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close"> <i class="fa fa-times"></i> </button>
        <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
        <div class="btn-group pull-right"> 
          <!--<button class="btn btn-link text-gray dropdown-toggle" data-toggle="dropdown" type="button">
                                  <i class="si si-drop"></i>
                              </button>--> 
          
        </div>
        <a class="h5 text-white logo-custom" href="{!! URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').'dashboard') !!}"> <span class="h4 font-w600 sidebar-mini-hide"><img src="{!! URL::to(config('constants.LOGO_PATH').$_meta->site_logo) !!}" alt="-" /></span> </a> </div>
      <!-- END Side Header --> 
      
      <!-- Side Content -->
      <div class="side-content">
        <ul class="nav-main" id="admin_nav_set">
          <li id="dashboard"> <a class="active" href="{!! URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').'dashboard/') !!}"><i class="si si-speedometer"></i><span class="sidebar-mini-hide">Dashboard </span></a> </li>
          <!--<li id="administration"> <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:;"><i class="si si-user"></i><span class="sidebar-mini-hide">Vendors</span></a>
            <ul>
              <li id="administration-admin_group"> <a href="{!! URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').'admin_group/') !!}">Vendor Roles</a> </li>
              <li id="administration-admin"> <a href="{!! URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').'admin/') !!}">Vendor</a> </li>
              <li id="administration-admin_widget"> <a href="{!! URL::to(config('pl_{wildcard_identifier}.DIR_PANEL').'admin_widget/') !!}">Widgets</a> </li>
            </ul>
          </li>-->
        </ul>
      </div>
      <!-- END Side Content --> 
    </div>
    <!-- Sidebar Content --> 
  </div>
  <!-- END Sidebar Scroll Container --> 
</nav>
<!-- END Sidebar -->