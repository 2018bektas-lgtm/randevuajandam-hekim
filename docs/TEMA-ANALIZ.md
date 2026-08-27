# Hekim Sitesi — Tema Hataları ve Mantık Tutarsızlıkları

Tarih: 2026-08-27  
Kapsam: `randevuajandam-hekim` (port 8001) tema sistemi, CSS, layout, panel ve anasayfa builder.

Bu rapor düzeltme listesi değildir; mevcut durumun envanteridir. Öncelik: **P0** = kullanıcıya görünür hata, **P1** = mantık/çift sistem, **P2** = kopya/ölü kod/dokümantasyon.

---

## 1. Sistem nasıl çalışıyor (kısa)

Aktif tema `site_options.tema_id` üzerinden çözülür.

| Katman | Dosya | Ne yapar |
|--------|--------|----------|
| Katalog (panel kartları) | `config/themes.php` | `tema-1`, `tema-2`, `tema-3` |
| Katalog (modül/palet) | `config/tema_modulleri.php` | Aynı 3 tema + 12 modül + 3 palet |
| Çözümleme | `app/helpers.php` | `current_theme_id()`, `theme_layout()`, `theme_view()` |
| Anasayfa render | `SiteBuilderService::renderIcinModuller()` | DB `site_homepage_sections` |
| Blade paketleri | `resources/views/frontend/themes/{tema-N}/` | layout + pages + modules |
| CSS | `public/css/themes/tema-{1,2,3}.css` | Hipno kopyası |

Aktif katalogdaki üç tema Hipno HTML paketinin **üç hero varyantıdır** (statik / slider / video). Header, footer, iç sayfalar ve CSS pratikte aynıdır.

---

## 2. Kritik hatalar (P0)

### 2.1 Tema-2 hero slider görseli yanlış alandan okunuyor

Config alanı `resim_baslik_metin` ve varsayılan yapı:

```php
['resim' => '', 'baslik' => '...', 'metin' => '...']
```

Blade ise görseli `ikon` anahtarından okuyor:

```php
// resources/views/frontend/themes/tema-2/modules/hero_slider.blade.php
$bg = !empty($s['ikon']) ? $s['ikon'] : $fallbackImg;
```

Sonuç: hekim slayta görsel yüklese bile slider profil fotoğrafına (veya boş arka plana) düşer. Tema-2’nin tek farkı (çoklu slayt görseli) çalışmaz.

### 2.2 Builder, `resim_baslik_metin` tipini tanımıyor

`panel/sayfa-builder/index.blade.php` içinde özel UI yalnızca `ikon_baslik_metin` ve `resim` için var. `resim_baslik_metin` (tema-2 slayt listesi) varsayılan **tek satır text input**’a düşer.

Hekim slaytları düzenleyince dizi bozulur, JSON yerine düz string kaydolur. `modulKaydet()` switch’inde de bu tip yok; `default` dalına düşer.

### 2.3 İki ayrı renk sistemi birbirini ezer

| Kaynak | Panel yeri | Option key | CSS değişkenleri |
|--------|------------|------------|------------------|
| Vurgu rengi | Site Ayarları → Temalar | `tema_renk` | `--brand-50` … `--brand-900` |
| Palet | Sayfa Builder | `renk_palet_kod` / `renk_palet_ozel` | `--primary-color`, `--accent-color`, `--text-color` |

Hipno CSS neredeyse tamamen `--accent-color` / `--primary-color` kullanır. Site Ayarları’ndaki “vurgu rengi” bu yüzden **buton ve linkleri değiştirmez**; en fazla `theme-color` meta’sını ve kullanılmayan `--brand-*` skalasını etkiler.

Üstüne `tema-1.css` içinde **iki `:root` bloğu** var:

1. Üstte: `--accent-color: var(--brand-500, #9B9A84);`
2. Alttaki orijinal Hipno bloğu: `--accent-color: #9B9A84;` (sabit)

