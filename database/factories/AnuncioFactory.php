<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AnuncioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // 'dni_donante' => $this->faker->name(),
            'dni_donante' => "12345671",
            'fecha_anuncio' => now(),
            'fecha_vencimiento' => now(),
            'nombre' => $this->faker->firstName(),
            'presentacion' => Str::random(8),
            'concentracion' => Str::random(2),
            'descripcion' => $this->faker->sentence(4, false),
            'cantidad' => $this->faker->randomNumber(1, true),
            'requiere_receta' => $this->faker->boolean,
            'requiere_diagnostico' => $this->faker->boolean,
            'activo' => true,
        ];
    }
}
