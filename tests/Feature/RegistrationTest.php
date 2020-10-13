<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_confirmation_email_is_sent_upon_registration() 
    {
        Mail::fake();

        event(new Registered(create(User::class)));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    public function test_a_user_can_fully_confirm_their_email_address() 
    {
        $this->post('/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'testing123',
            'password_confirmation' => 'testing123'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);
        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}
