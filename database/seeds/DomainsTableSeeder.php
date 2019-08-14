<?php

use App\Domain;
use Illuminate\Database\Seeder;

class DomainsTableSeeder extends Seeder
{
    /**
     * The fixed domain UUID.
     *
     * @var string
     */
    protected static $uuid = '49a2f0df-3cce-4855-9df1-94a913ef8787';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $environment = EnvironmentsTableSeeder::getProductionEnvironment();

        $domain = new Domain([
            'id' => static::$uuid,
            'domain_name' => sprintf('demo.pagie.local', $environment->slug),
        ]);

        $domain->team()->associate(TeamsTableSeeder::getWildcatsTeam());
        $domain->environment()->associate(EnvironmentsTableSeeder::getProductionEnvironment());

        $domain->save();
    }

    public static function getDemoDomain(): Domain
    {
        return Domain::findOrFail(static::$uuid);
    }
}
