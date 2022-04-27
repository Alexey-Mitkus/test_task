<?php

use Illuminate\Database\Seeder;

class CreateEducationCategoriesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateEducationCategoriesSeeder

        $categories = [
			[
				'name' => 'Высшее',
                'description' => null,
                'slug' => 'high',
                'is_active' => 1
            ],
			[
				'name' => 'Среднее профессиональное',
                'description' => null,
                'slug' => 'middle-professional',
                'is_active' => 1
            ],
			[
				'name' => 'Курсы / Повышение квалификации',
                'description' => null,
                'slug' => 'advanced',
                'is_active' => 1
			]
        ];

        if( !empty($categories) )
        {
            foreach ($categories as $category):
                \App\Models\EducationCategory::create($category);
            endforeach;
        }
    }
}
