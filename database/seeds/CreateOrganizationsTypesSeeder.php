<?php

use Illuminate\Database\Seeder;

class CreateOrganizationsTypesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateOrganizationsTypesSeeder
        
        $types = [
			[
				'name' => 'Медицина',
				'description' => null,
				'is_active' => 1,
				'slug' => 'medical',
            ],
			[
				'name' => 'Образование',
				'description' => null,
				'is_active' => 1,
				'slug' => 'education',
            ],
			[
				'name' => 'Прочие',
				'description' => null,
				'is_active' => 1,
				'slug' => 'other',
            ]
        ];

        if( !empty($types) )
        {
            foreach ($types as $type):
                \App\Models\OrganizationType::create($type);
            endforeach;
        }
    }
}
