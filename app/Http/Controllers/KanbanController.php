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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KanbanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
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
               ->select('id', 'name', 'isActive', 'created_at', 'ownerUserId')
               ->first();

            if(!is_null($data['kanban']) && $this->checkIfKanbanAllow($data['kanban']))
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
                
                $peopleAccessBoard = Invitation::query()
                    ->where('kanbanId', '=', $id)
                    ->join('users', 'users.id', '=', 'invitations.userId')
                    ->select('name')
                    ->get();

                $peopleAccessBoard->add(
                    User::query()
                        ->join('kanbans', 'kanbans.ownerUserId', '=', 'users.id')
                        ->where('kanbans.id', '=', $id)
                        ->select('kanbans.name', 'kanbans.id')
                        ->first()
                );
               
                $data['people'] = $peopleAccessBoard;

            }
            else
            {
                $data['kanban'] = null;
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

    public function storeItem(Request $request)
    {
        
        $rules = [
            "name" => "required|max:50",
            "description" => "required",
            "colId" => "required|numeric"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'BLA IFIEBF']), 400, ['Content-Type' => 'application/json']);
        }
        
        $data = $request->only('name', 'description', 'colId');
        
        $kanban = Kanban::query()
            ->join('cols', 'cols.kanbanId', '=', 'kanbans.id')
            ->where('cols.id', '=', $data['colId'])
            ->first();

        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!$this->checkIfKanbanAllow($kanban))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);

        $item = new Item;
        $item->name = $data['name'];
        $item->description = $data['description'];
        $item->colId = $data['colId'];
        $item->ownerUserId = \Auth::user()->id;
        $item->itemOrder = 1;
        $item->save();

        return response()->with('success', 'You are now logged in.');//json(['status' => 'Item saved successfully'])->with('success', 'You are now logged in.');
    }

    protected function checkIfKanbanAllow($kanban)
    {
        $userId = \Auth::user()->id;

        if($kanban->ownerUserId == $userId)
            return true;

        $res = Invitation::query()
            ->where('userId', '=', $userId)
            ->where('kanbanId', '=', $kanban->id)
            ->first();

        return !is_null($res);
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
}
