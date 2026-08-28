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

    public function test_render_aktif_temayi_kullanir(): void
    {
        $this->builder->temaSec('tema-2');
        $sonuc = $this->builder->renderIcinModuller();

        $this->assertTrue($sonuc->contains(fn ($m) => $m['kod'] === 'hero_slider'));
        $this->assertFalse($sonuc->contains(fn ($m) => $m['kod'] === 'hero_static'));
    }

    public function test_palet_vurgu_rengini_tema_renk_ile_senkronlar(): void
    {
        $this->builder->paletSec('acik-mavi');

        $this->assertEquals('#7BA7CF', app(SiteSettingsService::class)->option('tema_renk'));
    }

    public function test_vurgu_rengi_palet_accent_yazar(): void
    {
        $this->builder->vurguRenginiAyarla('#112233');

        $palet = $this->builder->aktifPalet();
        $this->assertEquals('ozel', $palet['kod']);
        $this->assertEquals('#112233', $palet['accent']);
        $this->assertEquals('#112233', app(SiteSettingsService::class)->option('tema_renk'));
    }

    public function test_delogis_temalar_katalogda(): void
    {
        foreach (['tema-4', 'tema-5', 'tema-6', 'tema-7', 'tema-8', 'tema-9'] as $id) {
            $this->assertTrue($this->builder->temaVarMi($id), $id.' tanımsız');
            $this->assertEquals('delogis', theme_pack_id($id));
        }
        $this->assertEquals(1, delogis_home_variant('tema-4'));
        $this->assertEquals(2, delogis_home_variant('tema-5'));
        $this->assertEquals(5, delogis_home_variant('tema-8'));
        $this->assertTrue(delogis_is_boxed('tema-9'));
        $this->assertFalse(delogis_is_boxed('tema-4'));
    }

    public function test_delogis_home1_default_set_ve_blade(): void
    {
        $eklenen = $this->builder->defaultSetiOlustur('tema-4');
        $this->assertEquals(11, $eklenen);
        $this->assertTrue(SiteHomepageSection::where('tema_id', 'tema-4')->where('key', 'hero')->exists());

        $sonuc = $this->builder->renderIcinModuller('tema-4');
        $this->assertGreaterThan(0, $sonuc->count());
        $this->assertTrue($sonuc->contains(fn ($m) => $m['kod'] === 'hero'));
        $this->assertTrue($sonuc->contains(fn ($m) => $m['blade'] === 'frontend.themes.delogis.modules.hero'));
        $this->assertTrue(view()->exists('frontend.themes.delogis.modules.hero'));
        $this->assertTrue(view()->exists('frontend.themes.delogis.modules.about'));
        $this->assertTrue(view()->exists('frontend.themes.delogis.modules.services'));
    }

    public function test_delogis_boxed_home1_ile_ayni_moduller(): void
    {
        $this->builder->defaultSetiOlustur('tema-9');
        $this->assertTrue(SiteHomepageSection::where('tema_id', 'tema-9')->where('key', 'get_started')->exists());
        $this->assertFalse(SiteHomepageSection::where('tema_id', 'tema-9')->where('key', 'video')->exists());
    }

    public function test_delogis_home5_video_modulu(): void
    {
        $this->builder->defaultSetiOlustur('tema-8');
        $this->assertTrue(SiteHomepageSection::where('tema_id', 'tema-8')->where('key', 'video')->exists());
        $this->assertTrue(view()->exists('frontend.themes.delogis.modules.video'));
        $sonuc = $this->builder->renderIcinModuller('tema-8');
        $this->assertTrue($sonuc->contains(fn ($m) => $m['kod'] === 'hero'));
        $this->assertTrue($sonuc->contains(fn ($m) => $m['kod'] === 'video'));
    }

    public function test_delogis_palet_secimi(): void
    {
        $this->builder->temaSec('tema-4');
        $this->builder->paletSec('altin');
        $palet = $this->builder->aktifPalet('tema-4');
        $this->assertEquals('altin', $palet['kod']);
        $this->assertEquals('#B9905D', $palet['accent']);
    }

    public function test_delogis_moduller_html_uretir(): void
    {
        $doktor = [
            'ad_soyad' => 'Test Hekim',
            'unvan' => 'Uzm. Dr.',
            'uzmanlik' => 'Psikoloji',
            'profil_resmi' => '/themes/delogis/images/resources/about-one-img-1.jpg',
            'telefon' => '0532 000 00 00',
            'hizmetler' => [
                ['baslik' => 'Bireysel terapi', 'slug' => 'bireysel', 'aciklama' => 'Kişiye özel seans'],
                ['baslik' => 'Çift terapisi', 'slug' => 'cift', 'aciklama' => 'İlişki çalışması'],
            ],
            'yorumlar' => [['yorum' => 'Çok yardımcı oldu', 'hasta_adi' => 'Ayşe', 'puan' => 5]],
            'bloglar' => [['baslik' => 'İlk yazı', 'slug' => 'ilk-yazi']],
            'galeri' => [['image' => '/themes/delogis/images/gallery/gallery-page-1-1.jpg', 'baslik' => 'Klinik']],
            'sss' => [['soru' => 'Seans kaç dakika?', 'cevap' => '45-50 dakika.']],
        ];

        foreach (['tema-4', 'tema-5', 'tema-6', 'tema-7', 'tema-8', 'tema-9'] as $temaId) {
            $this->builder->defaultSetiOlustur($temaId);
            $sonuc = $this->builder->renderIcinModuller($temaId);
            $this->assertGreaterThan(0, $sonuc->count(), $temaId.' boş render');
            foreach ($sonuc as $m) {
                $html = view($m['blade'], [
                    'ayar' => $m['ayar'],
                    'doktor' => $doktor,
                ])->render();
                $this->assertIsString($html);
                $this->assertTrue(
                    strlen($html) > 20,
                    $temaId.' / '.$m['kod'].' boş HTML'
                );
            }
        }
    }
}
