<?php

namespace Tests\Feature;

use App\Services\SiteFooterService;
use App\Services\SiteSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tema bazlı footer tasarımı.
 *
 * Önceden tek bir footer markup'ı vardı (tema-1/2/3 birbirinin kopyası,
 * delogis ayrı) ve panelde yalnızca link listesi düzenlenebiliyordu.
 * Artık her tema paketi bir footer grubuna bağlı (hipno / delogis), her
 * grubun kendi tasarım kataloğu var ve seçim grup bazında saklanıyor —
 * hekim tema değiştirip geri döndüğünde seçimi korunur.
 *
 * Katalog: config/footer_tasarimlari.php
 */
class FooterTasarimTest extends TestCase
{
    use RefreshDatabase;

    private function footer(): SiteFooterService
    {
        return app(SiteFooterService::class);
    }

    private function ayarlar(): SiteSettingsService
    {
        return app(SiteSettingsService::class);
    }

    /** @return array<string, mixed> */
    private function ornekDoktor(array $ek = []): array
    {
        return array_merge([
            'unvan' => 'Uzm. Psk.',
            'ad_soyad' => 'Ayşe Yılmaz',
            'slogan' => 'Hazır olduğunuzda buradayız',
            'kisa_bio' => 'Bireysel terapi ve çift terapisi.',
            'telefon' => '0224 555 00 00',
            'telefon_raw' => '02245550000',
            'e_posta' => 'info@ornek.com',
            'adres' => 'Örnek Mah. No:1',
            'il' => 'Bursa',
            'sosyal' => ['instagram' => 'https://instagram.com/x'],
        ], $ek);
    }

    public function test_tema_paketi_footer_grubunu_belirler(): void
    {
        $svc = $this->footer();

        $this->assertSame('hipno', $svc->grup('tema-1'));
        $this->assertSame('hipno', $svc->grup('tema-2'));
        $this->assertSame('hipno', $svc->grup('tema-3'));
        // tema-4..9 delogis paketini kullanır
        $this->assertSame('delogis', $svc->grup('tema-4'));
        $this->assertSame('delogis', $svc->grup('tema-9'));
    }

    public function test_her_grup_kendi_tasarim_katalogunu_dondurur(): void
    {
        $svc = $this->footer();

        $hipno = array_keys($svc->tasarimlar('tema-1'));
        $delogis = array_keys($svc->tasarimlar('tema-4'));

        $this->assertContains('klasik', $hipno, 'Hipno kendi orijinal footerini korumalı');
        $this->assertNotContains('genis', $hipno, 'Delogis tasarımı hipno kataloğunda görünmemeli');

        $this->assertContains('genis', $delogis, 'Delogis kendi orijinal footerini korumalı');
        $this->assertNotContains('klasik', $delogis);
    }

    public function test_secim_grup_bazinda_saklanir(): void
    {
        $svc = $this->footer();

        $this->assertSame('footer_tasarim_hipno', $svc->secimAnahtari('tema-1'));
        $this->assertSame('footer_tasarim_delogis', $svc->secimAnahtari('tema-4'));

        $this->ayarlar()->setOptions([
            'footer_tasarim_hipno' => 'koyu',
            'footer_tasarim_delogis' => 'merkezi',
        ]);

        // Tema değişse de her grubun kendi seçimi korunur
        $this->assertSame('koyu', $svc->aktifTasarim('tema-1'));
        $this->assertSame('merkezi', $svc->aktifTasarim('tema-4'));
    }

    public function test_gecersiz_secim_grup_varsayilanina_duser(): void
    {
        $svc = $this->footer();

        // Delogis'e ait bir kod hipno temasında geçersizdir
        $this->ayarlar()->setOption('footer_tasarim_hipno', 'genis');

        $this->assertSame('zarif', $svc->aktifTasarim('tema-1'));
        $this->assertSame('genis', $svc->aktifTasarim('tema-4'));
    }

    public function test_her_tasarimin_blade_dosyasi_mevcut(): void
    {
        foreach (['tema-1', 'tema-4'] as $temaId) {
            foreach ($this->footer()->tasarimlar($temaId) as $kod => $tasarim) {
                $view = 'frontend.partials.footer.'.$tasarim['view'];
                $this->assertTrue(
                    view()->exists($view),
                    "Footer tasarımı '{$kod}' için blade bulunamadı: {$view}"
                );
            }
        }
    }

    public function test_tum_tasarimlar_hatasiz_render_edilir(): void
    {
        $svc = $this->footer();
        $doktor = $this->ornekDoktor();

        foreach (['tema-1' => 'hipno', 'tema-4' => 'delogis'] as $temaId => $grup) {
            $this->ayarlar()->setOption('tema_id', $temaId);
            $doktor['tema_id'] = $temaId;

            foreach (array_keys($svc->tasarimlar($temaId)) as $kod) {
                $this->ayarlar()->setOption('footer_tasarim_'.$grup, $kod);

                $f = $svc->verisi($doktor);
                $html = view($svc->viewName($temaId, $f['ayar']), ['f' => $f, 'doktor' => $doktor])->render();

                $this->assertStringContainsString('Ayşe Yılmaz', $html, "Tasarım '{$kod}' hekim adını basmalı");
                $this->assertNotEmpty(trim($html));
            }
        }
    }

