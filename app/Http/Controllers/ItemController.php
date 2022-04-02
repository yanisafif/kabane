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
            "item_name" => "required|max:50",
            "description" => "present",
            "colId" => "required|numeric",
            "assignedUser_id" => "required|numeric",
            "deadline" => "nullable|date"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }
        
        $data = $request->only('item_name', 'description', 'colId', "assignedUser_id", "deadline");

        $kanban = Kanban::query()
            ->join('cols', 'cols.kanbanId', '=', 'kanbans.id')
            ->where('cols.id', '=', $data['colId'])
            ->first();

        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);

        $item = new Item;
        $item->name = $data['item_name'];
        $item->description = $data['description'];
        $item->colId = $data['colId'];
        $item->ownerUserId = \Auth::user()->id;
        $item->assignedUserId = ($data['assignedUser_id'] > 0 ? $data['assignedUser_id'] : NULL);
        $item->deadline = (!is_null($data['deadline']) ? $data['deadline'] : NULL);
        $item->itemOrder = 1;
        $item->save();

        return response()->json(['status' => 'Item saved successfully', 'item_id' => $item->id]);
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

        $data = $request->only('itemId', "item_name", "assignedUser_id", "deadline", "description"); 

        $record = Item::find($data['itemId']);
        if(is_null($record)) 
        {
            return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);
        }
        
        if(Arr::exists($data, 'item_name')) 
            $record->name = $data['item_name']; 
        if(Arr::exists($data, 'assignedUser_id'))
            $record->assignedUserId = $data['assignedUser_id']; 
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

        $kanban = Kanban::query()
            ->join('cols', 'kanbans.id', 'cols.kanbanId')
            ->join('items', 'items.colId', 'cols.id')
            ->where('items.id', '=', $itemId)
            ->first();
    
        if(is_null($kanban))
            return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);

        Item::find($itemId)->delete();

        return response()->json(['status' => 'Succeed']);
    }

    public function move(Request $request) 
    {
        $rules = [
            "itemId" => "required|numeric",
            "targetCol" => "required|numeric"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, redirect on same page with error
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);
        }
        $data = $request->only('itemId', 'targetCol');

        $kanbanFromSource = Kanban::query()
            ->join('cols', 'kanbans.id', 'cols.kanbanId')
            ->join('items', 'items.colId', 'cols.id')
            ->where('items.id', '=', $data['itemId'])
            ->first();

        $kanbanFromTarget = Kanban::query()
            ->join('cols', 'kanbans.id', 'cols.kanbanId')
            ->where('cols.id', '=', $data['targetCol'])
            ->first();

        if(is_null($kanbanFromSource) || is_null($kanbanFromTarget) || $kanbanFromSource->kanbanId != $kanbanFromTarget->kanbanId)
        {
            return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);
        }
        if(checkIfKanbanAllow($kanbanFromSource))
        {
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);
        }
        
        $item = Item::find($data['itemId']); 
        $item->colId = $data['targetCol']; 
        $item->save();

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

}
