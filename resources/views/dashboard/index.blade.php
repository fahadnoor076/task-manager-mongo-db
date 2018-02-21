@include(DIR_ADMIN.'header')

<div class="bst-wrapper">
        <header class="bst-header">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <div class="bst-logo">
                            <a class="navbar-brand" href="index.html"> <img class="img-responsive display-ib" src="assets/img/beast-logo.png" alt="logo" width="130" height="16"> </a>
                        </div>
                        <div class="bst-mobile-toggle-btn text-right pull-right">
                            <ul class="list-inline">
                                <li>
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                                </li>
                                <li>
                                    <button class="c-hamburger c-hamburger--htra bst-bars pull-right"> <span>toggle menu</span></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse" data-hover="dropdown">
                        <ul class="bst-mega-menu-wrapper nav navbar-nav">
                            <li class="dropdown hidden-xs hidden-sm hidden-md"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Mega <span class="caret"></span> </a>
                                <div class="dropdown-menu bst-mega-menu">
                                    <div class="bst-mega-menu-wrap pad-all-lg">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                                                <h4 class="sidenav-heading text-uppercase mrgn-b-md">Dashboards</h4>
                                                <ul class="list-unstyled">
                                                    <li><a href="index.html" class="btn-default mrgn-b-xs pad-l-sm">Dashboard 1</a></li>
                                                    <li><a href="dashboard-v2.html" class="btn-default mrgn-b-xs pad-l-sm">Dashboard 2</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                                                <h4 class="sidenav-heading text-uppercase mrgn-b-md">Features</h4>
                                                <ul class="list-unstyled">
                                                    <li><a href="ui-buttons.html">UI Elements</a></li>
                                                    <li><a href="notification.html">Components</a></li>
                                                    <li><a href="google-chart.html">Graph and Charts</a></li>
                                                    <li><a href="googlemap.html">Maps</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                                                <h4 class="sidenav-heading text-uppercase mrgn-b-md">Layouts</h4>
                                                <ul class="list-unstyled">
                                                    <li><a href="index.html">Sidebar At left</a></li>
                                                    <li><a href="right-sidebar.html">Sidebar At right</a></li>
                                                    <li><a href="fixed-header.html">Fixed Header</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                                                <h4 class="sidenav-heading text-uppercase mrgn-b-md">Pages</h4>
                                                <ul class="list-unstyled">
                                                    <li><a href="users-list.html">Users</a></li>
                                                    <li><a href="ecommerce-product.html">Ecommerce</a></li>
                                                    <li><a href="email.html">Mailbox</a></li>
                                                    <li><a href="login.html">Extra Pages</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                                <div class="bst-sparkline">
                                                    <div class="bst-card-box bst-sparkline-list bg-success clearfix">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <span class="show count-item" data-count="5000">0</span> <span>New visitors</span> </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="chart sparkline text-center" data-chart="sparkline" data-type="bar" data-height="50px" data-barwidth="6" data-width="100%" data-barspacing="2" data-barcolor="#ffffff" data-values="[9, 8, 9, 7, 6, 8, 7, 8]"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="bst-card-box bst-sparkline-list clearfix bg-info">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <span class="show count-item" data-count="3000">0</span> <span>New Users</span> </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="chart sparkline text-center" data-chart="sparkline" data-type="bar" data-height="50px" data-barwidth="6" data-width="100%" data-barspacing="2" data-barcolor="#ffffff" data-values="[5, 6, 8, 9, 5, 8, 4, 6]"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="bst-card-box bst-sparkline-list clearfix bg-secondary">
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> <span class="show count-item" data-count="7000">0</span> <span>Active Users</span> </div>
                                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                            <div class="chart sparkline text-center" data-chart="sparkline" data-type="bar" data-height="50px" data-barwidth="6" data-width="100%" data-barspacing="2" data-barcolor="#ffffff" data-values="[9, 8, 9, 7, 6, 8, 7, 8]"> </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="bst-navbar-search">
                                <div class="overflow-wrapper">
                                    <form class="bst-search-form" method="post" role="search" action="javascript:;">
                                        <div class="input-group"> <span class="input-group-addon"> <span class="bst-search-form-title fa fa-search"></span></span>
                                            <input type="text" value="" name="s"> </div>
                                    </form>
                                </div>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell-o"> </i><span class="badge badge-danger">5</span></a>
                                <ul class="dropdown-menu dropdown-custom dropdown-notifi">
                                    <li> <strong class="drop-title"><span><i class="fa fa-bell-o" aria-hidden="true"></i></span>Notifications</strong><a href="javascript:;" class="pull-right bg-primary base-reverse">Marks As Read</a></li>
                                    <li>
                                        <a href="javascript:;" class="pos-relative">
                                            <div class="mrgn-b-xs"> <span class="notification-icon"><i class="fa fa-database text-danger"></i></span> <span class="notification-title">Database overload</span> <span class="notification-ago">3 min ago</span> </div>
                                            <p class="mrgn-all-none">Database overload due to incorrect queries</p>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="pos-relative">
                                            <div class="mrgn-b-xs"> <span class="notification-icon"><i class="fa fa-circle-o-notch fa-spin text-success" aria-hidden="true"></i></span> <span class="notification-title">Installing App v1.2.1</span> <span class="notification-ago ">60 % Done</span> </div>
                                            <div class="progress progress-sm-height mrgn-all-none">
                                                <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:60%"> </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="pos-relative">
                                            <div class="mrgn-b-xs"> <span class="notification-icon"><i class="fa fa-exclamation-triangle text-warning"></i></span> <span class="notification-title">Application Error</span> <span class="notification-ago ">10 min ago</span> </div>
                                            <p class="mrgn-all-none">failed to initialize the application due to error weblogic.application.moduleexception</p>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="mrgn-b-xs"> <span class="notification-icon"><i class="fa fa-server text-info"></i></span> <span class="notification-title">Server Status</span> <span class="notification-ago ">30GB Free Space</span> </div>
                                            <div class="progress progress-sm-height mrgn-all-none">
                                                <div class="progress-bar progress-bar-info" role="progressbar" style="width:40%"></div>
                                                <div class="progress-bar progress-bar-success" role="progressbar" style="width:10%"></div>
                                                <div class="progress-bar progress-bar-danger" role="progressbar" style="width:20%"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="mrgn-b-xs"> <span class="notification-icon"><i class="fa fa-cogs text-success"></i></span> <span class="notification-title">Application Configured</span> <span class="notification-ago ">30 min ago</span> </div>
                                            <p class="mrgn-all-none">Your setting is updated on server Sav3060</p>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li> <a class="text-center" href="javascript:;"> See all notifications <i class="fa fa-angle-right mrgn-l-xs"></i> </a> </li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-envelope-o"></i><span class="badge badge-danger">5</span></a>
                                <ul class="dropdown-menu dropdown-custom">
                                    <li> <strong class="drop-title">You have 5 new messages</strong> </li>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="user-thumb pull-left"><img class="img-responsive img-circle" src="assets/img/register-user-2.jpg" alt="User-image" width="64" height="64"> <span class="profile-status online pull-right"></span> </div>
                                            <div class="user-content pull-left">
                                                <h6 class="mrgn-all-none">Marya Kale</h6>
                                                <p class="mrgn-b-xs">Just see the my admin!</p> <span>9:30 AM</span> <span class="notification-ago">30 min ago</span> </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="user-thumb pull-left"><img class="img-responsive img-circle" src="assets/img/histroy-user-1.png" alt="User-image" width="64" height="64"> <span class="profile-status online pull-right"></span> </div>
                                            <div class="user-content pull-left">
                                                <h6 class="mrgn-all-none">Jim Gray</h6>
                                                <p class="mrgn-b-xs">Just see the my admin!</p> <span>9:30 AM</span> <span class="notification-ago ">10 min ago</span> </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="user-thumb pull-left"><img class="img-responsive img-circle" src="assets/img/register-user-2.jpg" alt="User-image" width="64" height="64"> <span class="profile-status online pull-right"></span> </div>
                                            <div class="user-content pull-left">
                                                <h6 class="mrgn-all-none">Marya Kale</h6>
                                                <p class="mrgn-b-xs">Just see the my admin!</p> <span>9:30 AM</span> <span class="notification-ago ">20 min ago</span> </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;">
                                            <div class="user-thumb pull-left"><img class="img-responsive img-circle" src="assets/img/histroy-user-1.png" alt="User-image" width="64" height="64"> <span class="profile-status online pull-right"></span> </div>
                                            <div class="user-content pull-left">
                                                <h6 class="mrgn-all-none">Jim Gray</h6>
                                                <p class="mrgn-b-xs">Just see the my admin!</p> <span>9:30 AM</span> <span class="notification-ago ">5 min ago</span> </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                    <li> <a class="text-center" href="javascript:;">See all notifications<i class="fa fa-angle-right mrgn-l-xs"></i> </a> </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="user-dropdown">John Doe</span> <span class="caret"></span><img class="img-responsive display-ib mrgn-l-sm img-circle" src="assets/img/user-1.jpg" width="64" height="64" alt="User-image"> </a>
                                <ul class="dropdown-menu">
                                    <li><a href="user-profile.html"><i class="fa fa-user"></i> My Profile</a></li>
                                    <li><a href="users-list.html"><i class="fa fa-money"></i> My Contact</a></li>
                                    <li><a href="email.html" class="pos-relative"><i class="fa fa-inbox"></i> My Inbox <span class="badge badge-danger pull-right profile-badge">2</span></a></li>
                                    <li><a href="user-profile.html" class="pos-relative"><i class="fa fa-tasks"></i> My task <span class="badge badge-success pull-right profile-badge">5</span></a></li>
                                    <li><a href="user-settings.html"><i class="fa fa-cog"></i> Account Setting</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="login.html"><i class="fa fa-power-off"></i>Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div class="bst-main">
            <div class="fyt-main-top"></div>
            <div class="bst-main-wrapper pad-all-md">
                <div class="bst-sidebar">
                    <div class="bst-sidebar-back"> </div>
                    <div class="bst-sidebar-nav-wrapper">
                        <div class="bst-site-link mrgn-b-md bg-white overflow-wrapper">
                            <div class="bst-site-info pull-left"><i class="fa fa-globe mrgn-r-sm"> </i> <span>Beast </span> </div>
                            <button class="c-hamburger c-hamburger--htra bst-bars pull-right"> <span>toggle menu</span></button>
                        </div>
                        <div class="bst-sidebar-menu pad-lr-lg pad-tb-md bg-white">
                            <nav class="sidebar-nav collapse">
                                <ul class="list-unstyled sidebar-menu">
                                    <li class="sidenav-heading text-uppercase">Dashboards</li>
                                    <li class="has-children active opened"><a href="#/"><i class="fa fa-dashboard" aria-hidden="true"></i> <span>Dashboards</span></a>
                                        <ul class="list-unstyled sub-menu">
                                            <li><a href="index.html"><span>Dashboard 1</span></a></li>
                                            <li><a class="active" href="dashboard-v2.html"><span>Dashboard 2</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href="#/"><i class="fa fa-sliders" aria-hidden="true"></i><span>UI Elements</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="ui-buttons.html"><span>Buttons</span></a></li>
                                            <li><a href="progress-bar.html"><span>Progress bar </span></a></li>
                                            <li><a href="tabs-accordions.html"><span>Tabs &amp; accordion</span></a></li>
                                            <li><a href="form-elements.html"><span>Form elements</span></a></li>
                                            <li><a href="form-validation.html"><span>Form Validation</span></a></li>
                                            <li><a href="no-uislider.html"><span>UI Sliders</span></a></li>
                                            <li><a href="input-mask.html"><span>Form input mask</span></a></li>
                                            <li><a href="pagination-tooltip.html"><span>Pagination &amp; tooltip</span></a></li>
                                            <li><a href="panels.html"><span>Panels</span></a></li>
                                            <li><a href="social-icon.html"><span>Social icons</span></a></li>
                                            <li><a href="typography.html"><span>Typography</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href=""><i class="fa fa-server" aria-hidden="true"></i><span>Advanced UI Elements</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="bootstrap-fileinput.html"><span>Bootstrap File Input</span></a></li>
                                            <li><a href="bootstrap-tagsinput.html"><span>Bootstrap Tag Input</span></a></li>
                                            <li><a href="bootstrap-switch.html"><span>Bootstrap Switches</span></a></li>
                                            <li><a href="selectize.html"><span>Selectize</span></a></li>
                                            <li><a href="typeahead.html"><span>Typeahead</span></a></li>
                                            <li><a href="fancytree.html"><span>Fancy Tree</span></a></li>
                                            <li><a href="dragula.html"><span>Dragula Drag &amp; drop</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href="#/"><i class="fa fa-inbox" aria-hidden="true"></i><span>Components</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="notification.html"><span>Notification &amp; Alerts</span></a></li>
                                            <li><a href="date-time-picker.html"><span>Date &amp; Time picker</span></a></li>
                                            <li><a href="color-picker.html"><span>Color picker</span></a></li>
                                            <li><a href="bootstrap-multiselect-dropdown.html"><span> Multiselect Dropdowns</span></a></li>
                                            <li><a href="modals.html"><span>Modals </span></a></li>
                                            <li><a href="fileupload.html"><span>File Upload</span></a></li>
                                            <li><a href="plupload.html"><span>Plupload</span></a></li>
                                            <li><a href="dropzone.html"><span>Dropzone</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href="#/"><i class="fa fa-pie-chart" aria-hidden="true"></i><span>Graphs and Charts</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="google-chart.html"><span>Google chart</span></a></li>
                                            <li><a href="high-chart.html"><span>High Chart</span></a></li>
                                            <li><a href="morris.html"><span>Morris Chart</span></a></li>
                                            <li><a href="flow-chart.html"><span>Flow Chart</span></a></li>
                                            <li><a href="chartjs.html"><span>js charts</span></a></li>
                                            <li><a href="sparkline-chart.html"><span>Sparkline chart</span></a></li>
                                            <li><a href="easypie.html"><span>Easypie Chart</span></a> </li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href=""><i class="fa fa-map" aria-hidden="true"></i><span>Maps</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="googlemap.html"><span>Google map</span></a></li>
                                            <li><a href="vectormap.html"><span>Vector map</span></a></li>
                                            <li><a href="snazzymaps.html"><span>Snazzy map</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href=""><i class="fa fa-list-ul" aria-hidden="true"></i><span>Tables</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="basic-table.html"><span>Basic tables</span></a></li>
                                            <li><a href="bootstrap-table.html"><span>Bootstrap table</span></a></li>
                                            <li><a href="data-tables.html"><span>Data tables</span></a></li>
                                            <li><a href="dynamitable.html"><span>Dynamitable</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href=""><i class="fa fa-calendar" aria-hidden="true"></i><span>Event calendars</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="full-calendar.html"><span>Basic full calendar</span></a></li>
                                            <li><a href="full-calendar-advanced.html"><span>Advanced full calendar</span></a></li>
                                            <li><a href="full-calendar-format.html"><span>Full calendar Format</span></a></li>
                                            <li><a href="full-calendar-styling.html"><span>full calendar Styling</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"> <a href=""><i class="fa fa-edit" aria-hidden="true"></i><span>Editors</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="summernote.html"><span>summernote Editor</span></a></li>
                                            <li><a href="bootstrap-wysiwyg.html"><span>bootstrap wysiwyg Editor</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"> <a href="#/"><i class="fa fa-flag" aria-hidden="true"></i><span>Icons</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="simple-line-icons.html"><span>Simple Line Icon</span></a></li>
                                            <li><a href="themify-icons.html"><span>Themify Icon</span></a></li>
                                            <li><a href="weather-icons.html"><span>Weather Icon</span></a></li>
                                            <li><a href="material-icons.html"><span>Material Icon</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="sidenav-heading text-uppercase">Layouts</li>
                                    <li class="has-children"><a href=""><i class="fa fa-columns" aria-hidden="true"></i><span>Page layouts</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="right-sidebar.html"><span>Right Sidebar</span></a></li>
                                            <li><a href="collapse-sidebar.html"><span>Collapse Sidebar</span></a></li>
                                            <li><a href="fixed-header.html"><span>Fixed Header</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="sidenav-heading text-uppercase">Pages</li>
                                    <li class="has-children"><a href=""><i class="fa fa-user" aria-hidden="true"></i><span>Users</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="users-list.html"><span>Users list</span></a></li>
                                            <li><a href="user-profile.html"><span>Users profile</span></a></li>
                                            <li><a href="user-settings.html"><span>User settings</span></a></li>
                                            <li><a href="chat.html"><span>User chat</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"> <a href=""><i class="fa fa-shopping-bag" aria-hidden="true"></i><span>E-Commerce</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="ecommerce-product.html"><span>Ecommerce Product</span></a></li>
                                            <li><a href="ecommerce-product-detail.html"><span>Ecommerce Product detail</span></a></li>
                                            <li><a href="ecommerce-order.html"><span>Ecommerce order</span></a></li>
                                            <li><a href="ecommerce-order-detail.html"><span>Ecommerce order detail</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href=""><i class="fa fa-envelope" aria-hidden="true"></i><span>Mailbox</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="email.html"><span>Emails</span></a></li>
                                            <li><a href="email-detail.html"><span>Email Detail</span></a></li>
                                            <li><a href="email-compose.html"><span>Compose Email</span></a></li>
                                        </ul>
                                    </li>
                                    <li class="has-children"><a href=""><i class="fa fa-magic" aria-hidden="true"></i><span>Extra</span></a>
                                        <ul class="list-unstyled sub-menu collapse">
                                            <li><a href="login.html"><span>Login</span></a></li>
                                            <li><a href="coming-soon.html"><span>Coming Soon</span></a></li>
                                            <li><a href="404.html"><span>404</span></a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="bst-sidebar-link mrgn-b-sm bg-white">
                            <a href="email.html"> <i class="fa fa-envelope-open mrgn-r-sm"> </i> <span class="closed-menu-title">Inbox</span> <span class="pull-right badge badge-primary">23</span> </a>
                        </div>
                        <div class="bst-sidebar-link mrgn-b-sm bg-white">
                            <a href="users-list.html"> <i class="fa fa-user mrgn-r-sm"> </i> <span class="closed-menu-title">New Users</span> <span class="pull-right badge badge-warning">5</span> </a>
                        </div>
                        <div class="bst-sidebar-link mrgn-b-sm bg-white">
                            <a href="users-list.html"> <i class="fa fa-clock-o mrgn-r-sm"> </i> <span class="closed-menu-title">Online</span> <span class="pull-right badge badge-success">450</span> </a>
                        </div>
                        <div class="bst-sidebar-link mrgn-b-sm bg-white">
                            <a href="ecommerce-order.html"> <i class="fa fa-credit-card mrgn-r-sm"> </i> <span class="closed-menu-title">Payment due</span> <span class="pull-right badge badge-danger">9</span> </a>
                        </div>
                    </div>
                </div>
                <div class="bst-content-wrapper">
                    <div class="bst-content">
                        <div class="bst-page-bar mrgn-b-md breadcrumb-double-arrow">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item text-capitalize">
                                    <h3>Dashboard 2</h3> </li>
                                <li class="breadcrumb-item"><a href="#/">Home</a></li>
                                <li class="breadcrumb-item active"><a href="index.html">Dashboard 2</a></li>
                            </ul>
                        </div>
                        <div class="bst-block">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-center pad-tb-xs">
                                    <div class="mrgn-b-sm"> <i class="fa fa-arrow-up fa-2x text-success" aria-hidden="true"></i> </div>
                                    <p class="font-3x mrgn-b-none">$<span class="count-item" data-count="142525">1425,25</span></p>
                                    <p class="font-xl mrgn-b-none">Today's Profit</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-center pad-tb-xs">
                                    <div class="mrgn-b-sm"> <i class="fa fa-arrow-up fa-2x text-success" aria-hidden="true"></i> </div>
                                    <p class="font-3x mrgn-b-none">$<span class="count-item" data-count="42525">425.25</span></p>
                                    <p class="font-xl mrgn-b-none">This Week</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-center pad-tb-xs">
                                    <div class="mrgn-b-sm"> <i class="fa fa-arrow-down fa-2x text-danger" aria-hidden="true"></i> </div>
                                    <p class="font-3x mrgn-b-none"><span class="count-item" data-count="753">753</span></p>
                                    <p class="font-xl mrgn-b-none">Total Sale</p>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 text-center pad-tb-xs">
                                    <div class="mrgn-b-sm"> <i class="fa fa-arrow-down fa-2x text-danger" aria-hidden="true"></i> </div>
                                    <p class="font-3x mrgn-b-none"><span class="count-item" data-count="14250">14,250</span></p>
                                    <p class="font-xl mrgn-b-none">Visitors</p>
                                </div>
                            </div>
                        </div>
                        <div class="bst-full-block">
                            <div class="pad-all-lg clearfix">
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 pad-tb-xs">
                                    <div class="clearfix">
                                        <div class="pull-left text-center mrgn-r-lg">
                                            <div class="bst-pie-chart" data-chart="easypie" data-animate="2000" data-size="80" data-linewidth="5" data-barcolor="#ff5723" data-trackcolor="#e1e3e8" data-scale="false" data-percent="75"><span>75%</span></div>
                                        </div>
                                        <div class="pull-left pad-tb-xs">
                                            <div class="font-2x"> <span class="count-item" data-count="1300">1,300</span> </div> Visits </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 pad-tb-xs">
                                    <div class="clearfix">
                                        <div class="pull-left text-center mrgn-r-lg">
                                            <div class="bst-pie-chart" data-chart="easypie" data-animate="2000" data-size="80" data-linewidth="5" data-barcolor="#00c854" data-trackcolor="#e1e3e8" data-scale="false" data-percent="35"><span>35%</span></div>
                                        </div>
                                        <div class="pull-left pad-tb-xs">
                                            <div class="font-2x"> <span class="count-item" data-count="800">800</span> </div> Orders </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 pad-tb-xs">
                                    <div class="clearfix">
                                        <div class="pull-left text-center mrgn-r-lg">
                                            <div class="bst-pie-chart" data-chart="easypie" data-animate="2000" data-size="80" data-linewidth="5" data-barcolor="#0092eb" data-trackcolor="#e1e3e8" data-scale="false" data-percent="62"><span>62%</span></div>
                                        </div>
                                        <div class="pull-left pad-tb-xs">
                                            <div class="font-2x"> <span class="count-item" data-count="3800">3,800</span> </div> Users </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pos-relative overflow-wrapper">
                                <div class="site-stats-area-chart chart"> </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 full-col-lg-md bst-lg-desktop-full">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="bg-inverse pad-all-lg">
                                        <div class="row mrgn-b-lg">
                                            <div class="col-xs-5 col-sm-6 col-md-6 col-md-6">
                                                <h4>Inbox</h4> </div>
                                            <div class="col-xs-7 col-sm-6 col-md-6 col-md-6 text-right"> <a href="javascript:;" class="overlay-link pad-sll-sm bg-white">Online <span class="caret mrgn-l-sm"></span></a> </div>
                                        </div>
                                        <div class="row fw-light inbox-tabs">
                                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                <div class="bst-3 pull-left"><i class="fa fa-sticky-note-o fa-2x"> </i></div>
                                                <div class="bst-9 pull-left font-md">2456
                                                    <br>This week</div>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                <div class="bst-3 pull-left"><i class="fa fa-th fa-2x"> </i></div>
                                                <div class="bst-9 pull-left font-md">2456
                                                    <br> This month</div>
                                            </div>
                                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                <div class="bst-3 pull-left"><i class="fa fa-th-large fa-2x"> </i></div>
                                                <div class="bst-9 pull-left font-md">2456
                                                    <br> Total Message</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bst-msg-widget tabs-default">
                                        <ul class="nav nav-tabs bg-inverse pad-lr-lg">
                                            <li class="active"> <a data-toggle="tab" href="#tab-1" aria-expanded="false">Unread Messages</a> </li>
                                            <li class=""> <a data-toggle="tab" href="#tab-2" aria-expanded="true">Archive Messages</a> </li>
                                            <li class=""> <a data-toggle="tab" href="#tab-3" aria-expanded="true">All Messages</a> </li>
                                        </ul>
                                        <div class="tab-content element-thumb pad-all-lg">
                                            <div id="tab-1" class="tab-pane fade border-list-bottom active in">
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-1.png" alt="feature box" height="94" width="94"> <span class="base-reverse badge-danger square-30 img-circle pos-absolute pos-abs-right-top">2</span> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Emma Alexander</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 16 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Thanks for the chartlist. There are awesome designs and very user friendly.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-2.png" alt="feature box" height="95" width="95"> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">James Allen </h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">User profile that you are providing is great.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-3.png" alt="feature box" height="94" width="94"></div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Fox Grey </h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Having plenty of options is really cool. I can choose what suits to my work.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-4.png" alt="feature box" height="94" width="94"><span class="base-reverse badge-danger square-30 img-circle pos-absolute pos-abs-right-top">2</span> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Jennifer Bell</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 13 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Doing work with such a tool is really fun.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-5.png" alt="feature box" height="95" width="95"> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Albert Lowe</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">User profile that you are providing is great.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="tab-2" class="tab-pane fade border-list-bottom">
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-2.png" alt="feature box" height="95" width="95"> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">James Allen </h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">User profile that you are providing is great.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-4.png" alt="feature box" height="94" width="94"><span class="base-reverse badge-danger square-30 img-circle pos-absolute pos-abs-right-top">2</span> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Jennifer Bell</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 13 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Doing work with such a tool is really fun.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-3.png" alt="feature box" height="94" width="94"></div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Fox Grey </h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Having plenty of options is really cool. I can choose what suits to my work.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-5.png" alt="feature box" height="95" width="95"> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Albert Lowe</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">User profile that you are providing is great.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-1.png" alt="feature box" height="94" width="94"> <span class="base-reverse badge-danger square-30 img-circle pos-absolute pos-abs-right-top">2</span> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Emma Alexander</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 16 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Thanks for the chartlist. There are awesome designs and very user friendly.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="tab-3" class="tab-pane fade border-list-bottom">
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-2.png" alt="feature box" height="95" width="95"> <span class="base-reverse badge-danger square-30 img-circle pos-absolute pos-abs-right-top">2</span> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">James Allen </h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">User profile that you are providing is great.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-4.png" alt="feature box" height="94" width="94"></div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Jennifer Bell</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 13 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Doing work with such a tool is really fun.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-3.png" alt="feature box" height="94" width="94"><span class="base-reverse badge-danger square-30 img-circle pos-absolute pos-abs-right-top">2</span></div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Fox Grey </h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Having plenty of options is really cool. I can choose what suits to my work.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-1.png" alt="feature box" height="94" width="94"> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Emma Alexander</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 16 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">Thanks for the chartlist. There are awesome designs and very user friendly.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="element-list pad-tb-xs">
                                                    <div class="clearfix">
                                                        <div class="feature-box-thumb thumb-wid pull-left mrgn-r-lg">
                                                            <div class="display-ib pos-relative"> <img class="img-responsive img-circle" src="assets/img/msg-user-5.png" alt="feature box" height="95" width="95"> </div>
                                                        </div>
                                                        <div class="thumb-content pull-left">
                                                            <div class="clearfix mrgn-b-xs">
                                                                <div class="pull-left">
                                                                    <h5 class="text-info">Albert Lowe</h5></div>
                                                                <div class="pull-right edit-post"><a class="gray" href="javascript:;"><i class="fa fa-clock-o"></i> 14 Jan 2017</a></div>
                                                            </div>
                                                            <p class="base-dark">User profile that you are providing is great.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 full-col-lg-md bst-lg-desktop-full">
                                <div class="bst-full-block pos-relative">
                                    <div class="bst-block-title pad-all-md">
                                        <div class="pos-relative">
                                            <div class="caption">
                                                <h4>Latest User Statistics</h4> </div>
                                            <div class="contextual-link">
                                                <ul class="list-inline list-unstyled">
                                                    <li>
                                                        <div class="dropdown display-ib"> <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-chevron-down"></i></a>
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
                                    <div class="bst-block-content">
                                        <div class="table-responsive">
                                            <table class="table table-middle table-hover fw-light th-fw-light th-pad-all-sm td-pad-all-sm">
                                                <thead>
                                                    <tr class="bg-default">
                                                        <th>#</th>
                                                        <th>Username</th>
                                                        <th>E-mail</th>
                                                        <th>Mobile</th>
                                                        <th class="text-center">Verification</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td class="text-primary">Charles A. Martin</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (795) 784 8989</td>
                                                        <td class="text-center"><span class="label label-success label-md font-sm fw-light display-ib btn-rounded">Paid</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td class="text-primary">Julie Morgan</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (397) 781 2371</td>
                                                        <td class="text-center"><span class="label label-danger label-md font-sm fw-light display-ib btn-rounded">Cancel</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td class="text-primary">Shawn Peterson</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (896) 785 4596</td>
                                                        <td class="text-center"><span class="label label-primary label-md font-sm fw-light display-ib btn-rounded">Pending</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td class="text-primary">Dennis Freeman</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (473) 396 7238</td>
                                                        <td class="text-center"><span class="label label-danger label-md font-sm fw-light display-ib btn-rounded">Cancel</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td class="text-primary">Ernest Scott</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (285) 457 7458</td>
                                                        <td class="text-center"><span class="label label-warning label-md font-sm fw-light display-ib btn-rounded">Error</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td class="text-primary">Betty Warren</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (633) 256 9683</td>
                                                        <td class="text-center"><span class="label label-success label-md font-sm fw-light display-ib btn-rounded">Paid</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td class="text-primary">Lisa W. Edwards</td>
                                                        <td>example@example.com</td>
                                                        <td>+1 (739) 875 1256</td>
                                                        <td class="text-center"><span class="label label-warning label-md font-sm fw-light display-ib btn-rounded">Error</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="bst-twitter-widget bg-primary base-reverse pad-all-lg mrgn-b-lg">
                                    <p class="mrgn-b-lg"><i class="fa fa-twitter fa-2x" aria-hidden="true"></i></p>
                                    <p class="font-xl">Never in all their history have men been able truly... </p>
                                    <p class="twt-date">22 May, 2015 via mobile</p>
                                    <ul class="list-inline list-unstyled">
                                        <li><i class="fa fa-thumbs-o-up mrgn-r-sm" aria-hidden="true"></i> 254</li>
                                        <li><i class="fa fa-retweet mrgn-r-sm" aria-hidden="true"></i> 2544</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 full-col-lg-md bst-lg-desktop-half">
                                <div class="bst-full-block">
                                    <div class="bst-wheather-widget text-center bg-warning base-reverse">
                                        <div class="bst-weather-detail">
                                            <div class="clearfix mrgn-b-lg">
                                                <div class="pull-left temp-status"> <img class="img-responsive display-ib" src="assets/img/bottom-down.png" width="26" height="49" alt="arrow down" /> <span id="min-temp" class="temp-value">
                                                        <i class="fa fa-circle pull-right" aria-hidden="true"></i>
                                                    </span> </div>
                                                <div class="pull-right temp-status"> <img class="img-responsive display-ib" src="assets/img/bottom-up.png" width="26" height="49" alt="arrow down" /> <span id="max-temp" class="temp-value">
                                                        <i class="fa fa-circle pull-right" aria-hidden="true"></i>
                                                    </span> </div>
                                            </div>
                                            <h2 class="fw-normal fw-light mrgn-b-sm" id="date"></h2>
                                            <h4 id="today-temp" class="fw-light mrgn-b-md text-capitalize font-xl"></h4>
                                            <div class="wheather-view mrgn-b-lg">
                                                <div class="wheather-view" id="today-icon">
                                                    <canvas class="weather-icon pad-t-md" width="150" height="150" data-color="#ffffff"></canvas>
                                                </div>
                                            </div>
                                            <div class="city-active">
                                                <h3 class="fw-light" id="city"></h3> </div>
                                        </div>
                                        <div class="wheather-list-wrap bst-card-box clearfix">
                                            <div class="feature-box-list col-xs-3 col-sm-3 col-md-3 col-lg-3 pad-all-none">
                                                <div class="square-50">
                                                    <canvas class="weather-icon" width="40" height="40" data-color="#ffffff"></canvas>
                                                </div> <span class="show base-reverse">12:00</span> </div>
                                            <div class="feature-box-list col-xs-3 col-sm-3 col-md-3 col-lg-3 pad-all-none">
                                                <div class="square-50">
                                                    <canvas class="weather-icon" width="40" height="40" data-color="#ffffff"></canvas>
                                                </div> <span class="show base-reverse">12:30</span> </div>
                                            <div class="feature-box-list col-xs-3 col-sm-3 col-md-3 col-lg-3 pad-all-none">
                                                <div class="square-50">
                                                    <canvas class="weather-icon" width="40" height="40" data-color="#ffffff"></canvas>
                                                </div> <span class="show base-reverse">1:00</span> </div>
                                            <div class="feature-box-list col-xs-3 col-sm-3 col-md-3 col-lg-3 pad-all-none">
                                                <div class="square-50">
                                                    <canvas class="weather-icon" width="40" height="40" data-color="#ffffff"> </canvas>
                                                </div> <span class="show base-reverse">1:30</span> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 full-col-lg-md bst-lg-desktop-half">
                                <div class="bst-full-block bst-chat-session-widget">
                                    <div class="pad-all-lg">
                                        <div class="bst-block-title pos-relative">
                                            <div class="caption">
                                                <h4 class="mrgn-b-none">Chat Sessions</h4> </div>
                                            <div class="contextual-link">
                                                <ul class="list-inline list-unstyled">
                                                    <li>
                                                        <div class="dropdown display-ib"> <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-chevron-down"></i></a>
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
                                    <div class="bst-block-content">
                                        <div class="alternate-bg-list">
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-1.png" width="64" height="64" alt="Chat session" /> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9 pad-lr-none">
                                                        <h4 class="font-xl">Connor Soto</h4> <span class="text-success">Online</span> </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12 text-right xs-text-left">
                                                        <a href="#/" class="btn-primary btn-circle display-ib mrgn-r-xs"> <i class="fa fa-phone square-50 display-ib" aria-hidden="true"></i> </a>
                                                        <a href="#/" class="btn-primary btn-circle display-ib"> <i class="fa fa-comments square-50 display-ib" aria-hidden="true"></i> </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-2.png" width="64" height="64" alt="Chat session" /> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9 pad-lr-none">
                                                        <h4 class="font-xl">Miguel Romero</h4> <span class="text-danger">Offline</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-3.png" width="64" height="64" alt="Chat session" /> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9 pad-lr-none">
                                                        <h4 class="font-xl">Marguerite Riley</h4> <span class="base-light">Away</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3"><img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-4.png" width="64" height="64" alt="Chat session" /> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9 pad-lr-none">
                                                        <h4 class="font-xl">Marian Burke</h4> <span class="text-danger">Offline</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3"><img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-5.png" width="64" height="64" alt="Chat session" /> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9 pad-lr-none">
                                                        <h4 class="font-xl">Wayne Hoffman</h4> <span class="base-light">Away</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-6.png" width="64" height="64" alt="Chat session" /> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9 pad-lr-none">
                                                        <h4 class="font-xl">Chris Evans</h4> <span class="text-success">Online</span> </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-12 text-right xs-text-left">
                                                        <a href="#/" class="btn-primary btn-circle display-ib mrgn-r-xs"> <i class="fa fa-phone square-50 display-ib" aria-hidden="true"></i> </a>
                                                        <a href="#/" class="btn-primary btn-circle display-ib"> <i class="fa fa-comments square-50 display-ib" aria-hidden="true"></i> </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 full-col-lg-md bst-lg-desktop-full">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-sm">
                                        <div class="caption">
                                            <h4 class="mrgn-b-none">Recent Activities</h4> </div>
                                        <div class="contextual-link">
                                            <ul class="list-inline list-unstyled">
                                                <li>
                                                    <div class="dropdown display-ib"> <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-chevron-down"></i></a>
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
                                    <div class="bst-block-content">
                                        <div class="slimscrollbar" style="height:560px;">
                                            <div class="bst-activities-widget">
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">9 Min Ago</p>
                                                    <p>John Added a new product in Fahion Store</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">15 Min Ago</p>
                                                    <p class="fw-medium">80 New user subscribed to News letter updates.</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">15 Min Ago</p>
                                                    <p>USA Server was down and site is not working</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">21 Min Ago</p>
                                                    <p>You updated your profile picture</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">A Day Ago</p>
                                                    <p>New Team member added to team list</p>
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-sm"> <img class="img-responsive img-circle square-50" src="assets/img/grantt-user.png" width="50" height="50"> </div>
                                                        <div class="pull-left">
                                                            <h6>Garrett Frank</h6>
                                                            <p class="base-light">Jr. Designer</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">1 day 5 Hours Ago</p>
                                                    <p>Author Fee for included support sale IVIP13444036</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">9 Min Ago</p>
                                                    <p>John Added a new product in Fahion Store</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">15 Min Ago</p>
                                                    <p>80 New user subscribed to News letter updates.</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">15 Min Ago</p>
                                                    <p>USA Server was down and site is not working</p>
                                                </div>
                                                <div class="activity-entry pad-tb-sm">
                                                    <p class="base-light">21 Min Ago</p>
                                                    <p>You updated your profile picture</p>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">A Day Ago</p>
                                                    <p>New Team member added to team list</p>
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-sm"> <img class="img-responsive img-circle square-50" src="assets/img/grantt-user.png" width="50" height="50"> </div>
                                                        <div class="pull-left">
                                                            <h6>Garrett Frank</h6>
                                                            <p>Jr. Designer</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="activity-entry pad-tb-xs">
                                                    <p class="base-light">1 day 5 Hours Ago</p>
                                                    <p>Author Fee for included support sale IVIP13444036</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-block">
                                    <div class="bst-block-title">
                                        <h4>Orders</h4> </div>
                                    <div class="bst-block-content">
                                        <div class="table-responsive">
                                            <table class="table table-hover base-light fw-normal">
                                                <thead>
                                                    <tr>
                                                        <th class="base-light fw-normal">ID</th>
                                                        <th class="base-light fw-normal">Product</th>
                                                        <th class="base-light fw-normal">Sold quantity</th>
                                                        <th class="base-light fw-normal">Price</th>
                                                        <th class="base-light fw-normal">Available Stock</th>
                                                        <th class="base-light fw-normal">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>001</td>
                                                        <td>Lunar probe project</td>
                                                        <td>809</td>
                                                        <td>$625.50</td>
                                                        <td>809</td>
                                                        <td> <a href="#/"><i class="fa fa-cog base-light" aria-hidden="true"></i></a> <a href="#/"><i class="fa fa-chevron-down base-light" aria-hidden="true"></i></a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>002</td>
                                                        <td>Dream successful plan</td>
                                                        <td>6709</td>
                                                        <td>$915.50</td>
                                                        <td>6709</td>
                                                        <td> <a href="#/"><i class="fa fa-cog base-light" aria-hidden="true"></i></a> <a href="#/"><i class="fa fa-chevron-down base-light" aria-hidden="true"></i></a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>003</td>
                                                        <td>Office automatization</td>
                                                        <td>784</td>
                                                        <td>$878.50</td>
                                                        <td>784</td>
                                                        <td> <a href="#/"><i class="fa fa-cog base-light" aria-hidden="true"></i></a> <a href="#/"><i class="fa fa-chevron-down base-light" aria-hidden="true"></i></a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>004</td>
                                                        <td>The sun climbing plan</td>
                                                        <td>2245</td>
                                                        <td>$925.50</td>
                                                        <td>2245</td>
                                                        <td> <a href="#/"><i class="fa fa-cog base-light" aria-hidden="true"></i></a> <a href="#/"><i class="fa fa-chevron-down base-light" aria-hidden="true"></i></a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>005</td>
                                                        <td>Open strategy</td>
                                                        <td>1245</td>
                                                        <td>$25.00</td>
                                                        <td>1245</td>
                                                        <td> <a href="#/"><i class="fa fa-cog base-light" aria-hidden="true"></i></a> <a href="#/"><i class="fa fa-chevron-down base-light" aria-hidden="true"></i></a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>006</td>
                                                        <td>Tantas earum numeris</td>
                                                        <td>21245</td>
                                                        <td>$925.00</td>
                                                        <td>21245</td>
                                                        <td> <a href="#/"><i class="fa fa-cog base-light" aria-hidden="true"></i></a> <a href="#/"><i class="fa fa-chevron-down base-light" aria-hidden="true"></i></a> </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="pad-all-lg bg-primary">
                                        <div class="bst-block-title">
                                            <div class="caption">
                                                <h4 class="base-reverse mrgn-b-none">Support &amp; Tickets</h4> </div>
                                            <div class="contextual-link">
                                                <ul class="list-inline list-unstyled">
                                                    <li><a href="#/"><i class="fa fa-search base-reverse" aria-hidden="true"></i></a></li>
                                                    <li>
                                                        <div class="dropdown display-ib"> <a href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-chevron-down base-reverse"></i></a>
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
                                    <div class="bst-block-content bst-support-widget">
                                        <div class="alternate-bg-list">
                                            <div class="bst-support-ticket pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3"> <img class="img-responsive display-ib img-circle square-50" src="assets/img/member-3.png" alt="User Photo" height="85" width="85"> </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                                        <div class="clearfix">
                                                            <h6 class="text-primary pull-left">Helena Boone</h6> <span class="text-right text-success pull-right"><i class="fa fa-dot-circle-o"></i> open</span> </div>
                                                        <p class="font-xs mrgn-b-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry...</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bst-support-ticket pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3"> <img class="img-responsive display-ib img-circle square-50" src="assets/img/task-user-3.png" alt="User Photo" height="50" width="50"> </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                                        <div class="clearfix">
                                                            <h6 class="text-primary pull-left">Helena Boone</h6> <span class="text-right text-warning pull-right"><i class="fa fa-dot-circle-o"></i> Pending</span> </div>
                                                        <p class="font-xs mrgn-b-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry...</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bst-support-ticket pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3"> <img class="img-responsive display-ib img-circle square-50" src="assets/img/task-user-4.png" alt="User Photo" height="50" width="50"> </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                                        <div class="clearfix">
                                                            <h6 class="text-primary pull-left">Clayton Owen</h6> <span class="text-right base-light pull-right"><i class="fa fa-dot-circle-o"></i> Close</span> </div>
                                                        <p class="font-xs mrgn-b-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry...</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bst-support-ticket pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3"> <img class="img-responsive display-ib img-circle square-50" src="assets/img/task-user-2.png" alt="User Photo" height="50" width="50"> </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-9">
                                                        <div class="clearfix">
                                                            <h6 class="text-primary pull-left">Hester Chapman</h6> <span class="text-right text-success pull-right"><i class="fa fa-dot-circle-o"></i> open</span> </div>
                                                        <p class="font-xs mrgn-b-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-md pad-lr-lg bg-warning clearfix">
                                            <p class="base-reverse pull-left mrgn-b-none"><i class="fa fa-bell" aria-hidden="true"></i> 5 New tickets</p> <a class="base-reverse pull-right" href="#/">
                                                View All
                                            </a> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-sm">
                                        <div class="row">
                                            <div class="col-xs-5 col-sm-6 col-md-6 col-md-6">
                                                <h4>Inbox</h4> </div>
                                            <div class="col-xs-7 col-sm-6 col-md-6 col-md-6 text-right"> <a href="javascript:;" class="more-btn">View All <span class="caret mrgn-l-sm"></span></a> </div>
                                        </div>
                                    </div>
                                    <div class="bst-block-content">
                                        <div class="table-responsive">
                                            <table class="table table-hover mrgn-b-none">
                                                <thead>
                                                    <tr>
                                                        <th> ID </th>
                                                        <th> Review </th>
                                                        <th> Rating </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> 125 </td>
                                                        <td>
                                                            <p>Lorem ipsum dolor sit amet, onsectetuer adipiscing elit, sed iam nonummy nibh - Person Name</p> <a class="btn btn-sm btn-success" href="#/">Approve</a> <a class="btn btn-sm btn-danger" href="#/">Reject</a> </td>
                                                        <td> <i class="fa fa-star" aria-hidden="true"></i> 3/5 </td>
                                                    </tr>
                                                    <tr>
                                                        <td> 247 </td>
                                                        <td>
                                                            <p>Lorem ipsum dolor sit amet, onsectetuer adipiscing elit, sed iam nonummy nibh - Person Name</p> <a class="btn btn-sm btn-success" href="#/">Approve</a> <a class="btn btn-sm btn-danger" href="#/">Reject</a> </td>
                                                        <td> <i class="fa fa-star" aria-hidden="true"></i> 3/5 </td>
                                                    </tr>
                                                    <tr>
                                                        <td> 345 </td>
                                                        <td>
                                                            <p>Lorem ipsum dolor sit amet, onsectetuer adipiscing elit, sed iam nonummy nibh - Person Name</p> <a class="btn btn-sm btn-success" href="#/">Approve</a> <a class="btn btn-sm btn-danger" href="#/">Reject</a> </td>
                                                        <td> <i class="fa fa-star" aria-hidden="true"></i> 3/5 </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <h4>Total Revenue</h4> </div>
                                    <div class="bst-block-content">
                                        <canvas id="total-revenue-chart" height="175"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 full-col-lg-md bst-lg-desktop-full mrgn-b-lg">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="pad-all-lg bg-inverse">
                                        <div class="bst-block-title mrgn-b-lg">
                                            <h4 class="base-reverse">Chat History</h4> </div>
                                        <div class="bst-block-content">
                                            <div class="bst-chat-wrap">
                                                <div class="slimscrollbar" style="height: 540px;" data-always-visible="1" data-rail-visible="0">
                                                    <div class="chat-wrap-list">
                                                        <div class="clearfix">
                                                            <div class="col-lg-custom-3">
                                                                <div class="feature-box-thumb"> <img class="img-responsive img-circle" src="assets/img/chat-user-5.png" alt="User Image" width="64" height="64"> </div>
                                                            </div>
                                                            <div class="col-lg-custom-9">
                                                                <h6 class="text-primary">John Stone</h6>
                                                                <div class="bst-chat-msg border-rad-xs bg-primary">
                                                                    <p>Wow! This is something exactly I needed. You guys did a great work.</p>
                                                                </div>
                                                                <div class="post-meta"> <span>2 min ago</span> <a href="javascript:;"><i class="fa fa-reply text-success mrgn-l-xs" aria-hidden="true"></i></a> </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix right-side">
                                                            <div class="col-lg-custom-3">
                                                                <div class="feature-box-thumb"> <img class="img-responsive img-circle" src="assets/img/histroy-user-2.png" alt="User Image" width="64" height="64"> </div>
                                                            </div>
                                                            <div class="col-lg-custom-9">
                                                                <h6 class="text-primary">Brad Lee</h6>
                                                                <div class="bst-chat-msg border-rad-xs bg-warning">
                                                                    <p>Thank you :) We are always here to help you. Our customer care service is 24X7. You may reach us anytime. </p>
                                                                </div>
                                                                <div class="post-meta"> <span>3 hours ago</span> <a href="javascript:;"><i class="fa fa-reply text-primary mrgn-l-xs" aria-hidden="true"></i></a> </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix">
                                                            <div class="col-lg-custom-3">
                                                                <div class="feature-box-thumb"> <img class="img-responsive img-circle" src="assets/img/chat-user-5.png" alt="User Image" width="64" height="64"> </div>
                                                            </div>
                                                            <div class="col-lg-custom-9">
                                                                <h6 class="text-primary">Jonna Luke</h6>
                                                                <div class="bst-chat-msg border-rad-xs bg-primary">
                                                                    <p>I am impressed with your immediate help. I got my work done efficiently. </p>
                                                                </div>
                                                                <div class="post-meta"> <span>4 hours ago</span> <a href="javascript:;"><i class="fa fa-reply text-primary mrgn-l-xs" aria-hidden="true"></i></a> </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix right-side">
                                                            <div class="col-lg-custom-3">
                                                                <div class="feature-box-thumb"> <img class="img-responsive img-circle" src="assets/img/histroy-user-2.png" alt="User Image" width="64" height="64"> </div>
                                                            </div>
                                                            <div class="col-lg-custom-9">
                                                                <h6 class="text-primary">Justin Finn</h6>
                                                                <div class="bst-chat-msg border-rad-xs bg-warning">
                                                                    <p>You can find the date time picker and notification & alerts under "Components" menu. It is really very easy to use them.</p>
                                                                </div>
                                                                <div class="post-meta"> <span> 7 hours ago</span> <a href="javascript:;"><i class="fa fa-reply text-primary mrgn-l-xs" aria-hidden="true"></i></a> </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix">
                                                            <div class="col-lg-custom-3">
                                                                <div class="feature-box-thumb"> <img class="img-responsive img-circle" src="assets/img/chat-user-5.png" alt="User Image" width="64" height="64"> </div>
                                                            </div>
                                                            <div class="col-lg-custom-9">
                                                                <h6 class="text-primary">Merry Coles</h6>
                                                                <div class="bst-chat-msg border-rad-xs bg-primary">
                                                                    <p>Hey please tell me where can I find date time picker and notifications & alerts in it.</p>
                                                                </div>
                                                                <div class="post-meta"> <span>8 hours ago</span> <a href="javascript:;"><i class="fa fa-reply text-primary mrgn-l-xs" aria-hidden="true"></i></a> </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix right-side">
                                                            <div class="col-lg-custom-3">
                                                                <div class="feature-box-thumb"> <img class="img-responsive img-circle" src="assets/img/histroy-user-2.png" alt="User Image" width="64" height="64"> </div>
                                                            </div>
                                                            <div class="col-lg-custom-9">
                                                                <h6 class="text-primary">Jaime Chin</h6>
                                                                <div class="bst-chat-msg border-rad-xs bg-warning">
                                                                    <p>I love the charts and animations. This theme has super cool stuff to use.</p>
                                                                </div>
                                                                <div class="post-meta"> <span>13 hours ago</span> <a href="javascript:;"><i class="fa fa-reply text-primary mrgn-l-xs" aria-hidden="true"></i></a> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 full-col-lg-md bst-lg-desktop-half">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <h4>Statistics</h4> </div>
                                    <div class="bst-block-content">
                                        <div id="statistics-chart" class="overflow-wrapper"></div>
                                    </div>
                                </div>
                                <div class="bst-block">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pad-t-xs">
                                            <p>Totle Expense</p>
                                            <p class="font-2x text-danger">$25,430</p>
                                            <p class="mrgn-b-none font-xl">+20% More than Last Month</p>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="expenses-chart" style="height: 150px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 full-col-lg-md bst-lg-desktop-half">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="pad-all-lg bg-success base-reverse">
                                        <p class="font-xl pad-lr-xs pad-t-sm">Total Earnings</p>
                                        <h4 class="mrgn-b-none font-3x fw-light pad-lr-sm">$580.50</h4> </div>
                                    <div class="bst-block-content">
                                        <div class="alternate-bg-list">
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                        <div class="clearfix"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-1.png" width="64" height="64" alt="earning user" /> </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-8 col-xs-6 pad-lr-none">
                                                        <h4 class="font-xl">Connor Soto</h4> <span class="text-success">Online</span> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-3 text-right"> <span class="font-2x">$35</span></div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                        <div class="clearfix"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-2.png" width="64" height="64" alt="earning user" /> </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-8 col-xs-6 pad-lr-none">
                                                        <h4 class="font-xl">Miguel Romero</h4> <span class="text-danger">Offline</span> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-3 text-right"> <span class="font-2x">$35</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                        <div class="clearfix"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-3.png" width="64" height="64" alt="earning user" /> </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-8 col-xs-6 pad-lr-none">
                                                        <h4 class="font-xl">Marguerite Riley</h4> <span class="base-light">Away</span> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-3 text-right"> <span class="font-2x">$35</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                        <div class="clearfix"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-4.png" width="64" height="64" alt="earning user" /> </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-8 col-xs-6 pad-lr-none">
                                                        <h4 class="font-xl">Marian Burke</h4> <span class="text-danger">Offline</span> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-3 text-right"> <span class="font-2x">$35</span> </div>
                                                </div>
                                            </div>
                                            <div class="chat-session-entry pad-tb-sm pad-lr-lg">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                        <div class="clearfix"> <img class="img-responsive img-circle mrgn-r-md pull-left" src="assets/img/chat-user-5.png" width="64" height="64" alt="earning user" /> </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-8 col-xs-6 pad-lr-none">
                                                        <h4 class="font-xl">Wayne Hoffman</h4> <span class="base-light">Away</span> </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-3 text-right"> <span class="font-2x">$35</span> </div>
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

@include(DIR_ADMIN.'footer')