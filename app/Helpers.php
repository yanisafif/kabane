<?php

use App\Models\Invitation;
use App\Models\Kanban;

if (!function_exists('checkIfKanbanAllow')) 
{
    function checkIfKanbanAllow($kanban)
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
if (!function_exists('getLayoutData')) 
{
    function getLayoutData()
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