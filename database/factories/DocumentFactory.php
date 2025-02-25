<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Document;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'doc_file_path' => $this->faker->filePath(),
            'doc_size' => $this->faker->numberBetween(1000, 100000), // Random size between 1KB and 100KB
            'doc_name' => $this->faker->word . '.pdf', // Example: "document.pdf"
            'post_id' => Post::inRandomOrder()->first()->id ?? Post::factory()->create()->id, // Associate with a Post
        ];
    }
}
