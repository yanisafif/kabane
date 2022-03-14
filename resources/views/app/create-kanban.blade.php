@extends('layout.master')

@section('title')Create Kanban

@endsection

@push('css')
<link type="text/css" href=" {{ asset('assets/css/vanilla-picker.css') }} ">
@endpush

@section('content')

<section>
	    <div class="container-fluid p-0" style="margin: 0 auto;">
	        <div class="row m-0">
	            {{-- <div class="col-xl-7 p-0"><img class="bg-img-cover bg-center" src="{{ asset('assets/images/login/1.jpg') }}" alt="looginpage" /></div> --}}
	            <div class=" p-0">
	                <div class="create-kanban-card">
	                    <form class="theme-form login-form" method="POST" action="{{ route('kanban.store')}}">
                            @csrf
	                        <h4>Create a kanban</h4>
	                        <div class="form-group">
	                            <label>Name</label>
								<div class="input-group">
									<input class="form-control" maxlength="50" name="name" type="text" required />
								</div>
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
                            <div class=" pointer text-end mt-2" style="cursor: pointer">
                                <a class="link text-underline" onclick="addCol()" > + Add a column</a>
                            </div>
	                        <div class="form-group mt-4">
	                            <button class="btn btn-primary btn-block" type="submit">Create Kanban</button>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

    @push('scripts')
        <script src="https://unpkg.com/vanilla-picker@2"></script>
        <script>
            var colorFields = document.getElementsByClassName('color-field');
            for(const colorField of colorFields) {

                const picker = new Picker(colorField);
                picker.onChange = function(color) {
                    colorField.querySelector('input').value = color.hex;
                };
            }
        </script>
        <script> 
            let n = 2;
            let colContainer = document.getElementById("col-fields-container");

            function addCol() {
                let element = document.createElement('div');
                element.classList.add('small-group', 'mt-1');
                element.id = 'col-' + n;

                element.innerHTML =
                        '<div class="input-group">' +
                            '<span class="input-group-text"> Name</span>' +
                            '<input class="form-control" type="text" maxlength="50" name="colname['+ n +']" required="" />' +
                        '</div>' +
                        '<div class="input-group color-field">' +
                            '<span class="input-group-text">Color</span>' +
                            '<input class="form-control" type="text" maxlength="9" name="colcolor['+ n +']" required="" />' +
                        '</div>' +
                        '<img style="width: 20px; height: 20px" src="{{ asset('assets/svg/close.svg')  }}" onclick="deleteCol(' + n + ')">';
                colContainer.appendChild(element);
                n++;
            }

            function deleteCol(id) {
                colContainer.removeChild(document.getElementById('col-' + id))
            }
        </script>
    @endpush

@endsection
