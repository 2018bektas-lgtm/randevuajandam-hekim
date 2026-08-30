<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Panelde görünen her modül alanı sayfada bir işe yaramalı.
 *
 * Bildirilen sorun: "Hipno temasında CTA görseli statik geliyor; randevu
 * bandındaki resmi yükledim ama ana sayfaya gelmiyor."
 *
 * Üretim verisinde görsel GERÇEKTEN kayıtlıydı:
 *
 *   cta / tema-2 -> {"arkaplan_resmi": "/storage/uploads/panel/2026-08/….png"}
 *
 * Fakat `cta.blade.php` yalnızca `resim` alanını okuyor, `arkaplan_resmi`'ni
 * hiç kullanmıyordu. Yani panel alanı görünüyor, yükleme çalışıyor, kayıt
 * tutuyor — ama sayfada hiçbir etkisi yok. Kullanıcı için bu, sessizce
 * kaybolan bir iş demek.
 *
 * Tarama Hipno temalarında aynı sınıftan beş alan buldu:
 *   cta > arkaplan_resmi · about > resim_2 · services > aciklama
 *   case_study > aciklama · blog > aciklama
 *
 * Bu test aynı taramayı yapar: config'te tanımlı her alanın ilgili blade
 * tarafından okunduğunu doğrular.
 *
 * İki meşru istisna var:
 *  - `db_kaynak` tipi alanlar: panelde "bu bölüm şu tablodan besleniyor"
 *    bilgisini gösteren salt okunur etiketler; blade'in okuması beklenmez.
 *  - Dinamik kurulan anahtarlar (ör. `$ayar["sayac_{$i}_sayi"]`); blade'de
 *    düz metin olarak geçmezler.
 */
class ModulOluAlanTest extends TestCase
{
    /**
     * Blade'de düz metin olarak geçmeyen, döngüyle kurulan alan adları.
     *
     * Örnek: what_we_do / counters modülleri sayaçları
     * `$ayar["sayac_{$i}_sayi"]` biçiminde okur.
     */
    private const DINAMIK_DESENLER = [
        '~^sayac_\d+_(sayi|ek|etiket)$~',
    ];

    /**
     * @return array<string, array{0: string}>
     */
    public static function temaSaglayici(): array
    {
        $config = require dirname(__DIR__, 2).'/config/tema_modulleri.php';

        $out = [];
        foreach (array_keys((array) ($config['temalar'] ?? [])) as $temaId) {
            $out[(string) $temaId] = [(string) $temaId];
        }

        return $out;
    }

    private function modulBlade(string $temaId, string $kod): ?string
    {
        $pack = function_exists('theme_pack_id') ? theme_pack_id($temaId) : $temaId;

        foreach (array_unique([$pack, $temaId]) as $klasor) {
            $yol = resource_path("views/frontend/themes/{$klasor}/modules/{$kod}.blade.php");
            if (is_file($yol)) {
                return $yol;
            }
        }

        return null;
    }

    private function dinamikMi(string $alan): bool
    {
        foreach (self::DINAMIK_DESENLER as $desen) {
            if (preg_match($desen, $alan)) {
                return true;
            }
        }

        return false;
    }

    #[DataProvider('temaSaglayici')]
    public function test_panel_alanlari_sayfada_kullaniliyor(string $temaId): void
    {
        $moduller = (array) config("tema_modulleri.temalar.{$temaId}.moduller", []);
        $this->assertNotEmpty($moduller, "{$temaId}: modul tanimi yok.");

        $olu = [];

        foreach ($moduller as $kod => $modul) {
            $yol = $this->modulBlade($temaId, (string) $kod);
            if ($yol === null) {
                continue;   // blade'i olmayan modul cizilmiyor zaten
            }

            $icerik = (string) file_get_contents($yol);

            foreach ((array) ($modul['alanlar'] ?? []) as $alan => $tanim) {
                $alan = (string) $alan;

                // Salt bilgi amacli etiket; blade okumaz
                if (($tanim['tip'] ?? '') === 'db_kaynak') {
                    continue;
                }
                if ($this->dinamikMi($alan)) {
                    continue;
                }

                $kullaniliyor = str_contains($icerik, "'{$alan}'")
                    || str_contains($icerik, "\"{$alan}\"")
                    || str_contains($icerik, '$'.$alan);

                if (! $kullaniliyor) {
                    $olu[] = "{$kod} > {$alan}";
                }
            }
        }

        $this->assertSame([], $olu, sprintf(
            "%s: panelde gorunen ama sayfada HIC kullanilmayan alanlar var.\n".
            "Hekim bunlari doldurur/yukler, kaydedilir ve hicbir sey degismez:\n  - %s",
            $temaId,
            implode("\n  - ", $olu)
        ));
    }

