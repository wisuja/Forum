<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_user_can_fetch_their_most_recent_replies()
    {
        $user = create(User::class);

        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    public function test_a_user_can_determine_their_avatar_path() {
        $user = create(User::class);

        $this->assertEquals(asset('/storage/avatars/default.jpg'), $user->avatar_path);
        
        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals(asset('/storage/avatars/me.jpg'), $user->avatar_path);
    }
}
