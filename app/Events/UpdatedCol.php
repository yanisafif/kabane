<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Col;

class UpdatedCol implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Col $col; 
    public $kanbanId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Col $col, $kanbanId)
    {
        $this->col = $col; 
        $this->kanbanId = $kanbanId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('kanban.' . $this->kanbanId);
    }

    /**
     * Get the data that should be broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return array(
            'colId' => $this->col->id, 
            'colName' => $this->col->name,
            'colColor' => $this->col->colorHexa,
            'actionMadeByUserId' => \Auth::user()->id
        );
    }
}
