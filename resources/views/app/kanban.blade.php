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
                            <div class="kanban-block" id="kabane"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" name="creation-modal" id="creation-modal" role="dialog" aria-labelledby="creation-modal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New message</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label class="col-form-label" for="recipient-name">Recipient:</label>
                                <input class="form-control" type="text" value="@getbootstrap">
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label" for="message-text">Message:</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="button">Send message</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="{{asset('assets/js/jkanban/jkanban.js')}}"></script>
        <script>

            const boards = new Array();

            (function() {
                const data = JSON.parse('@json($data["cols"])');
                const style = document.createElement('style');
                for(col of data)
                {
                    style.innerHTML += `
                        .col${col.id} {
                            background-color: ${col.colorHexa};
                            color: ${figureTextColor(col.colorHexa)};
                        }
                    `
                    const board = {
                        id: '_col' + col.id,
                        title: col.name,
                        class: 'col' + col.id,
                        item: new Array()
                    }

                    for(item of col.items)
                    {
                        board.item.push({
                            title: createItem(item.name, item.created_at)
                        });
                    }

                    boards.push(board)
                    i++;
                }

               document.getElementsByTagName('head')[0].appendChild(style);
            })();

            var kanban = new jKanban({
                element: '#kabane',
                gutter: '15px',
                click: function (el) {
                    el.class
                },
                boards: boards,
                itemAddOptions: {
                    enabled: true,                                              // add a button to board for easy item creation
                    content: '+ Add item',                                                // text or html content of the board button
                    class: 'kanban-title-button btn btn-default text-center w-100',         // default class of the button
                    footer: true                                                // position the button on footer
                }, 
                buttonClick: (el, boardId) => {
                    $("#creation-modal").modal('show');
                    console.log(el, boardId);
                }
            });

            function createItem(name, date) {
                const dateDisplay = new Date(Date.parse(date)).toLocaleDateString('fr-FR', { year: 'numeric', month: 'numeric', day: 'numeric' });
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


            function figureTextColor(bgColor) {
                var color = (bgColor.charAt(0) === '#') ? bgColor.substring(1, 7) : bgColor;
                var r = parseInt(color.substring(0, 2), 16); // hexToR
                var g = parseInt(color.substring(2, 4), 16); // hexToG
                var b = parseInt(color.substring(4, 6), 16); // hexToB
                var uicolors = [r / 255, g / 255, b / 255];
                var c = uicolors.map((col) => {
                    if (col <= 0.03928) {
                    return col / 12.92;
                    }
                    return Math.pow((col + 0.055) / 1.055, 2.4);
                });
                var L = (0.2126 * c[0]) + (0.7152 * c[1]) + (0.0722 * c[2]);
                return (L > 0.179) ? '#000' : '#fff';
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
