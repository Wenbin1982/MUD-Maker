<?php

use App\ControllersSpecific;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ControllersSpecificTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('controllers_specifics')->delete();

        ControllersSpecific::create([
            'id' => 1,
            'name' => 'name',
            'portl' => 'Any',
            'port' => 'Any',
            'initiatedBy' => 'either',
            'protocolSelect' => 'any',
            'json_id' => 1,
        ]);
    }
}
