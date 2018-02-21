@include(DIR_ADMIN.'header')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css" />
<!-- Datatables Buttons -->
<link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />

{{--*/
// statuses
$admin_statuses = config("constants.ADMIN_STATUSES");
/*--}}
<div class="bst-wrapper">
        @include(DIR_ADMIN.'side_overlay')
        <div class="bst-main">
            <div class="fyt-main-top"></div>
            <div class="bst-main-wrapper pad-all-md">
            @include(DIR_ADMIN.'sidebar')
                <div class="bst-content-wrapper">
                    <div class="bst-content">
                        <div class="bst-page-bar mrgn-b-md breadcrumb-double-arrow">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item text-capitalize">
                                    <h3>Dashboard 1</h3> </li>
                                <li class="breadcrumb-item"><a href="#/">Home</a></li>
                                <li class="breadcrumb-item active"><a href="index.html">Dashboard 1</a></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-6 mrgn-b-md full-col-lg-md">
                                <div class="dashboard-calendar-styling bg-white overflow-wrapper">
                                    <div class="bst-block-title pad-all-md">
                                        <h4 class="base-reverse">Welcome John,</h4>
                                        <h3 class="base-reverse mrgn-b-none">Today's Scheduled Services</h3> </div>
                                    <div class="bst-block-content">
                                        <div class='dashboard-calendar'></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 mrgn-b-md full-col-lg-md">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="bst-block">
                                            <div class="bst-block-title">
                                                <h4>Tol Sales</h4> </div>
                                            <div class="bst-block-content">
                                                <canvas id="top-sales-chart" height="225"></canvas>
                                                <div id="top-sales-google-chart" class="overflow-wrapper"></div>
                                            </div>
                                        </div>
                                        <div class="bg-inverse pad-all-lg mrgn-b-md bst-card-box">
                                            <div class="bst-block-title">
                                                <h4 class="base-reverse">Schedule</h4> </div>
                                            <div class="bst-block-content">
                                                <canvas id="schedule-bar-chart" height="210"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="site-stat bg-white mrgn-b-md pad-all-lg bst-card-box">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3"> <img class="img-responsive" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'today-sales-icon.png') !!}" width="50" height="50" alt="Revenue" /> </div>
                                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                    <h5>Total Revenue</h5>
                                                    <h2 class="mrgn-b-none">$35,455</h2> </div>
                                            </div>
                                        </div>
                                        <div class="site-stat bg-white mrgn-b-md bst-block bst-card-box">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3"> <img class="img-responsive" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'revenue-icon.png') !!}" width="50" height="50" alt="Revenue" /> </div>
                                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                    <h5>Today's visits</h5>
                                                    <h2 class="mrgn-b-none">$35,455</h2> </div>
                                            </div>
                                        </div>
                                        <div class="site-stat bg-white mrgn-b-md pad-all-lg bst-card-box">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3"> <img class="img-responsive" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'today-visits-icon.png') !!}" width="50" height="50" alt="Revenue" /> </div>
                                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                    <h5>Today's Sales</h5>
                                                    <h2 class="mrgn-b-none">$35,455</h2> </div>
                                            </div>
                                        </div>
                                        <div class="site-stat bg-white mrgn-b-md pad-all-lg bst-card-box">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3"> <img class="img-responsive" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'conversion-icon.png') !!}" width="50" height="50" alt="Revenue" /> </div>
                                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                    <h5>Conversion</h5>
                                                    <h2 class="mrgn-b-none">.56%</h2> </div>
                                            </div>
                                        </div>
                                        <div class="site-stat bg-white mrgn-b-md pad-all-lg bst-card-box">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3"> <img class="img-responsive" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'overall-sales-icon.png') !!}" width="50" height="50" alt="Revenue" /> </div>
                                                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                                                    <h5>Overall Sales</h5>
                                                    <h2 class="mrgn-b-none">$20,111</h2> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="bst-block overflow-wrapper">
                                    <div class="row">
                                        <div class="pull-left col-lg-3 col-md-3 col-sm-3 col-xs-3 pad-tb-xs text-center"> <img class="img-responsive display-ib" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'maintenance-icon.png') !!}" width="45" height="46" alt="Maintenace icon" /> </div>
                                        <div class="pull-left col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <h2 class="font-3x fw-normal">521</h2>
                                            <h4 class="mrgn-b-none fw-normal">Over due Maintainance Services</h4> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="bst-block overflow-wrapper">
                                    <div class="row">
                                        <div class="pull-left col-lg-3 col-md-3 col-sm-3 col-xs-3 pad-tb-xs text-center"> <img class="img-responsive display-ib" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'update-request-icon.png') !!}" width="43" height="40" alt="Update request icon" /> </div>
                                        <div class="pull-left col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <h2 class="font-3x fw-normal">9123</h2>
                                            <h4 class="mrgn-b-none fw-normal">Resident Information Update Request</h4> </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="bst-block overflow-wrapper">
                                    <div class="row">
                                        <div class="pull-left col-lg-3 col-md-3 col-sm-3 col-xs-3 pad-tb-xs text-center"> <img class="img-responsive display-ib" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'schedule-icon.png') !!}" width="48" height="46" alt="schedule change icon" /> </div>
                                        <div class="pull-left col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                            <h2 class="font-3x fw-normal">300</h2>
                                            <h4 class="mrgn-b-none fw-normal">Schduled Change Request</h4> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="bst-block pad-all-md">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <div class="caption">
                                            <h4 class="mrgn-all-none">Member Management</h4> </div>
                                        <div class="contextual-link">
                                            <div class="dropdown"> <a href="javascript:;" class="base-dark" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"><i class="fa fa-cog fa-lg"></i></a>
                                                <ul class="dropdown-menu dropdown-arrow dropdown-menu-right">
                                                    <li>
                                                        <a class="test" href="javascript:;"> <i class="fa fa-eye"></i> <span class="mrgn-l-sm">View</span> </a>
                                                    </li>
                                                    <li>
                                                        <a class="test" href="javascript:;"> <i class="fa fa-pencil"></i> <span class="mrgn-l-sm">Edit </span> </a>
                                                    </li>
                                                    <li>
                                                        <a class="test" href="javascript:;"> <i class="fa fa-trash-o"></i> <span class="mrgn-l-sm">Delete</span> </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bst-block-content">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-middle th-fw-light mrgn-b-none">
                                                <tbody>
                                                    <tr>
                                                        <td><img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'histroy-user-1.jpg') !!}" class="square-50 img-responsive display-ib img-circle mrgn-r-md" alt="User Image"></td>
                                                        <td>Alberto Decosta</td>
                                                        <td class="text-center"><span class="badge badge-success rounded">Schedules</span> </td>
                                                        <td>User Name</td>
                                                        <td>012 345 6978</td>
                                                        <td>Oct 16, 2016</td>
                                                        <td>3</td>
                                                        <td>Lorem Ipsum is simply dummy text..</td>
                                                        <td><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></td>
                                                        <td><a href="javascript:;"><i class="fa fa-edit mrgn-r-sm"></i></a><a href="javascript:;"><i class="fa fa-times text-danger"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="square-50 bg-primary img-circle base-reverse mrgn-r-md">DR</span></td>
                                                        <td>Jainne Frost</td>
                                                        <td class="text-center"><span class="badge badge-danger rounded">Free</span> </td>
                                                        <td>User Name</td>
                                                        <td>012 345 6978</td>
                                                        <td>Oct 12, 2016</td>
                                                        <td>5</td>
                                                        <td>Lorem Ipsum is simply dummy text..</td>
                                                        <td><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></td>
                                                        <td><a href="javascript:;"><i class="fa fa-edit mrgn-r-sm"></i></a><a href="javascript:;"><i class="fa fa-times text-danger"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'task-user-2.png') !!}" class="square-50 img-responsive display-ib img-circle mrgn-r-md" alt="User Image"></td>
                                                        <td>Vic Phill</td>
                                                        <td class="text-center"><span class="badge badge-success rounded">Schedules</span> </td>
                                                        <td>User Name</td>
                                                        <td>012 345 6978</td>
                                                        <td>May 18, 2016</td>
                                                        <td>2</td>
                                                        <td>Lorem Ipsum is simply dummy text..</td>
                                                        <td><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></td>
                                                        <td><i class="fa fa-edit mrgn-r-sm"></i><i class="fa fa-times text-danger"></i></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="square-50 bg-success img-circle base-reverse mrgn-r-md">VS</span></td>
                                                        <td>Venna Mercy</td>
                                                        <td class="text-center"><span class="badge badge-success rounded">Schedules</span> </td>
                                                        <td>User Name</td>
                                                        <td>012 345 6978</td>
                                                        <td>Apr 28, 2016</td>
                                                        <td>5</td>
                                                        <td>Lorem Ipsum is simply dummy text..</td>
                                                        <td><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></td>
                                                        <td><a href="javascript:;"><i class="fa fa-edit mrgn-r-sm"></i></a><a href="javascript:;"><i class="fa fa-times text-danger"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'task-user-3.png') !!}" class="square-50 img-responsive display-ib img-circle mrgn-r-md" alt="User Image"></td>
                                                        <td>Rose Golddust</td>
                                                        <td class="text-center"><span class="badge badge-danger rounded">Free</span> </td>
                                                        <td>User Name</td>
                                                        <td>012 345 6978</td>
                                                        <td> Oct 16, 2016</td>
                                                        <td>3</td>
                                                        <td>Lorem Ipsum is simply dummy text..</td>
                                                        <td><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></td>
                                                        <td><a href="javascript:;"><i class="fa fa-edit mrgn-r-sm"></i></a><a href="javascript:;"><i class="fa fa-times text-danger"></i></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'task-user-4.png') !!}" class="square-50 img-responsive display-ib img-circle mrgn-r-md" alt="User Image"></td>
                                                        <td>Dusty Raims</td>
                                                        <td class="text-center"><span class="badge badge-success rounded">Schedules</span> </td>
                                                        <td>User Name</td>
                                                        <td>012 345 6978</td>
                                                        <td>Oct 12, 2016</td>
                                                        <td>2</td>
                                                        <td>Lorem Ipsum is simply dummy text..</td>
                                                        <td><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star" aria-hidden="true"></i><i class="fa fa-star"></i><i class="fa fa-star-half-o"></i></td>
                                                        <td><a href="javascript:;"><i class="fa fa-edit mrgn-r-sm"></i></a><a href="javascript:;"><i class="fa fa-times text-danger"></i></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <h4>To Do List</h4> </div>
                                    <div class="bst-block-content">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-middle th-fw-default table-spacing-xl mrgn-b-none">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="" checked>
                                                        </td>
                                                        <td><del>Do your ToDo List today at 6AM GMT</del></td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="tast" value="">
                                                        </td>
                                                        <td>Do your ToDo List today at 6AM GMT</td>
                                                        <td class="text-right">Today: 06:30PM</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="bg-primary pad-all-lg">
                                        <div class="embed-responsive-item">
                                            <div class="continents-map vector"></div>
                                        </div>
                                    </div>
                                    <div class="pad-all-lg bg-white custom-sq-class text-center">
                                        <div class="row mrgn-b-md">
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2"> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-1.png') !!}" class="display-ib square-85 img-circle" alt="users images" height="85" width="85"> </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                                                <div class="square-85"> <span class="bg-danger base-reverse show img-circle">DR</span> </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 ">
                                                <div class="square-85"> <span class="bg-success base-reverse show img-circle">VP</span> </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                                                <div class="square-85"> <span class="bg-primary base-reverse show img-circle">VS</span> </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 "> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-5.png') !!}" class="display-ib square-85 img-circle" alt="users images" height="85" width="85"> </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 ">
                                                <div class="square-85"> <span class="bg-danger base-reverse show img-circle">GD</span> </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                                                <div class="square-85"> <span class="bg-primary base-reverse show img-circle">GD</span> </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2"> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-2.png') !!}" class="display-ib square-85 img-circle" alt="users images" height="85" width="85"> </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 "> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-3.png') !!}" class="display-ib square-85 img-circle" alt="users images" height="85" width="85"> </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2"> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-4.png') !!}" class="display-ib square-85 img-circle" alt="users images" height="85" width="85"> </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 ">
                                                <div class="square-85"> <span class="bg-success base-reverse show img-circle">GD</span> </div>
                                            </div>
                                            <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2 "> <img src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-6.png') !!}" class="display-ib square-85 img-circle" alt="users images" height="85" width="85"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-12 col-sm-12 bst-lg-desktop-full">
                                <div class="bst-block">
                                    <div class="bst-block-title">
                                        <h4>Last Orders</h4> </div>
                                    <div class="bst-block-content">
                                        <div class="table-responsive">
                                            <table class="table table-hover th-fw-default mrgn-b-none">
                                                <thead>
                                                    <tr>
                                                        <th> Order ID </th>
                                                        <th> Product </th>
                                                        <th> Buyer </th>
                                                        <th> Date </th>
                                                        <th> Payment </th>
                                                        <th class="text-center"> Status </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>#1548</td>
                                                        <td>Kode Gaming Laptop</td>
                                                        <td>Stephen Howard</td>
                                                        <td>Oct 16, 2016</td>
                                                        <td>$500</td>
                                                        <td><span class="badge badge-success rounded show">Completed</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>#1548</td>
                                                        <td>Sony Play Station</td>
                                                        <td>Johnny Gonzalez</td>
                                                        <td>Oct 12, 2016</td>
                                                        <td>$993</td>
                                                        <td><span class="badge badge-success rounded show">Completed</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>#1548</td>
                                                        <td>Macbook Pro RAM 16Gb</td>
                                                        <td>Roy Perez</td>
                                                        <td>May 18, 2016</td>
                                                        <td>$2680</td>
                                                        <td><span class="badge badge-danger rounded show">On Hold</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>#1548</td>
                                                        <td>iMAC pro 2016</td>
                                                        <td>Carlos Porter</td>
                                                        <td>Apr 28, 2016</td>
                                                        <td>$240</td>
                                                        <td><span class="badge badge-primary rounded show">Returned</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>#1548</td>
                                                        <td>Nikon D3100 camera</td>
                                                        <td>Denise Jenkins</td>
                                                        <td>Oct 16, 2016</td>
                                                        <td>$1009</td>
                                                        <td><span class="badge badge-success rounded show">Completed</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>#1548</td>
                                                        <td>Canon Printer</td>
                                                        <td>Steve Griffin</td>
                                                        <td>Oct 12, 2016</td>
                                                        <td>$550</td>
                                                        <td><span class="badge badge-success rounded show">Completed</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 bst-lg-desktop-full">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-md">
                                        <h4>Recent Comments</h4> </div>
                                    <div class="bst-block-content">
                                        <div class="pad-tb-sm">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                    <div class="display-ib pos-relative"><img class="img-responsive img-circle" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-7.png') !!}" alt="feature box" height="50" width="50"></div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-9">
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-8 col-md-12">
                                                            <h4>Albert Wallace</h4> </div>
                                                        <div class="col-lg-4 col-md-4 col-md-12 text-right">15 Min</div>
                                                    </div>
                                                    <p class="base-light">Thanks for the chartlist. There are awesome designs and very user friendly.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-sm">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                    <div class="display-ib pos-relative"><img class="img-responsive img-circle" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-8.png') !!}" alt="feature box" height="50" width="50"></div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-9">
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-8 col-md-12">
                                                            <h4>Ollie McCarthy</h4></div>
                                                        <div class="col-lg-4 col-md-4 col-md-12 text-right">15 Min</div>
                                                    </div>
                                                    <p class="base-light">User profile that you are providing is great.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-sm">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                    <div class="display-ib pos-relative"><img class="img-responsive img-circle" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-9.png') !!}" alt="feature box" height="50" width="50"></div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-9">
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-8 col-md-12">
                                                            <h4>William Hernandez</h4> </div>
                                                        <div class="col-lg-4 col-md-4 col-md-12 text-right">15 Min</div>
                                                    </div>
                                                    <p class="base-light">Having plenty of options is really cool. I can choose what suits to my work.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-sm">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-2 col-xs-3">
                                                    <div class="display-ib pos-relative"><img class="img-responsive img-circle" src="{!! URL::to(config('constants.ADMIN_IMG_URL').'member-10.png') !!}" alt="feature box" height="50" width="50"></div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-10 col-xs-9">
                                                    <div class="row">
                                                        <div class="col-lg-8 col-md-8 col-md-12">
                                                            <h4>Timothy Holt</h4> </div>
                                                        <div class="col-lg-4 col-md-4 col-md-12 text-right">15 Min</div>
                                                    </div>
                                                    <p class="base-light">Doing work with such a tool is really fun.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-7 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-block">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <div class="caption">
                                            <h4>Site Visits</h4> </div>
                                        <div class="contextual-link">
                                            <div class="dropdown"> <a href="javascript:;" class="mrgn-l-xs more-btn" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">more<i class="fa fa-angle-down"></i></a>
                                                <ul class="dropdown-menu dropdown-icon pull-right">
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
                                        </div>
                                    </div>
                                    <div class="bst-block-content">
                                        <div class="site-visits-chart" data-highcharts-chart="0"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-12 col-sm-12 full-col-lg-md">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="pad-all-lg">
                                        <div class="bst-block-title mrgn-b-md">
                                            <h4>Visitors &amp; Views</h4> </div>
                                        <div class="bst-block-content">
                                            <div id="visitors-stat-chart"></div>
                                        </div>
                                    </div>
                                    <div class="visitor-stat-block xs-text-center">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"> <span class="text-primary font-3x">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                </span> </div>
                                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12 pad-tb-sm">
                                                <p class="font-lg mrgn-b-none">Page Views</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12 text-right pad-tb-xs"> <span class="count-item font-2x" data-count="230609">230,609</span> </div>
                                        </div>
                                    </div>
                                    <div class="visitor-stat-block xs-text-center">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"> <span class="text-success font-3x">
                                                    <i class="fa fa-globe" aria-hidden="true"> </i>
                                                </span> </div>
                                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12 pad-tb-sm">
                                                <p class="font-lg mrgn-b-none">Site Visitors</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12 text-right pad-tb-xs"> <span class="count-item font-2x" data-count="210405">210,405</span> </div>
                                        </div>
                                    </div>
                                    <div class="visitor-stat-block xs-text-center">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"> <span class="text-danger font-3x">
                                                    <i class="fa fa-mouse-pointer" aria-hidden="true"></i>
                                                </span> </div>
                                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12 pad-tb-sm">
                                                <p class="font-lg mrgn-b-none">Total Clicks</p>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12 text-right pad-tb-xs"> <span class="count-item font-2x" data-count="420540">420,540</span> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 bst-sm-desktop-half">
                                <div class="bg-inverse pad-all-lg base-reverse mrgn-b-md bst-card-box">
                                    <div class="bst-block-title mrgn-b-lg">
                                        <div class="caption">
                                            <h4 class="mrgn-all-none base-reverse">Email Statics</h4> </div>
                                        <div class="contextual-link">
                                            <div class="dropdown display-ib">
                                                <a href="javascript:;" class="mrgn-l-xs base-reverse" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true"> <i class="fa fa-cog fa-lg base-reverse"></i> </a>
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
                                        </div>
                                    </div>
                                    <div class="bst-block-content">
                                        <div class="pad-tb-sm">
                                            <p class="mrgn-b-md">Email Sent</p>
                                            <div class="progress progress-xs-height">
                                                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" data-width="65%"> <span class="sr-only">65% Complete (secondary)</span> </div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-sm">
                                            <p class="mrgn-b-md">Opened Emails</p>
                                            <div class="progress progress-xs-height">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" data-width="85%"></div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-sm">
                                            <p class="mrgn-b-md">Bounce Rate</p>
                                            <div class="progress progress-xs-height">
                                                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" data-width="65%"></div>
                                            </div>
                                        </div>
                                        <div class="pad-tb-sm">
                                            <p class="mrgn-b-md">Spam Mails</p>
                                            <div class="progress progress-xs-height">
                                                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" data-width="35%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 bst-sm-desktop-half">
                                <div class="bst-block">
                                    <div class="bst-block-title">
                                        <h4>Browsers</h4> </div>
                                    <div class="bst-block-content">
                                        <div id="browser-stats-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 bst-sm-desktop-half">
                                <div class="bst-full-block overflow-wrapper">
                                    <div class="pad-all-lg bg-success base-reverse text-center">
                                        <h4 class="font-xl">Total Share</h4>
                                        <h2 class="font-3x">14,562</h2>
                                        <p class="mrgn-b-none"><i class="fa fa-arrow-up" aria-hidden="true"></i> +40% More than Last Year</p>
                                    </div>
                                    <div class="border-list-bottom">
                                        <div class="element-list pad-lr-md pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8"> <span class="btn-circle btn-facebook display-ib base-reverse mrgn-r-sm" data-original-title="facebook"> <i class="fa fa-facebook fa-2x square-50" aria-hidden="true"></i> </span> <span class="hidden-xs">Facebook </span> </div>
                                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 pad-tb-sm text-right"> 124 </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-md pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8"> <span class="btn-circle btn-twitter display-ib base-reverse mrgn-r-sm" data-original-title="facebook"> <i class="fa fa-twitter fa-2x square-50" aria-hidden="true"></i> </span> <span class="hidden-xs">Twitter </span> </div>
                                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 pad-tb-sm text-right"> 156 </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-md pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8"> <span class="btn-circle btn-google display-ib base-reverse mrgn-r-sm" data-original-title="facebook"> <i class="fa fa-google-plus fa-2x square-50" aria-hidden="true"></i> </span> <span class="hidden-xs">Google Plus </span> </div>
                                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 pad-tb-sm text-right"> 112 </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-md pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-8 col-sm-8 col-xs-8"> <span class="btn-circle btn-linkedin display-ib base-reverse mrgn-r-sm" data-original-title="facebook"> <i class="fa fa-linkedin fa-2x square-50" aria-hidden="true"></i> </span> <span class="hidden-xs">Linkedin </span> </div>
                                                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-4 pad-tb-sm text-right"> 112 </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 bst-sm-desktop-half">
                                <div class="bst-full-block">
                                    <div class="bst-block-title pad-lr-lg pad-tb-md clearfix">
                                        <div class="bst-block-title">
                                            <div class="row">
                                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                                    <h4 class="pull-left mrgn-b-none">
                                                        Download Reports
                                                    </h4> </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                                                    <a class="display-ib" href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bst-reports border-list-top">
                                        <div class="element-list pad-lr-lg pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-md text-warning report-file"> <span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i> </span> </div>
                                                        <p class="mrgn-b-none">Sales</p> <span>10-Jan to 10-Feb</span> </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 text-right">
                                                    <a href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-lg pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-md text-warning report-file"> <span><i class="fa fa-file-word-o fa-2x" aria-hidden="true"></i> </span> </div>
                                                        <p class="mrgn-b-none">Visitors</p> <span>10-Jan to 10-Feb</span> </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 text-right">
                                                    <a href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-lg pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-md text-warning report-file"> <span><i class="fa fa-file-word-o fa-2x" aria-hidden="true"></i> </span> </div>
                                                        <p class="mrgn-b-none">Revenue</p> <span>10-Jan to 10-Feb</span> </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 text-right">
                                                    <a href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-lg pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-md text-warning report-file"> <span><i class="fa fa-file-powerpoint-o fa-2x" aria-hidden="true"></i></span> </div>
                                                        <p class="mrgn-b-none">Stock</p> <span>10-Jan to 10-Feb</span> </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 text-right">
                                                    <a href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-lg pad-tb-sm clearfix">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-md text-warning report-file"> <span><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i> </span> </div>
                                                        <p class="mrgn-b-none">Member Detail</p> <span>10-Jan to 10-Feb</span> </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 text-right">
                                                    <a href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="element-list pad-lr-lg pad-tb-sm">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-8">
                                                    <div class="clearfix">
                                                        <div class="pull-left mrgn-r-md text-warning report-file"> <span><i class="fa fa-file-archive-o fa-2x" aria-hidden="true"></i> </span> </div>
                                                        <p class="mrgn-b-none">Salary</p> <span>10-Jan to 10-Feb</span> </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 text-right">
                                                    <a href="#/"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12 bst-usage-widget">
                                <div class="bst-block">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-4 col-xs-6">
                                            <h4 class="font-xl base-light">Network Usage</h4> <span class="font-2x fw-light">84%</span> </div>
                                        <div class="col-lg-7 col-md-7 col-sm-8 col-xs-6">
                                            <div id="network-usage-chart" class="overflow-wrapper"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 bst-usage-widget">
                                <div class="bst-block">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-4 col-xs-6">
                                            <h4 class="font-xl base-light">RAM Usage</h4> <span class="font-2x fw-light">512KB/1GB</span> </div>
                                        <div class="col-lg-7 col-md-7 col-sm-8 col-xs-6">
                                            <div id="ram-usage-chart" class="overflow-wrapper"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-sm-12 bst-usage-widget">
                                <div class="bst-block">
                                    <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-4 col-xs-6">
                                            <h4 class="font-xl base-light">Overall Load</h4> <span class="font-2x fw-light">65%</span> </div>
                                        <div class="col-lg-7 col-md-7 col-sm-8 col-xs-6">
                                            <div id="load-chart" class="overflow-wrapper"></div>
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

<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'pages/base_tables_datatables.js') !!}"></script> 
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script> 

<!-- Datatables Buttons -->
<!--<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.24/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>-->

<script src="{!! URL::to(config('constants.ADMIN_JS_URL').'plugins/magnific-popup/magnific-popup.min.js') !!}"></script>