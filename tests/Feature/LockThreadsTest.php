<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
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

    public function test_once_locked_a_thread_may_not_receive_new_replies() 
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'foobar',
            'user_id' => create(User::class)
        ])->assertStatus(422);
    }
}
