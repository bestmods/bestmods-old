<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('head')
    <body>
        @include('background')
        
        <div class="mx-auto px-10">
            @include('header')
            @include($page)

            @include('footer')
        </div>
    </body>
</html>
