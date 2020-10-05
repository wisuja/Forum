<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $threads = Thread::factory(50)->create();
        $threads->each(function ($thread) {
            Reply::factory(10)->create(['thread_id' => $thread->id]);
        });

        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$.l6nAxxUdU2gayAYkQW9T.6d/35KCHr.eX3qdN9OrVt5xjX/Skwwu'
        ]);
    }
}
