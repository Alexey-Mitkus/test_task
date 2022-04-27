<?php

use Illuminate\Database\Seeder;

class CreateUsersManagmentsRolesSeeder extends Seeder
{
    public function run()
    {
        //php artisan db:seed --class=CreateUsersManagmentsRolesSeeder

        $roles = [
			[
				'name' => 'Куратор проектной деятельности',
				'description' => null,
				'is_active' => 1,
                'slug' => null,
            ],
			[
				'name' => 'Руководитель проектов',
				'description' => null,
				'is_active' => 1,
                'slug' => null,
            ],
			[
				'name' => 'Участник проектной деятельности',
				'description' => null,
				'is_active' => 1,
                'slug' => null,
            ],
			[
				'name' => 'Участник проектной команды',
				'description' => null,
				'is_active' => 1,
                'slug' => null,
            ],
			[
				'name' => 'Новичок в проектной деятельности',
				'description' => null,
				'is_active' => 1,
                'slug' => null,
            ],
			[
				'name' => 'Нет опыта',
				'description' => null,
				'is_active' => 1,
                'slug' => null,
            ]
        ];

        if( !empty($roles) )
        {
            foreach ($roles as $ufield):
                $field = \App\Models\UserManagmentRole::create($ufield);
            endforeach;
        }

    }
}
