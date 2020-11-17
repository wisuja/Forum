<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void 
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function() {
            return Mockery::mock(Recaptcha::class, function($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    public function test_a_confirmation_email_is_sent_upon_registration() 
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'testing123',
            'password_confirmation' => 'testing123',
            'g-recaptcha-response' => 'token'
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
            'password_confirmation' => 'testing123',
            'g-recaptcha-response' => 'token'
        ]);

        $user = User::where('name', 'John')->first();

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
