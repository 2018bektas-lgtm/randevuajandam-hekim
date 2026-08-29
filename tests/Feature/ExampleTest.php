<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * RefreshDatabase yorum satirindaydi; migration'lar calismadigi icin
     * anasayfa `site_homepage_sections` tablosunu sorgularken
     * "no such table" ile 500 veriyordu ve suite kalici olarak kirmiziydi.
     */
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
