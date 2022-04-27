<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // CreateCountriesSeeder::class,
            CreateRegionsCitiesSeeder::class,
            CreatePermissionSeeder::class,
            CreateRolesSeeder::class,
            CreateUserFieldSeeder::class,
            CreateUsersManagmentsRolesSeeder::class,
            CreateEducationCategoriesSeeder::class,
            CreateEducationsSeeder::class,
            CreatePassportCategoriesSeeder::class,
            CreateOrganizationsTypesSeeder::class,
            CreateOrganizationsCategoriesSeeder::class,
            CreateOrganizationsSeeder::class,
            CreateEventLogCategoriesSeeder::class,

            
            CreateAdministratorSeeder::class,

            // KnowledgeBaseSeeder::class,
        ]);
    }
}
