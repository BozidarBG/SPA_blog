<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'content'=>$this->faker->sentence(20, true),
            'user_id'=>random_int(1,34),
            'article_id'=>random_int(1,300),
            
        ];
    }
}
