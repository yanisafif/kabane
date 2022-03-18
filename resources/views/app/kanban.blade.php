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

        </div>

        <div class="d-none" id="data">
            @json($data)
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
