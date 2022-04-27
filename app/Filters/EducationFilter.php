<?php

namespace App\Filters;

class EducationFilter extends QueryFilter
{
    public function search($search = '')
    {
        if( !empty($search) )
        {
            return $this->builder->search($search);
        }
    }

    public function type($type = '')
    {
        if( !empty($type) )
        {
            return $this->builder->whereHas('categories', function($query) use ($type){
                return $query->where('slug', $type);
            });
        }
    }

    public function orderBy($orderBy = '')
    {
        if( !empty($orderBy) )
        {
            return $this->builder->orderBy($orderBy, 'ASC');
        }
    }
}