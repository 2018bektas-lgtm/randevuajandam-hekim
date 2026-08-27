<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Services\SayfaIcerikService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Frontend sayfa banner + kisa icerik ayarlari paneli.
 *
 * URL: /yonetim/sayfa-icerikleri
 * Katalog: config/sayfa_icerikleri.php
 * Servis:  App\Services\SayfaIcerikService
 */
class SayfaIcerikController extends Controller
{
    public function __construct(protected SayfaIcerikService $service) {}

    public function index()
    {
        $sayfalar = (array) config('sayfa_icerikleri', []);
        $ayarlar = $this->service->tumu();

        return view('panel.sayfa-icerikleri.index', compact('sayfalar', 'ayarlar'));
    }

    public function kaydet(Request $request, string $sayfa): JsonResponse
    {
        $tanim = (array) config("sayfa_icerikleri.$sayfa", []);
        if ($tanim === []) {
            return response()->json(['success' => false, 'message' => 'Bilinmeyen sayfa.'], 404);
        }

        // Alan tanimlarina gore veri filtrele
        $degerler = [];
        foreach ((array) ($tanim['alanlar'] ?? []) as $alanKod => $alan) {
            if ($request->has($alanKod)) {
                $degerler[$alanKod] = $request->input($alanKod);
            }
        }

        $this->service->sayfaKaydet($sayfa, $degerler);

        return response()->json([
            'success' => true,
            'message' => 'Sayfa ayarları kaydedildi.',
        ]);
    }
}
