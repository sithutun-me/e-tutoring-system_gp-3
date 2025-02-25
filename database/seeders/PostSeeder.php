<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run():void
    {
        $posts = [
            [
                'post_title' => 'First Post',
                'post_description' => 'This is the first post.',
                'post_status' => 'New',
                'post_create_by' => 13,
                'post_received_by' => 2,
            ],
            [
                'post_title' => 'Second Post',
                'post_description' => 'This is the second post.',
                'post_status' => 'New',
                'post_create_by' => 2,
                'post_received_by' =>13,
            ],
            [
                'post_title' => 'Third Post',
                'post_description' => 'This is the third post.',
                'post_status' => 'New',
                'post_create_by' => 3,
                'post_received_by' => 14,
            ],
            [
                'post_title' => 'Fourth Post',
                'post_description' => 'This is the fourth post.',
                'post_status' => 'New',
                'post_create_by' => 14,
                'post_received_by' => 5,
            ],
            [
                'post_title' => 'Fifth Post',
                'post_description' => 'This is the fifth post.',
                'post_status' => 'New',
                'post_create_by' => 6,
                'post_received_by' => 14,
            ],
        ];

        foreach ($posts as $post) {
            Post::Create($post);
        }
    }
}
