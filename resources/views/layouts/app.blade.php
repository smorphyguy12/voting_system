@include('partials/main')

<head>
    @include('partials/title-meta')

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Additional Styles --}}
    @stack('styles')

    @include('partials/head-css')
</head>

@include('partials/preloader')

<!-- Begin page -->
<div id="wrapper">
    @include($sideBar)

    <div class="content-page">
    @include('partials/topbar')

        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">
                @yield('content')
            </div> <!-- container-fluid -->

        </div> <!-- content -->

        @include('partials/footer')

    </div>

</div>
<!-- END wrapper -->

@include('partials/right-sidebar')

@include('partials/footer-scripts')

{{-- Additional Scripts --}}
@stack('scripts')
</body>

</html>