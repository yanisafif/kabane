<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kanban;
use App\Models\Invitation;
use App\Models\User;

class AdminController extends Controller
{
    /** access to the admin panel of the web site
     *  Admin can see all user register in kabane
     *
     * @method GET
     * @param void
     * @return Route app.admin.panel | fail = kanban.board
     */
    public function panel(){
        if(auth()->user()->is_admin){
            $users = User::query()
                ->select('name', 'email', 'title', 'is_admin', 'created_at', 'updated_at')
                ->get();

            $kanbans = $this->getLayoutData();
            return view('app.admin.panel', ['users' => $users, 'kanbans' => $kanbans]);
        }

        return redirect()->route('kanban.board')->with('danger', 'You do not have access to this page.');
    }

    /**
     *
     * @param void
     * @return array $data
     */
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
