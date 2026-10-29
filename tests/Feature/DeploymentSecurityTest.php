<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeploymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_configured_hosts_are_accepted(): void
    {
        config(['travelista.security.allowed_hosts' => ['example.test', '.trusted.test']]);
        $this->get('http://example.test/hotels')->assertOk();
        $this->get('http://sub.trusted.test/hotels')->assertOk();
        $this->get('http://trusted.test/hotels')->assertOk();
        $this->get('http://untrusted.test/hotels')->assertBadRequest();
        $this->get('http://nottrusted.test/hotels')->assertBadRequest();
    }

    public function test_https_redirect_preserves_the_path_and_query(): void
    {
        config(['travelista.security.force_https' => true]);
        $this->get('http://localhost/hotels?q=baku')->assertStatus(301)->assertRedirect('https://localhost/hotels?q=baku');
        $this->get('https://localhost/hotels')->assertOk();
    }

    public function test_hsts_is_only_added_to_https_responses_when_enabled(): void
    {
        config([
            'travelista.security.hsts_seconds' => 3600,
            'travelista.security.hsts_include_subdomains' => true,
            'travelista.security.hsts_preload' => true,
        ]);
        $this->get('https://localhost/hotels')->assertOk()
            ->assertHeader('Strict-Transport-Security', 'max-age=3600; includeSubDomains; preload');
        $this->get('http://localhost/hotels')->assertOk()->assertHeaderMissing('Strict-Transport-Security');
    }

    public function test_untrusted_origins_are_rejected_and_explicitly_trusted_origins_are_accepted(): void
    {
        config(['travelista.security.csrf_trusted_origins' => ['https://trusted.example']]);
        $this->withHeader('Origin', 'https://untrusted.example')->post('/newsletter', ['EMAIL' => 'test@example.com'])->assertStatus(419);
        $this->withHeader('Origin', 'https://trusted.example')->post('/newsletter', ['EMAIL' => 'test@example.com'])->assertRedirect();
        $this->assertDatabaseCount('newsletter_subscriptions', 1);
    }

    public function test_forwarded_proto_is_ignored_from_untrusted_sources(): void
    {
        config(['travelista.security.force_https' => true]);
        $this->withHeader('X-Forwarded-Proto', 'https')->get('http://localhost/hotels')->assertStatus(301);
    }

    public function test_laravel_csrf_token_checks_still_apply_to_trusted_origins(): void
    {
        config(['travelista.security.csrf_trusted_origins' => ['https://trusted.example']]);
        $this->app->instance('env', 'local');
        $this->withHeader('Origin', 'https://trusted.example')->post('/newsletter', ['EMAIL' => 'test@example.com'])->assertStatus(419);
    }
}
