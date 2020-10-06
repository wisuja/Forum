<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->withoutExceptionHandling()
            ->post($thread->path() . '/subscriptions');

        //TODO: Add notification whenever there is a new reply
        // $thread->addReply([
        //     'user_id' => auth()->id(),
        //     'body' => 'reply'
        // ]);

        $this->assertCount(1, $thread->subscriptions);
    }

    public function test_a_user_can_unsubscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->withoutExceptionHandling()
            ->delete($thread->path() . '/subscriptions');

        $this->assertCount(0, $thread->subscriptions);
    }
}
