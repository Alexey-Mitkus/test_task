<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client AS GuzzleHttpClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;

class getDataFromHeadHunterRu extends Command
{
    protected $signature = 'hh:getData';
    protected $description = 'Getting data from HeadHunter API';
    protected $_GUZZLE_CLIENT;

    public function __construct()
    {
        $this->_GUZZLE_CLIENT = new GuzzleHttpClient();
        parent::__construct();
    }
    
    public function handle()
    {
        
        $countries = Country::active()->pluck('name');
        $responseRegions = $this->_GUZZLE_CLIENT->request('GET', 'https://api.hh.ru/areas');
        $regions = collect(json_decode((string) $responseRegions->getBody()));

        if( !$countries->count() )
        {
            Artisan::call('db:seed', [
                '--class' => 'CreateCountriesSeeder'
            ]);

            $countries = Country::active()->pluck('name');
        }

        if( $countries->count() && $regions->count() )
        {
            $this->info('============= Start getting regions and cities data from HeadHunter API =============');
            foreach($regions->whereIn('name', $countries) as $ckey => $region):

                $country = Country::where('name', $region->name)->first();
    
                if( Storage::disk('local')->exists('private/headhunter-api/' . Str::lower($country->code) . '/regions.json') )
                {
                    Storage::disk('local')->delete('private/headhunter-api/' . Str::lower($country->code) . '/regions.json');
                }
    
                $resultSaveRegions = Storage::disk('local')->put('private/headhunter-api/' . Str::lower($country->code) . '/regions.json', json_encode(['country_id' => $country->id, 'count' => count($region->areas), 'items' => $region->areas], JSON_UNESCAPED_UNICODE));
    
                $this->info('save regions ' . $country->code . ' status: ' . ( $resultSaveRegions ? 'true' : 'false' ) );
    
            endforeach;
            $this->info('============= END getting regions and cities data from HeadHunter API =============');
        }else{
            $this->info('HeadHunter API not available or countries list is empty');
        }

        
        return 0;
    }
}
