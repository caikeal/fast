<?php

namespace App\Events;

use App\Events\Event;
use App\ModuleStatistics;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserLog extends Event
{
    use SerializesModels;

    public $moduleData;

    /**
     * Create a new event instance.
     *
     * UserLog constructor.
     * @param ModuleStatistics $moduleData
     */
    public function __construct(ModuleStatistics $moduleData)
    {
        $this->moduleData = $moduleData;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
