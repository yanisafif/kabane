<?php

if (!function_exists('checkIfKanbanAllow')) 
{
    function checkIfKanbanAllow($kanban, $requireAdmin = false)
    {
        $userId = \Auth::user()->id;

        if($kanban->ownerUserId == $userId)
            return true;
        
        if($requireAdmin) 
            return false;

        $res = App\Models\Invitation::query()
            ->where('userId', '=', $userId)
            ->where('kanbanId', '=', $kanban->id)
            ->first();

        return !is_null($res);
    }
}

if (!function_exists('getKanbanFromId')) 
{
    function getKanbanFromId($id)
    {
        return  App\Models\Kanban::find($id);
    }
}

if (!function_exists('getLayoutData')) 
{
    function getLayoutData()
    {
        $userId = \Auth::user()->id;
        $data = [];
    
        $data['invitedKanban'] = App\Models\Kanban::query()
            ->whereIn(
                'id',
                App\Models\Invitation::query()
                    ->where('userId', '=', $userId)
                    ->select('kanbanId')
                    ->get()
            )
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();
    
        $data['ownedKanban'] = App\Models\Kanban::query()
            ->where('ownerUserId', '=', $userId)
            ->select('id', 'name', 'isActive', 'ownerUserId')
            ->get();

        return $data;
    }
}