<?php

use Illuminate\Database\Seeder;

class CreateUserFieldSeeder extends Seeder
{

    public function run()
    {
        //php artisan db:seed --class=CreateUserFieldSeeder

        $fields = [
			[
				'name' => 'Имя',
				'type_id' => 100,
				'options' => [],
				'group_id' => 0,
				'is_active' => 1,
				'slug' => 'first_name',
            ],
			[
				'name' => 'Фамилия',
				'type_id' => 100,
				'options' => [],
				'group_id' => 0,
				'is_active' => 1,
				'slug' => 'last_name',
            ],
			[
				'name' => 'Отчество',
				'type_id' => 100,
				'options' => [],
				'group_id' => 0,
				'is_active' => 1,
				'slug' => 'middle_name',
            ],
			[
				'name' => 'Пол',
				'type_id' => 100,
				'options' => [],
				'group_id' => 0,
				'is_active' => 1,
				'slug' => 'sex',
            ],
			[
				'name' => 'Дата рождения',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => false
					]
				],
				'group_id' => 0,
				'is_active' => 1,
				'slug' => 'birth_date',
            ],


			[
				'name' => 'Номер телефона',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => true
					]
				],
				'group_id' => 1,
				'is_active' => 1,
				'slug' => 'telephone',
            ],
			[
				'name' => 'Skype',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => true
					]
				],
				'group_id' => 1,
				'is_active' => 1,
				'slug' => 'skype',
            ],
			[
				'name' => 'Facebook',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => true
					]
				],
				'group_id' => 2,
				'is_active' => 0,
				'slug' => 'facebook',
            ],
			[
				'name' => 'Вконтакте',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => true
					]
				],
				'group_id' => 2,
				'is_active' => 1,
				'slug' => 'vkontakte',
            ],
			[
				'name' => 'Instagram',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => true
					]
				],
				'group_id' => 2,
				'is_active' => 0,
				'slug' => 'instagram',
            ],
			[
				'name' => 'Личный сайт',
				'type_id' => 100,
				'options' => [
					'points' => [
						'value' => 10,
						'group' => true
					]
				],
				'group_id' => 2,
				'is_active' => 1,
				'slug' => 'website',
            ]
        ];

        if( !empty($fields) )
        {
            foreach ($fields as $ufield):
                $field = \App\Models\UserField::create($ufield);
            endforeach;
        }

    }
}
