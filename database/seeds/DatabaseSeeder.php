<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(MudFilesTableSeeder::class);
         $this->call(ControllerAccessTableSeeder::class);
         $this->call(ControllersSpecificTableSeeder::class);
         $this->call(DevicesTableSeeder::class);
         $this->call(DomainsTableSeeder::class);
         $this->call(InternetCommunicationTableSeeder::class);
         $this->call(LocalCommunicationTableSeeder::class);
         $this->call(SpecificTypesTableSeeder::class);
    }
}
