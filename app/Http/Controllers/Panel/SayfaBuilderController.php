<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\SiteBuilderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

/**
 * Hekim paneli — Sayfa Builder.
 *
 * Anasayfa modüllerinin sıralama, aktif/pasif, özel ayarlar ve renk paleti
 * yönetimini yapar. Konfigürasyon: config/tema_modulleri.php
 */
class SayfaBuilderController extends Controller
{
    public function __construct(protected SiteBuilderService $builder) {}

    public function index(Request $request)
    {
        $temaId = (string) $request->query('tema', $this->builder->aktifTemaId());
        if (! $this->builder->temaVarMi($temaId)) {
            $temaId = $this->builder->aktifTemaId();
        }

        $tema = (array) config("tema_modulleri.temalar.$temaId");
        $moduller = $this->builder->builderIcinModuller($temaId);
        $paletler = (array) ($tema['renk_paletleri'] ?? []);
        $aktifPalet = $this->builder->aktifPalet($temaId);
        $temalar = (array) config('tema_modulleri.temalar', []);

        return view('panel.sayfa-builder.index', compact(
            'temaId', 'tema', 'moduller', 'paletler', 'aktifPalet', 'temalar'
        ));
    }

    /**
     * Toplu sıra + aktif/pasif kaydet (drag-drop).
     * Payload: { tema_id, liste: [{kod, aktif, sira}, ...] }
     */
    public function siraKaydet(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tema_id' => ['required', 'string', 'max:20'],
            'liste' => ['required', 'array'],
            'liste.*.kod' => ['required', 'string', 'max:60'],
            'liste.*.aktif' => ['required', 'boolean'],
            'liste.*.sira' => ['required', 'integer', 'min:0'],
        ]);

        try {
            $this->builder->siraAktifKaydet($data['tema_id'], $data['liste']);
        } catch (InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'message' => 'Modül sıraları kaydedildi.']);
    }

    /**
     * Bir modülün özel ayarlarını kaydet.
     */
    public function modulKaydet(Request $request, string $kod): JsonResponse
    {
        $temaId = (string) $request->input('tema_id', $this->builder->aktifTemaId());
        $tanim = (array) config("tema_modulleri.temalar.$temaId.moduller.$kod");
        if ($tanim === []) {
            return response()->json(['success' => false, 'message' => 'Bilinmeyen modül.'], 404);
        }

        // Modül alan tanımlarına göre gelen veriyi filtrele
        $alanlar = (array) ($tanim['alanlar'] ?? []);
        $ayar = [];
        foreach ($alanlar as $alanKod => $alan) {
            if (! $request->has($alanKod)) {
                continue;
            }
            $val = $request->input($alanKod);

            // Tip'e göre normalize
            switch ($alan['tip'] ?? 'metin') {
                case 'sayi':
                    $ayar[$alanKod] = (int) $val;
                    break;
                case 'ikon_baslik_metin':
                    // JSON string olarak da gelebilir
                    if (is_string($val)) {
                        $dec = json_decode($val, true);
                        $ayar[$alanKod] = is_array($dec) ? array_values($dec) : [];
                    } else {
                        $ayar[$alanKod] = is_array($val) ? array_values($val) : [];
                    }
                    break;
                case 'liste':
                case 'metin':
                case 'uzun_metin':
                case 'renk':
                    $ayar[$alanKod] = is_string($val) ? trim($val) : $val;
                    break;
                case 'resim':
                    // Dosya yükleme ayrı endpoint'te — burada url string kabul
                    $ayar[$alanKod] = is_string($val) ? trim($val) : null;
                    break;
                default:
                    $ayar[$alanKod] = $val;
            }
        }

        $kayit = $this->builder->modulKaydet($temaId, $kod, $ayar);

        return response()->json([
            'success' => true,
            'message' => 'Modül ayarları kaydedildi.',
            'kayit_id' => $kayit->id,
        ]);
    }

    /**
     * Tema seç. defaultSetiOlustur() sayesinde yeni tema için otomatik seed.
     */
    public function temaSec(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tema_id' => ['required', 'string', 'max:20'],
        ]);

        try {
            $this->builder->temaSec($data['tema_id']);
        } catch (InvalidArgumentException $e) {
            return back()->with('hata', $e->getMessage());
        }

        return redirect()
            ->route('panel.sayfa-builder.index', ['tema' => $data['tema_id']])
            ->with('basarili', 'Tema değiştirildi: '.$data['tema_id']);
    }

    /**
     * Renk paleti seç (hazır varyant veya özel).
     */
    public function paletSec(Request $request): JsonResponse
    {
        $data = $request->validate([
            'palet_kod' => ['required', 'string', 'max:20'],
            'ozel' => ['nullable', 'array'],
            'ozel.primary' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'ozel.accent' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'ozel.bg' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'ozel.text' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'ozel.text_light' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        try {
            $this->builder->paletSec($data['palet_kod'], $data['ozel'] ?? null);
        } catch (InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Renk paleti kaydedildi.',
            'palet' => $this->builder->aktifPalet(),
        ]);
    }
}
