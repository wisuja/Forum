<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Facade;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_a_user_can_view_all_threads
     *
     * @return void
     */

    public function test_a_user_can_view_all_threads()
    {
        $thread = Thread::factory()->create();

        $this->get('/threads')
            ->assertSee($thread->title);
    }

    /**
     * test_a_user_can_view_a_single_thread
     *
     * @return void
     */

    public function test_a_user_can_view_a_single_thread()
    {
        $thread = Thread::factory()->create();
        $this->get("/threads/{$thread->id}")
            ->assertSee($thread->id);
    }
}
