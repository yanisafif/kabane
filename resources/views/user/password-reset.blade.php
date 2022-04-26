@extends('user.master')

@section('title')Forget Password
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
@endpush

@section('content')
    <section>
	    <div class="container-fluid p-0">
	        <div class="row m-0">
	            <div class="col-12 p-0">
	                <div class="login-card">
	                    <div class="login-main">
	                        <form id="change-password" class="theme-form login-form" method="POST" action="{{ route('user.reset.password.uuid')}}">
                                @csrf
	                            <h4 class="mb-3">Reset Your Password</h4>
	                            <div class="form-group">
	                                <label class="mt-4">Enter Your Mail</label>
	                                <div class="row">
	                                    <div class="col-12 col-sm-12">
	                                        <input class="form-control" name="email" type="email" placeholder="email@gmail.com" />
	                                    </div>
	                                </div>
                                    <div class="row">
                                        <label class="mt-4">New Password</label>
	                                    <div class="col-12 col-sm-12">
	                                        <input class="form-control" name="password" type="password" placeholder="******" />
	                                    </div>
	                                </div>
                                    <div class="row">
                                        <label class="mt-4">Repeat New Password</label>
	                                    <div class="col-12 col-sm-12">
	                                        <input class="form-control" name="password_confirmation" type="password" placeholder="******" />
	                                    </div>
	                                </div>
                                    <div class="row">
	                                    <div class="col-12 col-sm-12">
	                                        <input class="form-control" name="uuid" type="hidden" value="{{$uuid}}" />
	                                    </div>
	                                </div>
                                    <div class="form-group">
                                        {!! NoCaptcha::displaySubmit(
                                            'change-password', 'Change Password',
                                            ['class' => 'btn btn-primary btn-block mt-4 col-12 col-sm-12'])
                                        !!}
                                    </div>
	                            </div>

	                            <p>Already have an password?<a class="ms-2" href="{{ route('user.login') }}">Log in here</a></p>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

    @push('scripts')
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js') }}"></script>
    @endpush

@endsection
