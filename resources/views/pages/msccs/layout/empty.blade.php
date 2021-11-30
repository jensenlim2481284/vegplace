<!doctype html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="/img/logo/logo.ico" />

    <link href="/css/prod/component/general.min.css{{ config('app.link_version') }}" type="text/css" rel="stylesheet"/>
    <link href="/css/prod/msccs/index.min.css{{ config('app.link_version') }}" type="text/css" rel="stylesheet" />
    <link href="/css/plugin/record.css{{ config('app.link_version') }}" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="/js/prod/component/general.min.js{{ config('app.link_version') }}"></script>
    <script type="text/javascript" src="/js/prod/msccs/index.min.js{{ config('app.link_version') }}"></script>
    
    @yield('head')
    
</head>
<body>

    <!--Loader section -->
    @include('component.loader')

    <!-- Nav & Content -->
    <div class="wrapper d-flex flex-column align-items-stretch">

        <!-- Page Content  -->
        @yield('content')

    </div>

</body>


<!-- Post & backend script processing -->
@include('script.index') 
@include('script.record', ['user'=>$user, 'company'=>$company]) 

</html>