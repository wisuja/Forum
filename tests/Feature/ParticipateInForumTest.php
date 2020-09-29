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

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_an_authenticated_user_can_participate_in_a_forum
     *
     * @return void
     */
    public function test_an_authenticated_user_can_participate_in_a_forum()
    {
        $this->be(User::factory()->create());

        $thread = Thread::factory()->create();
        $reply = Reply::factory()->make();

        $this->post("{$thread->path()}/replies", $reply->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title);
    }

    /**
     * test_an_unauthenticated_user_can_participate_in_a_forum
     *
     * @return void
     */
    public function test_an_unauthenticated_user_can_participate_in_a_forum()
    {
        $this->expectException(AuthenticationException::class);

        $this->withoutExceptionHandling()
            ->post("/threads/1/replies", []);
    }
}
