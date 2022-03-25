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
use Illuminate\Support\Arr;

class ItemController extends Controller
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

    public function store(Request $request)
    {
        $rules = [
            "name" => "required|max:50",
            "description" => "present",
            "colId" => "required|numeric",
            "assign" => "required|numeric",
            "deadline" => "nullable|date"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }
        
        $data = $request->only('name', 'description', 'colId', "assign", "deadline");

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
        $item->assignedUserId = ($data['assign'] > 0 ? $data['assign'] : NULL);
        $item->deadline = (!is_null($data['deadline']) ? $data['deadline'] : NULL);
        $item->itemOrder = 1;
        $item->save();

        return response()->json(['status' => 'Item saved successfully', 'itemId' => $item->id]);
    }

    public function update(Request $request)
    {
        $rules = [
            "itemId" => "required|numeric",
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Error', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }

        $data = $request->only('itemId', "title", "assign", "deadline", "description"); 

        $record = Item::find($data['itemId']);
        if(is_null($record)) 
        {
            return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);
        }
        
        if(Arr::exists($data, 'title')) 
            $record->name = $data['title']; 
        if(Arr::exists($data, 'assign'))
            $record->assignedUserId = $data['assign']; 
        if(Arr::exists($data, 'deadline'))
            $record->deadline = $data['deadline']; 
        if(Arr::exists($data, 'description'))
            $record->description = $data['description'];
        
        $record->save(); 

        return response()->json(['status' => 'Succeed']);
    }

    public function delete(Request $request) 
    {
        $rules = [
            "itemId" => "required|numeric",
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);
        }

        $itemId = $request->only('itemId')['itemId'];

        Item::find($itemId)->delete();

        return response()->json(['status' => 'Succeed']);
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
        $userId = \Auth::user()->id;
        $data = [];

        $data['invitedKanban'] = Kanban::query()
            ->whereIn(
                'id',
                Invitation::query()
                    ->where('userId', '=', $userId)
                    ->select('userId')
                    ->get()
            )
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();

        $data['ownedKanban'] = Kanban::query()
            ->where('ownerUserId', '=', $userId)
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();

        return $data;
    }
}
