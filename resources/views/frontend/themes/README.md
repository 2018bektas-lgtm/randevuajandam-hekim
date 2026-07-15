# Tema paketleri (tam blade seti)

Her tema kendi klasöründe **layout + tüm sayfalar** taşır:

```
themes/{pack}/
  layouts/
    app.blade.php
    partials/
      head.blade.php   (klasik; diğerleri ortak head kullanabilir)
      header.blade.php
      footer.blade.php
      script.blade.php (opsiyonel)
  pages/
    anasayfa.blade.php
    hakkimda.blade.php
    hizmetler.blade.php
    hizmet-detay.blade.php
    blog.blade.php
    blog-detay.blade.php
    galeri.blade.php
    iletisim.blade.php
    sss.blade.php
    egitimler.blade.php      (doktor sitesi)
    egitim-detay.blade.php
    hekimler.blade.php       (klinik sitesi)
    hekim-detay.blade.php
```

## Çözümleme

1. `theme_layout()` → `themes/{pack}/layouts/app`
2. `theme_view('pages.X')` → `themes/{pack}/pages/X` (yoksa klasik, yoksa eski `frontend/pages`)

## Yeni tema

1. `config/themes.php` kaydı (`layout` = klasör adı)
2. `themes/{id}/` tüm layout + pages kopyala / tasarla
3. `public/css/themes/{id}.css`

Panelden tema seçince **tüm sayfa blade seti** o paketten yüklenir.
