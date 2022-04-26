<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Message;
use App\Models\Kanban;
use App\Models\Invitation;
use App\Models\User;
use View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Events\NewItem;
use App\Events\UpdatedItem;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null)
    {
        $data = [ 'kanbanNotSelected' => false, 'kanbanNotFound' => false ];

        if(is_null($id))
        {
            $data['kanbanNotSelected'] = true;
        }
        else
        {
            $kanban = Kanban::query()
               ->where('id', '=', $id)
               ->select('id', 'name', 'isActive', 'created_at', 'ownerUserId')
               ->first();

            if(!is_null($kanban) && checkIfKanbanAllow($kanban))
            {
                $data['messages'] = Message::query()
                    ->join('users', 'users.id', '=', 'messages.userId')
                    ->where('kanbanId', '=', $id)
                    ->select('content', 'userId', 'users.created_at', 'users.name AS username', 'users.path_image')
                    ->orderBy('created_at')
                    ->get();

                $currentUserId = \Auth::user()->id;

                foreach($data['messages'] as $message)
                {
                    $message['isCurrentUser'] = ($currentUserId === $message['userId']);
                }

                $peopleAccessBoard = Invitation::query()
                    ->where('kanbanId', '=', $id)
                    ->join('users', 'users.id', '=', 'invitations.userId')
                    ->select('users.name', 'users.id', 'users.path_image')
                    ->get();

                $kanbanOwner = User::query()
                        ->join('kanbans', 'kanbans.ownerUserId', '=', 'users.id')
                        ->where('kanbans.id', '=', $id)
                        ->select('users.name', 'users.id', 'users.path_image')
                        ->first();

                $peopleAccessBoard->add($kanbanOwner);
                

                foreach($peopleAccessBoard as $person)
                {
                    $person['isCurrentUser'] = ($currentUserId == $person['id']);
                }
                
                $data['people'] = $peopleAccessBoard;
            }
            else
            {
                $data['kanbanNotFound'] = true;
            }
        }

        return view('app.chat', compact('data'));
    }

}
