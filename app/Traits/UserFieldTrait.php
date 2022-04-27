<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait UserFieldTrait
{
    protected $noFields = true;
    protected $fieldsLists = [];

    public function toArray()
    {
        $data = parent::toArray();

        if( $this->noFields )
        {
            return $data;
        }

        $fields = Cache::rememberForever('users_fields_' . $this->id, function(){
            return $this->fields;
        });
        
        if( !empty($fields) )
        {
            foreach($fields as $key => $field)
            {
                if( empty($this->fieldsLists) )
                {
                    
                    $data[$field->slug] = $field;

                }else{

                    if( in_array($field->slug, $this->fieldsLists) )
                    {
                        $data[$field->slug] = $field;
                    }
                }
                
            }

            $full_name = collect([]);

            if( !empty($data['last_name']) )
            {
                $full_name->push($data['last_name']->pivot->value);
            }

            if( !empty($data['first_name']) )
            {
                $full_name->push($data['first_name']->pivot->value);
            }

            if( !empty($data['middle_name']) )
            {
                $full_name->push($data['middle_name']->pivot->value);
            }

            if( $full_name->count() )
            {
                $data['full_name'] = $full_name->implode(' ');
            }

            $short_full_name = collect([]);

            if( !empty($data['last_name']) )
            {
                $short_full_name->push($data['last_name']->pivot->value . ' ');
            }

            if( !empty($data['first_name']) )
            {
                $short_full_name->push(Str::of($data['first_name']->pivot->value)->substr(0, 1)->finish('.'));
            }

            if( !empty($data['middle_name']) )
            {
                $short_full_name->push(Str::of($data['middle_name']->pivot->value)->substr(0, 1)->finish('.'));
            }

            if( $short_full_name->count() )
            {
                $data['full_name_short'] = $short_full_name->implode('');
            }
        }

        return $data;
    }

    public function withoutFiedls()
    {
        $this->noFields = true;
        return $this;
    }

    /**
     * Enable formatting for the dates
     *
     * @return $this
     */
    public function withFields($lists = [])
    {
        $this->noFields = false;
        $this->fieldsLists = $lists;
        return $this;
    }
}