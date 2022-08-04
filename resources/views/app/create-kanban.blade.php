@extends('layout.master')

@section('title')Create Kanban
@endsection

@push('css')
<link type="text/css" href=" {{ asset('assets/css/vanilla-picker.css') }} ">
@endpush

@section('content')

<section>
	    <div class="container-fluid p-0">
	        <div class="row m-0">
	            <div class=" p-0">
	                <div class="create-kanban-card">
	                    <form class="kanban-form" method="POST" action="{{ route('kanban.store')}}">
                            @csrf
	                        <h4>Create A Kanban</h4>
	                        <div class="form-group">
	                            <label>Name</label>
								<div class="input-group">
									<input class="form-control" maxlength="25" name="name" type="text" required />
								</div>
	                        </div>
                            <div class="form-group mb-1" id="invite-field-container">
	                            <label class="d-block mb-0">People to invite</label>
                                <span>Write their name or email</span>
								<div class="input-group">
									<input class="form-control m-r-20" maxlength="50" name="invite[0]" type="text"/>
								</div>
	                        </div>
                            <div class="text-end mt-2">
                                <a class="link text-underline" onclick="addInvite()" > + Add invite field</a>
                            </div>
	                        <div class="form-group mb-1">
								<label>Columns</label>
                                <div id="col-fields-container">
                                    <div class="small-group">
                                        <div class="input-group">
                                            <span class="input-group-text"> Name</span>
                                            <input class="form-control" type="text" maxlength="50" name="colname[0]" required />
                                        </div>
                                        <div class="input-group m-r-20 color-field">
                                            <span class="input-group-text">Color</span>
                                            <input class="form-control" type="text"maxlength="9"  name="colcolor[0]" required />
                                        </div>
                                    </div>
                                    <div class="small-group mt-1">
                                        <div class="input-group">
                                            <span class="input-group-text">Name</span>
                                            <input class="form-control" type="text" maxlength="50" name="colname[1]" required/>
                                        </div>
                                        <div class="input-group m-r-20 color-field">
                                            <span class="input-group-text">Color</span>
                                            <input class="form-control" type="text" maxlength="9" name="colcolor[1]"  required />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="text-end mt-2">
                                <a class="link text-underline" onclick="addCol()" > + Add a column</a>
                            </div>
	                        <div class="form-group mt-4">
	                            <button class="btn btn-primary" type="submit">CREATE KANBAN</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

    @push('scripts')
        <script src="https://unpkg.com/vanilla-picker@2"></script>
        <script src="{{asset('assets/js/page/app/create-kanban.js')}}"></script>
    @endpush

@endsection