Inline `<style>` paleti sonra basıyor, ama hekim “vurgu rengi = teal, palet = koyu-altın” seçerse sitede altın, panelde teal görünür. İki ekran iki gerçeklik.

Varsayılanlar da çelişiyor:

- Hipno / `themes.php`: `#9B9A84`
- `SiteSettingsService`, migration, seeder, fallback layout: `#0d9488` (eski teal)
- `theme_palette()` fallback: `#0d9488`
- `tema-1` head fallback: `#9B9A84`
- Eski `frontend/layouts/partials/head.blade.php` fallback: `#0d9488` + Cormorant/Inter font

### 2.4 Tema-2 ve tema-3 layout’ları tema-1’i hardcode ediyor

```blade
{{-- themes/tema-2/layouts/app.blade.php ve tema-3 aynı --}}
@include('frontend.themes.tema-1.layouts.partials.head')
<body class="theme-{{ $bodyTema }} layout-tema-1">
@include('frontend.themes.tema-1.layouts.partials.header', ...)
@include('frontend.themes.tema-1.layouts.partials.footer', ...)
@include('frontend.themes.tema-1.layouts.partials.script', ...)
```

Sorunlar:

- Body class her zaman `layout-tema-1` — CSS ile tema ayrımı imkânsız.
- tema-2/3’ün kendi `head/header/footer/script` dosyaları **hiç kullanılmıyor** (ölü kopya).
- Tema seçmek header/footer’ı değiştirmez; panel metni (“header, anasayfa, kartlar ve footer tamamen değişir”) gerçeğe uymaz.

### 2.5 CSS üçlüsü birebir aynı dosya

```
public/css/themes/tema-1.css   113 621 byte   MD5 AFED2164F5206AFAAE570F043BAAFFF6
public/css/themes/tema-2.css   113 621 byte   (aynı hash)
public/css/themes/tema-3.css   113 621 byte   (aynı hash)
```

Üç dosyanın başlığı da hâlâ `TEMA-1 (Hipno)`. Tema-2/3 için ayrı CSS yok. Ayrıca dosyaların başında UTF-8 BOM (`U+FEFF`) var.

`vendor/hipno/css/custom.css` (112 773 byte) ayrıca duruyor; head onu yüklemiyor, kopyasını `tema-N.css` olarak yüklüyor.

### 2.6 Özel sayfalar (KVKK vb.) temasız kalıyor

`theme_view('pages.sayfa')` temalarda `sayfa.blade.php` arar. **Hiçbir temada yok.** Fallback:

`resources/views/frontend/pages/sayfa.blade.php`

Bu dosya Hipno `page-header` kullanmaz; düz inline stilli bir kutu. Menüden açılan yasal sayfalar sitenin geri kalanına benzemez.

---

## 3. Çift sistemler (P1) — hekim hangisini kullansın belirsiz

### 3.1 İki tema seçici, iki katalog

| Yer | Config | Seed eder mi? |
|-----|--------|----------------|
| Site Ayarları → Temalar | `config/themes.php` | Hayır (`kaydetTema` sadece `tema_id` + `tema_renk`) |
| Sayfa Builder → Tema dropdown | `config/tema_modulleri.php` | Evet (`defaultSetiOlustur`) |

İkisi aynı `site_options.tema_id`’ye yazar. Kataloglar bugün çakışıyor; yarın biri güncellenirse diğeri kırılır.

Sayfa Builder `?tema=tema-2` ile **aktif olmayan** temanın modüllerini gösterebilir. Dropdown’dan “Uygula” demeden düzenleme, yanlış temaya yazılabilir.

Builder tema değişince flash key `basarili`; panel layout yalnızca `session('basari')` okur. **Başarı mesajı görünmez.**

### 3.2 İki anasayfa editörü

