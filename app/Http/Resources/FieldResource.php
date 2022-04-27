<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Field;
use Auth;
use App\Sharing;
use App\Http\Resources\SharingResource as SharingResource;
use App\Requesting;

class FieldResource extends JsonResource
{	
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    
    public function toArray($request)
    {
	    $target_id = $this->user_id;
	    $user_id = Auth::user()->id;
	    $sharingsObj = Sharing::where([['user_id', '=', $target_id],['target_id', '=', $user_id]])->first();
	    
	    
	    if($sharingsObj)
	    {
		    $sharings = new SharingResource($sharingsObj);
		    $fieldIds = $sharings->fieldIds;
		    $fieldShowUser = in_array($this->field_id, $fieldIds); 
		    if($this->ifShow || $fieldShowUser)
		    	$ifShow = 1;
		    else
		    	$ifShow = 0;
		}
		else
			$ifShow = $this->ifShow;
        return [
		'value' => $this->value,
		'ifShow' => $ifShow,
	    ];

    }
}
