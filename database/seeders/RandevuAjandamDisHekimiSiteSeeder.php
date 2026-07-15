<?php

namespace Database\Seeders;

use App\Models\SiteHomepageSection;
use App\Models\SiteOption;
use Illuminate\Database\Seeder;

class RandevuAjandamDisHekimiSiteSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            'site_baslik_ek' => 'Genel Diş Hekimi',
            'slogan_override' => 'Sağlıklı ve doğal gülüşler için yanınızdayız.',
            'footer_metin' => 'Dt. Randevu Ajandam ile ağız ve diş sağlığınız için güvenilir bakım.',
            'tema_id' => 'klasik',
            'tema_renk' => '#0d9488',
            'vitrin_badge' => 'Genel Diş Hekimliği',
            'seo_meta_baslik' => 'Dt. Randevu Ajandam | Genel Diş Hekimi',
            'seo_meta_aciklama' => 'Genel diş hekimliği, koruyucu bakım ve estetik gülüş uygulamaları için online randevu.',
            'seo_meta_anahtar' => 'genel diş hekimi, diş sağlığı, diş taşı temizliği, estetik dolgu, diş beyazlatma',
            'iletisim_baslik' => 'İletişim ve online randevu',
            'iletisim_alt_metin' => 'Size uygun randevu saatini seçin; talebiniz onay sonrası kesinleşsin.',
            'iletisim_form_goster' => '1',
            'iletisim_harita_goster' => '1',
            'iletisim_saatler_goster' => '1',
        ];
        foreach ($options as $key => $value) {
            SiteOption::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $sections = [
            ['key' => 'hakkimda', 'label' => 'Hakkımda özeti', 'baslik' => 'Gülüşünüz için özenli bakım', 'alt_metin' => 'Koruyucu, fonksiyonel ve estetik diş hekimliği uygulamaları.'],
            ['key' => 'hizmetler', 'label' => 'Hizmetler', 'baslik' => 'Diş sağlığınız için hizmetlerimiz', 'alt_metin' => 'İhtiyacınıza uygun tedavi planı ve düzenli takip.'],
            ['key' => 'surec', 'label' => 'Süreç adımları', 'baslik' => 'Randevu süreci', 'alt_metin' => 'Randevu oluşturun, talebiniz onaylansın ve kliniğimize gelin.'],
            ['key' => 'blog', 'label' => 'Blog', 'baslik' => 'Ağız ve diş sağlığı rehberi', 'alt_metin' => 'Günlük bakım ve sağlıklı gülüş hakkında faydalı bilgiler.'],
            ['key' => 'cta', 'label' => 'Alt CTA bandı', 'baslik' => 'Sağlıklı bir gülüş için ilk adımı atın', 'alt_metin' => 'Online randevu talebinizi birkaç adımda oluşturun.'],
        ];
        foreach ($sections as $index => $section) {
            SiteHomepageSection::query()->updateOrCreate(
                ['key' => $section['key']],
                array_merge($section, ['aktif' => true, 'sira' => $index + 4])
            );
        }
    }
}
