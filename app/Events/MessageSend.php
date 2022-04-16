<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Http\Resources\CustomerResource;
use App\Models\User;
use App\Models\Ads;

class MessageSend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $user, $message)
    {
        $this->message = $message;

        $this->user     = $user;

        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $this->message['customer'] = new CustomerResource(User::find($this->message->users_id));

        if( $this->message->is_buyer == 0 ) {

            return [
                new Channel('chat.'.$this->message->ads_id.'.'.$this->message->users_id),
                    // new Channel('chat.'.$this->message->ads_id.'.'.'1')
            ];  
        } else {
            $ad = Ads::find($this->message->ads_id);
            return [
                new Channel('chat.'.$this->message->ads_id.'.'.$ad->users_id),
                new Channel('chat.'.$this->message->ads_id.'.'.'1')
            ];   
        }

    }
}
