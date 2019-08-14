<?php

use App\Page;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = new Page([
            'slug' => '/',
            'name' => 'Homepage',
            'dom' => file_get_contents(resource_path('demo-page.html'))
        ]);

        $page->team()->associate(TeamsTableSeeder::getWildcatsTeam());
        $page->domain()->associate(DomainsTableSeeder::getDemoDomain());

        $page->save();
    }
}
