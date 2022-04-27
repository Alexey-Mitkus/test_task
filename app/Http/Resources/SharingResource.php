<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Field;

class SharingResource extends JsonResource
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
		'user_id' => $this->user_id,
		'user_name' => User::find($this->user_id)->name,
		'target' => new UserResource(User::find($this->target_id)),
		'target_id' => $this->target_id,
		'target_name' => User::find($this->target_id)->name,
		'message' => $this->message,
		'fieldIds' => $this->fieldIds,
		'fields' => Field::whereIn('id', $this->fieldIds)->pluck('name'),
		'created_at' => strtotime($this->created_at),
		'status' => $this->status,
	    ];

    }
}
