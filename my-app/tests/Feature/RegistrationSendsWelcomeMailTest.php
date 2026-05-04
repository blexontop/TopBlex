<?php

namespace Tests\Feature;

use App\Mail\WelcomeToTopblexMail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationSendsWelcomeMailTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $env = parse_ini_file(dirname(__DIR__, 2).'/.env', false, INI_SCANNER_RAW) ?: [];

            foreach (['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'] as $key) {
                if (! array_key_exists($key, $env)) {
                    continue;
                }

                putenv($key.'='.$env[$key]);
                $_ENV[$key] = $env[$key];
                $_SERVER[$key] = $env[$key];
            }
        }

        parent::setUp();
    }

    public function test_registration_sends_a_welcome_email(): void
    {
        Mail::fake();

        $response = $this->post(route('register.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('account.index'));
        $this->assertAuthenticated();

        Mail::assertSent(WelcomeToTopblexMail::class, function (WelcomeToTopblexMail $mail): bool {
            return $mail->hasTo('test@example.com');
        });
    }
}