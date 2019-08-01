<?php

use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role([
            'name' => 'Super Admin',
            'description' => '!!(For internal use only)!!',
        ]);

        $role->team()->associate(TeamsTableSeeder::getWildcatsTeam());
        $role->save();

        Permission::all()->each(function (Permission $permission) use ($role) {
            $permission->roles()->attach($role);
        });

        UsersTableSeeder::getDemoUser()->roles()->attach($role);
    }
}
