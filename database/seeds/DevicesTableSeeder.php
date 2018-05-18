<?php

use App\Device;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('devices')->delete();

        Device::create([
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
