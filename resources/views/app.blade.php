<!DOCTYPE html>
<html>

@include('sections.head')

<body class="hold-transition layout-fixed layout-navbar-fixed">
    <div class="wrapper" id="app">

        @if (Auth::check()) 
            <App ruta="{{route('basepath')}}" :usuario="{{Auth::user()->load('file')}}" ></App>
        @else
            <Home ruta="{{route('basepath')}}"></Home>
        @endif
    </div>

    @include('sections.script')
</body>

</html>
