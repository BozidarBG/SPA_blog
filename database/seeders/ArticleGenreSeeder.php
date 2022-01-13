<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ArticleGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id_arr=[];
        for($x=1; $x<=300; $x++){
            $random_number=random_int(0,4);

            for($i=0; $i<$random_number; $i++){
                $id=random_int(1,24);

                if(!in_array($id, $id_arr)){
                    \App\Models\ArticleGenre::create(['genre_id'=>$id, 'article_id'=>$x,]);
                    $id_arr[]=$id;
                }


            }
            $id_arr=[];
        }
    }
}
