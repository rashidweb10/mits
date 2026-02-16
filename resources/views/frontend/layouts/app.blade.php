<!DOCTYPE html>
<html lang="en">
<head> 
    @include('frontend.partials.meta')
    @include('frontend.partials.styles')
    <!-- <script src="{{ asset('assets/frontend/js/content-protect.js') }}" defer></script> -->
</head>
<body>

    @include('frontend.partials.header')

    <main>
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    @include('frontend.partials.scripts')
    @yield('scripts')
    @stack('components.scripts')

</body>
</html>
