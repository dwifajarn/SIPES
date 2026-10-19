<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(Message::TYPES);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => ucfirst($type).': '.fake()->sentence(4),
            'body' => fake()->sentence(12),
            'code' => null,
            'url' => null,
            'is_read' => fake()->boolean(40),
        ];
    }

    public function error(): static
    {
        return $this->state([
            'type' => Message::TYPE_ERROR,
            'code' => 422,
        ]);
    }

    public function belumDibaca(): static
    {
        return $this->state(['is_read' => false]);
    }
}
