<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Col;
use App\Models\Kanban;
use App\Models\Invitation;

class KanbanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $res = Kanban::query()
            ->where('ownerUserId', '=', '1')
            ->orWhereIn(
                'id',
                Invitation::query()
                    ->where('userId', '=', '1')
                    ->select('userId')
                    ->get()
            )->get();
        dd($res);
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'colname', 'colcolor');

        $kanban = new Kanban;
        $kanban->name = $data['name'];
        $kanban->isActive = true;
        $kanban->ownerUserId = 1;
        $kanban->save();
        $len = count($data['colname']);

        for($i = 0; $i <  $len; $i++)
        {
            $currentCol = new Col;
            $currentCol->name = $data['colname'][$i];
            $currentCol->colorHexa = $data['colcolor'][$i];
            $currentCol->colOrder = $i + 1;
            $currentCol->kanbanId = $kanban->id;
            $currentCol->save();
        }
        return redirect(route('kanban.index'));
    }
}
