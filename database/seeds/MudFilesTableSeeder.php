<?php

use App\MudFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MudFilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mud_files')->delete();

        MudFile::create([
            'id' => 1,
            'manufacturer' => 'apple',
            'domain' => 'gmail.com',
            'delete_name_user' => null,
            'model' => 'apple',
            'deviceType' => 'deviceType',
            'software' => '123',
            'deviceSelect' => 'both',
            'path' => '/',
            'nameMudFile' => 'tsconfig.json',
            'link_mud_file' => null,
            'user_id' => 1,
            'last_update_user_id' => 1,
        ]);
    }
}
