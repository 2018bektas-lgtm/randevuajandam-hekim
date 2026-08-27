<?php

/**
 * Tema + Modül registry.
 *
 * Her tema kendi modül setine, renk paletine ve anasayfa layout'una sahiptir.
 * Modüller hekim tarafından açılıp kapatılabilir, sıralanabilir ve düzenlenebilir
 * (bkz. HekimSayfaBuilderController).
 *
 * Kaynak HTML paketleri: /html-hekim-tema/tema-{N}/
 * Blade karşılıkları: resources/views/frontend/themes/tema-{N}/modules/{kod}.blade.php
 *
 * Tema-1 Hipno Klasik (statik hero), tema-2 Hipno Slider, tema-3 Hipno Video.
 * Her temanın modül seti ayrıdır; hekim tema seçince o set seed edilir ve düzenlenir.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Varsayılan tema
    |--------------------------------------------------------------------------
    | Yeni hekim tema seçmeden site kurarsa buradan yüklenir.
    */
    'default_tema' => 'tema-1',

    /*
    |--------------------------------------------------------------------------
    | Modül alan tipleri (form input türleri)
    |--------------------------------------------------------------------------
    | Sayfa builder modül düzenle modal'ında bu tiplere göre form üretilir.
    */
    'alan_tipleri' => [
        'metin' => 'Tek satır metin',
        'uzun_metin' => 'Çok satırlı metin (textarea)',
        'sayi' => 'Sayısal değer',
        'resim' => 'Resim yükle (dosya)',
        'liste' => 'Sıralı liste (satır satır)',
        'ikon_baslik_metin' => 'İkon + Başlık + Metin (kart listesi)',
        'resim_baslik_metin' => 'Resim yükle + Başlık + Metin (slayt/kart listesi)',
        'renk' => 'Renk seçici',
        'db_kaynak' => 'DB kaynağı (blog/yorum/hizmet vs. — hekim panelinde ayrı düzenlenir)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tema kataloğu
    |--------------------------------------------------------------------------
    */
    'temalar' => [

        // ============================================================
        // TEMA-1 — Hipno Klasik (MVP)
        // ============================================================
        'tema-1' => [
            'ad' => 'Hipno Klasik',
            'aciklama' => 'Psikoloji ve danışmanlık için premium tasarım. Koyu zemin, altın vurgu, serif başlıklar.',
            'onizleme' => '/uploads/tema-onizleme/tema-1.jpg',
            'kaynak_html' => 'tema-1/html.awaikenthemes.com/hipno/index.html',
            'onerilen_uzmanlik' => ['psikoloji', 'psikiyatri', 'danışmanlık'],

            // Renk paletleri — hekim varsayılandan farklı seçerse hekim_web_siteleri.renk_paleti override eder
            'renk_paletleri' => [
                'koyu-altin' => [
                    'ad' => 'Koyu & Altın (varsayılan)',
                    'primary' => '#262626',
                    'accent' => '#9B9A84',
                    'bg' => '#F9F9F9',
                    'text' => '#333333',
                    'text_light' => '#FFFFFF',
                ],
                'acik-mavi' => [
                    'ad' => 'Açık & Mavi',
                    'primary' => '#2E5C8A',
                    'accent' => '#7BA7CF',
                    'bg' => '#F0F4F8',
                    'text' => '#1A2E42',
                    'text_light' => '#FFFFFF',
                ],
                'doga-yesil' => [
                    'ad' => 'Doğal Yeşil',
                    'primary' => '#4A5C3E',
                    'accent' => '#C4A76D',
                    'bg' => '#F5F3EE',
                    'text' => '#2A2A2A',
                    'text_light' => '#FFFFFF',
                ],
            ],
            'varsayilan_palet' => 'koyu-altin',

            // Bu temadaki modüller — sırayla varsayılan aktif olarak eklenir
            'moduller' => [
                'hero_static' => [
                    'ad' => 'Hero (Statik)',
                    'kategori' => 'ust',
                    'sira' => 10,
                    'aktif_varsayilan' => true,
                    'silinebilir' => false, // Zorunlu modül (hero olmadan sayfa olmaz)
                    'aciklama' => 'Sayfanın en üstünde büyük başlık + CTA + arkaplan resmi.',
                    'alanlar' => [
                        'ust_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hoş Geldiniz'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık (H1)', 'varsayilan' => 'Ruh sağlığı yolculuğunuzda yanınızdayım'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama Paragrafı', 'varsayilan' => 'Bireysel, çift ve aile danışmanlığı ile hayatınızdaki değişimlere destek oluyorum.'],
                        'cta_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
                        'sosyal_kanit_goster' => ['tip' => 'sayi', 'label' => 'Sosyal Kanıt (0=gizle, 1=göster)', 'varsayilan' => 1],
                        'sosyal_kanit_sayi' => ['tip' => 'sayi', 'label' => 'Danışan Sayısı', 'varsayilan' => 100],
                        'sosyal_kanit_metin' => ['tip' => 'metin', 'label' => 'Sosyal Kanıt Metni', 'varsayilan' => 'Mutlu Danışan'],
                        'uzmanlik_listesi' => ['tip' => 'liste', 'label' => 'Uzmanlık Alanları (satır satır)', 'varsayilan' => "Bireysel Danışmanlık\nÇift Terapisi\nAile Terapisi\nÇocuk/Ergen Danışmanlığı"],
                        'arkaplan_resmi' => ['tip' => 'resim', 'label' => 'Hero Arkaplan Resmi (opsiyonel, boş bırakırsanız profil resminiz kullanılır)', 'varsayilan' => null],
                    ],
                ],

                'about' => [
                    'ad' => 'Hakkımda',
                    'kategori' => 'orta',
                    'sira' => 20,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'İki fotoğraflı hakkımda bölümü + misyon/vizyon.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hakkımda'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Deneyim ve empati ile birlikte'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Ana Açıklama', 'varsayilan' => 'Onlarca yıllık klinik deneyimimle, danışanlarımın hayatlarında kalıcı değişimler yaratmayı hedefliyorum.'],
                        'misyon_baslik' => ['tip' => 'metin', 'label' => 'Misyon Başlığı', 'varsayilan' => 'Misyonumuz'],
                        'misyon_metin' => ['tip' => 'uzun_metin', 'label' => 'Misyon Metni', 'varsayilan' => 'Her danışanın kendine özgü yolunda ona rehberlik etmek.'],
                        'vizyon_baslik' => ['tip' => 'metin', 'label' => 'Vizyon Başlığı', 'varsayilan' => 'Vizyonumuz'],
                        'vizyon_metin' => ['tip' => 'uzun_metin', 'label' => 'Vizyon Metni', 'varsayilan' => 'Ruh sağlığı hizmetine erişimi kolaylaştırmak.'],
                        'misyon_maddeler' => ['tip' => 'liste', 'label' => 'Misyon maddeleri (satır satır, opsiyonel)', 'varsayilan' => "Şefkatli ve yargısız alan\nKanıta dayalı yaklaşım\nGizlilik ve güven"],
                        'resim_1' => ['tip' => 'resim', 'label' => 'Fotoğraf 1', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Fotoğraf 2 (opsiyonel)', 'varsayilan' => null],
                        'buton_1' => ['tip' => 'metin', 'label' => 'Sol buton', 'varsayilan' => 'Daha fazla'],
                        'buton_2' => ['tip' => 'metin', 'label' => 'Sağ buton', 'varsayilan' => 'İletişim'],
                    ],
                ],

                'services' => [
                    'ad' => 'Hizmetler',
                    'kategori' => 'orta',
                    'sira' => 30,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Hekim panelindeki "Hizmetler" bölümünden çekilir. Kart ızgarası olarak render edilir.',
                    'alanlar' => [
                        'bolum_baslik' => ['tip' => 'metin', 'label' => 'Bölüm Başlığı', 'varsayilan' => 'Sunduğum Hizmetler'],
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hizmetlerim'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Her danışanın ihtiyacına özel programlar sunuyorum.'],
                        'hizmet_limiti' => ['tip' => 'sayi', 'label' => 'Anasayfada Gösterilecek Hizmet Sayısı', 'varsayilan' => 6],
                        'hizmet_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hekim Paneli → Hizmetler', 'varsayilan' => 'hizmetler'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Sağ buton', 'varsayilan' => 'Tüm hizmetler'],
                        'alt_cta' => ['tip' => 'metin', 'label' => 'Alt bant metin (boş = gizle)', 'varsayilan' => ''],
                    ],
                ],

                'why_choose' => [
                    'ad' => 'Neden Ben?',
                    'kategori' => 'orta',
                    'sira' => 40,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'İkon + başlık + kısa metin şeklinde 4 sebep.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Neden Ben?'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Farkımı yaratan yaklaşımlar'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Danışanlarımın güvenini kazanan üç temel yaklaşım.'],
                        'resim' => ['tip' => 'resim', 'label' => 'Sol büyük fotoğraf', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Alt küçük fotoğraf', 'varsayilan' => null],
                        'govde_baslik' => ['tip' => 'metin', 'label' => 'Alt blok başlık', 'varsayilan' => 'Ruh sağlığınız için yanınızdayım'],
                        'govde_metin' => ['tip' => 'uzun_metin', 'label' => 'Alt blok metin', 'varsayilan' => 'Kanıta dayalı, kişiye özel ve şefkatli bir süreçle yanınızdayım.'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Alt buton', 'varsayilan' => 'İletişim'],
                        'sebepler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Sebep Kartları (max 4)', 'varsayilan' => [
                            ['ikon' => 'fa-heart', 'baslik' => 'Empati Odaklı', 'metin' => 'Her danışanı yargısız ve şefkatli bir alanda karşılıyorum.'],
                            ['ikon' => 'fa-shield-halved', 'baslik' => 'Gizlilik Garantisi', 'metin' => 'Görüşmeleriniz tamamen gizlidir, güvenli bir alan sunarım.'],
                            ['ikon' => 'fa-user-doctor', 'baslik' => 'Bilimsel Yaklaşım', 'metin' => 'Kanıta dayalı terapi teknikleri uyguluyorum.'],
                            ['ikon' => 'fa-clock', 'baslik' => 'Esnek Randevu', 'metin' => 'Programınıza uygun online ve yüz yüze seçenekler.'],
                        ]],
                    ],
                ],

                'what_we_do' => [
                    'ad' => 'Ne Yapıyorum?',
                    'kategori' => 'orta',
                    'sira' => 50,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Video arka plan + 4 sayaç (Hipno intro-video-box).',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Yaklaşımım'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarıma sunduğum destek'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Farklı ihtiyaçlara özel yaklaşımlar.'],
                        'video_url' => ['tip' => 'metin', 'label' => 'Video URL (mp4, boş = tema videosu)', 'varsayilan' => ''],
                        'youtube_id' => ['tip' => 'metin', 'label' => 'YouTube video ID (opsiyonel)', 'varsayilan' => ''],
                        'poster' => ['tip' => 'resim', 'label' => 'Video kapak görseli', 'varsayilan' => null],
                        'sayac_1_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 1 sayı', 'varsayilan' => '200'],
                        'sayac_1_ek' => ['tip' => 'metin', 'label' => 'Sayaç 1 ek (k / % / +)', 'varsayilan' => 'k'],
                        'sayac_1_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 1 etiket', 'varsayilan' => 'mutlu danışan'],
                        'sayac_2_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 2 sayı', 'varsayilan' => '97'],
                        'sayac_2_ek' => ['tip' => 'metin', 'label' => 'Sayaç 2 ek', 'varsayilan' => '%'],
                        'sayac_2_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 2 etiket', 'varsayilan' => 'memnuniyet'],
                        'sayac_3_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 3 sayı', 'varsayilan' => '12'],
                        'sayac_3_ek' => ['tip' => 'metin', 'label' => 'Sayaç 3 ek', 'varsayilan' => '+'],
                        'sayac_3_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 3 etiket', 'varsayilan' => 'yıllık deneyim'],
                        'sayac_4_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 4 sayı', 'varsayilan' => '40'],
                        'sayac_4_ek' => ['tip' => 'metin', 'label' => 'Sayaç 4 ek', 'varsayilan' => '+'],
                        'sayac_4_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 4 etiket', 'varsayilan' => 'tedavi programı'],
                    ],
                ],

                'case_study' => [
                    'ad' => 'Öne Çıkan Yazılar',
                    'kategori' => 'orta',
                    'sira' => 60,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Blog yazılarınızdan seçtiklerinizi vurgulu şekilde gösterir (case study formatında).',
                    'alanlar' => [
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Öne Çıkan Makalelerim'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => null],
                        'blog_limiti' => ['tip' => 'sayi', 'label' => 'Gösterilecek Yazı Sayısı', 'varsayilan' => 3],
                        'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hekim Paneli → Blog', 'varsayilan' => 'bloglar'],
                    ],
                ],

                'how_it_work' => [
                    'ad' => 'Nasıl Çalışır?',
                    'kategori' => 'orta',
                    'sira' => 70,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Danışan yolculuğu: 3-4 adım (numaralı).',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Süreç'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'İlk seansa kadar süreç'],
                        'adimlar' => ['tip' => 'ikon_baslik_metin', 'label' => 'Adımlar (max 4)', 'varsayilan' => [
                            ['ikon' => '01', 'baslik' => 'Randevu Alın', 'metin' => 'Online takvimden uygun saati seçin.'],
                            ['ikon' => '02', 'baslik' => 'Ön Görüşme', 'metin' => 'Kısa telefon görüşmesi ile ihtiyacınızı anlayalım.'],
                            ['ikon' => '03', 'baslik' => 'İlk Seans', 'metin' => 'Yüz yüze veya online ilk seansımız.'],
                            ['ikon' => '04', 'baslik' => 'Süreç Takibi', 'metin' => 'Düzenli seanslarla hedefinize doğru.'],
                        ]],
                    ],
                ],

                'cta' => [
                    'ad' => 'Randevu Bandı (CTA)',
                    'kategori' => 'orta',
                    'sira' => 80,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Büyük renkli arka plan, tek satırlı mesaj + buton.',
                    'alanlar' => [
                        'baslik' => ['tip' => 'metin', 'label' => 'Ana Mesaj', 'varsayilan' => 'İlk adımı bugün atın'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Alt Açıklama', 'varsayilan' => 'Hayatınızda değişim başlatmak için 30 dakika yeter.'],
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Randevu'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
                        'resim' => ['tip' => 'resim', 'label' => 'CTA görseli (boş = tema görseli)', 'varsayilan' => null],
                        'arkaplan_resmi' => ['tip' => 'resim', 'label' => 'Arkaplan Resmi (opsiyonel)', 'varsayilan' => null],
                    ],
                ],

                'testimonial' => [
                    'ad' => 'Danışan Yorumları',
                    'kategori' => 'alt',
                    'sira' => 90,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Hekim panelindeki onaylı danışan yorumları slider olarak gösterilir.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Danışan Yorumları'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarımın hikayeleri'],
                        'yorum_limiti' => ['tip' => 'sayi', 'label' => 'Slider Yorum Sayısı', 'varsayilan' => 6],
                        'yorum_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hekim Paneli → Danışan Yorumları (onaylı olanlar)', 'varsayilan' => 'yorumlar'],
                    ],
                ],

                'faq' => [
                    'ad' => 'Sıkça Sorulan Sorular',
                    'kategori' => 'alt',
                    'sira' => 100,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Hekim panelindeki S.S.S. bölümünden accordion olarak.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'S.S.S'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Sıkça sorulan sorular'],
                        'sss_limiti' => ['tip' => 'sayi', 'label' => 'Gösterilecek Soru Sayısı', 'varsayilan' => 6],
                        'sss_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hekim Paneli → S.S.S.', 'varsayilan' => 'sss'],
                    ],
                ],

                'blog' => [
                    'ad' => 'Son Blog Yazılarım',
                    'kategori' => 'alt',
                    'sira' => 110,
                    'aktif_varsayilan' => true,
                    'silinebilir' => true,
                    'aciklama' => 'Hekim panelindeki blog yazılarınızın son N tanesi kart olarak.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Blog'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Son yazılarım'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => null],
                        'blog_limiti' => ['tip' => 'sayi', 'label' => 'Gösterilecek Yazı Sayısı', 'varsayilan' => 3],
                        'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hekim Paneli → Blog', 'varsayilan' => 'bloglar'],
                    ],
                ],

                'appointment' => [
                    'ad' => 'Randevu Formu',
                    'kategori' => 'alt',
                    'sira' => 120,
                    'aktif_varsayilan' => true,
                    'silinebilir' => false, // Randevu formu zorunlu
                    'aciklama' => 'Sayfanın en altında hasta randevu wizard\'ı — otomatik oluşturulur.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Randevu'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Hemen randevu alın'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Randevu takvimimden size uygun saati seçin.'],
                        'calisma_saatleri' => ['tip' => 'metin', 'label' => 'Çalışma saatleri', 'varsayilan' => 'Pzt - Cmt 09:00 - 21:00'],
                        'saat_baslik' => ['tip' => 'metin', 'label' => 'Saat başlığı', 'varsayilan' => 'Çalışma saatleri'],
                    ],
                ],
            ],
        ],

        // ============================================================
        // TEMA-2 — Hipno Slider (çoklu slayt hero)
        // ============================================================
        'tema-2' => [
            'ad' => 'Hipno Slider',
            'aciklama' => 'Hero bölümünde swiper slider — 2-5 slayt arası çoklu tanıtım.',
            'onizleme' => '/uploads/tema-onizleme/tema-2.jpg',
            'kaynak_html' => 'tema-1/html.awaikenthemes.com/hipno/index-slider.html',
            'onerilen_uzmanlik' => ['psikoloji', 'psikiyatri', 'danışmanlık'],
            'renk_paletleri' => [
                'koyu-altin' => [
                    'ad' => 'Koyu & Altın (varsayılan)',
                    'primary' => '#262626', 'accent' => '#9B9A84', 'bg' => '#F9F9F9',
                    'text' => '#333333', 'text_light' => '#FFFFFF',
                ],
                'acik-mavi' => [
                    'ad' => 'Açık & Mavi',
                    'primary' => '#2E5C8A', 'accent' => '#7BA7CF', 'bg' => '#F0F4F8',
                    'text' => '#1A2E42', 'text_light' => '#FFFFFF',
                ],
                'doga-yesil' => [
                    'ad' => 'Doğal Yeşil',
                    'primary' => '#4A5C3E', 'accent' => '#C4A76D', 'bg' => '#F5F3EE',
                    'text' => '#2A2A2A', 'text_light' => '#FFFFFF',
                ],
            ],
            'varsayilan_palet' => 'koyu-altin',

            'moduller' => [
                'hero_slider' => [
                    'ad' => 'Hero (Slider)',
                    'kategori' => 'ust',
                    'sira' => 10,
                    'aktif_varsayilan' => true,
                    'silinebilir' => false,
                    'aciklama' => 'Swiper ile 2-5 slaytlık çoklu tanıtım. Her slayt kendi arkaplan görselini + başlık + metin sahibidir.',
                    'alanlar' => [
                        'slaytlar' => ['tip' => 'resim_baslik_metin', 'label' => 'Slaytlar (max 5) — Görsel + Başlık + Alt metin', 'varsayilan' => [
                            ['resim' => '', 'baslik' => 'Ruh sağlığı yolculuğunuzda yanınızdayım', 'metin' => 'Bireysel danışmanlık ile hayatınıza değişim başlatın.'],
                            ['resim' => '', 'baslik' => 'Çift ve aile terapisinde uzman destek', 'metin' => 'İlişkilerinizde yeni bir dönem başlatmak için buradayım.'],
                            ['resim' => '', 'baslik' => 'Çocuk ve ergen danışmanlığı', 'metin' => 'Genç danışanlarınızın gelişimine güvenilir bir yol arkadaşı.'],
                        ]],
                        'ust_baslik' => ['tip' => 'metin', 'label' => 'Slaytlar Üstü Küçük Başlık', 'varsayilan' => 'Hoş Geldiniz'],
                        'cta_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
                        'otomatik_gecis_sn' => ['tip' => 'sayi', 'label' => 'Slayt Otomatik Geçiş (saniye, 0=kapalı)', 'varsayilan' => 6],
                    ],
                ],

                // Kalan modüller tema-1 ile aynı — burada aynen tekrar tanımlanıyor
                // ki hekim tema değiştirdiğinde default set doğru üretilsin.
                'about' => [
                    'ad' => 'Hakkımda', 'kategori' => 'orta', 'sira' => 20,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'İki fotoğraflı hakkımda bölümü + misyon/vizyon.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hakkımda'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Deneyim ve empati ile birlikte'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Ana Açıklama', 'varsayilan' => 'Onlarca yıllık klinik deneyimimle danışanlarımın hayatlarında kalıcı değişimler yaratmayı hedefliyorum.'],
                        'misyon_baslik' => ['tip' => 'metin', 'label' => 'Misyon Başlığı', 'varsayilan' => 'Misyonum'],
                        'misyon_metin' => ['tip' => 'uzun_metin', 'label' => 'Misyon Metni', 'varsayilan' => 'Her danışanın kendine özgü yolunda rehberlik etmek.'],
                        'vizyon_baslik' => ['tip' => 'metin', 'label' => 'Vizyon Başlığı', 'varsayilan' => 'Vizyonum'],
                        'vizyon_metin' => ['tip' => 'uzun_metin', 'label' => 'Vizyon Metni', 'varsayilan' => 'Ruh sağlığı hizmetine erişimi kolaylaştırmak.'],
                        'misyon_maddeler' => ['tip' => 'liste', 'label' => 'Misyon maddeleri (satır satır, opsiyonel)', 'varsayilan' => "Şefkatli ve yargısız alan\nKanıta dayalı yaklaşım\nGizlilik ve güven"],
                        'resim_1' => ['tip' => 'resim', 'label' => 'Fotoğraf 1', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Fotoğraf 2 (opsiyonel)', 'varsayilan' => null],
                        'buton_1' => ['tip' => 'metin', 'label' => 'Sol buton', 'varsayilan' => 'Daha fazla'],
                        'buton_2' => ['tip' => 'metin', 'label' => 'Sağ buton', 'varsayilan' => 'İletişim'],
                    ],
                ],
                'services' => [
                    'ad' => 'Hizmetler', 'kategori' => 'orta', 'sira' => 30,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Hekim panelindeki hizmetler kart olarak.',
                    'alanlar' => [
                        'bolum_baslik' => ['tip' => 'metin', 'label' => 'Bölüm Başlığı', 'varsayilan' => 'Sunduğum Hizmetler'],
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hizmetlerim'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Her danışanın ihtiyacına özel programlar sunuyorum.'],
                        'hizmet_limiti' => ['tip' => 'sayi', 'label' => 'Hizmet Sayısı', 'varsayilan' => 6],
                        'hizmet_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hekim Paneli → Hizmetler', 'varsayilan' => 'hizmetler'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Sağ buton', 'varsayilan' => 'Tüm hizmetler'],
                        'alt_cta' => ['tip' => 'metin', 'label' => 'Alt bant metin (boş = gizle)', 'varsayilan' => ''],
                    ],
                ],
                'why_choose' => [
                    'ad' => 'Neden Ben?', 'kategori' => 'orta', 'sira' => 40,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => '4 sebep kartı.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Neden Ben?'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Farkımı yaratan yaklaşımlar'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Danışanlarımın güvenini kazanan yaklaşımlar.'],
                        'resim' => ['tip' => 'resim', 'label' => 'Sol büyük fotoğraf', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Alt küçük fotoğraf', 'varsayilan' => null],
                        'govde_baslik' => ['tip' => 'metin', 'label' => 'Alt blok başlık', 'varsayilan' => 'Ruh sağlığınız için yanınızdayım'],
                        'govde_metin' => ['tip' => 'uzun_metin', 'label' => 'Alt blok metin', 'varsayilan' => 'Kanıta dayalı, kişiye özel ve şefkatli bir süreçle yanınızdayım.'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Alt buton', 'varsayilan' => 'İletişim'],
                        'sebepler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Sebep Kartları (max 4)', 'varsayilan' => [
                            ['ikon' => 'fa-heart', 'baslik' => 'Empati Odaklı', 'metin' => 'Yargısız ve şefkatli bir alan sunuyorum.'],
                            ['ikon' => 'fa-shield-halved', 'baslik' => 'Gizlilik Garantisi', 'metin' => 'Görüşmeleriniz tamamen gizlidir.'],
                            ['ikon' => 'fa-user-doctor', 'baslik' => 'Bilimsel Yaklaşım', 'metin' => 'Kanıta dayalı terapi teknikleri.'],
                            ['ikon' => 'fa-clock', 'baslik' => 'Esnek Randevu', 'metin' => 'Online ve yüz yüze seçenekler.'],
                        ]],
                    ],
                ],
                'what_we_do' => [
                    'ad' => 'Ne Yapıyorum?', 'kategori' => 'orta', 'sira' => 50,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Video arka plan + 4 sayaç (Hipno intro-video-box).',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Yaklaşımım'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarıma sunduğum destek'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Farklı ihtiyaçlara özel yaklaşımlar.'],
                        'video_url' => ['tip' => 'metin', 'label' => 'Video URL (mp4, boş = tema videosu)', 'varsayilan' => ''],
                        'youtube_id' => ['tip' => 'metin', 'label' => 'YouTube video ID (opsiyonel)', 'varsayilan' => ''],
                        'poster' => ['tip' => 'resim', 'label' => 'Video kapak görseli', 'varsayilan' => null],
                        'sayac_1_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 1 sayı', 'varsayilan' => '200'],
                        'sayac_1_ek' => ['tip' => 'metin', 'label' => 'Sayaç 1 ek', 'varsayilan' => 'k'],
                        'sayac_1_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 1 etiket', 'varsayilan' => 'mutlu danışan'],
                        'sayac_2_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 2 sayı', 'varsayilan' => '97'],
                        'sayac_2_ek' => ['tip' => 'metin', 'label' => 'Sayaç 2 ek', 'varsayilan' => '%'],
                        'sayac_2_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 2 etiket', 'varsayilan' => 'memnuniyet'],
                        'sayac_3_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 3 sayı', 'varsayilan' => '12'],
                        'sayac_3_ek' => ['tip' => 'metin', 'label' => 'Sayaç 3 ek', 'varsayilan' => '+'],
                        'sayac_3_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 3 etiket', 'varsayilan' => 'yıllık deneyim'],
                        'sayac_4_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 4 sayı', 'varsayilan' => '40'],
                        'sayac_4_ek' => ['tip' => 'metin', 'label' => 'Sayaç 4 ek', 'varsayilan' => '+'],
                        'sayac_4_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 4 etiket', 'varsayilan' => 'tedavi programı'],
                    ],
                ],
                'case_study' => [
                    'ad' => 'Öne Çıkan Yazılar', 'kategori' => 'orta', 'sira' => 60,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Blog yazılarınızdan öne çıkanlar.',
                    'alanlar' => [
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Öne Çıkan Makalelerim'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => null],
                        'blog_limiti' => ['tip' => 'sayi', 'label' => 'Yazı Sayısı', 'varsayilan' => 3],
                        'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Blog', 'varsayilan' => 'bloglar'],
                    ],
                ],
                'how_it_work' => [
                    'ad' => 'Nasıl Çalışır?', 'kategori' => 'orta', 'sira' => 70,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Danışan yolculuğu adımları.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Süreç'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'İlk seansa kadar süreç'],
                        'adimlar' => ['tip' => 'ikon_baslik_metin', 'label' => 'Adımlar (max 4)', 'varsayilan' => [
                            ['ikon' => '01', 'baslik' => 'Randevu Alın', 'metin' => 'Online takvimden uygun saati seçin.'],
                            ['ikon' => '02', 'baslik' => 'Ön Görüşme', 'metin' => 'Kısa telefon görüşmesi ile tanışalım.'],
                            ['ikon' => '03', 'baslik' => 'İlk Seans', 'metin' => 'Yüz yüze veya online ilk seansımız.'],
                            ['ikon' => '04', 'baslik' => 'Süreç Takibi', 'metin' => 'Düzenli seanslarla hedefinize.'],
                        ]],
                    ],
                ],
                'cta' => [
                    'ad' => 'Randevu Bandı', 'kategori' => 'orta', 'sira' => 80,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Büyük CTA bandı.',
                    'alanlar' => [
                        'baslik' => ['tip' => 'metin', 'label' => 'Ana Mesaj', 'varsayilan' => 'İlk adımı bugün atın'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Alt Açıklama', 'varsayilan' => 'Hayatınızda değişim başlatmak için 30 dakika yeter.'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
                        'arkaplan_resmi' => ['tip' => 'resim', 'label' => 'Arkaplan Resmi', 'varsayilan' => null],
                    ],
                ],
                'testimonial' => [
                    'ad' => 'Danışan Yorumları', 'kategori' => 'alt', 'sira' => 90,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Onaylı yorumlar slider.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Danışan Yorumları'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarımın hikayeleri'],
                        'yorum_limiti' => ['tip' => 'sayi', 'label' => 'Yorum Sayısı', 'varsayilan' => 6],
                        'yorum_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Yorumlar', 'varsayilan' => 'yorumlar'],
                    ],
                ],
                'faq' => [
                    'ad' => 'S.S.S.', 'kategori' => 'alt', 'sira' => 100,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Accordion sorular.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'S.S.S'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Sıkça sorulan sorular'],
                        'sss_limiti' => ['tip' => 'sayi', 'label' => 'Soru Sayısı', 'varsayilan' => 6],
                        'sss_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: S.S.S.', 'varsayilan' => 'sss'],
                    ],
                ],
                'blog' => [
                    'ad' => 'Son Yazılar', 'kategori' => 'alt', 'sira' => 110,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'Son blog yazıları kart.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Blog'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Son yazılarım'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => null],
                        'blog_limiti' => ['tip' => 'sayi', 'label' => 'Yazı Sayısı', 'varsayilan' => 3],
                        'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Blog', 'varsayilan' => 'bloglar'],
                    ],
                ],
                'appointment' => [
                    'ad' => 'Randevu Formu', 'kategori' => 'alt', 'sira' => 120,
                    'aktif_varsayilan' => true, 'silinebilir' => false,
                    'aciklama' => 'Sayfa altı randevu CTA.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Randevu'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Hemen randevu alın'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Randevu takvimimden size uygun saati seçin.'],
                        'calisma_saatleri' => ['tip' => 'metin', 'label' => 'Çalışma saatleri', 'varsayilan' => 'Pzt - Cmt 09:00 - 21:00'],
                        'saat_baslik' => ['tip' => 'metin', 'label' => 'Saat başlığı', 'varsayilan' => 'Çalışma saatleri'],
                    ],
                ],
            ],
        ],

        // ============================================================
        // TEMA-3 — Hipno Video (fullscreen background video)
        // ============================================================
        'tema-3' => [
            'ad' => 'Hipno Video',
            'aciklama' => 'Hero bölümünde tam ekran arka plan videosu — sinemasal, dikkat çekici.',
            'onizleme' => '/uploads/tema-onizleme/tema-3.jpg',
            'kaynak_html' => 'tema-1/html.awaikenthemes.com/hipno/index-video.html',
            'onerilen_uzmanlik' => ['psikoloji', 'psikiyatri', 'danışmanlık'],
            'renk_paletleri' => [
                'koyu-altin' => [
                    'ad' => 'Koyu & Altın (varsayılan)',
                    'primary' => '#262626', 'accent' => '#9B9A84', 'bg' => '#F9F9F9',
                    'text' => '#333333', 'text_light' => '#FFFFFF',
                ],
                'acik-mavi' => [
                    'ad' => 'Açık & Mavi',
                    'primary' => '#2E5C8A', 'accent' => '#7BA7CF', 'bg' => '#F0F4F8',
                    'text' => '#1A2E42', 'text_light' => '#FFFFFF',
                ],
                'doga-yesil' => [
                    'ad' => 'Doğal Yeşil',
                    'primary' => '#4A5C3E', 'accent' => '#C4A76D', 'bg' => '#F5F3EE',
                    'text' => '#2A2A2A', 'text_light' => '#FFFFFF',
                ],
            ],
            'varsayilan_palet' => 'koyu-altin',

            'moduller' => [
                'hero_video' => [
                    'ad' => 'Hero (Video)',
                    'kategori' => 'ust',
                    'sira' => 10,
                    'aktif_varsayilan' => true,
                    'silinebilir' => false,
                    'aciklama' => 'Tam ekran arka plan video + başlık + CTA.',
                    'alanlar' => [
                        'video_url' => ['tip' => 'metin', 'label' => 'Video URL (mp4)', 'varsayilan' => ''],
                        'video_youtube_id' => ['tip' => 'metin', 'label' => 'YouTube Video ID (mp4 yoksa kullanılır)', 'varsayilan' => ''],
                        'ust_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hoş Geldiniz'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık (H1)', 'varsayilan' => 'Ruh sağlığı yolculuğunuzda yanınızdayım'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama Paragrafı', 'varsayilan' => 'Bireysel, çift ve aile danışmanlığı ile hayatınızdaki değişimlere destek oluyorum.'],
                        'cta_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
                        'sosyal_kanit_goster' => ['tip' => 'sayi', 'label' => 'Sosyal Kanıt (0=gizle, 1=göster)', 'varsayilan' => 1],
                        'sosyal_kanit_sayi' => ['tip' => 'sayi', 'label' => 'Danışan Sayısı', 'varsayilan' => 100],
                        'sosyal_kanit_metin' => ['tip' => 'metin', 'label' => 'Sosyal Kanıt Metni', 'varsayilan' => 'Mutlu Danışan'],
                    ],
                ],

                // Kalan modüller tema-1 ile aynı — kod tekrarını önlemek için Sprint 3'te
                // bir spread helper'a alınabilir. Şimdilik açık tanım.
                'about' => [
                    'ad' => 'Hakkımda', 'kategori' => 'orta', 'sira' => 20,
                    'aktif_varsayilan' => true, 'silinebilir' => true,
                    'aciklama' => 'İki fotoğraflı hakkımda + misyon/vizyon.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hakkımda'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Deneyim ve empati ile birlikte'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Ana Açıklama', 'varsayilan' => 'Onlarca yıllık klinik deneyimimle danışanlarımın hayatlarında kalıcı değişimler yaratıyorum.'],
                        'misyon_baslik' => ['tip' => 'metin', 'label' => 'Misyon Başlığı', 'varsayilan' => 'Misyonum'],
                        'misyon_metin' => ['tip' => 'uzun_metin', 'label' => 'Misyon Metni', 'varsayilan' => 'Her danışanın kendine özgü yolunda rehberlik etmek.'],
                        'vizyon_baslik' => ['tip' => 'metin', 'label' => 'Vizyon Başlığı', 'varsayilan' => 'Vizyonum'],
                        'vizyon_metin' => ['tip' => 'uzun_metin', 'label' => 'Vizyon Metni', 'varsayilan' => 'Ruh sağlığı hizmetine erişimi kolaylaştırmak.'],
                        'misyon_maddeler' => ['tip' => 'liste', 'label' => 'Misyon maddeleri (satır satır, opsiyonel)', 'varsayilan' => "Şefkatli ve yargısız alan\nKanıta dayalı yaklaşım\nGizlilik ve güven"],
                        'resim_1' => ['tip' => 'resim', 'label' => 'Fotoğraf 1', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Fotoğraf 2 (opsiyonel)', 'varsayilan' => null],
                        'buton_1' => ['tip' => 'metin', 'label' => 'Sol buton', 'varsayilan' => 'Daha fazla'],
                        'buton_2' => ['tip' => 'metin', 'label' => 'Sağ buton', 'varsayilan' => 'İletişim'],
                    ],
                ],
                'services' => [
                    'ad' => 'Hizmetler', 'kategori' => 'orta', 'sira' => 30,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Hizmet kart ızgarası.',
                    'alanlar' => [
                        'bolum_baslik' => ['tip' => 'metin', 'label' => 'Bölüm Başlığı', 'varsayilan' => 'Sunduğum Hizmetler'],
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Hizmetlerim'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Her danışana özel programlar.'],
                        'hizmet_limiti' => ['tip' => 'sayi', 'label' => 'Hizmet Sayısı', 'varsayilan' => 6],
                        'hizmet_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Hizmetler', 'varsayilan' => 'hizmetler'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Sağ buton', 'varsayilan' => 'Tüm hizmetler'],
                        'alt_cta' => ['tip' => 'metin', 'label' => 'Alt bant metin (boş = gizle)', 'varsayilan' => ''],
                    ],
                ],
                'why_choose' => [
                    'ad' => 'Neden Ben?', 'kategori' => 'orta', 'sira' => 40,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => '4 sebep kartı.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Neden Ben?'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Farkımı yaratan yaklaşımlar'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Danışanlarımın güvenini kazanan yaklaşımlar.'],
                        'resim' => ['tip' => 'resim', 'label' => 'Sol büyük fotoğraf', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Alt küçük fotoğraf', 'varsayilan' => null],
                        'govde_baslik' => ['tip' => 'metin', 'label' => 'Alt blok başlık', 'varsayilan' => 'Ruh sağlığınız için yanınızdayım'],
                        'govde_metin' => ['tip' => 'uzun_metin', 'label' => 'Alt blok metin', 'varsayilan' => 'Kanıta dayalı, kişiye özel ve şefkatli bir süreçle yanınızdayım.'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Alt buton', 'varsayilan' => 'İletişim'],
                        'sebepler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Sebep Kartları', 'varsayilan' => [
                            ['ikon' => 'fa-heart', 'baslik' => 'Empati Odaklı', 'metin' => 'Yargısız alan.'],
                            ['ikon' => 'fa-shield-halved', 'baslik' => 'Gizlilik', 'metin' => 'Tamamen gizli görüşme.'],
                            ['ikon' => 'fa-user-doctor', 'baslik' => 'Bilimsel', 'metin' => 'Kanıta dayalı yöntemler.'],
                            ['ikon' => 'fa-clock', 'baslik' => 'Esnek', 'metin' => 'Online + yüz yüze.'],
                        ]],
                    ],
                ],
                'what_we_do' => [
                    'ad' => 'Ne Yapıyorum?', 'kategori' => 'orta', 'sira' => 50,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Yaklaşım başlıkları.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Yaklaşımım'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarıma sunduğum destek'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Farklı ihtiyaçlara özel yaklaşımlar.'],
                        'video_url' => ['tip' => 'metin', 'label' => 'Video URL (mp4, boş = tema videosu)', 'varsayilan' => ''],
                        'youtube_id' => ['tip' => 'metin', 'label' => 'YouTube video ID (opsiyonel)', 'varsayilan' => ''],
                        'poster' => ['tip' => 'resim', 'label' => 'Video kapak görseli', 'varsayilan' => null],
                        'sayac_1_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 1 sayı', 'varsayilan' => '200'],
                        'sayac_1_ek' => ['tip' => 'metin', 'label' => 'Sayaç 1 ek', 'varsayilan' => 'k'],
                        'sayac_1_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 1 etiket', 'varsayilan' => 'mutlu danışan'],
                        'sayac_2_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 2 sayı', 'varsayilan' => '97'],
                        'sayac_2_ek' => ['tip' => 'metin', 'label' => 'Sayaç 2 ek', 'varsayilan' => '%'],
                        'sayac_2_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 2 etiket', 'varsayilan' => 'memnuniyet'],
                        'sayac_3_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 3 sayı', 'varsayilan' => '12'],
                        'sayac_3_ek' => ['tip' => 'metin', 'label' => 'Sayaç 3 ek', 'varsayilan' => '+'],
                        'sayac_3_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 3 etiket', 'varsayilan' => 'yıllık deneyim'],
                        'sayac_4_sayi' => ['tip' => 'metin', 'label' => 'Sayaç 4 sayı', 'varsayilan' => '40'],
                        'sayac_4_ek' => ['tip' => 'metin', 'label' => 'Sayaç 4 ek', 'varsayilan' => '+'],
                        'sayac_4_etiket' => ['tip' => 'metin', 'label' => 'Sayaç 4 etiket', 'varsayilan' => 'tedavi programı'],
                    ],
                ],
                'case_study' => [
                    'ad' => 'Öne Çıkan Yazılar', 'kategori' => 'orta', 'sira' => 60,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Blog seçkileri.',
                    'alanlar' => [
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Öne Çıkan Makalelerim'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => null],
                        'blog_limiti' => ['tip' => 'sayi', 'label' => 'Yazı Sayısı', 'varsayilan' => 3],
                        'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Blog', 'varsayilan' => 'bloglar'],
                    ],
                ],
                'how_it_work' => [
                    'ad' => 'Nasıl Çalışır?', 'kategori' => 'orta', 'sira' => 70,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Süreç adımları.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Süreç'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'İlk seansa kadar süreç'],
                        'adimlar' => ['tip' => 'ikon_baslik_metin', 'label' => 'Adımlar', 'varsayilan' => [
                            ['ikon' => '01', 'baslik' => 'Randevu Alın', 'metin' => 'Online takvim.'],
                            ['ikon' => '02', 'baslik' => 'Ön Görüşme', 'metin' => 'Kısa telefon.'],
                            ['ikon' => '03', 'baslik' => 'İlk Seans', 'metin' => 'İlk seansımız.'],
                            ['ikon' => '04', 'baslik' => 'Süreç', 'metin' => 'Düzenli seanslar.'],
                        ]],
                    ],
                ],
                'cta' => [
                    'ad' => 'Randevu Bandı', 'kategori' => 'orta', 'sira' => 80,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Büyük CTA.',
                    'alanlar' => [
                        'baslik' => ['tip' => 'metin', 'label' => 'Ana Mesaj', 'varsayilan' => 'İlk adımı bugün atın'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Alt Açıklama', 'varsayilan' => '30 dakika yeter.'],
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
                        'arkaplan_resmi' => ['tip' => 'resim', 'label' => 'Arkaplan Resmi', 'varsayilan' => null],
                    ],
                ],
                'testimonial' => [
                    'ad' => 'Danışan Yorumları', 'kategori' => 'alt', 'sira' => 90,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Onaylı yorumlar.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Danışan Yorumları'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarımın hikayeleri'],
                        'yorum_limiti' => ['tip' => 'sayi', 'label' => 'Yorum Sayısı', 'varsayilan' => 6],
                        'yorum_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Yorumlar', 'varsayilan' => 'yorumlar'],
                    ],
                ],
                'faq' => [
                    'ad' => 'S.S.S.', 'kategori' => 'alt', 'sira' => 100,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Accordion.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'S.S.S'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Sıkça sorulan sorular'],
                        'sss_limiti' => ['tip' => 'sayi', 'label' => 'Soru Sayısı', 'varsayilan' => 6],
                        'sss_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: S.S.S.', 'varsayilan' => 'sss'],
                    ],
                ],
                'blog' => [
                    'ad' => 'Son Yazılar', 'kategori' => 'alt', 'sira' => 110,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => 'Son yazılar.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Blog'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Son yazılarım'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => null],
                        'blog_limiti' => ['tip' => 'sayi', 'label' => 'Yazı Sayısı', 'varsayilan' => 3],
                        'blog_kaynagi' => ['tip' => 'db_kaynak', 'label' => 'Kaynak: Blog', 'varsayilan' => 'bloglar'],
                    ],
                ],
                'appointment' => [
                    'ad' => 'Randevu Formu', 'kategori' => 'alt', 'sira' => 120,
                    'aktif_varsayilan' => true, 'silinebilir' => false, 'aciklama' => 'Sayfa altı CTA.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Randevu'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Hemen randevu alın'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Takvimimden size uygun saati seçin.'],
                    ],
                ],
            ],
        ],
    ],
];
