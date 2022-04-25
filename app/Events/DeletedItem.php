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

class DeletedItem implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemId; 
    public $colId;
    public $kanbanId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($itemId, $colId, $kanbanId)
    {
        $this->itemId = $itemId; 
        $this->colId = $colId;
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
            'item_id' => $this->itemId, 
            'colId' => $this->colId, 
            'actionMadeByUserId' => \Auth::user()->id
        );
    }
}
