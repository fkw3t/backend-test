<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Seller>
 */
class SellerFactory extends Factory
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
            'name' => fake()->name(),
            'person_type' => $person_type,
            'document_id' => $document_id,
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('123'), // password
        ];
    }
}
