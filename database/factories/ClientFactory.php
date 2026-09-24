<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
#[UseModel(Client::class)]
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'site' => $this->faker->unique()->domainName(),
            'email' => $this->faker->unique()->safeEmail(),
            'ip' => $this->faker->ipv4(),
            'is_active' => $this->faker->boolean()
        ];
    }
}
