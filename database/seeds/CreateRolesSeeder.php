<?php

use Illuminate\Database\Seeder;

class CreateRolesSeeder extends Seeder
{
    public function run()
    {
        // php artisan db:seed --class=CreateRolesSeeder

		$roles = [
			[
				'name' => 'admin',
                'title' => 'Администратор',
                'permissions' => \Spatie\Permission\Models\Permission::whereNotIn('name', ['cant-exchange-contacts', 'verified','internal'])->get()
            ],
			// [
			// 	'name' => 'teams-admin',
            //     'title' => 'Администратор команды',
            //     'permissions' => \Spatie\Permission\Models\Permission::whereIn('name', ['user-project-list', 'user-project-create', 'user-project-edit', 'user-project-delete'])->get()
            // ],
			// [
			// 	'name' => 'teams-manager',
            //     'title' => 'Руководитель команды',
            //     'permissions' => \Spatie\Permission\Models\Permission::whereIn('name', ['user-project-list', 'user-project-create'])->get()
            // ],
			// [
			// 	'name' => 'teams-lead',
            //     'title' => 'Лидер команды',
            //     'permissions' => \Spatie\Permission\Models\Permission::whereIn('name', ['user-project-list', 'user-project-create'])->get()
            // ],
			// [
			// 	'name' => 'teams-participant',
            //     'title' => 'Участник команды',
            //     'permissions' => \Spatie\Permission\Models\Permission::whereIn('name', ['user-project-list'])->get()
            // ],
			// [
			// 	'name' => 'teams-project-participant',
            //     'title' => 'Участник проекта',
            //     'permissions' => \Spatie\Permission\Models\Permission::whereIn('name', ['user-project-list'])->get()
            // ],
			// [
			// 	'name' => 'teams-project-coordinator',
            //     'title' => 'Координатор проекта',
            //     'permissions' => \Spatie\Permission\Models\Permission::whereIn('name', ['user-project-list', 'user-project-edit'])->get()
            // ]
		];

        if( !empty($roles) )
        {
            foreach($roles as $role):
                $roleSelect = \Spatie\Permission\Models\Role::create([
                    'name' => $role['name'],
                    'title' => $role['title']
                ]);

                if( !empty($role['permissions']) )
                {
                    $roleSelect->syncPermissions($role['permissions']);
                }
            endforeach;
        }
    }
}
