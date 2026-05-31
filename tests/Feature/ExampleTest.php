<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_root_shows_landing_when_enabled(): void
    {
        config(['xiway.landing_on_root' => true]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Xiway POS');
    }

    public function test_root_redirects_to_login_when_landing_disabled(): void
    {
        putenv('LANDING_ON_ROOT=false');
        $_ENV['LANDING_ON_ROOT'] = 'false';
        $_SERVER['LANDING_ON_ROOT'] = 'false';

        $this->refreshApplication();

        $response = $this->get('/');

        $response->assertRedirect(route('login'));

        putenv('LANDING_ON_ROOT=true');
        $_ENV['LANDING_ON_ROOT'] = 'true';
        $_SERVER['LANDING_ON_ROOT'] = 'true';

        $this->refreshApplication();
    }
}
