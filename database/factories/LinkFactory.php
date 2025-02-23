<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => clean($this->faker->name),
            'link' => $this->faker->url,
        ];
    }
}