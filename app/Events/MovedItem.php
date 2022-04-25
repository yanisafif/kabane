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

class MovedItem implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemId; 
    public $kanbanId;
    public $colIdFrom; 
    public $colIdTo;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($itemId, $kanbanId, $colIdFrom, $colIdTo)
    {
        $this->itemId = $itemId; 
        $this->kanbanId = $kanbanId;
        $this->colIdFrom = $colIdFrom; 
        $this->colIdTo = $colIdTo;
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
            'colIdFrom' => $this->colIdFrom,
            'colIdTo' => $this->colIdTo,
            'actionMadeByUserId' => \Auth::user()->id
        );
    }
}
