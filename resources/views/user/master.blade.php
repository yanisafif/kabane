<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon" />
        <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon" />
        <title>@yield('title')</title>
        <!-- Google font-->
        @includeIf('user.partials.css')
    </head>
    <body>
        <!-- Loader starts-->
        <div class="loader-wrapper">
            <div class="theme-loader">
                <div class="loader-p"></div>
            </div>
        </div>
        <!-- Loader ends-->
        <!-- error page start //-->
        @yield('content')
        <!-- error page end //-->
        <!-- latest jquery-->
        @includeIf('user.partials.js')
    </body>
</html>

{{-- FLASH MESSAGE IN SESSION --}}
@foreach (['danger', 'warning', 'success', 'info'] as $key)
    @if(Session::has($key))
        <script>

            $.notify({
                message:'{{ Session::get($key) }}'
            },
            {
                type:'{{$key}}',
                allow_dismiss:true,
                newest_on_top:true ,
                mouse_over:true,
                showProgressbar:false,
                spacing:10,
                timer:2000,
                placement:{
                    from:'bottom',
                    align:'right'
            },
                offset:{
                    x:30,
                    y:30
                },
                delay:1000 ,
                z_index:10000,
                animate:{
                    enter:'animated bounce',
                    exit:'animated bounce'
                }
            });
        </script>
    @endif
@endforeach



