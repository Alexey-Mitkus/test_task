<?php

namespace App\Filters;

class KbPostFilter extends QueryFilter
{
    public function search($search = '')
    {
        if( !empty($search) )
        {
            return $this->builder->search($search);
        }
    }

    public function formats($formats = '')
    {
        if( !empty($formats) )
        {
            if( is_array($formats) )
            {
                return $this->builder->whereHas('formats', function($query) use ($formats){
                    $query->whereIn('id', $formats)->orWhereIn('name', $formats);
                });
            }

            if( is_string($formats) )
            {
                return $this->builder->whereHas('formats', function($query) use ($formats){
                    $query->where('id', (int) $formats)->orWhere('name', $formats);
                });
            }
        }
    }

    public function tags($tags = '')
    {
        if( !empty($tags) )
        {
            if( is_array($tags) )
            {
                return $this->builder->whereHas('tags', function($query) use ($tags){
                    $query->whereIn('id', $tags)->orWhereIn('name', $tags);
                });
            }
            
            if( is_string($tags) )
            {
                return $this->builder->whereHas('tags', function($query) use ($tags){
                    $query->where('id', (int) $tags)->orWhere('name', $tags);
                });
            }
        }
    }

    public function themes($themes = '')
    {
        if( !empty($themes) )
        {
            if( is_array($themes) )
            {
                return $this->builder->whereHas('themes', function($query) use ($themes){
                    $query->whereIn('id', $themes)->orWhereIn('name', $themes);
                });
            }

            if( is_string($themes) )
            {
                return $this->builder->whereHas('themes', function($query) use ($themes){
                    $query->whereIn('id', (int) $themes)->orWhere('name', $themes);
                });
            }
        }
    }
    public function subthemes($subthemes = '')
    {
        if( !empty($subthemes) )
        {
            if( is_array($subthemes) )
            {
                return $this->builder->whereHas('themes', function($query) use ($subthemes){
                    $query->whereNotNull('parent_id')->whereIn('id', $subthemes)->orWhereIn('name', $subthemes);
                });
            }

            if( is_string($subthemes) )
            {
                return $this->builder->whereHas('themes', function($query) use ($subthemes){
                    $query->whereNotNull('parent_id')->whereIn('id', (int) $subthemes)->orWhere('name', $subthemes);
                });
            }
        }
    }

    public function orderBy($orderBy = '')
    {
        if( !empty($orderBy) )
        {
            switch ($orderBy)
            {
                case 'popular':
                    return $this->builder->orderBy('views', 'DESC');
                break;
                case 'newest':
                default:
                    return $this->builder->orderBy('updated_at', 'DESC');
                break;
                case 'likes':
                    return $this->builder->withCount('likes')->orderBy('likes_count', 'DESC');
                break;
            }
        }
    }

    public function state($state = '')
    {
        if( !empty($state) )
        {
            switch ($state)
            {
                case 0:
                    return $this->builder->where('is_active', 0);
                break;
                case 1:
                default:
                    return $this->builder->where('is_active', 1);
                break;
            }
        }
    }

    public function status($status_id = '')
    {
        if( !empty($status_id) )
        {
            return $this->builder->where('status_id', (int) $status_id);
        }
    }
}