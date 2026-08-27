# Hekim tema paketleri

Aktif katalog (`config/themes.php` + `config/tema_modulleri.php`):

| id | Ad | Hero | Klasör |
|----|-----|------|--------|
| `tema-1` | Hipno Klasik | statik | `themes/tema-1/` |
| `tema-2` | Hipno Slider | swiper | `themes/tema-2/` |
| `tema-3` | Hipno Video | video | `themes/tema-3/` |

Her temanın anasayfa modülleri ayrı kayıttır (`site_homepage_sections.tema_id`). Hekim tema seçip kaydedince o set seed edilir; diğer temanın ayarları silinmez.

```
themes/{pack}/
  layouts/app.blade.php + partials
  modules/*.blade.php
  pages/*.blade.php
```

## Çözümleme

1. `theme_layout()` → `themes/{pack}/layouts/app`
2. Anasayfa → `SiteBuilderService::renderIcinModuller(aktif tema)`
3. İç sayfalar → `themes/{pack}/pages/X`

Panel: **Tema Seçimi** (`/yonetim/site-ayarlari/temalar`) → kaydet → **Ana Sayfa Tasarımı** (`/yonetim/sayfa-builder`).
