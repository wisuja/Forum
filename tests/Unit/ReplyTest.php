<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * test_it_has_an_owner
     *
     * @return void
     */
    public function test_it_has_an_owner()
    {
        $reply = create(Reply::class);
        $this->assertInstanceOf(User::class, $reply->owner);
    }

    public function test_it_knows_if_it_was_just_published()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    public function test_it_can_detects_mentioned_users_in_the_body() {
        $reply = create(Reply::class, ['body' => '@JaneDoe wants to talk to @JohnDoe']);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }
}
