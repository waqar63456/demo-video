<!doctype html>
<html lang="en">

<head>

</head>
@include('admin.include.head')

<body>
<div id="page-container"
        class="sidebar-o enable-page-overlay side-scroll page-header-fixed page-header-dark main-content-narrow">

        @include('admin.include.sidebar')
        <!-- END Sidebar -->

        <!-- Header -->
        @include('admin.include.header')
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">

            @yield('content')
            <!-- END Page Content -->

        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        {{-- @include('admin.include.footer') --}}
        <!-- END Footer -->
    </div>
    <!-- END Page Container -->

   
    @include('admin.include.scripit')

</body>

</html>
