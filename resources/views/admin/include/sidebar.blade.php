<nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-10">
            <!-- Logo -->
            <a class="link-fx font-w600 font-size-lg text-white" href="/dashboard">
                <span class="smini-visible">
                    <span class="text-white-75">D</span><span class="text-white">x</span>
                </span>
                <span class="smini-hidden">
                    <span class="text-white-75">Video</span><span class="text-white">Genrater</span>
                </span>
            </a>
            <div>
                <a class="js-class-toggle text-white-75" data-target="#sidebar-style-toggler"
                    data-class="fa-toggle-off fa-toggle-on" data-toggle="layout" data-action="sidebar_style_toggle"
                    href="javascript:void(0)">
                    <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
                </a>
                <a class="d-lg-none text-white ml-2" data-toggle="layout" data-action="sidebar_close"
                    href="javascript:void(0)">
                    <i class="fa fa-times-circle"></i>
                </a>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Side Navigation -->
    <div class="content-side content-side-full">
        <ul class="nav-main">
            {{-- <li class="nav-main-item">
                <a class="nav-main-link active" href="be_pages_dashboard.html">
                    <i class="nav-main-link-icon si si-cursor"></i>
                    <span class="nav-main-link-name">Dashboard</span>
                    <span class="nav-main-link-badge badge badge-pill badge-success">5</span>
                </a>
            </li> --}}
            {{-- ==============================Marketing Advantges SEC=================================== --}}
            {{-- ------------ Settings --------------------- --}}

            {{-- ---------------------New users------------------- --}}
            <li class="nav-main-heading">Users:</li>
            <li
                class="nav-main-item {{ request()->routeIs('newusers.create', 'newusers.index', 'userpackages.index') ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                    aria-expanded="false" href="#">
                    <i class="nav-main-link-icon fa fa-chart-simple"></i>
                    <span class="nav-main-link-name">Users</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('newusers.index') ? 'active' : '' }}"
                            href="{{ route('newusers.index') }}" href="{{ route('newusers.index') }}">
                            <span class="nav-main-link-name">All Users</span>
                        </a>
                    </li>


                </ul>

            </li>
            {{-- ---------------------Services ------------------- --}}

            {{-- <li
                class="nav-main-item {{ request()->routeIs('packages.create', 'packages.index', 'packages.edit' , 'packages.reviews.index' , 'packages.faqs.index') ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                    aria-expanded="false" href="#">
                    <i class="nav-main-link-icon fa fa-chart-simple"></i>
                    <span class="nav-main-link-name">Packages</span>
                </a>
                <ul class="nav-main-submenu">
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('packages.index') ? 'active' : '' }}"
                            href="{{ route('packages.index') }}" href="{{ route('packages.index') }}">
                            <span class="nav-main-link-name">View Packages</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('packages.reviews.index') ? 'active' : '' }}"
                            href="{{ route('packages.reviews.index') }}" href="{{ route('packages.reviews.index') }}">
                            <span class="nav-main-link-name">Reviews</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->routeIs('packages.faqs.index') ? 'active' : '' }}"
                            href="{{ route('packages.faqs.index') }}" href="{{ route('packages.faqs.index') }}">
                            <span class="nav-main-link-name">Faqs</span>
                        </a>
                    </li>
                </ul>

            </li> --}}


        </ul>
    </div>
    <!-- END Side Navigation -->
</nav>
