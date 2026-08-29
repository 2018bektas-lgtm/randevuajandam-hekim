<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Tema varlık referanslarının diskte gerçekten karşılığı var mı?
 *
 * Regresyon P2-9: `public/vendor/hipno/images` altında 99 dosyanın 60'ı hiçbir
 * yerden referans edilmiyordu (4,4 MB ölü ağırlık) ve silindi. Bu test, o
 * temizliğin (ya da ileride yapılacak başka bir temizliğin) hâlâ kullanılan
 * bir dosyayı götürmediğini garanti eder — aksi halde sitede sessizce 404
 * veren görseller oluşur.
 */
class TemaVarlikReferanslariTest extends TestCase
{
    /**
     * Kodda geçen tüm `vendor/hipno/images/<dosya>` referansları.
     *
     * @return array<int, string>
     */
    private function referansEdilenGorseller(): array
    {
        $metin = '';
        $kokler = [
            resource_path(),
            app_path(),
            config_path(),
            public_path('css'),
            public_path('vendor/hipno/css'),
            public_path('vendor/hipno/js'),
        ];

        foreach ($kokler as $kok) {
            if (! is_dir($kok)) {
                continue;
            }
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($kok));
            foreach ($it as $dosya) {
                if (! $dosya->isFile()) {
                    continue;
                }
                if (! preg_match('/\.(php|css|js)$/', (string) $dosya)) {
                    continue;
                }
                $metin .= "\n".(string) file_get_contents((string) $dosya);
            }
        }

        // 1) Sabit adlar — YALNIZCA hipno/images altindakiler.
        //    (public/images/ ve themes/delogis/images/ ayri klasorler;
        //     kapsam daraltilmazsa onlar da yanlislikla "eksik" gorunur.)
        preg_match_all('#hipno/images/([A-Za-z0-9._-]+\.(?:jpg|jpeg|png|svg|gif|webp))#i', $metin, $m);
        $adlar = $m[1] ?? [];

        // 2) Dinamik önekler: hipno/images/'post-'.($i).'.jpg'
        preg_match_all("#hipno/images/([A-Za-z0-9._-]*?-)'\s*\.#", $metin, $mp);
        foreach (array_unique($mp[1] ?? []) as $onek) {
            // Önekle başlayan mevcut dosyaları kapsam dışı bırakmak yerine
            // referans sayıyoruz; bunlar döngü sayacıyla üretiliyor.
            foreach (glob(public_path('vendor/hipno/images/'.$onek.'*')) ?: [] as $yol) {
                $adlar[] = basename($yol);
            }
        }

        return array_values(array_unique($adlar));
    }

    public function test_referans_edilen_tum_gorseller_diskte_mevcut(): void
    {
        $eksik = [];

        foreach ($this->referansEdilenGorseller() as $ad) {
            if (! is_file(public_path('vendor/hipno/images/'.$ad))) {
                $eksik[] = $ad;
            }
        }

        $this->assertSame(
            [],
            $eksik,
            'Kodda referans edilen ama diskte olmayan gorsel(ler): '.implode(', ', $eksik)
        );
    }

    public function test_dinamik_onekli_gorsel_serileri_eksiksiz(): void
    {
        // blog.blade.php: post-1..6, services: service-image-1..6,
        // case_study: case-study-img-1..3  (döngü sayacı % N ile üretiliyor)
        $seriler = [
            'post-' => 6,
            'service-image-' => 6,
            'case-study-img-' => 3,
        ];

        foreach ($seriler as $onek => $adet) {
            for ($i = 1; $i <= $adet; $i++) {
                $this->assertFileExists(
                    public_path("vendor/hipno/images/{$onek}{$i}.jpg"),
                    "Dinamik seri eksik: {$onek}{$i}.jpg"
                );
            }
        }
    }


    /**
     * P2-8: LCP gorseli lazy OLMAMALI, ekran disi gorseller lazy OLMALI.
     */
    public function test_hero_gorselleri_lazy_degil(): void
    {
        $heroDosyalari = [
            'tema-1/modules/hero_static.blade.php',
            'tema-2/modules/hero_slider.blade.php',
            'tema-3/modules/hero_video.blade.php',
        ];

        foreach ($heroDosyalari as $rel) {
            $yol = resource_path('views/frontend/themes/'.$rel);
            if (! is_file($yol)) {
                continue;
            }
            $this->assertStringNotContainsString(
                'loading="lazy"',
                (string) file_get_contents($yol),
                "{$rel}: ilk ekran gorseli lazy olmamali (LCP'yi kotulestirir)."
            );
        }
    }

    public function test_ekran_disi_gorsellerin_cogu_lazy(): void
    {
        foreach (['tema-1', 'tema-2', 'tema-3', 'delogis'] as $tema) {
            $kok = resource_path('views/frontend/themes/'.$tema);
            $toplam = 0;
            $lazy = 0;

            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($kok));
            foreach ($it as $dosya) {
                if (! $dosya->isFile() || ! str_ends_with((string) $dosya, '.blade.php')) {
                    continue;
                }
                $icerik = (string) file_get_contents((string) $dosya);
                preg_match_all('/<img\s[^>]*>/', $icerik, $m);
                foreach ($m[0] as $etiket) {
                    $toplam++;
                    if (str_contains($etiket, 'loading=')) {
                        $lazy++;
                    }
                }
            }

            $this->assertGreaterThan(0, $toplam, "{$tema}: img bulunamadi.");
            $oran = $lazy / $toplam;
            $this->assertGreaterThan(
                0.75,
                $oran,
                sprintf('%s: img etiketlerinin yalnizca yuzde %d kadarinda loading niteligi var.', $tema, (int) ($oran * 100))
            );
        }
    }

    /**
     * P2-17: Preloader yalnizca window.load beklememeli; guvenlik zaman
     * asimi olmali, aksi halde tek bir varlik yuklenemezse sayfa kalici
     * olarak ortulu kaliyor.
     */
    public function test_preloader_guvenlik_zaman_asimina_sahip(): void
    {
        foreach (['tema-1', 'tema-2', 'tema-3'] as $tema) {
            $script = (string) file_get_contents(
                resource_path("views/frontend/themes/{$tema}/layouts/partials/script.blade.php")
            );

            $this->assertStringContainsString('raPreloaderKapat', $script, "{$tema}: preloader yedegi yok.");
            $this->assertStringContainsString('2500', $script, "{$tema}: preloader zaman asimi yok.");
            $this->assertStringContainsString('prefers-reduced-motion', $script);
        }
    }

    public function test_yerel_yer_tutucu_mevcut(): void
    {
        $this->assertFileExists(public_path('images/placeholder.svg'));
    }

    /**
     * hipno disindaki tema klasorlerinde referans edilen varliklar.
     */
    public function test_delogis_varliklari_mevcut(): void
    {
        $this->assertFileExists(public_path('themes/delogis/images/loader.png'));
    }
}
