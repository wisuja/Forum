<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_an_authenticated_user_can_add_a_reply_in_a_thread
     *
     * @return void
     */
    public function test_an_authenticated_user_can_add_a_reply_in_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class);

        $this->post("{$thread->path()}/replies", $reply->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title);
    }

    /**
     * test_an_unauthenticated_user_can_not_add_a_reply_in_a_thread
     *
     * @return void
     */
    public function test_an_unauthenticated_user_can_not_add_a_reply_in_a_thread()
    {
        $this->withExceptionHandling()
            ->post("/threads/slug/1/replies", [])
            ->assertRedirect(route('login'));
    }

    /**
     * test_a_reply_require_a_body
     *
     * @return void
     */
    public function test_a_reply_require_a_body()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post("{$thread->path()}/replies", $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function test_unauthorized_user_can_not_delete_a_reply()
    {
        $reply = create(Reply::class);

        $this->withExceptionHandling()
            ->delete('/replies/' . $reply->id)
            ->assertRedirect(route('login'));

        $this->signIn()
            ->delete('/replies/' . $reply->id)
            ->assertStatus(403);
    }

    public function test_authorized_user_can_delete_a_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->delete('/replies/' . $reply->id)
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}
