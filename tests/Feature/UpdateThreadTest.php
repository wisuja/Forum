<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    public function test_guests_can_not_create_threads()
    {
        $this->json('patch', '/threads/nope')
            ->assertUnauthorized();
    }

    public function test_it_fails_if_the_thread_does_not_exist()
    {
        $this->signIn();

        $this->json('patch', '/threads/nope')
            ->assertNotFound();
    }

    public function test_it_fails_if_the_user_cannot_update_the_thread()
    {
        $this->signIn();

        $this->json('patch', '/threads/' . $this->thread->getKey())
            ->assertForbidden();
    }

    public function test_it_requires_title()
    {
        $this->signIn();

        $thread = create(Thread::class, ["user_id"  => auth()->id()]);

        $this->json('patch', '/threads/' . $thread->getKey())
            ->assertJsonValidationErrors(['title']);
    }

    public function test_it_updates_the_thread()
    {
        $this->signIn();

        $thread = create(Thread::class, ["user_id"  => auth()->id()]);

        $this->json('patch', '/threads/' . $thread->getKey(), [
            'title' => $new_title = 'New Title',
        ])
            ->assertStatus(200);

        $this->assertDatabaseHas('threads', [
            'id'    => $thread->id,
            'title'    => $new_title,
        ]);
    }
}
