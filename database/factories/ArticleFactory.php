<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title=$this->faker->sentence(10, true);
        return [
            'title'=>$title,
            'slug'=>Str::slug($title),
            'body'=>$this->faker->paragraph(20, true),
            'user_id'=>random_int(1,34),
            
        ];
    
    }
}
