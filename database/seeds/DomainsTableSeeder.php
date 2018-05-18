<?php

use App\Domain;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('domains')->delete();

        Domain::create([
            'id' => 1,
            'domain' => 'gmail.com',
            'company' => 'gmail.com',
            'user_id' => 1,
        ]);
    }
}
