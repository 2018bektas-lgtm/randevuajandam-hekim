<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Anasayfa modülü (tema bazlı).
 *
 * key           : modül kodu (hero_static, about, services, ...)
 * tema_id       : hangi tema için (tema-1, tema-2, ...)
 * ozel_ayarlar  : JSON, config/tema_modulleri.php içindeki `alanlar` tanımına
 *                 karşılık gelen kullanıcı değerleri
 */
class SiteHomepageSection extends Model
{
    protected $table = 'site_homepage_sections';

    protected $fillable = [
        'key',
        'tema_id',
        'label',
        'baslik',
        'alt_metin',
        'ozel_ayarlar',
        'aktif',
        'sira',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'sira' => 'integer',
            'ozel_ayarlar' => 'array',
        ];
    }

    /**
     * Belirli tema için aktif modülleri sıralı olarak.
     */
    public static function aktifModuller(string $temaId)
    {
        return static::query()
            ->where('tema_id', $temaId)
            ->where('aktif', true)
            ->orderBy('sira')
            ->get();
    }
}