| Panel | Tablo | Gerçekten anasayfayı etkiler mi? |
|-------|--------|----------------------------------|
| Site Ayarları → Ana Sayfa | `site_homepage_sections` (eski `key` seti: slider, istatistik, …) | **Hayır.** Yeni temalar `renderIcinModuller('tema-N')` ile `hero_static` / `about` vb. okur. |
| Site Ayarları → Slider | `site_slider_slides` | **Hayır.** Tema-2 kendi `hero_slider.slaytlar` alanını kullanır. |
| Sayfa Builder | aynı tablo, `tema_id` + yeni `key` | **Evet.** |

Hekim Site Ayarları’ndan bölüm kapatıp sitede değişiklik görmez. Slider sekmesi Hipno anasayfasına bağlı değil.

`frontend/pages/anasayfa.blade.php` hâlâ eski slider/istatistik sistemini içeriyor; `theme_view()` onu yalnızca tema paketi yoksa kullanır. Aktif 3 temada ölü kod.

### 3.3 Delogis ve modern artık katalogda yok, dosyalar duruyor

`config/themes.php` yorumu: “tema-4..9 sonraki sprint’lerde Delogis”. Gerçek durum:

- `resources/views/frontend/themes/delogis/` tam paket (layout + pages)
- `public/themes/delogis/` vendor (CSS/JS/görseller)
- `public/css/themes/delogis.css`, `modern.css`
- `frontend/layouts/partials/nav-items.blade.php` hâlâ `$mode === 'delogis'` dalı

Katalogda `delogis` yok; `resolve_site_theme('delogis')` bunu `tema-1`’e map’ler (`aliases` listesinde `delogis` yok ama katalogda da yok → default). Eski sitede `tema_id=delogis` kaldıysa sessizce Hipno’ya düşer.

`themes/README.md` hâlâ “yalnızca tema-1 ve delogis” diyor. `tema_modulleri.php` başlığı hâlâ “Tema-2..9 sonraki sprint”. Üç kaynak üç farklı gerçek.

### 3.4 Alias haritası güncel değil

```php
$aliases = [
    'klasik' => 'tema-1',
    'hipno' => 'tema-1',
    'modern' => 'tema-1',
    'minimalist' => 'tema-1',
    'minimal' => 'tema-1',
    'custom' => 'tema-1',
];
```

`delogis` yok. `hipno` → `tema-1` (slider/video kaybolur). Fallback ad alanı hâlâ `'ad' => 'Hipno'` ve eski font string’i.

### 3.5 Tema seçilince palet sıfırlanmıyor

`temaSec()` sadece `tema_id` yazar. Önceki `renk_palet_kod` durur. Üç temanın palet kodları bugün aynı (`koyu-altin`); ileride farklı palet adları gelirse eski kod sessizce varsayılana düşer.

`kaydetTema()` `defaultSetiOlustur()` çağırmaz. İlk kez Site Ayarları’ndan tema-2 seçilirse anasayfa view’ı ilk istekte seed eder — panel “uygulandı” dedikten sonra ilk ziyarette boş/yanlış anasayfa riski.

---

## 4. Tema-2 / tema-3 aslında “tema” değil, hero varyantı (P1)

Üç “tema” arasındaki fark:

| | tema-1 | tema-2 | tema-3 |
|--|--------|--------|--------|
| Hero | `hero_static` | `hero_slider` | `hero_video` |
| Layout | tema-1 (hardcode) | tema-1 (hardcode) | tema-1 (hardcode) |
| CSS | aynı hash | aynı hash | aynı hash |
| İç sayfalar | Hipno | Hipno kopyası | Hipno kopyası |
| Diğer 11 modül | aynı | aynı (kopya blade) | aynı (kopya blade) |

Panel “3 hazır tasarım” satıyor; kullanıcı header/renk/kart değişimi bekler. Gerçek: yalnızca anasayfa hero’su değişir (o da 2.1/2.2 yüzünden tema-2’de kırık).

Daha doğru model: tek Hipno paketi + hero tipi ayarı. Ya da gerçekten farklı layout/CSS.

---

## 5. İçerik / marka tutarsızlıkları (P1)

