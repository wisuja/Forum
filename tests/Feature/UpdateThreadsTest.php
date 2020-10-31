<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void {
        parent::setUp();

        $this->signIn();

        $this->thread = create(Thread::class, ['user_id' => auth()->id()]);
    }

    public function test_an_unauthorized_users_can_not_update_threads() 
    {
        $thread = create(Thread::class, ['user_id' => create(User::class)->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }

    public function test_a_thread_can_be_updated_by_its_creator()
    {
        $this->patch($this->thread->path(), [
            'title' => 'Changed title',
            'body' => 'Changed body'
        ]);

        tap($this->thread->fresh(), function ($thread){
            $this->assertEquals('Changed title', $thread->title);
            $this->assertEquals('<p>Changed body</p>', $thread->body);
        });
    }

    public function test_a_thread_require_a_title_and_a_body_to_be_updated() 
    {
        $this->patch($this->thread->path(), [
            'title' => 'Changed title'
        ])->assertSessionHasErrors('body');

        $this->patch($this->thread->path(), [
            'body' => 'Changed body'
        ])->assertSessionHasErrors('title');
    }
}
