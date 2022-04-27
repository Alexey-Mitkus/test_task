<?php

use Illuminate\Database\Seeder;

class CreateEventLogCategoriesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateEventLogCategoriesSeeder

        $categories = [
			[
				'name' => 'зарегистрировался(лась) на сайте',
                'description' => 'Пользователь зарегистрировался на сайте',
                'is_active' => 1,
                'slug' => 'registration'
            ],
			[
				'name' => 'подтвердил(а) адрес электронной почты и <b>ожидает верификации</b>',
                'description' => 'Пользователь подтвердил почту. Ожидает верификации.',
                'is_active' => 1,
                'slug' => 'verify-email'
            ],
			[
				'name' => 'заполнил(а) профиль (более 80%)',
                'description' => 'Пользователь заполнил профиль как минимум на 80%',
                'is_active' => 1,
                'slug' => 'completed-profile'
            ],
			[
				'name' => '<b>верифицирован</b> администратором',
                'description' => 'Пользователь верифицирован одним из администраторов',
                'is_active' => 1,
                'slug' => 'verify-admin'
            ],
			[
				'name' => 'Получил(а) роль «Участник сообщества»',
                'description' => 'Пользователь получил роль Участник сообщества',
                'is_active' => 1,
                'slug' => 'get-role-community-member'
            ],
			[
				'name' => 'запросил(а) обмен контактами с',
                'description' => 'Пользователь запросил обмен контактами',
                'is_active' => 1,
                'slug' => 'send-requested-exchange-contacts'
            ],
			[
				'name' => 'изменил(а) запрос обмена контактами с',
                'description' => 'Пользователь изменил запрос обмена контактами',
                'is_active' => 1,
                'slug' => 'modified-requested-exchange-contacts'
            ],
			[
				'name' => 'принял(а) запрос на обмен контактами от',
                'description' => 'Пользователь принял запрос на обмен контактами',
                'is_active' => 1,
                'slug' => 'accept-requested-exchange-contacts'
            ],
			[
				'name' => 'изменил следующее поле в персональном профиле:',
                'description' => 'Пользователь изменил определённое поле в профиле',
                'is_active' => 1,
                'slug' => 'edit-profile-fields'
            ],
        ];

        if( !empty($categories) )
        {
            foreach ($categories as $category):
                \App\Models\EventLogCategory::create($category);
            endforeach;
        }
    }
}
