@extends('layout.master')
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
                                @if($data['isOwner'])
                                    <button class="btn btn-primary" id="settings-access-btn">Settings pannel</button>
                                    <button class="btn btn-primary" id="add-col-btn">Add a column</button>
                                @else
                                    <button class="btn btn-danger" id="self-uninvite-btn">Leave the kanban</button>
                                @endif
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
                                    <label class="col-form-label" for="item_name">Title:</label>
                                    <input class="form-control create-inputs" required="true" name="item_name" maxlength="50" type="text">
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label" for="assignedUser_id">Assigned:</label>
                                    <select class="form-select" name="assignedUser_id" id="select-people-creation" aria-label="Select assign person">
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
                                    <input type="text" name="item_name" class="edit-from-inputs rounded-1 w-100" id="edit-form-title" readonly="true" maxlength="50"
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
                                    <label class="col-form-label" for="assignedUser_id">Assigned:</label>
                                    <select class="form-select" name="assignedUser_id" id="edit-form-select-people" aria-label="Select a person to assign">
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
            @if($data['isOwner'])
                <div class="modal" id="settings-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Settings pannel</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h5>Poeple</h5>
                                <div>
                                    <h6>List of invited people</h6>
                                    <div class="mb-3">
                                        <div id="settings-people-list-container" class="mb-1">
                                            @if(count($data['people']) > 1)
                                                @foreach($data['people'] as $people)
                                                    @if(!$people['isCurrentUser'])
                                                        <div class="p-2 settings-person-container d-flex">
                                                            <div class="setting-person-name">{{ $people['name'] }} </div>
                                                            <img class="setting-person-uninvite" data-id="{{ $people['id'] }}" src=" {{ asset('assets/svg/trash.svg') }}">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <span id="settings-noinvited-message" class="{{ count($data['people']) > 1 ? 'd-none': ''}}"> 
                                                No one is invited to your kanban yet.
                                            </span>
                                        </div>
                                        <div class="pt-2">
                                            <h6>Invite a person</h6>
                                            <div class="pb-2">
                                                <span id="settings-invite-error-message" class="text-danger"> </span>
                                                <div class="small-group setting-invite">
                                                    <span>Write their name or email</span>
                                                    <div class="input-group">
                                                        <input class="form-control" onkeyup="event.keyCode === 13 && $('#settings-invite-btn').click()" id="settings-name-field" type="text" maxlength="50" name="name"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pb-2" style="text-align: end;">
                                                <button class="btn btn-success" id="settings-invite-btn"> Invite </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal" id="delete-col-modal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete confirmation</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <h6>Are you sure you want to delete the column <strong id="modal-delete-col-name"></strong> ?</h6>
                                    <p class="text-danger">Every item in the column would be deleted.</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">No</button>
                                <button class="btn btn-primary" id="modal-delete-col-yes-btn" type="button">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else 
            <div class="modal" id="uninvite-self-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete confirmation</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <h6>Are you sure you want to leave the kanban <strong>{{ $data['kanban']['name'] }}</strong> ?</h6>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary"  type="button" data-bs-dismiss="modal">No</button>
                            <button class="btn btn-primary" id="modal-delete-col-yes-btn" type="button">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>      


        <div class="d-none" id="dataCols">
            @json($data['cols'])
        </div>

        <div class="d-none" id="dataKanbanId" data-kanbanid="{{ $data['kanban']['id'] }}"></div>
        
        <div class='d-none' id="dataPeople" data-isowner="{{ $data['isOwner'] ? 'true' : 'false'}}">
            @json($data['people'])
        </div>

        @push('scripts')
            <script src="{{asset('assets/js/jkanban/jkanban.js')}}"></script>
            <script src="https://unpkg.com/vanilla-picker@2"></script>
            <script src="{{asset('assets/js/page/app/kanban.main.js')}}"></script>
            @if($data['isOwner'])
                <script src="{{asset('assets/js/page/app/kanban.admin.js')}}"></script>
            @else
                <script src="{{asset('assets/js/page/app/kanban.invite.js')}}"></script>
            @endif
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
