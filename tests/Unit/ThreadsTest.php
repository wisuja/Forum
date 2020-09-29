<?php

namespace Tests\Unit;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_a_thread_has_replies
     *
     * @return void
     */
    public function test_a_thread_has_replies()
    {
        $thread = Thread::factory()->create();
        $this->assertInstanceOf(Collection::class, $thread->replies);
    }

    /**
     * test_a_thread_has_a_creator
     *
     * @return void
     */
    public function test_a_thread_has_a_creator()
    {
        $thread = Thread::factory()->create();
        $this->assertInstanceOf(User::class, $thread->creator);
    }
}
