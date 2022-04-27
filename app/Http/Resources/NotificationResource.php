<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
		'id' => $this->id,
		'user' => new UserResource(User::find($this->user_id)),
		'target' => new UserResource(User::find($this->target_id)),
		'user_id' => $this->user_id,
		'user_name' => User::find($this->user_id)->name,
		'target_id' => $this->target_id,
		'target_name' => User::find($this->target_id)->name,
		'message' => $this->message,
		'created_at' => strtotime($this->created_at)*1000,
		'status' => $this->status,
		'type' => $this->type,
		'subject' => $this->subject,
	    ];

    }
}
