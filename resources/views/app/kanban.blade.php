@extends('layout.master',  ['kanbans' => $kanbans])
@section('title')Kanban Board

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/jkanban.css')}}">
@endpush

@section('content')

    @if(!$data['kanbanNotSelected'] && !is_null($data['kanban']))
        <div class="container-fluid jkanban-container">
            <div class="row">
                <div class="col-12 colorfull-kanban">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5> {{ $data['kanban']['name'] }} </h5>
                            <span class="date"> {{ date('j F, Y', strtotime($data['kanban']['created_at'])) }} </span>
                            <div class="mt-2">
                                <button class="btn btn-primary" id="addDefault">Add &quot;Default&quot; board</button>
                                <button class="btn btn-secondary" id="addToDo">Add element in &quot;To Do&quot; Board</button>
                                <button class="btn btn-danger mb-0" id="removeBoard">Remove &quot;Done&quot; Board</button>
                            </div>
                        </div>
                        <div class="card-body kanban-block">
                            <div class="kanban-block" id="kabane"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-container">
            <div class="modal fade" name="creation-modal" id="creation-modal" role="dialog" aria-labelledby="creation-modal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">New item</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span class="text-danger" id="form-error-label"></span>
                            <form id="creation-form">
                                <div class="mb-3">
                                    <label class="col-form-label" for="title">Title:</label>
                                    <input class="form-control create-inputs" required="true" name="title" maxlength="50" type="text">
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="assign">Assigned:</label>
                                    <select class="form-select" name="assign" id="select-people-creation" aria-label="Select assign person">
                                        <option value="-1"> Unassigned </option>
                                        @foreach($data['people'] as $person)
                                            <option value="{{$person['id']}}">{{$person['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="deadline">Deadline:</label>
                                    <input class="form-control create-inputs" name="deadline" type="date">
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="description">Description:</label>
                                    <textarea class="form-control create-inputs" name="description"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" id="modal-creation-submit-btn" type="button">Save item</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" name="modification-modal" id="modification-modal" role="dialog" aria-labelledby="modification-modal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form id="edit-form">
                            <div class="modal-header">
                                <h5 class="w-75 mb-0">
                                    <input type="text" name="title" class="edit-from-inputs rounded-1 w-100" id="edit-form-title" readonly="true" maxlength="50"
                                        ondblclick="this.readOnly=''; this.style.border = '2px solid'"
                                        onfocusout="this.readOnly='true'; this.style.border = 'none'"
                                        style="border: none">
                                </h5>
                                <button class="btn-close position-static" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <span class="text-danger" id="form-edit-error-label"></span>
                                </div>
                                <span class="date">Created: 
                                    <span id="edit-form-created" class="edit-form-date"></span> 
                                </span>
                                <span class="date d-inline-block" style="padding-inline: 10px"> Modified: 
                                    <span id="edit-form-modified" class="edit-form-date"></span> 
                                </span>
                                <div class="mb-3">
                                    <label class="col-form-label" for="assign">Assigned:</label>
                                    <select class="form-select" name="assign" id="edit-form-select-people" aria-label="Select a person to assign">
                                        <option value="-1"> Unassigned </option>
                                        @foreach($data['people'] as $person)
                                            <option value="{{$person['id']}}">{{$person['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="recipient-name">Deadline:</label>
                                    <input class="form-control edit-from-inputs" name="deadline" id="edit-form-deadline" type="date">
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="message-text">Description:</label>
                                    <textarea class="form-control edit-from-inputs" name="description" id="edit-form-description"></textarea>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" id="item-delete-btn">Delete</button>
                            <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" id="modal-edit-submit-btn" type="button">Save item</button>
                        </div>
                    </div>
                </div>

        </div>


        <div class="d-none" id="dataCols">
            @json($data['cols'])
        </div>
        
        <div class='d-none' id="dataPeople">
            @json($data['people'])
        </div>

        @push('scripts')
            <script src="{{asset('assets/js/jkanban/jkanban.js')}}"></script>
            <script src="{{asset('assets/js/page/app/kanban.js')}}"></script>
        @endpush

    @elseif($data['kanbanNotSelected'])
        <div class="text-center mt-5">
            <h1>Select a kanban or create one! </h1>
        </div>
    @elseif(is_null($data['kanban']))
        <div class="text-center mt-5">
            <h1>Sorry, this kanban couldn't be found. </h1>
        </div>
    @endif

@endsection
