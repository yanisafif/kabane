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
	                        <form id="send-email-password" class="theme-form login-form" method="POST" action="{{ route('user.reset.password')}}">
                                @csrf
	                            <h4 class="mb-3">Reset Your Password</h4>
                                <small>Please enter a valid email so that we can send you an email to be able to reset your password.</small>
	                            <div class="form-group">
	                                <label class="mt-4">Enter Your Mail</label>
	                                <div class="row">
	                                    <div class="col-12 col-sm-12">
	                                        <input class="form-control" name="emailPassword" type="email" placeholder="email@gmail.com" />
	                                    </div>
	                                </div>
                                    <div class="form-group">
                                        {!! NoCaptcha::displaySubmit(
                                            'send-email-password', 'Send',
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
