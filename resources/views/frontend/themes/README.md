# Hekim tema paketleri (tam blade seti)

**Hekim sitesi** yalnızca şu iki temayı kullanır:

| id | Ad | Klasör |
|----|-----|--------|
| `tema-1` | Hipno | `themes/tema-1/` |
| `delogis` | Delogis | `themes/delogis/` |

Klinik temaları (`randevuajandam-klinik`) buraya eklenmez.

```
themes/{pack}/
  layouts/
    app.blade.php
    partials/
      head.blade.php
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
    egitimler.blade.php
    egitim-detay.blade.php
```

## Çözümleme

1. `theme_layout()` → `themes/{pack}/layouts/app`
2. `theme_view('pages.X')` → `themes/{pack}/pages/X` (yoksa `tema-1`, yoksa eski `frontend/*`)

## Yeni hekim teması

1. `config/themes.php` kaydı (`layout` = klasör adı, `audience` = hekim)
2. `themes/{id}/` layout + pages
3. `public/css/themes/{id}.css` ve gerekirse `public/themes/{id}/`
4. Platform `randevuajandam-site/config/hekim_themes.php` kataloğuna aynı id’yi ekle

Panelden tema seçince **tüm sayfa blade seti** o paketten yüklenir.
