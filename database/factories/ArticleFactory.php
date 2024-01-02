<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrayCategoryId = ArticleCategory::all()->pluck('id')->toArray();
        $arrayUserId = User::all()->pluck('id')->toArray();
        
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(200),
            'status' => $this->faker->boolean(),
            'article_category_id' => $this->faker->randomElement($arrayCategoryId),
            'author_id' => $this->faker->randomElement($arrayUserId)
        ];
    }
}
