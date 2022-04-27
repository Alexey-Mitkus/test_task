<?php

use Illuminate\Database\Seeder;

class CreateOrganizationsCategoriesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateOrganizationsCategoriesSeeder

        $categories = [
			[
				'name' => 'Госпиталь',
				'description' => null,
				'is_active' => 1,
				'slug' => 'hospital',
                'childrens' => [
                    [
                        'name' => 'Ветеранов войн',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                ]
            ],
			[
				'name' => 'Учреждение',
				'description' => null,
				'is_active' => 1,
				'slug' => 'institution',
                'childrens' => [
                    [
                        'name' => 'Научно-исследовательская',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Научно-практическая',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Онкологическая',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                ]
            ],
			[
				'name' => 'Хоспис',
				'description' => null,
				'is_active' => 1,
				'slug' => 'hospice',
                'childrens' => []
            ],
			[
				'name' => 'Центр',
				'description' => null,
				'is_active' => 1,
				'slug' => 'center',
                'childrens' => [
                    [
                        'name' => 'Медицинский',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Планирования семьи и репродукции',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    
                ]
            ],
			[
				'name' => 'Консультация',
				'description' => null,
				'is_active' => 1,
				'slug' => 'consultation',
                'childrens' => [
                    [
                        'name' => 'Женская',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                ]
            ],
			[
				'name' => 'Санаторий',
				'description' => null,
				'is_active' => 1,
				'slug' => 'sanatorium',
                'childrens' => []
            ],
			[
				'name' => 'Поликлиника',
				'description' => null,
				'is_active' => 1,
				'slug' => 'polyclinic',
                'childrens' => [
                    [
                        'name' => 'Взрослая',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Детская',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Стоматологическая',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => [
                            [
                                'name' => 'Взрослая',
                                'description' => null,
                                'is_active' => 1,
                                'slug' => null,
                                'childrens' => []
                            ],
                            [
                                'name' => 'Детская',
                                'description' => null,
                                'is_active' => 1,
                                'slug' => null,
                                'childrens' => []
                            ],
                        ]
                    ],
                ]
            ],
			[
				'name' => 'Больница',
				'description' => null,
				'is_active' => 1,
				'slug' => 'hospital-municipal',
                'childrens' => [
                    [
                        'name' => 'Взрослая',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Детская',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Специализированная',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                ]
            ],
			[
				'name' => 'Диспансер',
				'description' => null,
				'is_active' => 1,
				'slug' => 'dispensary',
                'childrens' => [
                    [
                        'name' => 'Наркологический',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Психоневрологический',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Онкологический',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Противотуберкулезный',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Кожно-венерологический',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                    [
                        'name' => 'Другой',
                        'description' => null,
                        'is_active' => 1,
                        'slug' => null,
                        'childrens' => []
                    ],
                ]
            ],
			[
				'name' => 'Родильный дом',
				'description' => null,
				'is_active' => 1,
				'slug' => 'maternity',
                'childrens' => []
            ],
        ];

        if( !empty($categories) )
        {
            foreach ($categories as $categoryData):

                $category = \App\Models\OrganizationCategory::create([
                    'name' => $categoryData['name'],
                    'description' => ( !empty($categoryData['description']) ? $categoryData['description'] : null ),
                    'is_active' => $categoryData['is_active'],
                    'slug' => ( !empty($categoryData['slug']) ? $categoryData['slug'] : null ),
                ]);

                if( !empty($categoryData['childrens']) )
                {
                    $this->childrensCategories($category, $categoryData['childrens']);
                }
            endforeach;
        }
    }

    public function childrensCategories($parentCategory, $childrens)
    {
        if( empty($childrens) )
        {
            return null;
        }

        if( empty($parentCategory) )
        {
            return null;
        }

        if( !empty($childrens) )
        {
            foreach($childrens as $categoryData):
                $category = $parentCategory->childrens()->create([
                    'name' => $categoryData['name'],
                    'description' => ( !empty($categoryData['description']) ? $categoryData['description'] : null ),
                    'is_active' => $categoryData['is_active'],
                    'slug' => ( !empty($categoryData['slug']) ? $categoryData['slug'] : null ),
                ]);

                if( !empty($categoryData['childrens']) )
                {
                    $this->childrensCategories($category, $categoryData['childrens']);
                }
            endforeach;
        }

        return null;
    }
}
