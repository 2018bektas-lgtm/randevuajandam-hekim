<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Hipno anasayfa modülleri: içerik verisi statik olmamalı.
 *
 * Bildirilen sorun: "hiçbir şey statik olmayacak, hepsi dinamik olacak;
 * bazı veriler hâlâ statik duruyor."
 *
 * İki gerçek sorun bulundu ve düzeltildi:
 *
 * 1) appointment modülü sabit `'Pzt - Cmt 09:00 - 21:00'` gösteriyordu.
 *    Hekimin GERÇEK çalışma saatleri `calisma_saatleri_ozet` olarak zaten
 *    mevcut. Yanlış saat göstermek, hiç göstermemekten kötü.
 *
 * 2) what_we_do modülü sabit "200k mutlu danışan", "%97 memnuniyet",
 *    "12+ yıllık deneyim", "40+ tedavi programı" basıyordu. Bunlar hekimin
 *    hiç vermediği, uydurulmuş iddialardı — sağlık alanında bir sitede
 *    gerçek gibi görünüyordu.
 *
 * Not: başlık/etiket varsayılanları ("Randevu", "Hizmetler" gibi) statik
 * sayılmaz; onlar panelde özelleştirilebilen arayüz metinleridir.
 */
class ModulDinamikVeriTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function temaSaglayici(): array
    {
        return ['tema-1' => ['tema-1'], 'tema-2' => ['tema-2'], 'tema-3' => ['tema-3']];
    }

    /**
     * @param  array<string, mixed>  $ek
     * @return array<string, mixed>
     */
    private function doktor(array $ek = []): array
    {
        return array_merge([
            'id' => 1,
            'ad_soyad' => 'Ayse Yilmaz',
            'unvan' => 'Uzm. Psk.',
            'telefon' => '0 (532) 111 22 33',
            'telefon_raw' => '05321112233',
            'hizmetler' => [],
            'bloglar' => [],
            'galeri' => [],
            'slider' => [],
            'istatistikler' => [],
            'yorumlar' => [],
            'sss' => [],
            'ozellikler' => [],
        ], $ek);
    }

    private function modulCiz(string $tema, string $modul, array $doktor, array $ayar = []): string
    {
        return view("frontend.themes.{$tema}.modules.{$modul}", [
            'doktor' => array_merge($doktor, ['tema_id' => $tema]),
            'ayar' => $ayar,
        ])->render();
    }

    // ----------------------------------------------------- çalışma saatleri

    #[DataProvider('temaSaglayici')]
    public function test_calisma_saati_sabit_metin_icermez(string $tema): void
    {
        $html = $this->modulCiz($tema, 'appointment', $this->doktor());

        $this->assertStringNotContainsString(
            'Pzt - Cmt 09:00 - 21:00',
            $html,
            "{$tema}: sabit calisma saati hala basiliyor."
        );
    }

    #[DataProvider('temaSaglayici')]
    public function test_hekimin_gercek_calisma_saati_gosterilir(string $tema): void
    {
        $html = $this->modulCiz($tema, 'appointment', $this->doktor([
            'calisma_saatleri_ozet' => 'Hafta içi 10:00 – 18:00',
        ]));

        $this->assertStringContainsString('Hafta içi 10:00 – 18:00', $html);
    }

    #[DataProvider('temaSaglayici')]
    public function test_calisma_saati_yoksa_blok_gizlenir(string $tema): void
    {
        $html = $this->modulCiz($tema, 'appointment', $this->doktor());

        // Saat bilgisi yoksa "Çalışma saatleri" başlığı da basılmamalı
        $this->assertStringNotContainsString('Çalışma saatleri', $html);
    }

    #[DataProvider('temaSaglayici')]
    public function test_panel_ayari_gercek_saatin_onune_gecer(string $tema): void
    {
        $html = $this->modulCiz($tema, 'appointment', $this->doktor([
            'calisma_saatleri_ozet' => 'Hafta içi 10:00 – 18:00',
        ]), ['calisma_saatleri' => 'Randevu ile']);

        $this->assertStringContainsString('Randevu ile', $html);
        $this->assertStringNotContainsString('Hafta içi 10:00 – 18:00', $html);
    }

    // ------------------------------------------------------------ sayaçlar

    /**
     * @return array<string, array{0: string}>
     */
    public static function uydurmaSayacSaglayici(): array
    {
        return [
            'mutlu danisan' => ['mutlu danışan'],
            'memnuniyet' => ['memnuniyet'],
            'yillik deneyim' => ['yıllık deneyim'],
            'tedavi programi' => ['tedavi programı'],
        ];
    }

    #[DataProvider('uydurmaSayacSaglayici')]
    public function test_uydurma_istatistik_basilmaz(string $metin): void
    {
        $html = $this->modulCiz('tema-2', 'what_we_do', $this->doktor());

        $this->assertStringNotContainsString(
            $metin,
            $html,
            "Uydurma istatistik hala basiliyor: {$metin}"
        );
    }

    #[DataProvider('temaSaglayici')]
    public function test_istatistik_yoksa_sayac_bolumu_gizlenir(string $tema): void
    {
        $html = $this->modulCiz($tema, 'what_we_do', $this->doktor());

        $this->assertStringNotContainsString('intro-video-counter', $html);
    }

    #[DataProvider('temaSaglayici')]
    public function test_gercek_istatistikler_gosterilir(string $tema): void
    {
        $html = $this->modulCiz($tema, 'what_we_do', $this->doktor([
            'istatistikler' => [
                ['deger' => 8, 'suffix' => '+', 'etiket' => 'Yıllık Deneyim'],
                ['deger' => 4.8, 'suffix' => '', 'etiket' => 'Ortalama Puan'],
            ],
        ]));

        $this->assertStringContainsString('intro-video-counter', $html);
        $this->assertStringContainsString('Yıllık Deneyim', $html);
        $this->assertStringContainsString('Ortalama Puan', $html);
    }

    public function test_panel_sayaci_gercek_veriden_oncelikli(): void
    {
        $html = $this->modulCiz('tema-2', 'what_we_do', $this->doktor([
            'istatistikler' => [['deger' => 8, 'suffix' => '+', 'etiket' => 'Yıllık Deneyim']],
        ]), [
            'sayac_1_sayi' => '15',
            'sayac_1_ek' => '+',
            'sayac_1_etiket' => 'Yıl',
        ]);

        $this->assertStringContainsString('Yıl', $html);
        $this->assertStringNotContainsString('Yıllık Deneyim', $html);
    }
}
