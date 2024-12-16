@include('partials/main')

<head>
    @include('partials/title-meta')

    @include('partials/head-css')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<body>
    @yield('content')

    @include('partials/footer-scripts')
    @include('partials/right-sidebar')
</body>

</html>