<?php

namespace Tests\Feature;

use App\Services\SiteSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Hakkımda sayfasındaki "Yaklaşımım" maddeleri.
 *
 * Regresyon T-4: Bu maddeler `SiteContentService::fromApi()` içinde SABİT
 * yazılmıştı — API'den gelmiyordu. Sonuç: tüm hekim sitelerinde Hakkımda
 * sayfasında aynı üç jenerik metin görünüyordu (özgün içerik yok, üstelik
 * SEO açısından da tekrar eden içerik).
 *
 * Çözüm: panelde `yaklasim_json` anahtarıyla düzenlenebilir; boş bırakılırsa
 * varsayılan metinlere düşülür (mevcut siteler bozulmaz).
 */
class YaklasimIcerikTest extends TestCase
{
    use RefreshDatabase;

    private function ayarlar(): SiteSettingsService
    {
        return app(SiteSettingsService::class);
    }

    public function test_panel_bos_ise_madde_donmez(): void
    {
        $this->assertSame([], $this->ayarlar()->yaklasimMaddeleri());
    }

    public function test_panelden_girilen_maddeler_okunur(): void
    {
        $this->ayarlar()->setOption('yaklasim_json', json_encode([
            ['baslik' => 'EMDR terapisi', 'aciklama' => 'Travma odaklı çalışma.'],
            ['baslik' => 'Çift terapisi', 'aciklama' => 'İlişki dinamikleri.'],
        ], JSON_UNESCAPED_UNICODE));

        $maddeler = $this->ayarlar()->yaklasimMaddeleri();

        $this->assertCount(2, $maddeler);
        $this->assertSame('EMDR terapisi', $maddeler[0]['baslik']);
        $this->assertSame('Travma odaklı çalışma.', $maddeler[0]['aciklama']);
    }

    public function test_bosluk_ve_bozuk_kayitlar_elenir(): void
    {
        $this->ayarlar()->setOption('yaklasim_json', json_encode([
            ['baslik' => '  Geçerli  ', 'aciklama' => '  Açıklama  '],
            ['baslik' => '', 'aciklama' => 'Başlıksız — elenmeli'],
            ['aciklama' => 'Başlık alanı hiç yok — elenmeli'],
            'düz metin — elenmeli',
        ], JSON_UNESCAPED_UNICODE));

        $maddeler = $this->ayarlar()->yaklasimMaddeleri();

        $this->assertCount(1, $maddeler);
        $this->assertSame('Geçerli', $maddeler[0]['baslik']);
        $this->assertSame('Açıklama', $maddeler[0]['aciklama']);
    }

    public function test_bozuk_json_uygulamayi_dusurmez(): void
    {
        $this->ayarlar()->setOption('yaklasim_json', '{bozuk json');

        $this->assertSame([], $this->ayarlar()->yaklasimMaddeleri());
    }

    public function test_en_fazla_alti_madde(): void
    {
        $cok = [];
        for ($i = 1; $i <= 10; $i++) {
            $cok[] = ['baslik' => "Madde {$i}", 'aciklama' => 'x'];
        }
        $this->ayarlar()->setOption('yaklasim_json', json_encode($cok));

        $this->assertCount(6, $this->ayarlar()->yaklasimMaddeleri());
    }

    public function test_aciklama_alani_zorunlu_degil(): void
    {
        $this->ayarlar()->setOption('yaklasim_json', json_encode([
            ['baslik' => 'Yalnızca başlık'],
        ], JSON_UNESCAPED_UNICODE));

        $maddeler = $this->ayarlar()->yaklasimMaddeleri();

        $this->assertCount(1, $maddeler);
        $this->assertSame('', $maddeler[0]['aciklama']);
    }
}
