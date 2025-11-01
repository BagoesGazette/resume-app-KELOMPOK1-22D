<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('cobadiuji'),
            'email_verified_at' => Carbon::now()
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'name'      => 'Oskar Sihombing',
            'email'     => 'oskar@gmail.com',
            'password'  => bcrypt('cobadiuji'),
            'email_verified_at' => Carbon::now()
        ]);

        $user->assignRole('kandidat');
    }
}