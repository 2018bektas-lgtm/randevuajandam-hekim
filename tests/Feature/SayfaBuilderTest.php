<?php

namespace Tests\Feature;

use App\Models\SiteHomepageSection;
use App\Services\SiteBuilderService;
use App\Services\SiteSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SayfaBuilderTest extends TestCase
{
    use RefreshDatabase;

    private SiteBuilderService $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = app(SiteBuilderService::class);
        // Migration seed'ini temizle — bu testler sıfır durumundan başlar
        SiteHomepageSection::query()->delete();
        \App\Models\SiteOption::query()->delete();
    }

    public function test_default_seti_olusturur(): void
    {
        $eklenen = $this->builder->defaultSetiOlustur('tema-1');

        $this->assertGreaterThan(0, $eklenen);
        $sayi = SiteHomepageSection::where('tema_id', 'tema-1')->count();
        $this->assertEquals(12, $sayi); // config'te 12 modül tanımlı
    }

    public function test_default_seti_idempotent(): void
    {
        $this->builder->defaultSetiOlustur('tema-1');
        $eklenen2 = $this->builder->defaultSetiOlustur('tema-1');

        $this->assertEquals(0, $eklenen2, 'İkinci çağrıda yeni kayıt eklenmemeli.');
    }

    public function test_bilinmeyen_tema_boslugu_doner(): void
    {
        $eklenen = $this->builder->defaultSetiOlustur('yok-boyle-tema');
        $this->assertEquals(0, $eklenen);
    }

    public function test_aktif_tema_id_configden_okur(): void
    {
        $this->assertEquals('tema-1', $this->builder->aktifTemaId());
    }

    public function test_tema_secince_default_set_yaratir(): void
    {
        $this->builder->temaSec('tema-1');

        $this->assertEquals('tema-1', $this->builder->aktifTemaId());
        $this->assertGreaterThan(0, SiteHomepageSection::where('tema_id', 'tema-1')->count());
    }

    public function test_bilinmeyen_tema_secince_hata_atar(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->builder->temaSec('yok-boyle-tema');
    }

    public function test_render_icin_moduller_aktif_sirali_doner(): void
    {
        $this->builder->defaultSetiOlustur('tema-1');

        $sonuc = $this->builder->renderIcinModuller('tema-1');

        $this->assertGreaterThan(0, $sonuc->count());
        // Sıralama artan
        $prev = -1;
        foreach ($sonuc as $m) {
            $this->assertGreaterThanOrEqual($prev, $m['sira']);
            $prev = $m['sira'];
        }
        // Hero varsayılan zorunlu, aktif olmalı
        $this->assertTrue($sonuc->contains(fn ($m) => $m['kod'] === 'hero_static'));
    }

    public function test_modul_kaydet_ayar_merge_eder(): void
    {
        $this->builder->defaultSetiOlustur('tema-1');

        $this->builder->modulKaydet('tema-1', 'hero_static', [
            'ana_baslik' => 'Kendi Başlığım',
        ]);

        $kayit = SiteHomepageSection::where('tema_id', 'tema-1')->where('key', 'hero_static')->first();
        $this->assertEquals('Kendi Başlığım', $kayit->ozel_ayarlar['ana_baslik']);
        // Diğer varsayılan alanlar hâlâ mevcut
        $this->assertNotEmpty($kayit->ozel_ayarlar['cta_metin']);
    }

    public function test_sira_ve_aktif_toplu_guncelleme(): void
    {
        $this->builder->defaultSetiOlustur('tema-1');

        $this->builder->siraAktifKaydet('tema-1', [
            ['kod' => 'about', 'aktif' => false, 'sira' => 5],
            ['kod' => 'hero_static', 'aktif' => true, 'sira' => 10],
        ]);

        $about = SiteHomepageSection::where('tema_id', 'tema-1')->where('key', 'about')->first();
        $this->assertFalse($about->aktif);
        $this->assertEquals(5, $about->sira);
    }

    public function test_palet_hazir_secim_kaydeder(): void
    {
        $this->builder->paletSec('acik-mavi');

        $palet = $this->builder->aktifPalet();
        $this->assertEquals('acik-mavi', $palet['kod']);
        $this->assertEquals('#2E5C8A', $palet['primary']);
    }

    public function test_palet_ozel_deger_kaydeder(): void
    {
        $this->builder->paletSec('ozel', [
            'primary' => '#123456',
            'accent' => '#abcdef',
            'bg' => '#ffffff',
            'text' => '#000000',
            'text_light' => '#eeeeee',
        ]);

        $palet = $this->builder->aktifPalet();
        $this->assertEquals('ozel', $palet['kod']);
        $this->assertEquals('#123456', $palet['primary']);
    }

    public function test_bilinmeyen_palet_secince_hata_atar(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->builder->paletSec('yok-boyle-palet');
    }

    public function test_tema_2_hipno_slider_default_set(): void
    {
        $eklenen = $this->builder->defaultSetiOlustur('tema-2');
        $this->assertEquals(12, $eklenen);
        $this->assertTrue(SiteHomepageSection::where('tema_id', 'tema-2')->where('key', 'hero_slider')->exists());
        $this->assertFalse(SiteHomepageSection::where('tema_id', 'tema-2')->where('key', 'hero_static')->exists());
    }

    public function test_tema_3_hipno_video_default_set(): void
    {
        $eklenen = $this->builder->defaultSetiOlustur('tema-3');
        $this->assertEquals(12, $eklenen);
        $this->assertTrue(SiteHomepageSection::where('tema_id', 'tema-3')->where('key', 'hero_video')->exists());
        $this->assertFalse(SiteHomepageSection::where('tema_id', 'tema-3')->where('key', 'hero_static')->exists());
    }

    public function test_tema_secme_ile_gecis(): void
    {
        $this->builder->temaSec('tema-1');
        $this->builder->temaSec('tema-2');

        $this->assertEquals('tema-2', $this->builder->aktifTemaId());
        // Her iki tema kaydı da DB'de duruyor (silme yok, sadece aktif geçiş)
        $this->assertEquals(12, SiteHomepageSection::where('tema_id', 'tema-1')->count());
        $this->assertEquals(12, SiteHomepageSection::where('tema_id', 'tema-2')->count());
    }
}
