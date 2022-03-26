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
                    <div class="card-header pb-0">
                        <h5>About me</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Pseudo</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-user"></i></span>
                                            <input class="form-control" required="required" name="name" type="text" placeholder="Pseudo" value="{{$user->name}}" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-email"></i></span>
                                            <input class="form-control" required="required" name="email" type="text" placeholder="exemple@gmail.com" value="{{$user->email}}" />
                                        </div>
                                    </div>
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
                                <form>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Twitter</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-social-twitter"></i></span>
                                            <input class="form-control" name="link_twitter" type="text" value="{{ empty($user->link_twitter) ? 'https://www.twitter.com/' : $user->link_twitter }}" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Facebook</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icofont icofont-social-facebook"></i></span>
                                            <input class="form-control" name="link_facebook" type="text" value="{{ empty($user->link_facebook) ? 'https://www.facebook.com/' : $user->link_facebook }}" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Instagram</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-instagram"></i></span>
                                            <input class="form-control" name="link_instagram" type="text" value="{{ empty($user->link_instagram) ? 'https://www.instagram.com/' : $user->link_instagram }}" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Linkedin</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-linkedin"></i></span>
                                            <input class="form-control" name="link_linkedin" type="text" value="{{ empty($user->link_linkedin) ? 'https://www.linkedin.com/in' : $user->link_linkedin }}" />
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
                                <form>
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
                                            <input class="form-control" type="password" name="password" required="required" placeholder="*********" />
                                        </div>
                                    </div>
                                    <div class="mb-3 m-form__group">
                                        <label class="form-label">Reapeat new password</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="icon-lock"></i></span>
                                            <input class="form-control" type="password" name="password_confirmation" required="required" placeholder="*********" />
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
                                        <label class="form-label">Type this to delete your account : "We will miss you"</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-danger text-light"><i class="icon-user"></i></span>
                                            <input class="form-control" type="text" name="delete_account" required="required" />
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
