<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingLandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_with_cta_and_pricing(): void
    {
        config(['xiway.landing_on_root' => true]);

        $response = $this->get(route('marketing.home'));

        $response->assertOk();
        $response->assertSee('Sistem kasir praktis');
        $response->assertSee('Coba Gratis 14 Hari');
        $response->assertSee('Kasir &amp; pembayaran', false);
        $response->assertSee('Starter');
        $response->assertSee('Business');
        $response->assertSee('Enterprise');
    }

    public function test_landing_register_link_includes_utm_params(): void
    {
        config([
            'xiway.landing_on_root' => true,
            'xiway.marketing_register_url' => 'https://app.xiwaypos.com/register',
        ]);

        $response = $this->get(route('marketing.home'));

        $response->assertOk();
        $response->assertSee('utm_source=landing', false);
        $response->assertSee('utm_campaign=landing', false);
    }

    public function test_privacy_and_terms_pages_render(): void
    {
        config(['xiway.landing_on_root' => true]);

        $this->get(route('marketing.privacy'))->assertOk()->assertSee('Kebijakan Privasi');
        $this->get(route('marketing.terms'))->assertOk()->assertSee('Syarat & Ketentuan');
    }
}
