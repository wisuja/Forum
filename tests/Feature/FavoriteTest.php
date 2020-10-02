<?php

namespace Tests\Feature;

use App\Models\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use DatabaseMigrations;

    public function test_an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->withoutExceptionHandling()
            ->post("/replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    public function test_guests_can_not_favorite_any_reply()
    {
        $this->withExceptionHandling()
            ->post("/replies/1/favorites")
            ->assertRedirect(route('login'));
    }

    public function test_an_authenticated_user_can_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->withoutExceptionHandling()
            ->post("/replies/{$reply->id}/favorites");
        $this->withoutExceptionHandling()
            ->post("/replies/{$reply->id}/favorites");

        $this->assertCount(1, $reply->favorites);
    }

    public function test_an_authencated_user_can_unfavorite_a_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $reply->favorite();

        $this->delete("/replies/{$reply->id}/favorites");

        $this->assertCount(0, $reply->favorites);
    }
}
