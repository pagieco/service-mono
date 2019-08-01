<?php

use App\Team;
use Illuminate\Database\Seeder;

class TeamsTableSeeder extends Seeder
{
    /**
     * The fixed team UUID.
     *
     * @var string
     */
    protected static $uuid = 'f40167e6-d0ed-41b3-8eaf-2304fe4ee997';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $team = Team::create([
            'id' => static::$uuid,
            'name' => 'Wildcats',
        ]);
    }

    public static function getWildcatsTeam(): Team
    {
        return Team::findOrFail(static::$uuid);
    }
}
