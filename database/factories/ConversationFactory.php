<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conversation>
 */
class ConversationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeId = $this->faker->randomElement([1, 2]);
        $randomUser = User::inRandomOrder()->first();

        return [
            'conversation_type_id' => $typeId,
            'name' => $this->faker->sentence(3),
            'user_id' => $randomUser ? $randomUser->id : 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
