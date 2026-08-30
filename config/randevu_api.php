<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform API kökü (ayrı api/ projesi)
    |--------------------------------------------------------------------------
    | Örn: http://127.0.0.1:8001/api/v1
    */
    'platform_base' => rtrim(env('RANDEVU_API_PLATFORM', env('RANDEVU_API_BASE_URL', 'http://127.0.0.1:8001/api/v1')), '/'),

    'base_url' => rtrim(env('RANDEVU_API_BASE_URL', 'http://127.0.0.1:8001/api/v1/public'), '/'),

    /*
    |--------------------------------------------------------------------------
    | Platformun HALKA AÇIK site adresi (API kökü değil)
    |--------------------------------------------------------------------------
    | Hekim hesapları bu sitede tutulur; şifre sıfırlama gibi akışlar oraya
    | yönlendirilir. Boş bırakılırsa platform_base'ten türetilir:
    |   https://api.randevuajandam.com/api/v1 → https://randevuajandam.com
    |   http://127.0.0.1:8001/api/v1          → http://127.0.0.1:8001
    | Türetme yanlışsa RANDEVU_SITE_URL ile açıkça verin.
    */
    'site_url' => rtrim((string) env('RANDEVU_SITE_URL', ''), '/'),

    /*
    | Hekim sitesi API anahtarları (platformda oluşturulan api_keys kaydı)
    | api_key  → X-Api-Key
    | secret   → X-Api-Secret (kayıtta secret varsa zorunlu)
    */
    'api_key' => env('RANDEVU_API_KEY', ''),
    'api_secret' => env('RANDEVU_API_SECRET', ''),
    // Outbound webhook from main platform (SendWebhookJob HMAC)
    'webhook_receiver_secret' => env('WEBHOOK_RECEIVER_SECRET', ''),

    'enabled' => (bool) env('RANDEVU_API_ENABLED', true),

    /*
    | Medya kökü — API shared public proxy
    | Örn: http://127.0.0.1:8001/media  →  /media/uploads/hizmet/x.jpg
    | Dosyalar site/public (SHARED_PUBLIC_PATH) üzerinden servis edilir.
    */
    'media_base' => rtrim(env('RANDEVU_MEDIA_BASE', 'http://127.0.0.1:8001/media'), '/'),

    /*
    | Public site content cache (saniye).
    | Önceden 20s idi — her istekte 3–4 HTTP turu siteyi yavaşlatıyordu.
    */
    'content_cache_ttl' => (int) env('RANDEVU_CONTENT_CACHE_TTL', 300),

    /*
    | Public API HTTP timeout (saniye). Panel mutasyonları ayrı timeout kullanır.
    */
    'public_timeout' => (int) env('RANDEVU_PUBLIC_TIMEOUT', 8),
    'public_connect_timeout' => (int) env('RANDEVU_PUBLIC_CONNECT_TIMEOUT', 3),
];
