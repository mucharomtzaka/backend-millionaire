<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Cache;

class AuthloginEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userList;

    public function __construct($userList)
    {
        $this->userList = $userList;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('login-event-test'); // Ensure this matches React
    }

    public function broadcastAs()
    {
        return "AuthloginEvent";
    }

    public function broadcastWith() {
        return [
            'message' => 'Broadcasted Event...',
            'userList' => $this->userList,
        ];
    }
}
