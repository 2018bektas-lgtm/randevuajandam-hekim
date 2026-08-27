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
 * Tema-1 (Hipno Klasik): index.html karşılığı — MVP tema.
 * Tema-2..9 sonraki sprint'lerde eklenecek (Hipno slider/video + Delogis 6 varyant).
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
                        'resim_1' => ['tip' => 'resim', 'label' => 'Fotoğraf 1', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Fotoğraf 2', 'varsayilan' => null],
                        'danisan_puani' => ['tip' => 'sayi', 'label' => 'Danışan Puanı (0-5)', 'varsayilan' => 5],
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
                    'aktif_varsayilan' => false, // opsiyonel — hekim isterse açar
                    'silinebilir' => true,
                    'aciklama' => 'Tedavi yaklaşımınızın ana başlıkları (3-4 sütun).',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Yaklaşımım'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarıma sunduğum destek'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Farklı ihtiyaçlara özel yaklaşımlar.'],
                        'ogeler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Öğeler (max 4)', 'varsayilan' => [
                            ['ikon' => 'fa-brain', 'baslik' => 'Bilişsel Davranışçı', 'metin' => 'Düşünce kalıplarınızı yeniden şekillendirmek.'],
                            ['ikon' => 'fa-users', 'baslik' => 'İlişki Danışmanlığı', 'metin' => 'Çift ve aile dinamiklerini iyileştirmek.'],
                            ['ikon' => 'fa-hand-holding-heart', 'baslik' => 'Travma Odaklı', 'metin' => 'Geçmiş yaraların iyileşme yolculuğu.'],
                        ]],
                    ],
                ],

                'case_study' => [
                    'ad' => 'Öne Çıkan Yazılar',
                    'kategori' => 'orta',
                    'sira' => 60,
                    'aktif_varsayilan' => false,
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
                        'buton_metin' => ['tip' => 'metin', 'label' => 'Buton Metni', 'varsayilan' => 'Randevu Al'],
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
                    ],
                ],
            ],
        ],

        // TEMA-2..9 sonraki sprint'lerde eklenecek — katalog yapı örneği yukarıdaki tema-1 gibi.
    ],
];
