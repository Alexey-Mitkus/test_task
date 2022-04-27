<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client AS GuzzleHttpClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

use App\Models\Country;
use App\Models\Region;
use App\Models\City;

class getDataFromVkCom extends Command
{
    protected $signature = 'vk:getData';
    protected $description = 'Getting data from VK.com API';
    protected $_GUZZLE_CLIENT;
    protected $_VK_API;
    protected $_VK_API_SECRET_KEY;
    protected $_VK_API_VERSION;
    protected $_VK_API_LANGUAGE;

    public function __construct()
    {
        $this->_GUZZLE_CLIENT = new GuzzleHttpClient();
        $this->_VK_API_VERSION = env('VK_API_VERSION', '5.131');
        $this->_VK_API_SECRET_KEY = env('VK_API_SECRET_KEY', 'a51c2497a51c2497a51c249750a56af9afaa51ca51c2497c550efafd6596c4112076089');
        $this->_VK_API_LANGUAGE = \VK\Client\Enums\VKLanguage::RUSSIAN;
        $this->_VK_API = new \VK\Client\VKApiClient($this->_VK_API_VERSION, $this->_VK_API_LANGUAGE);
        parent::__construct();
    }

    public function handle()
    {
        $countries = collect([]);

        if( \Illuminate\Support\Facades\Storage::disk('local')->exists('private/country_ISO3166_RU.json') )
        {
            $lists = collect(json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('private/country_ISO3166_RU.json')));

            foreach($lists as $key => $item)
            {
            	$countries->push(json_decode(json_encode([
            		'name' => $item->name_ru,
            		'code' => $item->iso_code2,
            		'is_active' => 1,
            		'slug' => ( !empty($item->slug) ? $item->slug : null )
                ], JSON_UNESCAPED_UNICODE)));
            }
        }
        else
        {
			$countries = collect(json_decode(json_encode([
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
			], JSON_UNESCAPED_UNICODE)));
        }

        if( empty($countries) )
        {
            $this->info('countries list is empty');
            return 0;
        }

        foreach ($countries as $country)
        {
            $getCountry = Cache::remember('get_vk_api_country_' . $country->code, 60*60*24, function() use ($country){

                return $this->_VK_API->database()->getCountries($this->_VK_API_SECRET_KEY, [
                    'code'  => $country->code,
                    'offset'  => 0,
                    'count'  => 1
                ]);

            });

            if( !empty($getCountry['items'][0]['id']) )
            {
                $country_vk_id = $getCountry['items'][0]['id'];

                if( Storage::disk('local')->exists('private/vk-api/' . Str::lower($country->code) . '/country.json') )
                {
                    Storage::disk('local')->delete('private/vk-api/' . Str::lower($country->code) . '/country.json');
                }
                
                $country->id = $country_vk_id;

                Storage::disk('local')->put('private/vk-api/' . Str::lower($country->code) . '/country.json', json_encode($country, JSON_UNESCAPED_UNICODE));
    
                //////////////////////////////////////
                ////    получаем список регионов
                //////////////////////////////////////

                $get_regions = $this->getRegionsFull($country_vk_id, $collection = [], $offset = 0);

                if( !empty($get_regions) && $get_regions['count'] > 1000 )
                {

                    $this->info('Start getting regions ' . $country->code);
                    $bar = $this->output->createProgressBar((int) ceil($get_regions['count'] / 1000) - 1);
                    $bar->start();

                    for ($i=1; $i < (int) ceil($get_regions['count'] / 1000); $i++)
                    {
                        $get_regions = $this->getRegionsFull($country_id = $country_vk_id, $collection = $get_regions['items'], $offset = ($i * 1000));
                        $bar->advance();
                    }

                    $bar->finish();
                    $this->info('Ending getting regions ' . $country->code);
                }

                if( Storage::disk('local')->exists('private/vk-api/' . Str::lower($country->code) . '/regions.json') )
                {
                    Storage::disk('local')->delete('private/vk-api/' . Str::lower($country->code) . '/regions.json');
                }
                $resultSaveRegions = Storage::disk('local')->put('private/vk-api/' . Str::lower($country->code) . '/regions.json', json_encode(['country_id' => $country_vk_id, 'count' => $get_regions['count'], 'items' => $get_regions['items']], JSON_UNESCAPED_UNICODE));
                $this->info('save regions ' . $country->code . ' status: ' . ( $resultSaveRegions ? 'true' : 'false' ) );


                //////////////////////////////////////
                ////    получаем список городов
                //////////////////////////////////////

                $get_cities = $this->getCitiesFull($country_vk_id, $collection = [], $offset = 0);

                if( !empty($get_cities) && $get_cities['count'] > 1000 )
                {

                    $this->info('Start getting cities ' . $country->code);
                    $bar = $this->output->createProgressBar((int) ceil($get_cities['count'] / 1000) - 1);
                    $bar->start();

                    for ($i=1; $i < (int) ceil($get_cities['count'] / 1000); $i++)
                    {
                        $get_cities = $this->getCitiesFull($country_id = $country_vk_id, $collection = $get_cities['items'], $offset = ($i * 1000));
                        $bar->advance();
                    }

                    $bar->finish();
                    $this->info('Ending getting cities ' . $country->code);
                }

                if( Storage::disk('local')->exists('private/vk-api/' . Str::lower($country->code) . '/cities.json') )
                {
                    Storage::disk('local')->delete('private/vk-api/' . Str::lower($country->code) . '/cities.json');
                }

                $resultSaveCities = Storage::disk('local')->put('private/vk-api/' . Str::lower($country->code) . '/cities.json', json_encode(['country_id' => $country_vk_id, 'count' => $get_cities['count'], 'items' => $get_cities['items']], JSON_UNESCAPED_UNICODE));

                $this->info('save cities ' . $country->code . ' status: ' . ( $resultSaveCities ? 'true' : 'false' ) );

            }
        }

        return 0;
    }

    public function getRegionsFull($country_id, $collection = [], $offset = 0)
    {
        if( empty($collection) )
        {
            $collection = [];
        }

        if( empty($country_id) )
        {
            return $collection;
        }

        $data = Cache::remember('get_vk_api_country_' . $country_id . '_regions_offset_' . $offset, 60*60*24, function() use ($country_id, $offset){

            return $this->_VK_API->database()->getRegions($this->_VK_API_SECRET_KEY, [
                'country_id'  => $country_id,
                'offset' => $offset,
                'count' => 1000
            ]);

        });

        $collection = array_merge($collection, $data['items']);

        return ['count' => $data['count'], 'items' => $collection];
    }

    public function getCitiesFull($country_id, $collection = [], $offset = 0)
    {
        if( empty($collection) )
        {
            $collection = [];
        }

        if( empty($country_id) )
        {
            return $collection;
        }

        $data = Cache::remember('get_vk_api_country_' . $country_id . '_cities_offset_' . $offset, 60*60*24, function() use ($country_id, $offset){

            return $this->_VK_API->database()->getCities($this->_VK_API_SECRET_KEY, [
                'country_id'  => $country_id,
                'region_id' => '',
                'need_all' => 1,
                'offset' => $offset,
                'count' => 1000
            ]);

        });

        $collection = array_merge($collection, $data['items']);

        return ['count' => $data['count'], 'items' => $collection];
    }
}
