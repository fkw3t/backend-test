<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('pt_BR');
        $rand = rand(0,1);
        
        if($rand === 0){
            $document_id = str_replace(['-', '.'], '', $faker->cpf);
            $person_type = 'fisical';
        }
        else{
            $document_id = str_replace(['-', '.', '/'], '', $faker->cnpj);
            $person_type = 'legal';
        }

        return [
            'name' => fake()->firstName() . ' ' . fake()->lastName(),
            'person_type' => $person_type,
            'document_id' => $document_id,
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('123'), // password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
