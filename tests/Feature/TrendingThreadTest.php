<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() :void 
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    public function test_increments_a_threads_score_each_time_it_is_read() {
        $this->assertEmpty($this->trending->get());

        $thread = create(Thread::class);

        $this->call('GET', $thread->path());

        $trending = $this->trending->get();
        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