    /**
     * Bildirilen alanın kendisi: CTA arkaplan görseli gerçekten çizilmeli.
     */
    #[DataProvider('hipnoTemasi')]
    public function test_cta_arkaplan_gorseli_cizilir(string $temaId): void
    {
        $html = view("frontend.themes.{$temaId}.modules.cta", [
            'doktor' => ['ad_soyad' => 'Ayse Yilmaz', 'unvan' => 'Uzm. Psk.', 'tema_id' => $temaId],
            'ayar' => ['arkaplan_resmi' => '/storage/uploads/panel/2026-08/ornek.png'],
        ])->render();

        $this->assertStringContainsString(
            '/storage/uploads/panel/2026-08/ornek.png',
            $html,
            "{$temaId}: yuklenen CTA arkaplan gorseli sayfada cizilmiyor."
        );
    }

    /**
     * Görsel yoksa arkaplan stili hiç basılmamalı (boş url üretmesin).
     */
    #[DataProvider('hipnoTemasi')]
    public function test_arkaplan_yoksa_stil_basilmaz(string $temaId): void
    {
        $html = view("frontend.themes.{$temaId}.modules.cta", [
            'doktor' => ['ad_soyad' => 'Ayse Yilmaz', 'unvan' => 'Uzm. Psk.', 'tema_id' => $temaId],
            'ayar' => [],
        ])->render();

        $this->assertStringNotContainsString(
            'background-image:url()',
            $html,
            "{$temaId}: bos arkaplan url'i basiliyor."
        );
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function hipnoTemasi(): array
    {
        return ['tema-1' => ['tema-1'], 'tema-2' => ['tema-2'], 'tema-3' => ['tema-3']];
    }

    /**
     * TERS YÖN: blade'in okuduğu her `$ayar[...]` anahtarı panelde de
     * sunulmalı.
     *
     * Bildirilen sorunun gerçek kök nedeni buydu: `cta.blade.php`
     * `$ayar['resim']` okuyup yoksa tema görseline (cta-img.png) düşüyor,
     * ama tema-2 ve tema-3'te panel bu alanı HİÇ sunmuyordu. Hekimin
     * banttaki görseli değiştirmesinin yolu yoktu; ne yüklerse yüklesin
     * sabit tema görseli kalıyordu.
     */
    #[DataProvider('temaSaglayici')]
    public function test_bladein_okudugu_alanlar_panelde_sunuluyor(string $temaId): void
    {
        $moduller = (array) config("tema_modulleri.temalar.{$temaId}.moduller", []);
        $eksik = [];

        foreach ($moduller as $kod => $modul) {
            $yol = $this->modulBlade($temaId, (string) $kod);
            if ($yol === null) {
                continue;
            }

            $icerik = (string) file_get_contents($yol);
            $tanimli = array_keys((array) ($modul['alanlar'] ?? []));

            // $ayar['x'] / $ayar["x"] biciminde okunan anahtarlar
            preg_match_all('~\$ayar\[[\'"]([a-z0-9_]+)[\'"]\]~', $icerik, $m);

            foreach (array_unique($m[1]) as $anahtar) {
                if (! in_array($anahtar, $tanimli, true)) {
                    $eksik[] = "{$kod} > {$anahtar}";
                }
            }
        }

        $eksik = array_values(array_unique($eksik));

        $this->assertSame([], $eksik, sprintf(
            '%s: blade bu alanlari okuyor ama PANEL SUNMUYOR. '.
            'Hekim bu degerleri hicbir yerden giremez: %s',
            $temaId,
            implode(', ', $eksik)
        ));
    }
}
