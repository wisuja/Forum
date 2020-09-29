<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_an_authenticated_user_can_create_new_forum_threads
     *
     * @return void
     */
    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title);
    }

    /**
     * test_guests_can_not_create_threads
     *
     * @return void
     */
    public function test_guests_can_not_create_threads()
    {
        $this->get('/threads/create')
            ->assertRedirect(route('login'));

        $this->post('/threads')
            ->assertRedirect(route('login'));
    }
}
