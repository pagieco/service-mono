<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PassportTablesSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(EnvironmentsTableSeeder::class);
        $this->call(DomainsTableSeeder::class);
        $this->call(AssetsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(ProfilesTableSeeder::class);
    }
}
