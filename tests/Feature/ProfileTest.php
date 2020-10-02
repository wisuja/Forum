<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_has_a_profile()
    {
        $user = create(User::class);

        $this->withoutExceptionHandling()
            ->get("/profiles/{$user->name}")
            ->assertSee($user->name);
    }

    public function test_profiles_display_all_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->withoutExceptionHandling()
            ->get("/profiles/" . auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
