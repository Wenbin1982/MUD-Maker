<?php


use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->delete();

        User::create([
            'id' => 1,
            'name' => 'Ivan',
            'email' => 's.ivankorobchuk@gmail.com',
            'password' => Hash::make('123123'),
            'isAdmin' => 1,
            'api_token' => str_random(60),
            'registration_key' => null,
            'registration_company' => null,
            'forgot_password_key' => null
        ]);
        User::create([
            'id' => 2,
            'name' => 'servatko',
            'email' => 'yservatko@gbsfo.com',
            'password' => Hash::make('123123'),
            'isAdmin' => 1,
            'api_token' => str_random(60),
            'registration_key' => null,
            'registration_company' => null,
            'forgot_password_key' => null
        ]);
        User::create([
            'id' => 3,
            'name' => 'Pasha',
            'email' => 's.ivankorobchuk@cisco.com',
            'password' => Hash::make('123123'),
            'isAdmin' => 1,
            'api_token' => str_random(60),
            'registration_key' => null,
            'registration_company' => null,
            'forgot_password_key' => null
        ]);
    }
}
