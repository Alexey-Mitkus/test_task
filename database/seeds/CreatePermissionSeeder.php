<?php

use Illuminate\Database\Seeder;

class CreatePermissionSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreatePermissionSeeder

        $permissions = [
			[
				'name' => 'role-list',
				'title' => 'Просмотр групп доступа',
			],
			[
				'name' => 'role-create',
				'title' => 'Создание групп доступа',
			],
			[
				'name' => 'role-edit',
				'title' => 'Редактирование групп доступа',
			],
			[
				'name' => 'role-delete',
				'title' => 'Удаление групп доступа',
			],


			[
				'name' => 'user-list',
				'title' => 'Просмотр пользователей',
			],
			[
				'name' => 'user-create',
				'title' => 'Создание пользователей',
			],
			[
				'name' => 'user-edit',
				'title' => 'Редактирование пользователей',
			],
			[
				'name' => 'user-delete',
				'title' => 'Удаление пользователей',
			],


			[
				'name' => 'project-list',
				'title' => 'Просмотр проектов',
			],
			[
				'name' => 'project-create',
				'title' => 'Создание проектов',
			],
			[
				'name' => 'project-edit',
				'title' => 'Редактирование проектов',
			],
			[
				'name' => 'project-delete',
				'title' => 'Удаление проектов',
			],


			[
				'name' => 'news-list',
				'title' => 'Просмотр новостей',
			],
			[
				'name' => 'news-create',
				'title' => 'Создание новостей',
			],
			[
				'name' => 'news-edit',
				'title' => 'Редактирование новостей',
			],
			[
				'name' => 'news-delete',
				'title' => 'Удаление новостей',
			],


			[
				'name' => 'service-list',
				'title' => 'Просмотр цифровых сервисов',
			],
			[
				'name' => 'service-create',
				'title' => 'Создание цифровых сервисов',
			],
			[
				'name' => 'service-edit',
				'title' => 'Редактирование цифровых сервисов',
			],
			[
				'name' => 'service-delete',
				'title' => 'Удаление цифровых сервисов',
			],


			[
				'name' => 'motivation-list',
				'title' => 'Просмотр мотиваций',
			],
			[
				'name' => 'motivation-create',
				'title' => 'Создание мотиваций',
			],
			[
				'name' => 'motivation-edit',
				'title' => 'Редактирование мотиваций',
			],
			[
				'name' => 'motivation-delete',
				'title' => 'Удаление мотиваций',
			],


			[
				'name' => 'speaker-list',
				'title' => 'Просмотр спикеров',
			],
			[
				'name' => 'speaker-create',
				'title' => 'Создание спикеров',
			],
			[
				'name' => 'speaker-edit',
				'title' => 'Редактирование спикеров',
			],
			[
				'name' => 'speaker-delete',
				'title' => 'Удаление спикеров',
			],


			[
				'name' => 'document-list',
				'title' => 'Просмотр документов',
			],
			[
				'name' => 'document-create',
				'title' => 'Создание документов',
			],
			[
				'name' => 'document-edit',
				'title' => 'Редактирование документов',
			],
			[
				'name' => 'document-delete',
				'title' => 'Удаление документов',
			],


			[
				'name' => 'knowledgebase-list',
				'title' => 'Просмотр базы знаний',
			],
			[
				'name' => 'knowledgebase-create',
				'title' => 'Создание базы знаний',
			],
			[
				'name' => 'knowledgebase-edit',
				'title' => 'Редактирование базы знаний',
			],
			[
				'name' => 'knowledgebase-delete',
				'title' => 'Удаление базы знаний',
			],


			[
				'name' => 'digital-passport-list',
				'title' => 'Просмотр цифрового паспорта',
			],
			[
				'name' => 'digital-passport-create',
				'title' => 'Создание цифрового паспорта',
			],
			[
				'name' => 'digital-passport-edit',
				'title' => 'Редактирование цифрового паспорта',
			],
			[
				'name' => 'digital-passport-delete',
				'title' => 'Удаление цифрового паспорта',
			],


			[
				'name' => 'robot-gosha-list',
				'title' => 'Просмотр робота Гоши',
			],
			[
				'name' => 'robot-gosha-create',
				'title' => 'Создание робота Гоши',
			],
			[
				'name' => 'robot-gosha-edit',
				'title' => 'Редактирование  робота Гоши',
			],
			[
				'name' => 'robot-gosha-delete',
				'title' => 'Удаление робота Гоши',
			],


			[
				'name' => 'user-notification-list',
				'title' => 'Просмотр уведомлений пользователя',
			],
			[
				'name' => 'user-notification-create',
				'title' => 'Создание уведомлений пользователя',
			],
			[
				'name' => 'user-notification-edit',
				'title' => 'Редактирование уведомлений пользователя',
			],
			[
				'name' => 'user-notification-delete',
				'title' => 'Удаление уведомлений пользователя',
			],


            [
                'name' => 'cant-exchange-contacts',
                'title' => 'Обмен контактами запрещён'
            ],
            [
                'name' => 'verified',
                'title' => 'Верифицированный пользователь'
            ],
            [
                'name' => 'internal',
                'title' => 'Внутренний сотрудник'
            ],


			[
				'name' => 'notification-users-new',
				'title' => 'Получать уведомления о новых пользователях',
            ],
			[
				'name' => 'notification-knowledgebase-new',
				'title' => 'Получать уведомления о новых добавлених в базу знаний',
            ],


			[
				'name' => 'user-project-list',
				'title' => 'Просмотр команды',
			],
			[
				'name' => 'user-project-create',
				'title' => 'Создание команды',
			],
			[
				'name' => 'user-project-edit',
				'title' => 'Редактирование команды',
			],
			[
				'name' => 'user-project-delete',
				'title' => 'Удаление команды',
			],
        ];

        if( !empty($permissions) )
        {
            foreach ($permissions as $permission):
                \Spatie\Permission\Models\Permission::create([
                    'name' => $permission['name'],
                    'title' => $permission['title']
                ]);
            endforeach;
        }
    }
}
