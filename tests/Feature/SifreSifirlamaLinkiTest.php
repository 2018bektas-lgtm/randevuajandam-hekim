<?php

namespace Tests\Feature;

use App\Services\ApiConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Panel girişindeki "Şifremi unuttum" bağlantısı.
 *
 * Hekim hesabı bu sitede değil PLATFORMDA tutulur (giriş de API üzerinden
 * yapılır), dolayısıyla burada yerel bir sıfırlama akışı kurulamaz. Panel
 * girişi, platformun kendi sıfırlama sayfasına yönlendirir:
 *   {platform_site}/sifremi-unuttum?type=hekim
 *
 * Önceden bu bağlantı hiç yoktu; şifresini unutan hekimin panele girmesinin
 * yolu kalmıyordu.
 */
class SifreSifirlamaLinkiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    private function apiyiYapilandir(string $platform = 'https://api.randevuajandam.com/api/v1'): void
    {
        app(ApiConfigService::class)->save([
            'api_key' => 'test-key',
            'api_secret' => 'test-secret',
            'platform' => $platform,
        ]);
    }

    public function test_platform_adresi_api_kokunden_turetilir(): void
    {
        config(['randevu_api.site_url' => '']);

        config(['randevu_api.platform_base' => 'https://api.randevuajandam.com/api/v1']);
        $this->assertSame('https://randevuajandam.com/sifremi-unuttum', platform_site_url('/sifremi-unuttum'));

        // api. öneki olmayan kurulum olduğu gibi kalır
        config(['randevu_api.platform_base' => 'https://randevuajandam.com/api/v1']);
        $this->assertSame('https://randevuajandam.com/sifremi-unuttum', platform_site_url('/sifremi-unuttum'));

        // Port korunur (yerel geliştirme)
        config(['randevu_api.platform_base' => 'http://127.0.0.1:8001/api/v1']);
        $this->assertSame('http://127.0.0.1:8001/sifremi-unuttum', platform_site_url('/sifremi-unuttum'));
    }

    public function test_tek_etiketli_hostta_api_oneki_kirpilmaz(): void
    {
        // "api.localhost" → "localhost" kırpması geçersiz bir adres üretirdi
        config(['randevu_api.site_url' => '', 'randevu_api.platform_base' => 'http://api.localhost/api/v1']);

        $this->assertSame('http://api.localhost/sifremi-unuttum', platform_site_url('/sifremi-unuttum'));
    }

    public function test_site_url_ayari_turetmeyi_ezer(): void
    {
        config([
            'randevu_api.site_url' => 'https://ozel.example.com',
            'randevu_api.platform_base' => 'https://api.randevuajandam.com/api/v1',
        ]);

        $this->assertSame('https://ozel.example.com/sifremi-unuttum', platform_site_url('/sifremi-unuttum'));
    }

    public function test_platform_adresi_cozulemezse_null_doner(): void
    {
        config(['randevu_api.site_url' => '', 'randevu_api.platform_base' => '']);

        $this->assertNull(platform_site_url('/sifremi-unuttum'));
    }

    public function test_api_yapilandirildiginda_giris_sayfasinda_link_var(): void
    {
        config(['randevu_api.site_url' => '']);
        $this->apiyiYapilandir();

        $this->get(route('panel.giris'))
            ->assertOk()
            ->assertSee('Şifremi unuttum', false)
            ->assertSee('https://randevuajandam.com/sifremi-unuttum?type=hekim', false);
    }

    public function test_api_yoksa_link_basilmaz(): void
    {
        // API yapılandırılmamışsa yalnızca yerel yönetici girişi vardır; onun
        // şifresi platformdan sıfırlanamaz, yanıltıcı link gösterilmemeli.
        config(['randevu_api.site_url' => '', 'randevu_api.api_key' => '', 'randevu_api.platform_base' => '']);
        app(ApiConfigService::class)->forgetCache();

        $this->get(route('panel.giris'))
            ->assertOk()
            ->assertDontSee('sifremi-unuttum', false);
    }

    public function test_link_yeni_sekmede_ve_guvenli_acilir(): void
    {
        config(['randevu_api.site_url' => '']);
        $this->apiyiYapilandir();

        $html = $this->get(route('panel.giris'))->getContent();

        $this->assertMatchesRegularExpression(
            '/<a[^>]*href="[^"]*sifremi-unuttum[^"]*"[^>]*rel="noopener"/s',
            $html,
            'Dış bağlantı rel="noopener" ile açılmalı'
        );
    }
}
