<?php

namespace App\Events;

use App\Models\Activity;
use App\Models\Recurring;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class RecurringCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(Recurring $recurring)
    {
        $userId = null;

        if (Auth::check()) {
            $userId = Auth::user()->id;
        }

        Activity::create([
            'space_id' => $recurring->space_id,
            'user_id' => $userId,
            'entity_id' => $recurring->id,
            'entity_type' => 'recurring',
            'action' => 'recurring.created'
        ]);
    }
}
