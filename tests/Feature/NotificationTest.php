<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $thread = create(Thread::class)->subscribe();

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertCount(0, $user->notifications);

            $thread->addReply([
                'user_id' => auth()->id(),
                'body' => 'reply'
            ]);

            $this->assertCount(0, $user->fresh()->notifications);

            $thread->addReply([
                'user_id' => create(User::class)->id,
                'body' => 'reply'
            ]);

            $this->assertCount(1, $user->fresh()->notifications);
        });
    }

    public function test_a_user_can_fetch_all_unread_notifications()
    {
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'reply'
        ]);

        $response = $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json();

        $this->assertCount(1, $response);
    }

    public function test_a_user_can_mark_a_notification_as_read()
    {
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'reply'
        ]);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->withoutExceptionHandling()
                ->delete("/profiles/{$user->name}/notifications/{$notificationId}");

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
