<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->withoutExceptionHandling()
            ->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);
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
