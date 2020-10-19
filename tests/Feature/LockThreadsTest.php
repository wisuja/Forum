<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_non_administrator_may_not_lock_threads() 
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(403);
        
        $this->assertFalse($thread->fresh()->locked);
    }

    public function test_administrator_may_lock_threads() 
    {
        $this->signIn(User::factory()->administrator()->create());

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }

    public function test_administrator_may_unlock_threads() 
    {
        $this->signIn(User::factory()->administrator()->create());

        $thread = create(Thread::class, ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }

    public function test_once_locked_a_thread_may_not_receive_new_replies() 
    {
        $this->signIn();

        $thread = create(Thread::class, ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'foobar',
            'user_id' => create(User::class)
        ])->assertStatus(422);
    }
}
