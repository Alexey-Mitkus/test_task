<?php

use Illuminate\Database\Seeder;

class CreateAdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // php artisan db:seed --class=CreateAdministratorSeeder

        $users = [
            [
                'first_name' => 'сообщество',
                'last_name' => 'Проектное',
                'email' => env('MAIL_ADMINISTRATOR_EMAIL'),
                'password' => '*BH41$wDKm?|i{',
                'roles' => [],
                'permissions' => [],
                'delete' => true
            ]
        ];

        $UsersFields = \App\Models\UserField::all();

        if( !empty($users) )
        {
            foreach($users as $key => $udata):

                $user = \App\Models\User::create([
                    'email' => $udata['email'],
                    'api_token' => \Illuminate\Support\Str::random(60),
                    'password' => bcrypt($udata['password']),
                ]);


                if( !empty($udata['first_name']) )
                {
                    $field = $UsersFields->where('slug', 'first_name')->first();
                    $user->fields()->attach($field->id, [
                        'value' => $udata['first_name'],
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => 0
                    ]);
                }

                if( !empty($udata['last_name']) )
                {
                    $field = $UsersFields->where('slug', 'last_name')->first();
                    $user->fields()->attach($field->id, [
                        'value' => $udata['last_name'],
                        'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                        'is_show' => 0
                    ]);
                }

                $user->markEmailAsVerified();

                if( !empty($udata['roles']) )
                {
                    $user->assignRole(\Spatie\Permission\Models\Role::whereIn('name', $udata['roles'])->get());
                }

                if( !empty($udata['permissions']) )
                {
                    $user->givePermissionTo(\Spatie\Permission\Models\Permission::whereIn('name', $udata['permissions'])->get());
                }

                if( !empty($udata['delete']) && $udata['delete'] == true )
                {
                    $user->delete();
                }
                
                
            endforeach;
        }
    }
}
