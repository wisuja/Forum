<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_confirmation_email_is_sent_upon_registration() 
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'testing123',
            'password_confirmation' => 'testing123'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    public function test_a_user_can_fully_confirm_their_email_address() 
    {
        Mail::fake();
        
        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'testing123',
            'password_confirmation' => 'testing123'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);
        
        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
        ->assertRedirect(route('threads'));
        
        tap($user->fresh(), function($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }

    public function test_confirming_an_invalid_token() 
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash');
    }
}
