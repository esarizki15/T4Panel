<!doctype html>
<html lang="{{ app()->getLocale() }}">
@include('layouts.architect.head')
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <!--Header START-->
        @include('layouts.architect.header')
        <!--Header END-->
        <!--THEME OPTIONS START-->
        @include('layouts.architect.theme-setting')
        <!--THEME OPTIONS END-->
        <div class="app-main">
            @include('layouts.architect.sidebar')
            <div class="app-main__outer">
                @include('layouts.architect.app-main')
            </div>
            @include('layouts.architect.footer')
        </div>
    </div>
    </div>
    <!--DRAWER START-->
    @include('layouts.architect.drawer')
    <!--DRAWER END-->
    <!--SCRIPTS INCLUDES-->
    @include('layouts.architect.scripts')
</body>
</html>
