<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tema bazli modül sistemi:
 *  - site_homepage_sections.tema_id  → hangi tema için (tema-1, tema-2, ...)
 *  - site_homepage_sections.ozel_ayarlar → JSON, modül-özel alanlar
 *    (bkz. config/tema_modulleri.php içindeki `alanlar` tanımı)
 *
 * Tema seçimi ve renk paleti site_options tablosunda tutulur:
 *   key='tema_id'          → 'tema-1'
 *   key='renk_palet_kod'   → 'koyu-altin' | 'acik-mavi' | ... | 'ozel'
 *   key='renk_palet_ozel'  → JSON { primary, accent, bg, text, text_light }
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_homepage_sections', function (Blueprint $table) {
            if (! Schema::hasColumn('site_homepage_sections', 'tema_id')) {
                $table->string('tema_id', 20)->default('tema-1')->after('key');
                $table->index(['tema_id', 'sira'], 'shs_tema_sira_idx');
            }
            if (! Schema::hasColumn('site_homepage_sections', 'ozel_ayarlar')) {
                // SQLite json aslında text olarak saklanır; JSON cast model tarafinda
                $table->json('ozel_ayarlar')->nullable()->after('alt_metin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_homepage_sections', function (Blueprint $table) {
            if (Schema::hasColumn('site_homepage_sections', 'ozel_ayarlar')) {
                $table->dropColumn('ozel_ayarlar');
            }
            if (Schema::hasColumn('site_homepage_sections', 'tema_id')) {
                $table->dropIndex('shs_tema_sira_idx');
                $table->dropColumn('tema_id');
            }
        });
    }
};
