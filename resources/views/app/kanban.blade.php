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
                            <div class="mt-2">
                                <button class="btn btn-primary" id="addDefault">Add &quot;Default&quot; board</button>
                                <button class="btn btn-secondary" id="addToDo">Add element in &quot;To Do&quot; Board</button>
                                <button class="btn btn-danger mb-0" id="removeBoard">Remove &quot;Done&quot; Board</button>
                            </div>
                        </div>
                        <div class="card-body kanban-block">
                            <div class="kanban-block" id="demo2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
        <script src="{{asset('assets/js/jkanban/jkanban.js')}}"></script>
        <script>

            const boards =  new Array(); 
            
            (function() {
                const data = JSON.parse('@json($data["cols"])');
    
                for(col of data)
                {
                    const board = {
                        id: '_col1',
                        title: col.name,
                        class: 'info', 
                        item: new Array()
                    }
    
                    for(item of col.items)
                    {
                        board.item.push({
                            title: createItem(item.name, item.created_at)
                        });
                    }
                    boards.push(board)
                }
            })();

            var kanban = new jKanban({
                element: '#demo2',
                gutter: '15px',
                click: function (el) {
                    alert(el.innerHTML);
                },
                boards: boards
            });

            function createItem(name, date) {
                const dateDisplay = new Date(Date.parse(date)).toLocaleDateString('fr-FR', { year: 'numeric', month: 'numeric', day: 'numeric' });
                console.log(dateDisplay);
                return `
                    <a class="kanban-box" href="#"><span class="date">${dateDisplay}</span>
                        <h6>${name}</h6>
                        <div class="media"><img class="img-20 me-1 rounded-circle" src="../assets/images/user/3.jpg" alt="" data-original-title="" title="">
                        <div class="media-body">
                            <p>Pixelstrap, New york</p>
                        </div>
                        </div>
                        <div class="d-flex mt-3">
                        <ul class="list">
                            <li><i class="fa fa-comments-o"></i>2</li>
                            <li><i class="fa fa-paperclip"></i>2</li>
                            <li><i class="fa fa-eye"></i></i></li>
                        </ul>
                        <div class="customers">
                            <ul>
                            <li class="d-inline-block me-3">
                                <p class="f-12">+5</p>
                            </li>
                            <li class="d-inline-block"><img class="img-20 rounded-circle" src="../assets/images/user/3.jpg" alt="" data-original-title="" title=""></li>
                            <li class="d-inline-block"><img class="img-20 rounded-circle" src="../assets/images/user/1.jpg" alt="" data-original-title="" title=""></li>
                            <li class="d-inline-block"><img class="img-20 rounded-circle" src="../assets/images/user/5.jpg" alt="" data-original-title="" title=""></li>
                            </ul>
                        </div>
                        </div></a>
                    `
            }
        </script>
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
