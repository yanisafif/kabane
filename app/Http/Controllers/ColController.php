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

    public function rename(Request $request)
    {
        $rules = [
            "colName" => "required|max:50",
            "colId" => "required|numeric",
        ];

        // Validate the form with is data
        $validator = Validator::make($request->all(), $rules);

        // If data dont respect the validation rules, the request fails
        if ($validator->fails())
        {
            return response(json_encode(['status' => 'Form not valid ', $validator->errors()]), 400, ['Content-Type' => 'application/json']);
        }
        
        $data = $request->only('colName', 'colId');

        $kanban = Kanban::query()
            ->join('cols', 'cols.kanbanId', '=', 'kanbans.id')
            ->where('cols.id', '=', $data['colId'])
            ->first();

        if(is_null($kanban))
            return response(json_encode(['status' => 'Kanban not found']), 400, ['Content-Type' => 'application/json']);
        if(!checkIfKanbanAllow($kanban))
            return response(json_encode(['status' => 'You\'re not allowed to do that']), 403, ['Content-Type' => 'application/json']);
        
        $col = Col::find($data['colId']); 
        $col->name = $data['colName'];
        $col->save();

        return response()->json(['status' => 'Column renamed succefully']);
    }

}
