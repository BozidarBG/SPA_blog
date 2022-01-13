<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=new User();
        $user->name = 'Master Admin';
        $user->email='bozidar.djordjevic95@gmail.com';
        $user->role=1;
        $user->password=Hash::make('Ii123456/');
        $user->email_verified_at=\Carbon\Carbon::now();
        $user->save();
    }
}
