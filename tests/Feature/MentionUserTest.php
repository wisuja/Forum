<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentioned_users_in_a_reply_are_notified() {
        $john = create(User::class, ['name' => 'JohnDoe']);
        $this->signIn($john);

        $jane = create(User::class, ['name' => 'JaneDoe']);
        
        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => '@JaneDoe look at this.']);

        $this->post("{$thread->path()}/replies", $reply->toArray());

        $this->assertCount(1,$jane->notifications);
    }

    public function test_it_can_fetch_all_mentioned_users_with_the_given_characters() {
        create(User::class, ['name' => 'John Doe']);
        create(User::class, ['name' => 'Jane Doe']);
        create(User::class, ['name' => 'John Doe2']);

        $results = $this->json('get', '/api/users', ['name' => 'john']);
        
        $this->assertCount(2, $results->json());
    }
}
