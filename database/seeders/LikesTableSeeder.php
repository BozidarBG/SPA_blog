<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<34;$i++){
            $arrOfLiked=[];
            for($x=1; $x<11; $x++){
                $randomId=random_int(1,300);
                if(!in_array($randomId, $arrOfLiked)){
                    $like=new \App\Models\Like();
                    $like->user_id=$i;
                    $like->article_id=$randomId;
                    $like->save();
                    $arrOfLiked[]=$randomId;
                }
            }
        }
    }
}
