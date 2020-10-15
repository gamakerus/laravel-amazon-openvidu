<!DOCTYPE html>
<html lang="en">
    @include('header')
    <body>
        <div class="wrapper ">
            @section('alarm')
                @include('alarm')
            @yield('menu')
            <div class="main-panel">
                <div class="content">
                    <div class="container-fluid">
                        @yield('main')
                    </div>
                </div>
                @yield('footer')
            </div>
            @yield('setting')
        </div>
    </body>
</html>