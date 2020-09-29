<?php

namespace Tests\Unit;

use App\Models\Thread;
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
}
