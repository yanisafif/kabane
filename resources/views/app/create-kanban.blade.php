@extends('layout.master')

@section('title')Kanban Board

@endsection

@push('css')
@endpush

@section('content')

<section>
	    <div class="container-fluid p-0" style="margin: 0 auto;">
	        <div class="row m-0">
	            {{-- <div class="col-xl-7 p-0"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/1.jpg') }}" alt="looginpage" /></div> --}}
	            <div class="col-xl-12 p-0">
	                <div class="login-card">
	                    <form class="theme-form login-form" method="POST" action="{{ route('kanban.store')}}">
                            @csrf
	                        <h4>Create a kanban</h4>
	                        <div class="form-group">
	                            <label>Name</label>
								<div class="input-group">
									<input class="form-control" name="name" type="text" required="" />
								</div>
	                        </div>
	                        <div class="form-group">
								<label>Columns</label>
                                <div id="col-fields-container">
                                    <div class="small-group">
                                        <div class="input-group">
                                            <span class="input-group-text"> Name</span>
                                            <input class="form-control" type="text" name="colname[0]" required="" />
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text">Color</span>
                                            <input class="form-control" type="text" name="colcolor[0]" required="" />
                                        </div>
                                    </div>
                                    <div class="small-group mt-1">
                                        <div class="input-group">
                                            <span class="input-group-text">Name</span>
                                            <input class="form-control" type="text" name="colname[1]" required="" />
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text">Color</span>
                                            <input class="form-control" type="text" name="colcolor[1]" required="" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4 pointer">
                                    <a class="link" onclick="addCol()" > + Add a column</a>
                                </div>
                            </div>
	                        <div class="form-group">
	                            <button class="btn btn-primary btn-block" type="submit">Create Kanban</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

    @push('scripts')
        <script>
            let n = 2;
            let colContainer = document.getElementById("col-fields-container");

            function addCol() {
                let element = document.createElement('div');
                element.classList.add('small-group', 'mt-1');

                element.innerHTML =
                        '<div class="input-group">' +
                            '<span class="input-group-text"> Name</span>' +
                            '<input class="form-control" type="text" name="colname['+ n +']" required="" />' +
                        '</div>' +
                        '<div class="input-group">' +
                            '<span class="input-group-text">Color</span>' +
                            '<input class="form-control" type="text" name="colcolor['+ n +']" required="" />' +
                        '</div>';

                colContainer.appendChild(element);
                n++;
            }
        </script>

    @endpush

@endsection
