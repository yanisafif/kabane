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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Default Demo</h5>
                        </div>
                        <div class="card-body kanban-block">
                            <div id="demo1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 colorfull-kanban">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>Custom Board</h5>
                            <p class="mb-0">| colors, gutter, click on board&apos;s item and restricting which boards to drag items to</p>
                        </div>
                        <div class="card-body kanban-block">
                            <div class="kanban-block" id="demo2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>API</h5>
                            <p class="mb-0">add item, add board, delete board:</p>
                        </div>
                        <div class="card-body">
                            <div id="demo3"></div>
                            <button class="btn btn-primary" id="addDefault">Add &quot;Default&quot; board</button>
                            <button class="btn btn-secondary" id="addToDo">Add element in &quot;To Do&quot; Board</button>
                            <button class="btn btn-danger mb-0" id="removeBoard">Remove &quot;Done&quot; Board</button>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card note p-20">
                        jKanban is Pure agnostic Javascript plugin for Kanban boards for more options please refer <a href="http://www.riccardotartaglia.it/jkanban/" target="_blank">jkanban Official site </a>And
                        <a href="https://github.com/riktar/jkanban" target="_blank">githup link</a>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
        <script src="{{asset('assets/js/jkanban/jkanban.js')}}"></script>
        <script src="{{asset('assets/js/jkanban/custom.js')}}"></script>
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
