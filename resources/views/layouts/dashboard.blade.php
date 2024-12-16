@include('partials/main')

<head>
    @include('partials/title-meta')

    @include('partials/head-css')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<body>
    <!-- Begin page -->
    <div id="wrapper">

        @include('partials/menu')

        <div class="content-page">

            @include('partials/topbar')

            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- container -->

            </div> <!-- content -->

            @include('partials/footer')
        </div>

    </div>
    <!-- END wrapper -->

    @include('partials/right-sidebar')

    @include('partials/footer-scripts')
</body>

</html>