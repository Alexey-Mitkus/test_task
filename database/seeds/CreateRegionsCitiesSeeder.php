<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CreateRegionsCitiesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateRegionsCitiesSeeder

        $countries = \App\Models\Country::all();

        if( $countries->count() )
        {
            foreach($countries as $ckey => $country):
                
                if( Storage::disk('local')->exists('private/headhunter-api/' . Str::lower($country->code) . '/regions.json') )
                {
                    $regions = collect(json_decode(Storage::disk('local')->get('private/headhunter-api/' . Str::lower($country->code) . '/regions.json')));
                    if( !empty($regions['items']) )
                    {
                        $regions['items'] = collect($regions['items']);
                    }
                    if( !empty($regions['items']) )
                    {
                        foreach($regions['items'] as $arkey => $area):
                            $area->name = preg_replace('/\([^)]+\)/','', $area->name);
                            $area->name = (string) Str::of($area->name)->trim();
                            if( !empty($area->areas) )
                            {
                                $newRegion = \App\Models\Region::updateOrCreate(
                                    [
                                        'name' => $area->name,
                                        'country_id' => $country->id,
                                        'country_type' => get_class($country),
                                    ],
                                    [
                                        'name' => $area->name,
                                        'country_id' => $country->id,
                                        'country_type' => get_class($country),
                                        'is_active' => 1,
                                    ]
                                );
                                foreach($area->areas as $cckey => $city):
                                    $city->name = preg_replace('/\([^)]+\)/','', $city->name);
                                    $city->name = (string) \Illuminate\Support\Str::of($city->name)->trim();
                                    \App\Models\City::updateOrCreate(
                                        [
                                            'name' => $city->name,
                                            'country_id' => $country->id,
                                            'country_type' => get_class($country),
                                            'region_id' => $newRegion->id,
                                            'region_type' => get_class($newRegion),
                                        ],
                                        [
                                            'name' => $city->name,
                                            'country_id' => $country->id,
                                            'country_type' => get_class($country),
                                            'region_id' => $newRegion->id,
                                            'region_type' => get_class($newRegion),
                                            'is_active' => 1,
                                        ]
                                    );
                                endforeach;
                            }else{
                                \App\Models\City::updateOrCreate(
                                    [
                                        'name' => $area->name,
                                        'country_id' => $country->id,
                                        'country_type' => get_class($country),
                                        'region_id' => null,
                                        'region_type' => null,
                                    ],
                                    [
                                        'name' => $area->name,
                                        'country_id' => $country->id,
                                        'country_type' => get_class($country),
                                        'region_id' => null,
                                        'region_type' => null,
                                        'is_active' => 1,
                                    ]
                                );
                            }
                        endforeach;
                    }
                }

            endforeach;
        }
    
        $regions = \App\Models\Region::all();

        if( !empty($regions) )
        {
            foreach($regions as $region):
                
                if( !empty(optional($region->country->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
                {
                    $regionData = Cache::remember('get_vk_api_country_' . $region->country->id . '_region_' . $region->id, 60*60*24, function() use ($region){
                        $list = [
                            'country_id'  => $region->country->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first()->options['id'],
                            'q' => $region->name,
                            'offset' => 0,
                            'count' => 1
                        ];
                        return (new \VK\Client\VKApiClient(env('VK_API_VERSION'), \VK\Client\Enums\VKLanguage::RUSSIAN))->database()->getRegions(env('VK_API_SECRET_KEY'), $list);
                    });

                    if( !empty($regionData['items'][0]['id']) )
                    {
                        if( $regionData['items'][0]['title'] == $region->name )
                        {
                            $region->api()->updateOrCreate(
                                [
                                    'type_id' => \App\Models\ApiService::CONST_TYPE_VK_COM
                                ],
                                [
                                    'options' => $regionData['items'][0]
                                ]
                            );
                        }
                    }
				}
            endforeach;
        }

        $cities = \App\Models\City::all();

        if( !empty($cities) )
        {
            foreach($cities as $city):

                if( !empty(optional($city->country->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
                {
                    $cityData = Cache::remember('get_vk_api_country_' . $city->country->id . '_region_' . optional($city->region)->id . '_city_' . $city->id, 60*60*24, function() use ($city){
                        $list = [
                            'country_id'  => $city->country->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first()->options['id'],
                            'q' => $city->name,
                            'need_all' => 1,
                            'offset' => 0,
                            'count' => 1
                        ];
                        
                        if( !empty($city->region) && !empty(optional($city->region->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first())->options) )
                        {
                            $list['region_id'] = $city->region->api()->where('type_id', \App\Models\ApiService::CONST_TYPE_VK_COM)->first()->options['id'];
                        }

                        return (new \VK\Client\VKApiClient(env('VK_API_VERSION'), \VK\Client\Enums\VKLanguage::RUSSIAN))->database()->getCities(env('VK_API_SECRET_KEY'), $list);
                    });

                    if( !empty($cityData['items'][0]['id']) )
                    {
                        if( $cityData['items'][0]['title'] == $city->name )
                        {
                            $city->api()->updateOrCreate(
                                [
                                    'type_id' => \App\Models\ApiService::CONST_TYPE_VK_COM
                                ],
                                [
                                    'options' => $cityData['items'][0]
                                ]
                            );
                        }
                    }
				}

            endforeach;
        }
    }
}
