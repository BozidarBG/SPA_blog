<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names=['politics', 'economy', 'nature', 'sport', 'computers', 'smartphones', 'tablets', 'tech', 'cars', 'music', 'movies', 'pets', 'money', 'fitness', 'jobs', 'people', 'world', 'art', 'tattoos', 'food', 'environment', 'crime', 'holiday'];

        for($i=0; $i<count($names); $i++){
            $g=new Genre();
            $g->name = $names[$i];
            $g->description=ucwords($names[$i]).' is lorem ipsum something dolorem etc...';
            $g->save();
        }
    }
}
