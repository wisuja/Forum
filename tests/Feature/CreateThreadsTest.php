<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Channel;
use App\Models\Reply;
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

    public function test_a_new_user_must_first_confirm_their_email_address_before_creating_threads() 
    {
        $user = User::factory()->unconfirmed()->create();
        $this->signIn($user);

        $thread = make(Thread::class);

        $this->post(route('threads'), $thread->toArray())
            ->assertSessionHas('flash');
    }

    /**
     * test_an_authenticated_user_can_create_new_forum_threads
     *
     * @return void
     */
    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $response = $this->withoutExceptionHandling()
            ->post(route('threads'), $thread->toArray());

        $this->get($response->headers->get('Location'))
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

        $this->post(route('threads'))
            ->assertRedirect(route('login'));
    }

    /**
     * test_a_thread_require_a_title
     *
     * @return void
     */
    public function test_a_thread_require_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_require_a_unique_slug()
    {
        $this->signIn();
        
        $thread = create(Thread::class, ['title' => 'Foo Title', 'slug' => 'foo-title']);

        $this->assertEquals('foo-title', $thread->fresh()->slug);
        
        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());
    }

    /**
     * test_a_thread_require_a_body
     *
     * @return void
     */
    public function test_a_thread_require_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_require_a_valid_channel_id()
    {
        Channel::factory()->count(2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function test_an_authorized_users_can_delete_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function test_an_unauthorized_users_can_not_delete_threads()
    {
        $thread = create(Thread::class);

        $this->withExceptionHandling()
            ->delete($thread->path())
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->withExceptionHandling()
            ->delete($thread->path())
            ->assertStatus(403);
    }

    function publishThread($override = null)
    {
        $this->signIn();

        $thread = make(Thread::class, $override);

        return $this->post(route('threads'), $thread->toArray());
    }
}
