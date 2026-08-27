<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * site_homepage_sections.key uzerindeki tekil unique kaldirilir,
 * (tema_id, key) composite unique eklenir — birden fazla tema ayni modul
 * kodunu paylasabilsin.
 */
return new class extends Migration
{
    public function up(): void
    {
        // SQLite dropUnique bazi surumlerde sorunlu — recreate table yolunu izle
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $this->recreateForSqlite();
            return;
        }

        Schema::table('site_homepage_sections', function (Blueprint $table) {
            try {
                $table->dropUnique('site_homepage_sections_key_unique');
            } catch (\Throwable $e) {
                // isim farkli olabilir, ignore
            }
            $table->unique(['tema_id', 'key'], 'shs_tema_key_unique');
        });
    }

    protected function recreateForSqlite(): void
    {
        // SQLite: eski tabloyu yeni tabloya kopyala, unique degisir.
        Schema::create('site_homepage_sections_yeni', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('tema_id', 20)->default('tema-1');
            $table->string('label');
            $table->string('baslik')->nullable();
            $table->text('alt_metin')->nullable();
            $table->json('ozel_ayarlar')->nullable();
            $table->boolean('aktif')->default(true);
            $table->unsignedInteger('sira')->default(0);
            $table->timestamps();

            $table->unique(['tema_id', 'key'], 'shs_tema_key_unique');
            $table->index(['tema_id', 'sira'], 'shs_tema_sira_idx_v2');
        });

        DB::statement('INSERT INTO site_homepage_sections_yeni (id, key, tema_id, label, baslik, alt_metin, ozel_ayarlar, aktif, sira, created_at, updated_at)
            SELECT id, key, COALESCE(tema_id, "tema-1"), label, baslik, alt_metin, ozel_ayarlar, aktif, sira, created_at, updated_at FROM site_homepage_sections');

        Schema::drop('site_homepage_sections');
        Schema::rename('site_homepage_sections_yeni', 'site_homepage_sections');
    }

    public function down(): void
    {
        // Geri donus kimsenin ihtiyaci degil (breaking change tersine cevrilse
        // ekli tema-2/tema-3 kayitlari kaybolur). Sadece composite kisidi kaldir.
        Schema::table('site_homepage_sections', function (Blueprint $table) {
            try {
                $table->dropUnique('shs_tema_key_unique');
            } catch (\Throwable) {}
        });
    }
};
