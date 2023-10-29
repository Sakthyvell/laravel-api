<?php

namespace Database\Factories;

use App\Models\LegalEntity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LegalEntity>
 */
class LegalEntityFactory extends Factory
{

    /**
     * The name of the factory's corresponding model. 
     * @var string 
     */
    protected $model = LegalEntity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'le_tax_number' => $this->faker->numberBetween(),
           'le_name' => $this->faker->company(),
           'le_address' => $this->faker->address(),
        ];
    }
}
