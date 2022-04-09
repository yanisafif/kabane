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

class ColController extends Controller
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

    public function add(Request $request) 
    {
        $rules = [
            "colName" => "required|max:50",
            "colorHexa" => "required|max:9",
            "colOrder" => "required|numeric",
            "kanbanId" => "required|numeric"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, the request fails
        if($validator->fails())
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }

        $data = $request->only('colName', 'colorHexa', 'kanbanId', 'colOrder');
        
        $kanban = Kanban::query()
            ->where('id', '=', $data['kanbanId'])
            ->first();

        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban, true))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);

        $newCol = new Col;

        $newCol->kanbanId = $data['kanbanId']; 
        $newCol->name = $data['colName']; 
        $newCol->colorHexa = $data['colorHexa']; 
        $newCol->colOrder = $data['colOrder'];
        $newCol->save();

        return response()->json(['status' => 'Column saved successfully', 'itemId' => $newCol->id]);
    }

    public function edit(Request $request)
    {
        $rules = [
            "colName" => "nullable|present|max:50",
            "colorHexa" => "nullable|present|max:9",
            "colId" => "required|numeric"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        $data = $request->only('colName', 'colorHexa', 'colId');

        // If data dont respect the validation rules, the request fails
        if ($validator->fails() || (is_null($data['colName']) && is_null($data['colorHexa'])))
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }
        
        $kanban = Kanban::query()
            ->join('cols', 'cols.kanbanId', '=', 'kanbans.id')
            ->where('cols.id', '=', $data['colId'])
            ->first();

        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);
        
        $col = Col::find($data['colId']); 

        if(!is_null($data['colName']))
            $col->name = $data['colName'];
        
        if(!is_null($data['colorHexa']))
            $col->colorHexa = $data['colorHexa'];

        $col->save();

        return response()->json(['status' => 'Column renamed successfully']);
    }

    public function move(Request $request) 
    {
        $rules = [
            "cols" => "required|array",
            "kanbanId" => "required|numeric"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        
        // If data dont respect the validation rules, the request fails
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }

        $data = $request->only('cols', 'kanbanId');

        $kanban = Kanban::query()
            ->where('id', '=', $data['kanbanId'])
            ->first();


        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);
        
        $this->sortCols($data['kanbanId'], $data['cols']);

        return response()->json(['status' => 'Column moved successfully']);
    }

    public function delete(Request $request) 
    {
        $rules = [
            "deleteColId" => 'required|numeric',
            "cols" => "required|array",
            "kanbanId" => "required|numeric"
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        
        // If data dont respect the validation rules, the request fails
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }

        $data = $request->only('cols', 'kanbanId', 'deleteColId');

        $kanban = Kanban::query()
            ->where('id', '=', $data['kanbanId'])
            ->first();

        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban, true))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);

        $items = Item::query()
            ->where('colId', '=', $data['deleteColId'])
            ->get();
        
        if(!is_null($items))
        {
            foreach($items as $item)
            {
                $item->delete();
            }
        }

        Col::find($data['deleteColId'])->delete();

        $this->sortCols($data['kanbanId'], $data['cols']);
    }

    protected function sortCols($kanbanId, $listSortedCol)
    {
        $cols = Col::query()
            ->orderby('colOrder')
            ->select('cols.id', 'cols.colOrder')
            ->where('kanbanId', '=', $kanbanId)
            ->get();
    
        foreach($cols as $col)
        {
            $colNewOrder = null;
            foreach($listSortedCol as $colOrderMap)
            {
                if($col->id == $colOrderMap['colId'])
                {
                    $colNewOrder = $colOrderMap['colOrder'];
                    break;
                }
            }
            if(is_null($colNewOrder))
                return response(json_encode(['status' => 'Error']), 400, ['Content-Type' => 'application/json']);

            if($col->colOrder != $colNewOrder)
            {
                $col->colOrder = $colNewOrder;
                $col->save();
            }
        }
    }
}
