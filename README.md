# Randevu Ajandam — Doktor Sitesi

Bireysel hekim vitrin sitesi + CMS. Randevu verisi `api` üzerinden gelir.

## Gereksinim

- Hekim paketi: **Özel Web Sitesi**
- Çalışan `api` (8001) ve ana `site` (8000)

## Kurulum

```bash
cd doktorsitesi
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8002
```

### Önemli .env

```env
APP_URL=http://127.0.0.1:8002
RANDEVU_API_PLATFORM=http://127.0.0.1:8001/api/v1
RANDEVU_API_BASE_URL=http://127.0.0.1:8001/api/v1/public
RANDEVU_API_KEY=
RANDEVU_API_SECRET=
RANDEVU_MEDIA_BASE=http://127.0.0.1:8001/media
WEBHOOK_RECEIVER_SECRET=
LOCAL_ADMIN_AUTO_CREATE=true
```

Panel: `/yonetim` → API entegrasyon + **Site Ayarları → Temalar**

Not: Otomatik local admin yalnızca `local`/`testing` ortamında oluşur.