### 5.1 Tüm varsayılan metinler psikoloji kopyası

Modül default’ları diş hekimi, KBB, dahiliye sitelerinde anlamsız:

- “Ruh sağlığı yolculuğunuzda yanınızdayım”
- “Mutlu Danışan” (hekim sitesi; “hasta” / “danışan” karışık)
- “Bireysel, çift ve aile danışmanlığı”
- `onerilen_uzmanlik` = psikoloji / psikiyatri / danışmanlık

Dis hekimi seeder’ı `tema-1` + teal (`#0d9488`) basıyor; anasayfa metni yine psikoloji.

### 5.2 Sahte sosyal kanıt varsayılan açık

- `sosyal_kanit_goster = 1`, `sosyal_kanit_sayi = 100` → “100+ Mutlu Danışan”
- `danisan_puani = 5` → 5 yıldız kutusu, gerçek yorum olmasa bile

Hekim dokunmazsa uydurma sayı/yıldız yayınlanır.

### 5.3 Footer CTA sabit ve yanlış vaat

```blade
<h2>Ücretsiz danışma için arayın</h2>
```

Panelden değişmez. Birçok hekim ücretsiz danışma vermez.

### 5.4 Demo görseller gerçek içerik yoksa sızıyor

İç sayfalar (`hakkimda`, `hizmetler`, `blog`, `egitimler`) görsel yoksa Hipno stok fotoğrafına düşer:

- `vendor/hipno/images/about-img-1.jpg`
- `service-image-1.jpg`, `post-1.jpg`, `why-choose-img-1.jpg`

Hekim fotoğraf yüklemeden “başkasının kliniği” görünür.

### 5.5 Tema önizleme görselleri yok

`config/tema_modulleri.php`:

```
/uploads/tema-onizleme/tema-1.jpg
```

Panel Temalar ekranı bu path’i kullanmıyor; üç renk şeridi gösteriyor. Path’ler 404. Palet önizlemesi (`preview: ['#262626', '#9B9A84', '#F9F9F9']`) üç temada da aynı — kartlar ayırt edilemez.

---

## 6. Teknik borç ve kopya şişkinliği (P2)

### 6.1 Blade üç kez kopyalanmış

`tema-2` ve `tema-3` içinde about, services, why_choose, faq, blog, randevu, hakkimda… neredeyse satır satır tema-1. Yorum satırları bile “tema-1” diyor.

`config/tema_modulleri.php` about+services+… bloklarını üç temada tekrarlıyor (~700 satır kopya). Yorum: “Sprint 3’te spread helper”.

Tek kaynak + `@include` / config merge yok.

### 6.2 Kullanılmayan / eski dosyalar

- `resources/views/frontend/pages/*` (eski anasayfa dahil)
- `resources/views/frontend/layouts/app.blade.php` + head (Cormorant, `klasik` default)
- `public/css/site.css` (teal brand, eski layout)
- `public/css/themes/modern.css`
- `themes/tema-1/pages/anasayfa.blade.php.eski-backup`
- tema-2/3 `layouts/partials/*` (layout bunları include etmiyor)
- `themes/delogis/` tam paket
- `vendor/hipno/css/custom.css` (kopyası tema-N.css)

`theme_view_name()` 4 kademeli fallback (pack → id → tema-1 → `frontend.*`) bu ölü katmanı gizler; hata geç fark edilir.

### 6.3 Swiper iki kez yükleniyor (tema-2)

Hipno `script.blade.php` zaten `vendor/hipno/js/swiper-bundle.min.js` yüklüyor. `hero_slider.blade.php` bir de jsDelivr Swiper 11 + CSS push ediyor. Çift init / versiyon çakışması riski.

### 6.4 Magic cursor her sitede açık

`mousecursor.css` + `magiccursor.js` kapatılamaz. Mobilde ve erişilebilirlikte sorun; hekim tercihi yok.

### 6.5 `site_nav` klinik artığı

