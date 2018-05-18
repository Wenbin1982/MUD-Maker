<?php

use App\SpecificType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecificTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('specific_types')->delete();

        SpecificType::create([
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
