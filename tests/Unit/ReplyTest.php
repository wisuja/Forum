<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_it_wraps_mentioned_usernames_in_the_body_within_anchor_tags() {
        $reply = create(Reply::class, ['body' => 'Hello @JaneDoe.']);

        $this->assertEquals(
            '<p>Hello <a href="/profiles/JaneDoe">@JaneDoe</a>.</p>',
            $reply->body);
    }

    public function test_it_knows_if_it_is_the_best_reply() 
    {
        $reply = create(Reply::class);

        $this->assertFalse($reply->isBest());

        $reply->thread->setBestReply($reply);

        $this->assertTrue($reply->fresh()->isBest());
    }

    public function test_a_reply_sanitizes_its_own_body_automatically() 
    {
        $reply = make(Reply::class, ['body' => "<script>alert('gotcha')</script><p>This is okay</p>"]);

        $this->assertEquals("<p>This is okay</p>", $reply->body);
    }
}
