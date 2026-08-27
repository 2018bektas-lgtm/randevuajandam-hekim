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
                    'aciklama' => 'Swiper ile 2-5 slaytlık çoklu tanıtım. Her slayt kendi başlık/görsel/CTA sahibidir.',
                    'alanlar' => [
                        'slaytlar' => ['tip' => 'ikon_baslik_metin', 'label' => 'Slaytlar (max 5) — {ikon: resim_url, baslik, metin}', 'varsayilan' => [
                            ['ikon' => '', 'baslik' => 'Ruh sağlığı yolculuğunuzda yanınızdayım', 'metin' => 'Bireysel danışmanlık ile hayatınıza değişim başlatın.'],
                            ['ikon' => '', 'baslik' => 'Çift ve aile terapisinde uzman destek', 'metin' => 'İlişkilerinizde yeni bir dönem başlatmak için buradayım.'],
                            ['ikon' => '', 'baslik' => 'Çocuk ve ergen danışmanlığı', 'metin' => 'Genç danışanlarınızın gelişimine güvenilir bir yol arkadaşı.'],
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
                        'resim_1' => ['tip' => 'resim', 'label' => 'Fotoğraf 1', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Fotoğraf 2', 'varsayilan' => null],
                        'danisan_puani' => ['tip' => 'sayi', 'label' => 'Danışan Puanı (0-5)', 'varsayilan' => 5],
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
                    'aktif_varsayilan' => false, 'silinebilir' => true,
                    'aciklama' => 'Tedavi yaklaşımı başlıkları.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Yaklaşımım'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarıma sunduğum destek'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Farklı ihtiyaçlara özel yaklaşımlar.'],
                        'ogeler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Öğeler (max 4)', 'varsayilan' => [
                            ['ikon' => 'fa-brain', 'baslik' => 'Bilişsel Davranışçı', 'metin' => 'Düşünce kalıplarını yeniden şekillendirmek.'],
                            ['ikon' => 'fa-users', 'baslik' => 'İlişki Danışmanlığı', 'metin' => 'Çift ve aile dinamiklerini iyileştirmek.'],
                            ['ikon' => 'fa-hand-holding-heart', 'baslik' => 'Travma Odaklı', 'metin' => 'İyileşme yolculuğu.'],
                        ]],
                    ],
                ],
                'case_study' => [
                    'ad' => 'Öne Çıkan Yazılar', 'kategori' => 'orta', 'sira' => 60,
                    'aktif_varsayilan' => false, 'silinebilir' => true,
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
                        'resim_1' => ['tip' => 'resim', 'label' => 'Fotoğraf 1', 'varsayilan' => null],
                        'resim_2' => ['tip' => 'resim', 'label' => 'Fotoğraf 2', 'varsayilan' => null],
                        'danisan_puani' => ['tip' => 'sayi', 'label' => 'Danışan Puanı (0-5)', 'varsayilan' => 5],
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
                    ],
                ],
                'why_choose' => [
                    'ad' => 'Neden Ben?', 'kategori' => 'orta', 'sira' => 40,
                    'aktif_varsayilan' => true, 'silinebilir' => true, 'aciklama' => '4 sebep kartı.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Neden Ben?'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Farkımı yaratan yaklaşımlar'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Danışanlarımın güvenini kazanan yaklaşımlar.'],
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
                    'aktif_varsayilan' => false, 'silinebilir' => true, 'aciklama' => 'Yaklaşım başlıkları.',
                    'alanlar' => [
                        'kucuk_baslik' => ['tip' => 'metin', 'label' => 'Küçük Üst Başlık', 'varsayilan' => 'Yaklaşımım'],
                        'ana_baslik' => ['tip' => 'metin', 'label' => 'Ana Başlık', 'varsayilan' => 'Danışanlarıma sunduğum destek'],
                        'aciklama' => ['tip' => 'uzun_metin', 'label' => 'Açıklama', 'varsayilan' => 'Farklı ihtiyaçlara özel yaklaşımlar.'],
                        'ogeler' => ['tip' => 'ikon_baslik_metin', 'label' => 'Öğeler', 'varsayilan' => [
                            ['ikon' => 'fa-brain', 'baslik' => 'BDT', 'metin' => 'Düşünce kalıpları.'],
                            ['ikon' => 'fa-users', 'baslik' => 'İlişki', 'metin' => 'Çift ve aile.'],
                            ['ikon' => 'fa-hand-holding-heart', 'baslik' => 'Travma', 'metin' => 'İyileşme.'],
                        ]],
                    ],
                ],
                'case_study' => [
                    'ad' => 'Öne Çıkan Yazılar', 'kategori' => 'orta', 'sira' => 60,
                    'aktif_varsayilan' => false, 'silinebilir' => true, 'aciklama' => 'Blog seçkileri.',
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
