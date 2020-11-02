<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateChannelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_create_channel() {
        $admin = User::factory()->administrator()->create();

        $this->signIn($admin);

        $this->postJson('/channels', [
            "name" => "Some Channel"
        ])->assertStatus(201);
    }

    public function test_a_user_can_not_create_channel() 
    {
        $this->signIn();

        $this->postJson('/channels', [
            "name" => "Some Channel"
        ])->assertStatus(403);
    }

    public function test_a_guest_can_not_create_channel() 
    {
        $this->post('/channels', [
            "name" => "Some Channel"
        ])->assertRedirect(route('login'));
    }

    public function test_a_channel_requires_a_name() {
        $admin = User::factory()->administrator()->create();

        $this->signIn($admin);

        $this->post('/channels', [
            "name" => null
        ])->assertSessionHasErrors('name');
    }
}
