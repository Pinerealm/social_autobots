<?php

namespace App\Jobs;

use App\Models\Autobot;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Http;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Jobs\Job;

class CreateAutobotsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // public $timeout = 600; // 10 minutes
    public $tries = 3;
    // public $backoff = [60, 120, 180]; // Retry after 1, 2, and 3 minutes

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch 500 users from jsonplaceholder
        Log::info('Creating Autobots...');
        $users = Http::get('https://jsonplaceholder.typicode.com/users')->json();

        // Create 500 Autobots
        for ($i = 0; $i < 500; $i++) {
            $user = $users[$i % count($users)];

            // Ensure unique email by appending a timestamp
            $autobot = Autobot::create([
                'name' => $user['name'],
                'email' => $user['email'] . time() . '@autobot.com',
            ]);
            Log::info("Autobot {$i} created");

            // Fetch 10 posts for each Autobot
            $posts = Http::get('https://jsonplaceholder.typicode.com/posts')->json();

            foreach (array_slice($posts, 0, 10) as $post) {
                // Create the post
                $newPost = Post::create([
                    'autobot_id' => $autobot->id,
                    'title' => $post['title'] . '-' . uniqid(), // Ensuring unique title using uniqid()
                    'body' => $post['body'],
                ]);

                // Fetch 10 comments for each post
                $comments = Http::get('https://jsonplaceholder.typicode.com/comments')->json();

                foreach (array_slice($comments, 0, 10) as $comment) {
                    Comment::create([
                        'post_id' => $newPost->id,
                        'name' => $comment['name'],
                        'email' => $comment['email'] . time() . '@autobot.com',
                        'body' => $comment['body'],
                    ]);
                }
            }
            Log::info("Posts and comments created for Autobot {$i}");
        }
    }

    public function backoff()
    {
        return [10, 30, 60];  // Retry after 10, 30, and 60 seconds
    }
}
