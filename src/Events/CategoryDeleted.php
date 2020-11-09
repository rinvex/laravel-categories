<?php

declare(strict_types=1);

namespace Rinvex\Categories\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Rinvex\Categories\Models\Category;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CategoryDeleted implements ShouldBroadcast
{
    use InteractsWithSockets;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'events';

    /**
     * The model instance passed to this event.
     *
     * @var \Rinvex\Categories\Models\Category
     */
    public $category;

    /**
     * Create a new event instance.
     *
     * @param \Rinvex\Categories\Models\Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel($this->formatChannelName());
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'rinvex.categories.deleted';
    }

    /**
     * Format channel name.
     *
     * @return string
     */
    protected function formatChannelName(): string
    {
        return 'rinvex.categories.list';
    }
}
