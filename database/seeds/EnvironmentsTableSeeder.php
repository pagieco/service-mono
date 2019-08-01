<?php

use App\Environment;
use Illuminate\Database\Seeder;

class EnvironmentsTableSeeder extends Seeder
{
    /**
     * The fixed environment UUID.
     *
     * @var string
     */
    protected static $uuid = '32f1743c-69cf-4d06-a93b-da07be17ad17';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $environment = new Environment([
            'id' => static::$uuid,
            'name' => 'Production',
        ]);

        $environment->team()->associate(TeamsTableSeeder::getWildcatsTeam());

        $environment->save();
    }

    public static function getProductionEnvironment()
    {
        return Environment::findOrFail(static::$uuid);
    }
}
