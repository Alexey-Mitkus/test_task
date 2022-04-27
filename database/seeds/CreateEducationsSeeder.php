<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CreateEducationsSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateEducationsSeeder

        $categories = \App\Models\EducationCategory::all();

        $cities = \App\Models\City::with(['api'])->whereHas('api', function($query){
            return $query->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM);
        })->get();

		$schoolsLists = collect([]);

        if( !empty($cities) )
        {
			$category = $categories->where('slug', 'middle-professional')->first();

            foreach($cities as $key => $city):
                if( !empty(optional($city->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
                {
                    $list = $this->getSchoolsLists($city, $collection = [], $offset = 0);
                }
                if( !empty($list) && $list['count'] > 10000 )
                {
                    for ($i=1; $i < (int) ceil($list['count'] / 10000); $i++)
                    {
                        $list = $this->getSchoolsLists($city, $list['items'], ($i * 10000));
                    }
                }
				if( !empty($list['items']) )
				{
					foreach($list['items'] as $item):
                        $category->educations()->create([
                            'name' => $item['title'],
                            'country_id' => $city->country->id,
                            'country_type' => get_class($city->country),
                            'city_id' => $city->id,
                            'city_type' => get_class($city)
                        ]);
					endforeach;
				}
            endforeach;
			
            $category = $categories->where('slug', 'high')->first();

            foreach($cities as $key => $city):
                if( !empty(optional($city->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
                {
                    $list = $this->getUniversitiesLists($city, $collection = [], $offset = 0);
                }
                if( !empty($list) && $list['count'] > 10000 )
                {
                    for ($i=1; $i < (int) ceil($list['count'] / 10000); $i++)
                    {
                        $list = $this->getUniversitiesLists($city, $list['items'], ($i * 10000));
                    }
                }
				if( !empty($list['items']) )
				{
					foreach($list['items'] as $item):

						$education = \App\Models\Education::where([
                            ['name', '=', $item['title']],
                            ['city_id', '=', $city->id],
                            ['country_id', '=', $city->country->id]
                        ]);

						if( $education->exists() )
						{
                            if( $education->first()->categories()->where('id', $category->id)->doesntExist() )
                            {
                                $education->first()->categories()->attach($category->id);
                            }

						}else{

                            $category->educations()->create([
                                'name' => $item['title'],
                                'country_id' => $city->country->id,
                                'country_type' => get_class($city->country),
                                'city_id' => $city->id,
                                'city_type' => get_class($city)
                            ]);
                            
						}
					endforeach;
				}
            endforeach;
        }
    }

    public function getUniversitiesLists($city, $collection = [], $offset = 0)
    {
        if( empty($collection) )
        {
            $collection = [];
        }

        if( empty($city) )
        {
            return $collection;
        }

        if( !empty(optional($city->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
        {
            $data = \Illuminate\Support\Facades\Cache::remember('get_vk_api_education_universities_country_' . optional($city->country)->id . '_city_' . $city->id . '_offset_' . $offset, 60*60*24, function() use ($city, $offset){
                $list = [
                    'country_id'  => $city->country->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first()->options['id'],
                    'city_id'  => $city->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first()->options['id'],
                    'offset' => $offset,
                    'count' => 10000,
                    'lang' => 'ru'
                ];
                return (new \VK\Client\VKApiClient(env('VK_API_VERSION'), \VK\Client\Enums\VKLanguage::RUSSIAN))->database()->getSchools(env('VK_API_SECRET_KEY'), $list);
            });
        }else{
            return $collection;
        }

        $collection = array_merge($collection, $data['items']);

        return ['count' => $data['count'], 'items' => $collection];
    }

    public function getSchoolsLists($city, $collection = [], $offset = 0)
    {
        if( empty($collection) )
        {
            $collection = [];
        }

        if( empty($city) )
        {
            return $collection;
        }

        if( !empty(optional($city->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
        {
            $data = \Illuminate\Support\Facades\Cache::remember('get_vk_api_education_country_' . optional($city->country)->id . '_city_' . $city->id . '_offset_' . $offset, 60*60*24, function() use ($city, $offset){
                $list = [
                    'city_id'  => $city->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first()->options['id'],
                    'offset' => $offset,
                    'count' => 10000,
                    'lang' => 'ru'
                ];
                return (new \VK\Client\VKApiClient(env('VK_API_VERSION'), \VK\Client\Enums\VKLanguage::RUSSIAN))->database()->getSchools(env('VK_API_SECRET_KEY'), $list);
            });
        }else{
            return $collection;
        }


        $collection = array_merge($collection, $data['items']);

        return ['count' => $data['count'], 'items' => $collection];
    }
}