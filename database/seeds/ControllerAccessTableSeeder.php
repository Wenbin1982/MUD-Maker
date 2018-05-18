<?php

use App\ControllerAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ControllerAccessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('controller_accesses')->delete();

        ControllerAccess::create([
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