```php
if (Route::has('frontend.hekimler')) {
    $nav[] = [..., 'label' => 'Hekimler', ...];
}
```

Hekim projesinde `frontend.hekimler` route’u yok (klinik konsepti). Şimdilik zararsız; kopyala-yapıştır kalıntısı.

### 6.6 Head’de `custom.css` yok, Hipno `function.js` yok

Orijinal paket `custom.css` + `function.js` kullanır. Burada CSS kopyalanmış, JS `script.blade.php` içinde kısmen yeniden yazılmış (slicknav, wow, parallax, gsap). Eksik animasyon / orijinalden sapma normal.

### 6.7 Anasayfa view’ı tema id’yi hardcode ediyor

```php
$modulListesi = app(...)->renderIcinModuller('tema-1'); // tema-2 ve 3'te kendi id'si
```

`aktifTemaId()` kullanmıyor. Yanlış view çözülürse (fallback) yanlış modül seti gelir. `theme_layout()` doktor gelmeden çalışabildiği için bu kırılgan.

### 6.8 Panel “renk_temadan” checkbox’ı her seçimde zorla işaretleniyor

```javascript
if (document.getElementById('renk_temadan').checked || true) {
    // her zaman varsayılan rengi bas
    document.getElementById('renk_temadan').checked = true;
}
```

`|| true` yüzünden hekim özel renk seçmiş olsa bile tema kartına tıklayınca rengi ezer.

---

## 7. CSS değişken çakışması (detay)

`tema-1` head yükleme sırası:

1. Hipno vendor CSS (bootstrap, slicknav, swiper, fontawesome, animate, magnific, mousecursor)
2. `css/themes/{temaCssId}.css` — içinde **sabit** `--accent-color: #9B9A84` (ikinci `:root`)
3. Inline `:root` — `theme_palette(tema_renk)` → `--brand-*` **ve** `aktifPalet()` → `--accent-color`

Hipno bileşenleri `--accent-color` kullanır. Randevu wizard da (`randevu.blade.php` içindeki yüzlerce satır CSS) `--accent-color` / `--primary-color` kullanır. `--brand-600` (asıl vurgu rengi) pratikte ölü.

`temaCssId` `resolve_site_theme()` id’sinden gelir. tema-2 seçiliyse `tema-2.css` yüklenir; içerik `tema-1.css` ile aynı olduğu için fark yok. `?v=1` cache-bust; layout head’deki fallback `?v=14`. Versiyonlar temalar arasında tutarsız.

---

## 8. Dosya haritası (ne canlı, ne ölü)

```
CANLI (aktif 3 tema)
├── config/themes.php
├── config/tema_modulleri.php
├── app/helpers.php  (theme_*)
├── app/Services/SiteBuilderService.php
├── resources/views/frontend/themes/tema-{1,2,3}/pages/anasayfa.blade.php
├── resources/views/frontend/themes/tema-1/layouts/*   ← 2 ve 3 de burayı include eder
├── resources/views/frontend/themes/tema-{1,2,3}/modules/*
└── public/css/themes/tema-{1,2,3}.css   ← aynı içerik

YARI-CANLI / YANILTICI
├── panel/site-ayarlari/temalar.blade.php     (renk sistemi etkisiz)
├── panel/site-ayarlari/anasayfa.blade.php    (eski bölümler, sitede görünmez)
├── panel/site-ayarlari/slider.blade.php      (Hipno anasayfaya bağlı değil)
├── themes/tema-2|3/layouts/partials/*        (layout bunları kullanmıyor)
└── frontend/pages/sayfa.blade.php            (özel sayfa fallback, temasız)

ÖLÜ / ERTELENMİŞ
├── themes/delogis/**
├── public/themes/delogis/**
├── public/css/themes/delogis.css, modern.css
├── frontend/pages/anasayfa.blade.php (eski)
├── frontend/layouts/app.blade.php (eski)
├── public/css/site.css
└── themes/README.md (yanlış katalog)
```

