<?php

use Illuminate\Database\Seeder;

class CreateOrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=CreateOrganizationsSeeder

		// $organizations = collect(json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('private/organizations.json')));
		$organizations = collect(json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('private/organization-excel.json')));


        foreach($organizations as $organizationData):

            $organization = \App\Models\Organization::create([
                'name' => $organizationData->name,
                'abbreviation' => $organizationData->abbreviation,
                'country_id' => \App\Models\Country::where('name', 'Россия')->first()->id,
                'city_id' => null,
                'address' => null,
                'raw_country' => null,
                'raw_city' => null,
                'raw_full_address' => $organizationData->raw_full_address,
                'lat' => null,
                'lng' => null,
                'description' => null,
                'slug' => null,
            ]);

            if( !empty($organizationData->type) )
            {
                $organization->types()->attach(\App\Models\OrganizationType::where('name', $organizationData->type)->first()->id);
            }
        endforeach;
    }
}
