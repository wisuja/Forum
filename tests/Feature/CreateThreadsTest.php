<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void 
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function() {
            return Mockery::mock(Recaptcha::class, function($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    public function test_a_new_user_must_first_confirm_their_email_address_before_creating_threads() 
    {
        $user = User::factory()->unconfirmed()->create();
        $this->signIn($user);

        $thread = make(Thread::class);

        $this->post(route('threads'), $thread->toArray())
            ->assertSessionHas('flash');
    }

    public function test_an_authenticated_user_can_create_new_forum_threads()
    {
        $response = $this->publishThread(['title' => 'title']);

        $this->get($response->headers->get('Location'))
            ->assertSee('title');
    }

    public function test_guests_can_not_create_threads()
    {
        $this->get('/threads/create')
            ->assertRedirect(route('login'));

        $this->post(route('threads'))
            ->assertRedirect(route('login'));
    }

    public function test_a_thread_require_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_require_a_unique_slug()
    {
        $this->signIn();
        
        $thread = create(Thread::class, ['title' => 'Foo Title']);

        $this->assertEquals('foo-title', $thread->slug);
        
        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    public function test_a_thread_with_a_title_ends_in_a_number_should_generate_the_proper_slug() 
    {
        $this->signIn();
        
        $thread = create(Thread::class, ['title' => 'Some Title 24']);

        $this->assertEquals('some-title-24', $thread->slug);
        
        $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);    }

    public function test_a_thread_require_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_require_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'token'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    public function test_a_thread_require_a_valid_channel_id()
    {
        Channel::factory()->count(2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    function publishThread($override = null)
    {
        $this->signIn();

        $thread = make(Thread::class, $override);

        return $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);
    }
}
