<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('head')
    <body>
        @include('background')
        
        <div class="mx-auto">
            @include('header')
            @include($page)
        </div>
    </body>
</html>
