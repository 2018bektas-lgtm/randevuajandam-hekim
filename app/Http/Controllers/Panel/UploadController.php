<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Panel gorsel yukleme — lokal Storage (public disk).
 *
 * URL: POST /yonetim/upload/image
 * Body: multipart/form-data, field: file, alan (opsiyonel: banner/modul)
 * Return: { success, url: '/storage/uploads/panel/...' }
 *
 * storage:link ile /public/storage -> /storage/app/public baglantisi zaten var.
 */
class UploadController extends Controller
{
    public function image(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'], // 5 MB
        ], [
            'file.max' => 'Görsel boyutu en fazla 5 MB olabilir.',
            'file.image' => 'Yalnızca resim dosyaları yüklenebilir.',
            'file.mimes' => 'Desteklenen formatlar: JPG, PNG, WEBP, GIF.',
        ]);

        $file = $request->file('file');
        $klasor = 'uploads/panel/'.now()->format('Y-m');
        // Istemciden gelen uzanti yerine DOGRULANMIS MIME'dan turetilen uzanti;
        // uzanti/icerik uyusmazligi olusmasin.
        $uzanti = $file->extension() ?: $file->getClientOriginalExtension();
        $ad = Str::random(20).'.'.$uzanti;

        $path = $file->storeAs($klasor, $ad, 'public');

        return response()->json([
            'success' => true,
            'url' => Storage::url($path),   // '/storage/uploads/panel/2026-08/xxx.jpg'
            'path' => $path,
        ]);
    }
}
