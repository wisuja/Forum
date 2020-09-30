<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Facade;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /**
     * test_a_user_can_view_all_threads
     *
     * @return void
     */

    public function test_a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /**
     * test_a_user_can_view_a_single_thread
     *
     * @return void
     */

    public function test_a_user_can_view_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->id);
    }

    /**
     * test_a_user_can_read_replies_that_are_associated_with_a_thread
     *
     * @return void
     */
    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = create(Reply::class, ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /**
     * test_a_user_can_filter_threads_according_to_a_channel
     *
     * @return void
     */
    public function test_a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->withoutExceptionHandling()
            ->get("/threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /**
     * test_a_user_can_filter_threads_by_any_username
     *
     * @return void
     */
    public function test_a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(User::class, ['name' => 'John']));

        $threadByJohn = create(Thread::class, ['user_id' => auth()->id()]);
        $threadNotByJohn = create(Thread::class);

        $this->get('/threads/?by=John')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