---

## 9. Önerilen sadeleştirme (uygulama sırası, kod yok)

1. **Tek katalog.** `themes.php` tek kaynak; `tema_modulleri` sadece modül şeması. Panel’de tek tema seçici.
2. **Tek renk.** Ya `tema_renk` → `--accent-color`, ya palet. Diğerini kaldır veya paleti `tema_renk`’ten üret.
3. **Tema-2 slider bug.** `$s['resim']` oku; builder’a `resim_baslik_metin` repeater ekle.
4. **Layout tek paket.** tema-2/3 `app.blade.php` kendi pack’ini veya paylaşılan `hipno` layout’unu kullansın; `layout-tema-1` class’ı kalksın.
5. **CSS tek dosya.** `hipno.css` (veya `tema-1.css`); 2 ve 3 symlink/alias. BOM silinsin. İkinci hardcoded `:root` silinsin.
6. **Ölü editörleri bağla veya gizle.** Site Ayarları → Ana Sayfa ve Slider ya builder’a yönlendirilsin ya da Hipno’da gerçekten kullanılsın.
7. **`pages/sayfa.blade.php`** her temaya (veya paylaşılan Hipno partial’ına) eklensin.
8. **Varsayılan kopya** uzmanlığa göre (veya nötr: “Randevu alın”). Sahte “100+ danışan” / 5 yıldız kapalı gelsin.
9. **Delogis / modern / eski frontend/pages** arşiv veya silme. README güncelle.
10. **Flash key** `basari` tek olsun. `onTemaPick` içindeki `|| true` kalksın.

---

## 10. Öncelikli düzeltme listesi

| # | Öncelik | Konu | Etki |
|---|---------|------|------|
| 1 | P0 | Slider `$s['ikon']` → `$s['resim']` | Tema-2 hero görseli |
| 2 | P0 | Builder `resim_baslik_metin` UI | Tema-2 düzenleme |
| 3 | P0 | `tema_renk` vs palet birleşimi | Renk tutarsızlığı |
| 4 | P0 | tema-2/3 layout hardcode `tema-1` | Body class + ölü partial |
| 5 | P0 | `pages/sayfa.blade.php` eksik | KVKK/özel sayfa |
| 6 | P1 | Çift tema seçici + flash key | Panel UX |
| 7 | P1 | Site Ayarları Ana Sayfa / Slider ölü | Hekim yanılsaması |
| 8 | P1 | Üç CSS aynı hash + BOM + çift `:root` | Bakım / cascade |
| 9 | P1 | Sahte sosyal kanıt + psikoloji kopyası | Yanlış içerik |
| 10 | P1 | Footer “Ücretsiz danışma” | Yanlış vaat |
| 11 | P2 | Delogis/modern/eski pages | Şişkinlik |
| 12 | P2 | README / yorum / alias | Doküman yalanı |
| 13 | P2 | Çift Swiper CDN | Slider JS |
| 14 | P2 | `onTemaPick \|\| true` | Renk ezer |

---

## 11. Test notları (düzeltme sonrası)

- Site Ayarları’ndan tema-1 / 2 / 3 seç → anasayfa hero gerçekten değişsin.
- Tema-2: builder’dan 3 slayt + görsel kaydet → sitede 3 görsel dönsün.
- Vurgu rengi teal yap → butonlar teal olsun (veya palet tek kaynaksa panel metni onu söylesin).
- Palet “Açık & Mavi” → `--accent-color` mavi; Site Ayarları rengi ile çelişmesin.
- `/sayfa/kvkk` Hipno page-header ile açılsın.
- Site Ayarları → Ana Sayfa’da bölüm kapatmak ya sitede görünsün ya da o ekran kalksın.
- Özel sayfa, randevu wizard, blog detay aynı header/footer.
- `layout-tema-2` / `theme-tema-2` body class doğru.
- Panel tema değiştirince yeşil “uygulandı” mesajı çıksın.
