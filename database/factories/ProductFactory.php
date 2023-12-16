<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrayIds = \App\Models\ProductCategory::all()->pluck('id')->toArray();

        $name = $this->faker->name;
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'slug' => $slug,
            'price'=> $this->faker->randomFloat(2, 1, 99),
            'short_description' => $this->faker->text(),
            'description' => $this->faker->text(),
            'qty' => $this->faker->randomNumber(1, false),
            'weight' => $this->faker->randomFloat(2, 1, 10),
            'status' => $this->faker->randomElement([0, 1]),
            'product_category_id' => $this->faker->randomElement($arrayIds),
        ];
    }
}
