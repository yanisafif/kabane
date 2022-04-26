<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;

class UpdatedItem implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Item $item; 
    public $kanbanId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Item $item, $kanbanId)
    {
        $this->item = $item; 
        $this->kanbanId = $kanbanId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('kanban.' . $this->kanbanId);
    }

    public function broadcastWith()
    {
        return array(
            'name' => $this->item->name, 
            'description' => $this->item->description, 
            'colId' => $this->item->colId, 
            'ownerUserId' => $this->item->ownerUserId, 
            'assignedUserId' => $this->item->assignedUserId, 
            'deadline' => $this->item->deadline,
            'created_at' => $this->item->created_at,
            'updated_at' => $this->item->updated_at,
            'item_id' => $this->item->id, 
            'actionMadeByUserId' => \Auth::user()->id
        );
    }
}
