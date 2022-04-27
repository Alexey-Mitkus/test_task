<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class UpdateResource extends JsonResource
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
			'user' => new UserResource($this->user),
			'target' => new UserResource($this->target),
			'user_id' => $this->user_id,
			'target_id' => $this->target_id,

			
			
			'created_at' => strtotime($this->created_at)*1000,
	    ];

    }
}
