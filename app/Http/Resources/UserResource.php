<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
		'name' => $this->name,
		'patronymic' => $this->field('Ваше отчество'),
		'lastname' => $this->field('Ваша фамилия'),


		'place' => $this->field('Ваше место работы') ? $this->field('Ваше место работы')['wo_place'] : '',


		'position' => $this->field('Ваше место работы') ? $this->field('Ваше место работы')['wo_position'] : '',


		'role' => $this->field('Роль в проектной деятельности (при наличии)'),
		'education' => $this->field('Образование') ? $this->field('Образование') : '',

		'educationName' => $this->field('Образование') ? collect(array_values($this->bios->where('field_id', 14)
	   			->all()))->pluck('value')->map(function ($value){
		   			return json_decode($value);
	   			})->pluck('ed_universityTitle') : '',


		'avatar' => $this->avatar,
		'phone' => $this->fieldObj('Ваш мобильный телефон для связи'),
		'email' => $this->email,
		'skype' => $this->fieldObj('Skype'),
		'facebook' => $this->fieldObj('Facebook'),
		'vkontakte' => $this->fieldObj('Вконтакте'),
		'instagram' => $this->fieldObj('Instagram'),
		'website' => $this->fieldObj('Личный сайт'),
		'emailShow' => $this->emailShow,
		'created_at' => strtotime($this->created_at),
		'rating' => $this->rating('count'),
		'fields' => $this->fields,
		'bios' => $this->bios,
		'verified' => $this->hasRole('user'),
		'fullName' => $this->field('Ваша фамилия').' '.$this->name.' '.$this->field('Ваше отчество'),
		'ref_name' => $this->ref_name,
	    ];
    }
}
