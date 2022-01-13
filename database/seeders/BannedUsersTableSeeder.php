<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BannedUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<6; $i++){
            $u=new \App\Models\BannedUser();
            $u->user_id=$i;
            $u->reason="User is banned for writingg bad stuff.";
            $u->banned_by=1;
            $u->until=Carbon::now()->addDays($i);
            $u->save();
        }
    }
}
