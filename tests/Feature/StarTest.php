<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StarTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_can_not_give_star_to_another_user() 
    {
        $this->expectException(AuthenticationException::class);

        $user = create(User::class);

        $this->withoutExceptionHandling()
            ->post("/api/users/{$user->id}/stars");
    }

    public function test_a_user_can_not_give_star_to_himself()
    {
        $this->signIn();

        $this->post("/api/users/" . auth()->user()->name . "/stars")
            ->assertStatus(403);
    }

    public function test_a_user_can_give_star_to_another_user()
    {
        $this->signIn();

        $user = create(User::class);

        $this->post("/api/users/{$user->name}/stars");
        
        $this->assertEquals(1, $user->fresh()->stars);
    }

    public function test_a_user_can_only_give_three_stars_each_day() 
    {
        $this->signIn();

        $user = create(User::class);

        $this->post("/api/users/{$user->name}/stars");
        $this->post("/api/users/{$user->name}/stars");
        $this->post("/api/users/{$user->name}/stars");
        $this->post("/api/users/{$user->name}/stars");
        
        $this->assertEquals(3, $user->fresh()->stars);
    }
}
