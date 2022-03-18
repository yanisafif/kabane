<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Col;
use App\Models\Kanban;
use App\Models\Invitation;
use App\Models\Item;
use App\Models\User;
use View;

class KanbanController extends Controller
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

    public function board($id = null)
    {
        $data = [ 'kanbanNotSelected' => false ];

        if(is_null($id))
        {
            $data['kanbanNotSelected'] = true;
        }
        else
        {
           $data['kanban'] =  Kanban::query()
               ->where('id', '=', $id)
               ->first();

           if(!is_null($data['kanban']))
           {
               $cols = Col::query()
                   ->where('kanbanId', '=', $id)
                   ->orderBy('colOrder')
                   ->select('id', 'name', 'colorHexa', 'colOrder')
                   ->get();

               foreach($cols as $col)
               {
                   $col['items'] = Item::query()
                       ->where('colId', '=', $col['id'])
                       ->orderBy('colId')
                       ->leftJoin('users AS assignedUser', 'assignedUser.id' , '=',  'items.assignedUserId' )
                       ->join('users AS ownerUser', 'items.ownerUserId' , '=', 'ownerUser.id')
                       ->select('items.id as item_id', 'items.name as item_name', 'description', 'items.created_at', 'items.updated_at', 'itemOrder',
                           'assignedUser.name as assignedUser_name', 'assignedUser.email as assignedUser_email', 'assignedUser.id as assignedUser_id',
                           'ownerUser.name as ownerUser_name', 'ownerUser.email as ownerUser_email', 'ownerUser.id as ownerUser_id'
                        )
                       ->get();
               }
               $data['cols'] = $cols;
           }

        }
        $kanbans = $this->getLayoutData();
        return view('app.kanban', compact('kanbans', 'data'));
    }

    public function create()
    {
        $kanbans = $this->getLayoutData();
        return view('app.create-kanban', compact('kanbans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:25",
            "colname" => "required|array|min:2",
            "colcolor" => "required|array|min:2",
            "invite" => "present|array"
        ]);

        $data = $request->only('name', 'colname', 'colcolor', 'invite');
        $kanban = new Kanban;
        $kanban->name = $data['name'];
        $kanban->isActive = true;
        $kanban->ownerUserId = \Auth::user()->id;
        $kanban->save();

        $lenCol = count($data['colname']);
        for($i = 0; $i < $lenCol; $i++)
        {
            $currentCol = new Col;
            $currentCol->name = $data['colname'][$i];
            $currentCol->colorHexa = $data['colcolor'][$i];
            $currentCol->colOrder = $i + 1;
            $currentCol->kanbanId = $kanban->id;
            $currentCol->save();
        }

        $lenInvite = count($data['invite']);
        for($i = 0; $i < $lenInvite; $i++)
        {
            $item = $data['invite'][$i];
            if(is_null($item))
            {
                continue;
            }

            $user = User::query()
                ->where('name', '=', $item)
                ->orWhere('email', '=', $item)
                ->select('id')
                ->first();

            if(is_null($user))
            {
                continue;
            }

            $invite = new Invitation; 
            $invite->userId = $user->id; 
            $invite->kanbanId = $kanban->id;
            $invite->save();
        }
        return redirect(route('kanban.board'));
    }

    protected function getLayoutData()
    {
        $data = Kanban::query()
            ->where('ownerUserId', '=', \Auth::user()->id)
            ->orWhereIn(
                'id',
                Invitation::query()
                    ->where('userId', '=', \Auth::user()->id)
                    ->select('userId')
                    ->get()
            )
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();

        foreach ($data as $item)
        {
            if($item['ownerUserId'] == \Auth::user()->id)
            {
                $item['isOwner'] = true;
            }
            else
            {
                $item['isOwner'] = false;
            }
        }
        return $data;
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            "name" => "required|max:50",
            "description" => "required",
            "colId" => "required|numeric"
        ]);

        $data = $request->only('name', 'description', 'colId');

        $item = new Item;
        // $item->ge
    }
}
