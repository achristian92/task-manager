<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bsw - Task Manager')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon_task_manager16.ico') }}" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Argon CSS -->

    <link href="{{asset('css/plugins/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <meta name="user" content="{{ \Illuminate\Support\Facades\Auth::user() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery.dataTables.css') }}">

</head>
<body>

<!-- Sidenav -->
@include('layouts.admin.sidebard')

<div class="main-content" id="panel">
    @include('layouts.admin.topnav')
    <br>
    <div class="container-fluid pb-6 ">
        @include('components.alert')
        <div id="app">
            @yield('content')
        </div>
    </div>
</div>

<!-- Argon Scripts -->
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/scripts2.js')}}"></script>
<script src="{{asset('js/argon.js')}}"></script>
<script src="{{asset('js/plugins/select2.min.js')}}"></script>
<script type="text/javascript" charset="utf8" src="{{ asset('js/plugins/jquery.dataTables.js') }}" defer></script>
<script src="{{ asset('js/plugins/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datepicker.min.js') }}"></script>

<link rel="stylesheet" href="{{ asset('css/select2.custom.css') }}">

<script>
    var srclangdt = "{{ URL::asset('datatables.json') }}"
</script>

<script  src="{{ asset('js/dts.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/dts.css') }}">
@stack('js')
@stack('styles')

</body>
</html>
