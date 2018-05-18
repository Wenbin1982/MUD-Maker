<?php

use App\InternetCommunication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InternetCommunicationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('internet_communications')->delete();

        InternetCommunication::create([
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