    public function test_ikonlar_font_awesome_5_sozdizimi_kullanir(): void
    {
        // delogis paketi FA 5.15 yüklüyor; "fa-solid/fa-brands" (FA6) orada
        // kutu (tofu) olarak çizilir. FA5 kisa adlari her iki surumde de calisir.
        $dosyalar = glob(resource_path('views/frontend/partials/footer/**/*.blade.php'), GLOB_BRACE) ?: [];
        $dosyalar = array_merge($dosyalar, glob(resource_path('views/frontend/partials/footer/*.blade.php')) ?: []);

        foreach ($dosyalar as $dosya) {
            $icerik = (string) file_get_contents($dosya);
            $this->assertStringNotContainsString('fa-solid', $icerik, basename($dosya).' FA6 sözdizimi içeriyor');
            $this->assertStringNotContainsString('fa-brands', $icerik, basename($dosya).' FA6 sözdizimi içeriyor');
            $this->assertStringNotContainsString('fa-regular', $icerik, basename($dosya).' FA6 sözdizimi içeriyor');
        }

        $this->assertStringNotContainsString(
            'fa-solid',
            (string) file_get_contents(app_path('Services/SiteFooterService.php'))
        );
    }

    public function test_desteklenmeyen_bloklar_gizlenir(): void
    {
        $svc = $this->footer();
        $this->ayarlar()->setOptions(['tema_id' => 'tema-1', 'footer_tasarim_hipno' => 'zarif']);

        $f = $svc->verisi($this->ornekDoktor(['tema_id' => 'tema-1']));

        // "zarif" tasarımı randevu sütunu içermez
        $this->assertFalse($f['goster']['randevu']);
        // ama iletişim bloğunu içerir
        $this->assertTrue($f['goster']['iletisim']);
    }

    public function test_panel_ayarlari_footer_verisine_yansir(): void
    {
        $this->ayarlar()->setOptions([
            'tema_id' => 'tema-1',
            'footer_tasarim_hipno' => 'sutunlu',
            'footer_aciklama' => 'Panelden girilen tanıtım.',
            'footer_telif' => '© {yil} {ad} · özel',
            'footer_baslik_kesfet' => 'Sayfalar',
            'footer_sosyal_goster' => '0',
        ]);

        $f = $this->footer()->verisi($this->ornekDoktor(['tema_id' => 'tema-1']));

        $this->assertSame('Panelden girilen tanıtım.', $f['aciklama']);
        $this->assertSame('© '.date('Y').' Uzm. Psk. Ayşe Yılmaz · özel', $f['telif']);
        $this->assertSame('Sayfalar', $f['baslik_kesfet']);
        $this->assertFalse($f['goster']['sosyal'], 'Sosyal kapatıldığında ikonlar basılmamalı');
    }

    public function test_aciklama_bos_ise_eski_footer_metnine_duser(): void
    {
        // Mevcut siteler bozulmasın: Genel sekmesindeki footer_metin korunur.
        $doktor = $this->ornekDoktor(['footer_metin' => 'Eski footer metni.']);

        $f = $this->footer()->verisi($doktor);

        $this->assertSame('Eski footer metni.', $f['aciklama']);
    }

    public function test_logo_kaynagi_panelden_secilir(): void
    {
        $svc = $this->footer();
        $doktor = $this->ornekDoktor(['logo' => 'https://ornek.test/logo.png']);

        $this->ayarlar()->setOption('footer_logo_tip', 'site');
        $this->assertSame('https://ornek.test/logo.png', $svc->verisi($doktor)['logo_url']);

        $this->ayarlar()->setOption('footer_logo_tip', 'yazi');
        $this->assertSame('', $svc->verisi($doktor)['logo_url']);
        $this->assertSame('yazi', $svc->verisi($doktor)['logo_tip']);

        // Site logosu yoksa yazıya düşer (boş logo alanı kalmaz)
        $this->ayarlar()->setOption('footer_logo_tip', 'site');
        $this->assertSame('yazi', $svc->verisi($this->ornekDoktor())['logo_tip']);
    }

    public function test_gecersiz_telefon_footerda_gosterilmez(): void
    {
        $f = $this->footer()->verisi($this->ornekDoktor([
            'telefon' => '0532 000 00 00',
            'telefon_raw' => '05320000000',
        ]));

        $this->assertFalse($f['telefon_gecerli'], 'Yer tutucu telefon numarası basılmamalı');
    }

    public function test_calisma_saatleri_tek_satira_indirgenir(): void
    {
        $f = $this->footer()->verisi($this->ornekDoktor([
            'calisma_saatleri' => [
                'Pazartesi' => ['aktif_mi' => true, 'mesai_baslangic' => '09:00', 'mesai_bitis' => '18:00'],
                'Salı' => ['aktif_mi' => true, 'mesai_baslangic' => '09:00', 'mesai_bitis' => '18:00'],
                'Cuma' => ['aktif_mi' => true, 'mesai_baslangic' => '09:00', 'mesai_bitis' => '18:00'],
                'Pazar' => ['aktif_mi' => false],
            ],
        ]));

        $this->assertSame('Pazartesi – Cuma 09:00 – 18:00', $f['saatler']);
    }

    public function test_footer_ayarlari_frontend_bundleina_eklenir(): void
    {
        $this->ayarlar()->setOptions(['tema_id' => 'tema-1', 'footer_tasarim_hipno' => 'koyu']);

        $bundle = $this->ayarlar()->frontendBundle();

        $this->assertArrayHasKey('ayarlar', $bundle['footer']);
        $this->assertSame('koyu', $bundle['footer']['ayarlar']['tasarim']);
        $this->assertSame('hipno', $bundle['footer']['ayarlar']['grup']);
    }
}
