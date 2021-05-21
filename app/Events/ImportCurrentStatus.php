<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImportCurrentStatus implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $job_id;
    protected $completed_percent;

    /**
     * ImportCurrentStatus constructor.
     * @param int $job_id
     * @param float $completed_percent
     */
    public function __construct(int $job_id, float $completed_percent)
    {
        $this->job_id = $job_id;
        $this->completed_percent = $completed_percent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('import-current-status-'.$this->job_id);
    }

    /**
     * @return string
     */
    public function broadcastAs()
    {
        return 'changed';
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        return ['completed_percent' => $this->completed_percent];
    }
}
