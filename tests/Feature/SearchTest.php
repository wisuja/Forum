<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_search_thread()
    {
        config(['scout.driver' => 'algolia']);

        $search = "hello";

        create(Thread::class, [], 2);
        create(Thread::class, ['title' => $search], 2);

        do {
            $result = $this->getJson("/threads/search?q={$search}")->json()['data'];
        } while(empty($result));

        $this->assertCount(2, $result);

        Thread::latest()->take(4)->unsearchable();
    }
}
