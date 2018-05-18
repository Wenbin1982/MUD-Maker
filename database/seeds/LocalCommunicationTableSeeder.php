<?php

use App\LocalCommunication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalCommunicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('local_communications')->delete();

        LocalCommunication::create([
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
