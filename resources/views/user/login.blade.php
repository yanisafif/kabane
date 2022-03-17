@extends('user.master')

@section('title')login
@endsection

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
	    <div class="row">
	        <div class="col-xl-5"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/3.jpg') }}" alt="looginpage" /></div>
	        <div class="col-xl-7 p-0">
	            <div class="login-card">
	                <form class="theme-form login-form needs-validation" novalidate="" method="POST" action="{{ route('user.login.post')}}">
                        @csrf
	                    <h4>Login</h4>
	                    <h6>Welcome back to kabane! Log in to your account.</h6>
	                    <div class="form-group">
	                        <label>Email Address</label>
	                        <div class="input-group">
	                            <span class="input-group-text"><i class="icon-email"></i></span>
	                            <input class="form-control" type="email" name="email" required="" placeholder="Exemple@gmail.com" />
	                            <div class="invalid-tooltip">Please enter proper email.</div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label>Password</label>
	                        <div class="input-group">
	                            <span class="input-group-text"><i class="icon-lock"></i></span>
	                            <input class="form-control" type="password" name="password" required="" placeholder="*********" />
	                            <div class="invalid-tooltip">Please enter password.</div>
	                            <div class="show-hide"><span class="show"> </span></div>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="checkbox">
	                            <input id="checkbox1" type="checkbox" name="remember_me"/>
	                            <label class="text-muted" for="checkbox1">Remember password</label>
	                        </div>

	                    </div>
                        <p class="text-start"><a class="link" href="#">Forgot password?</a></p>
	                    <div class="form-group">
	                        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
	                    </div>
	                    <div class="login-social-title">
	                        <h5>Sign up</h5>
                        </div>
	                    <p>Don't have account?<a class="ms-2" href="{{ route('user.sign.up') }}">Create Account</a></p>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
	<script>
	    (function () {
	        "use strict";
	        window.addEventListener(
	            "load",
	            function () {
	                // Fetch all the forms we want to apply custom Bootstrap validation styles to
	                var forms = document.getElementsByClassName("needs-validation");
	                // Loop over them and prevent submission
	                var validation = Array.prototype.filter.call(forms, function (form) {
	                    form.addEventListener(
	                        "submit",
	                        function (event) {
	                            if (form.checkValidity() === false) {
	                                event.preventDefault();
	                                event.stopPropagation();
	                            }
	                            form.classList.add("was-validated");
	                        },
	                        false
	                    );
	                });
	            },
	            false
	        );
	    })();
	</script>


    @push('scripts')
    @endpush

@endsection
