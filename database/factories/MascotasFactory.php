<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mascotas>
 */
class MascotasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_tipo' => $this->faker->numberBetween(1,6),
            'raza' => $this->faker->word(),
            'nombre' => $this->faker->name(),
            'cuidados' => $this->faker->text(50),
            'fecha_nacimiento' => $this->faker->date(),
            'precio' => $this->faker->randomFloat(2, 500, 999),
            'foto' => $this->faker->imageUrl(640, 480, 'animals',true),
        ];
    }
}
