
{{--*/

// extra models
$user_module_model = $model_path."UserModule";
$user_module_model = new $user_module_model;

// get modules that are view-able
$query = $user_module_model->getModules();
$designationId = \Session::get(ADMIN_SESS_KEY.'auth')->fkDesignationId->{'$id'};

/*--}}

<div class="bst-sidebar">
    <div class="bst-sidebar-back"> </div>
    <div class="bst-sidebar-nav-wrapper">
        <div class="bst-site-link mrgn-b-md bg-white overflow-wrapper">
            <div class="bst-site-info pull-left"><i class="fa fa-globe mrgn-r-sm"> </i> <span>{!! APP_NAME !!} </span> </div>
            <button class="c-hamburger c-hamburger--htra bst-bars pull-right"> <span>toggle menu</span></button>
        </div>
        <div class="bst-sidebar-menu pad-lr-lg pad-tb-md bg-white">
            <nav class="sidebar-nav collapse">
                <ul class="list-unstyled sidebar-menu" id="admin_nav_set">
                 	@if(!empty($query))
                    	<!-- child container opened -->
                      {{--*/ $child_cont_opened = 0; /*--}}
                      @foreach($query as $key => $module)
                      <!-- get record -->
                      {{--*/ $record = $user_module_model->getModulePermission($key, $designationId); /*--}}
                      @if(!empty($record))
                      	@foreach($record as $permkey => $permvalue)
                        	@if($permvalue['viewPermission'] == 1)
                            	<!-- has child --> 
                      			{{--*/ $has_child = $user_module_model->getChildCountByParent($key); /*--}}
                                <!-- if has child -->
                                @if($has_child > 0)
                                	{{--*/ $checkChildPerms = $user_module_model->checkModulePermissionByParentId($key, $designationId); /*--}}
                                    @if($checkChildPerms == 'success') <!-- childpermscheck -->
                                        <li class="has-children" id="{!! $module['userModuleSlug']; !!}">
                                            <a href="#/"><i class="{!! $module['iconClass'] !!}" aria-hidden="true"></i><span>{!! $module['userModuleName']; !!}</span></a>
                                    {{--*/
                                    $c_module_ids = $user_module_model->getChildsByParent($key); /*--}}
                                    @if(!empty($c_module_ids))
                                        <ul class="list-unstyled sub-menu collapse">
                                        @foreach($c_module_ids as $key => $c_module_id)
                                            <!-- get record -->
                                            {{--*/ $record = $user_module_model->getModulePermission($key, $designationId); /*--}}
                                             @if(!empty($record))
                                                @foreach($record as $permkey => $permvalue)
                                                    @if($permvalue['viewPermission'] == 1)
                                                    {{--*/ $class_name = $module['userModuleSlug']."#".$c_module_id['userModuleSlug']; /*--}}
                                                    <li id="{!! $class_name !!}" class="{!! $c_module_id['userModuleSlug']; !!}">
                                                    <a href="{!! URL::to(DIR_ADMIN.$c_module_id['userModuleSlug'].'/') !!}"><i class="{!! $c_module_id['iconClass'] !!}" aria-hidden="true"></i><span>{!! $c_module_id['userModuleName'] !!}</span></a></li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        </ul>
                                    @endif
                                    
                                    </li>
                                   @endif <!-- childpermscheckend -->
                                @else
                                <li id="{!! $module['userModuleSlug']; !!}" class="navli"><a href="{!! URL::to(DIR_ADMIN.$module['userModuleSlug'].'/') !!}"><i class="{!! $module['iconClass'] !!}" aria-hidden="true"></i><span>{!! $module['userModuleName'] !!}</span></a></li>
                                @endif
                            @endif
                        @endforeach
                      @endif
                      
                      @endforeach
                    @endif
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