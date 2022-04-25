@extends('layout.master')

@section('title')Change user credentials
@endsection

@push('css')
@endpush

@section('content')
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Change your Avatar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">

                                @if (!empty(auth()->user()->path_image))
                                    <div class="avatars text-center">
                                        <div class="avatar"><img class="img-100 rounded-circle" style="height: 100px" src="{{asset('avatars/'.auth()->user()->path_image)}}" alt="Avatar">
                                            <a href="/user/profile/delete/avatar/{{auth()->user()->id}}"><div class="status status-100 bg-danger"><i class="icofont icofont-trash"> </i> </div></a>
                                        </div>
                                    </div>
                                @endif
                                <form class="dropzone digits" id="image-upload" action="{{ route('user.update.avatar') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div>
                                        <label for="formFileLg" class="form-label">Select your image file</label>
                                        <input class="form-control form-control-lg" id="formFileLg" type="file" name="file">
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Change your Pseudo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form method="POST" action="{{ route('user.update.name')}}">
                                    @csrf
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Pseudo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-user"></i></span>
                                            <input class="form-control" required="required" name="name" type="text" placeholder="Pseudo" value="{{$user->name}}" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Change your Email</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form method="POST" action="{{ route('user.update.email')}}">
                                    @csrf
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-email"></i></span>
                                            <input class="form-control" required="required" name="email" type="text" placeholder="exemple@gmail.com" value="{{$user->email}}" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>About me</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form method="POST" action="{{ route('user.update.information')}}">
                                    @csrf
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Title</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-company"></i></span>
                                            <input class="form-control" type="text" name="title" placeholder="exemple : Web developper" value="{{$user->title}}" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Description</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-read-book"></i></span>
                                            <input class="form-control" type="text" name="description" placeholder="Some information about me..." value="{{$user->description}}" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Contact path</h5>
                        <p>Make sure not to change the beginning of the link</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form method="POST" action="{{ route('user.update.link')}}">
                                    @csrf
                                    @php
                                        $twitter = explode(".com/", $user->link_twitter);
                                        $facebook = explode(".com/", $user->link_facebook);
                                        $instagram = explode(".com/", $user->link_instagram);
                                        $linkedin = explode(".com/in/", $user->link_linkedin);
                                    @endphp
                                    <div class="mb-3">
										<label class="form-label">Twitter</label>
										<div class="input-group">
											<span class="input-group-text"><i class="icofont icofont-social-twitter"></i></span><span class="input-group-text">https://www.twitter.com/ </span>
											<input class="form-control" type="text" name="link_twitter" value="{{ empty($user->link_twitter) ? '' : $twitter[1] }}" autocomplete="off" />
										</div>
									</div>
                                    <div class="mb-3">
										<label class="form-label">Facebook</label>
										<div class="input-group">
											<span class="input-group-text"><i class="icofont icofont-social-facebook"></i></span><span class="input-group-text">https://www.facebook.com/ </span>
											<input class="form-control" type="text" name="link_facebook" value="{{ empty($user->link_facebook) ? '' : $facebook[1] }}" autocomplete="off" />
										</div>
									</div>
                                    <div class="mb-3">
										<label class="form-label">Instagram</label>
										<div class="input-group">
											<span class="input-group-text"><i class="fa fa-instagram"></i></span><span class="input-group-text">https://www.instagram.com/ </span>
											<input class="form-control" type="text" name="link_instagram" value="{{ empty($user->link_instagram) ? '' : $instagram[1] }}" autocomplete="off" />
										</div>
									</div>
                                    <div class="mb-3">
										<label class="form-label">Linkedin</label>
										<div class="input-group">
											<span class="input-group-text"><i class="fa fa-linkedin"></i></span><span class="input-group-text">https://www.linkedin.com/in/ </span>
											<input class="form-control" type="text" name="link_linkedin" value="{{ empty($user->link_linkedin) ? '' : $linkedin[1] }}" autocomplete="off" />
										</div>
									</div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Change password</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form method="POST" action="{{ route('user.update.password')}}">
                                    @csrf
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Old password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-lock"></i></span>
                                            <input class="form-control" type="password" name="old_password" required="required" placeholder="*********" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">New password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-lock"></i></span>
                                            <input class="form-control" type="password" name="new_password" required="required" placeholder="*********" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Reapeat new password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-lock"></i></span>
                                            <input class="form-control" type="password" name="confirm_password" required="required" placeholder="*********" />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-primary m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Delete your account</h5>
                        <p>Make sure before deleting your account to provide someone account on your kanban to owner</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Type this to delete your account : "<span class="fw-bold">I will come back soon</span>"</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-danger text-light"><i class="icon-user"></i></span>
                                            <input class="form-control" type="text" name="delete_account" required="required"
                                            onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-danger m-r-15" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>

	@push('scripts')
	@endpush

@endsection
