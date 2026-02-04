      <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">{{ __('global.main') }}</li>
                            <li class="">
                                <a href="{{route('admin')}}" class="waves-effect {{ request()->is("admin") || request()->is("admin/*") ? "mm active" : "" }}">
                                    <i class="ti-home"></i><span class="badge badge-primary badge-pill float-right">2</span> <span> {{ __('global.dashboard') }} </span>
                                </a>
                            </li>
                            

                            <li>
                                <a href="javascript:void(0);" class="waves-effect"><i class="ti-user"></i><span> {{ __('global.employees') }} <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                                <ul class="submenu">
                                    <li>
                                        <a href="/employees" class="waves-effect {{ request()->is("employees") || request()->is("/employees/*") ? "mm active" : "" }}"><i class="dripicons-view-apps"></i><span>{{ __('global.employees_list') }}</span></a>
                                    </li>
                                   
                                </ul>
                            </li>

                            <li class="menu-title">{{ __('global.management') }}</li>

                            <li class="">
                                <a href="/schedule" class="waves-effect {{ request()->is("schedule") || request()->is("schedule/*") ? "mm active" : "" }}">
                                    <i class="ti-time"></i> <span> {{ __('global.schedule') }} </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/check" class="waves-effect {{ request()->is("check") || request()->is("check/*") ? "mm active" : "" }}">
                                    <i class="dripicons-to-do"></i> <span> {{ __('global.attendance_sheet') }} </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/sheet-report" class="waves-effect {{ request()->is("sheet-report") || request()->is("sheet-report/*") ? "mm active" : "" }}">
                                    <i class="dripicons-to-do"></i> <span> {{ __('global.sheet_report') }} </span>
                                </a>
                            </li>

                            <li class="">
                                <a href="/attendance" class="waves-effect {{ request()->is("attendance") || request()->is("attendance/*") ? "mm active" : "" }}">
                                    <i class="ti-calendar"></i> <span> {{ __('global.attendance_logs') }} </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/monthly-report" class="waves-effect {{ request()->is("monthly-report") || request()->is("monthly-report/*") ? "mm active" : "" }}">
                                    <i class="ti-folder"></i> <span> {{ __('global.monthly_report') }} </span>
                                </a>
                            </li>
                            <li class="">
                                <a href="/final-report" class="waves-effect {{ request()->is("final-report") || request()->is("final-report/*") ? "mm active" : "" }}">
                                    <i class="ti-clip"></i> <span> {{ __('global.final_report') }} </span>
                                </a>
                            </li>
                            <li class="menu-title">{{ __('global.tools') }}</li>

                        </ul>

                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->
