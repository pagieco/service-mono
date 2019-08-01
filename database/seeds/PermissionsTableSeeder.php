<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('auth.permissions');

        foreach ($permissions as $slug => $permission) {
            list($name, $description) = $permission;

            Permission::create([
                'slug' => $slug,
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}
