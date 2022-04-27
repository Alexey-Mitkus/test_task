<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client AS GuzzleHttpClient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CreateCountriesSeeder extends Seeder
{
    public function run()
    {
		// php artisan db:seed --class=CreateCountriesSeeder

        if( \Illuminate\Support\Facades\Storage::disk('local')->exists('private/country_ISO3166_RU.json') )
        {
            $countries = collect(json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('private/country_ISO3166_RU.json')));

            foreach($countries as $key => $item)
            {

            	$country = \App\Models\Country::create([
            		'name' => $item->name_ru,
            		'code' => $item->iso_code2,
            		'is_active' => 1,
            		'slug' => ( !empty($item->slug) ? $item->slug : null )
            	]);
				
				$getCountry = Cache::remember('get_vk_api_country_' . $country->code, 60*60*24, function() use ($country){
					return (new \VK\Client\VKApiClient(env('VK_API_VERSION'), \VK\Client\Enums\VKLanguage::RUSSIAN))->database()->getCountries(env('VK_API_SECRET_KEY'), [
						'code'  => $country->code,
						'offset'  => 0,
						'count'  => 1
					]);
				});

				if( !empty($getCountry['items'][0]['id']) )
				{
					$country->api()->updateOrCreate(
						[
							'type_id' => \App\Models\ApiService::CONST_TYPE_VK_COM
						],
						[
							'options' => $getCountry['items'][0]
						]
					);
				}
            }
        }
        else
        {
			$countries = [
				[
					'name' => 'Россия',
					'code' => 'RU',
					'is_active' => 1,
					'slug' => 'russia',
				],
				[
					'name' => 'Украина',
					'code' => 'UA',
					'is_active' => 1,
					'slug' => 'ukraine',
				],
				[
					'name' => 'Беларусь',
					'code' => 'BY',
					'is_active' => 1,
					'slug' => 'belarus',
				],
				[
					'name' => 'Казахстан',
					'code' => 'KZ',
					'is_active' => 1,
					'slug' => 'kazakhstan',
				]
			];

			foreach ($countries as $key => $countryData)
            {
                $country = \App\Models\Country::create($countryData);

				$getCountry = Cache::remember('get_vk_api_country_' . $country->code, 60*60*24, function() use ($country){
					return (new \VK\Client\VKApiClient(env('VK_API_VERSION'), \VK\Client\Enums\VKLanguage::RUSSIAN))->database()->getCountries(env('VK_API_SECRET_KEY'), [
						'code'  => $country->code,
						'offset'  => 0,
						'count'  => 1
					]);
				});

				if( !empty($getCountry['items'][0]['id']) )
				{
					$country->api()->updateOrCreate(
						[
							'type_id' => \App\Models\ApiService::CONST_TYPE_VK_COM
						],
						[
							'options' => $getCountry['items'][0]
						]
					);
				}
			}
        }
    }
}
