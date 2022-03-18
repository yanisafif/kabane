@extends('user.master')

@section('title')Welcome page
@endsection

@push('css')
@endpush

@section('content')
    <section>
        <h1>KABANE</h1>
	    <div class="flex-center position-ref full-height">
            @if (Route::has('user.login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ route('index.international') }}">Home</a>
                    @else
                        <a href="{{ route('user.login') }}">Login</a>

                        @if (Route::has('user.sign.up'))
                            <a href="{{ route('user.sign.up') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
	</section>
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
