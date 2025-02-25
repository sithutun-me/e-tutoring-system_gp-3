<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Comment::class;
    public function definition(): array
    {
        return [
            'text'=>$this->faker->sentence,
            'post_id'=> Post::inRandomOrder()->first()->id ?? Post::factory()->create()->id,
            'user_id'=> User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
        ];
    }
}
