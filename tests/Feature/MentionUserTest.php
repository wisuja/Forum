<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_mentioned_users_in_a_reply_are_notified() {
        $john = create(User::class, ['name' => 'JohnDoe']);
        $this->signIn($john);

        $jane = create(User::class, ['name' => 'JaneDoe']);
        
        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => '@JaneDoe look at this.']);

        $this->post("{$thread->path()}/replies", $reply->toArray());

        $this->assertCount(1,$jane->notifications);
    }
}
