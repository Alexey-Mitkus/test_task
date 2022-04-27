<?php

use Illuminate\Database\Seeder;

class CreatePassportCategoriesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreatePassportCategoriesSeeder

        $categories = [
			[
				'name' => 'Идея проекта',
				'description' => null,
				'is_active' => 1,
				'slug' => 'idea',
            ],
			[
				'name' => 'Паспорт проекта',
				'description' => null,
				'is_active' => 1,
				'slug' => 'passport',
            ]
        ];

        if( !empty($categories) )
        {
            foreach ($categories as $category):
                \App\Models\PassportCategory::create($category);
            endforeach;
        }
    }
}
