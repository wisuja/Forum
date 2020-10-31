<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrendingThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_thread_get_into_trending_list_whenever_it_has_highest_visits_count() {        
        $thread = create(Thread::class);
        $this->call('GET', $thread->path());

        $trending = resolve(Thread::class)->getTrendingThreads();

        $this->assertCount(1, $trending);
        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
