<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /**
     * test_a_thread_can_make_a_string_path
     *
     * @return void
     */
    public function test_a_thread_can_make_a_string_path()
    {
        $this->assertEquals("/threads/{$this->thread->channel->slug}/{$this->thread->id}", $this->thread->path());
    }

    /**
     * test_a_thread_has_a_creator
     *
     * @return void
     */
    public function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /**
     * test_a_thread_has_replies
     *
     * @return void
     */
    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /**
     * test_a_thread_can_add_a_reply
     *
     * @return void
     */
    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /**
     * test_a_thread_belongs_to_a_channel
     *
     * @return void
     */
    public function test_a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf(Channel::class, $this->thread->channel);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $this->signIn();
        $thread = create(Thread::class);

        $thread->subscribe();

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', auth()->id())->count()
        );
    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        $this->signIn();
        $thread = create(Thread::class);

        $thread->subscribe();
        $thread->unsubscribe();

        $this->assertCount(
            0,
            $thread->subscriptions
        );
    }
}
