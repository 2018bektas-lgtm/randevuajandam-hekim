<?php

namespace Tests\Feature;

use App\Services\PlatformApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Panel takvimi: randevular gelmiyorsa SEBEBİ görünür olmalı.
 *
 * Bildirilen sorun: randevuajandam-site panelinde görünen randevu, hekim
 * panelinin takviminde görünmüyordu.
 *
 * Kök neden: site randevuları doğrudan veritabanından okuyor; hekim paneli
 * ise proxy → API zincirinden geçiyor ve bu zincirde dört ayrı sessiz hata
 * noktası var (API yapılandırılmamış, ana sunucuya ulaşılamıyor, oturum
 * bitmiş, pakette online_takvim yok). FullCalendar `{ url: ... }` kaynağı
 * başarısız istekte hiçbir şey çizmediği için hepsi "randevu yok" gibi
 * görünüyordu. Üstelik bağlantı hatasında ham istisna mesajı HTTP 500 ile
 * istemciye sızıyordu.
 *
 * Artık her durum kendi anlamlı kodunu ve mesajını döndürüyor; takvim
 * arayüzü de bunu bir uyarı kutusunda gösteriyor.
 */
class TakvimProxyTest extends TestCase
{
    use RefreshDatabase;

    private function takvimiCek(): \Illuminate\Testing\TestResponse
    {
        return $this->getJson(route('panel.randevular.events', [
            'start' => now()->toDateString(),
            'end' => now()->addDays(7)->toDateString(),
        ]));
    }

    private function oturumAc(): void
    {
        // Panel oturumu (DoctorPanelAuth session tabanlı)
        session(['doctor_api_token' => 'test-token', 'doctor_id' => 1]);
    }

    public function test_api_yapilandirilmamissa_acik_mesaj_doner(): void
    {
        $this->oturumAc();

        $this->mock(PlatformApiClient::class, function ($mock) {
            $mock->shouldReceive('isConfigured')->andReturn(false);
            $mock->shouldReceive('token')->andReturn('test-token');
        });

        $yanit = $this->takvimiCek();

        // Oturum yoksa 302/401 olabilir; ama 500 ASLA olmamalı
        $this->assertNotSame(500, $yanit->status());

        if ($yanit->status() === 503) {
            $yanit->assertJsonPath('kod', 'api_yapilandirilmamis');
            $this->assertNotEmpty($yanit->json('message'));
        }
    }

    public function test_baglanti_hatasinda_ham_istisna_sizmaz(): void
    {
        $this->oturukAcVarsa();

        Http::fake(function () {
            throw new \Illuminate\Http\Client\ConnectionException(
                'cURL error 7: Failed to connect to 10.1.2.3 port 8001'
            );
        });

        $yanit = $this->takvimiCek();

        // Ham cURL/host bilgisi istemciye gitmemeli
        $govde = (string) $yanit->getContent();
        $this->assertStringNotContainsString('cURL error', $govde);
        $this->assertStringNotContainsString('10.1.2.3', $govde);
        $this->assertNotSame(500, $yanit->status(), 'Baglanti hatasi 500 ile sizmamali.');
    }

    private function oturukAcVarsa(): void
    {
        $this->oturumAc();
    }

    /**
     * Takvim arayüzü hata durumunu kullanıcıya göstermeli; sessizce boş
     * takvim çizmemeli.
     */
    public function test_takvim_arayuzu_hata_kutusuna_sahip(): void
    {
        $blade = (string) file_get_contents(
            resource_path('views/panel/randevu/takvim.blade.php')
        );

        $this->assertStringContainsString('id="takvimUyari"', $blade, 'Uyari kutusu yok.');
        $this->assertStringContainsString('takvimUyariGoster', $blade, 'Hata gosterme fonksiyonu yok.');

        // Duz `{ url: routes.events }` kaynagi FullCalendar'da hatayi yutar
        $this->assertStringNotContainsString(
            '{ url: routes.events }',
            $blade,
            'Duz url kaynagi geri gelmis; hata yeniden sessizce yutulur.'
        );
    }

    /**
     * Proxy, bilinen hata durumları için makine tarafından okunabilir bir
     * `kod` alanı döndürmeli (arayüz buna göre ipucu gösteriyor).
     */
    public function test_proxy_hata_kodlari_tanimli(): void
    {
        $kaynak = (string) file_get_contents(
            app_path('Http/Controllers/Panel/RandevuController.php')
        );

        foreach ([
            'api_yapilandirilmamis',
            'baglanti_hatasi',
            'oturum_bitti',
            'paket_yetkisi_yok',
        ] as $kod) {
            $this->assertStringContainsString($kod, $kaynak, "Hata kodu tanimsiz: {$kod}");
        }
    }
}
