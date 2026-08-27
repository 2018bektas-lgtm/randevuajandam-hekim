<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\PlatformApiClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Misafir randevu isteklerini sunucu tarafında API'ye iletir.
 * API key/secret tarayıcıya asla sızmaz.
 */
class BookingProxyController extends Controller
{
    public function __construct(protected PlatformApiClient $api) {}

    public function services(): JsonResponse
    {
        return $this->forward('GET', '/services');
    }

    public function slots(Request $request): JsonResponse
    {
        $date = (string) $request->query('date', '');
        if ($date === '' || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json([
                'success' => false,
                'message' => 'Geçerli bir tarih (YYYY-MM-DD) gerekli.',
            ], 422);
        }

        $query = ['date' => $date];
        $hizmetId = (int) $request->query('hizmet_id', 0);
        if ($hizmetId > 0) {
            $query['hizmet_id'] = $hizmetId;
        }

        return $this->forward('GET', '/slots', $query);
    }

    public function availability(Request $request): JsonResponse
    {
        $from = (string) $request->query('from', '');
        $to = (string) $request->query('to', '');
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $from) || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            return response()->json([
                'success' => false,
                'message' => 'Geçerli bir tarih aralığı (from, to) gerekli.',
            ], 422);
        }

        $query = ['from' => $from, 'to' => $to];
        $hizmetId = (int) $request->query('hizmet_id', 0);
        if ($hizmetId > 0) {
            $query['hizmet_id'] = $hizmetId;
        }

        return $this->forward('GET', '/availability', $query);
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'telefon' => ['required', 'string', 'max:30'],
        ]);

        return $this->forward('POST', '/otp/send', $data);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'telefon' => ['required', 'string', 'max:30'],
            'kod' => ['required', 'string', 'max:10'],
        ]);

        return $this->forward('POST', '/otp/verify', $data);
    }

    public function storeAppointment(Request $request): JsonResponse
    {
        // Honeypot — bot dolu doldurursa sessiz başarı
        if (filled($request->input('website_url'))) {
            return response()->json([
                'success' => true,
                'message' => 'Talebiniz alındı.',
                'data' => [],
            ]);
        }

        $captcha = app(\App\Services\RecaptchaService::class)->verify(
            $request->input('recaptcha_token'),
            'randevu',
            $request->ip()
        );
        if (! ($captcha['ok'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $captcha['message'] ?? 'Güvenlik doğrulaması başarısız.',
            ], 422);
        }

        $data = $request->validate([
            'hizmet_id' => ['required', 'integer', 'min:1'],
            'tarih' => ['required', 'date_format:Y-m-d'],
            'saat' => ['required', 'string', 'max:10'],
            'ad' => ['required', 'string', 'max:100'],
            'soyad' => ['required', 'string', 'max:100'],
            'telefon' => ['required', 'string', 'max:30'],
            'e_posta' => ['nullable', 'email', 'max:255'],
            'not' => ['nullable', 'string', 'max:1000'],
            'gorusme_tipi' => ['nullable', 'in:yuz_yuze,online'],
            'kvkk_onay' => ['required', 'accepted'],
            'otp_kod' => ['nullable', 'string', 'max:10'],
            'recaptcha_token' => ['nullable', 'string'],
        ], [
            'kvkk_onay.accepted' => 'KVKK onayı zorunludur.',
        ]);

        $payload = [
            'hizmet_id' => (int) $data['hizmet_id'],
            'tarih' => $data['tarih'],
            'saat' => $data['saat'],
            'ad' => $data['ad'],
            'soyad' => $data['soyad'],
            'telefon' => $data['telefon'],
            'e_posta' => $data['e_posta'] ?? null,
            'not' => $data['not'] ?? null,
            'gorusme_tipi' => ($data['gorusme_tipi'] ?? 'yuz_yuze') === 'online' ? 'online' : 'yuz_yuze',
            'kvkk_onay' => 1,
            'website_url' => '',
            'otp_kod' => $data['otp_kod'] ?? null,
            // Proxy zaten doğruladı; token'ı da ilet (platform secret varsa API ek kontrol eder / soft skip)
            'recaptcha_token' => $data['recaptcha_token'] ?? null,
        ];

        return $this->forward('POST', '/appointments', $payload);
    }

    /**
     * Eğitim başvurusu (ödeme siteden alınmaz).
     */
    public function storeEducationApplication(Request $request): JsonResponse
    {
        if (filled($request->input('website_url'))) {
            return response()->json([
                'success' => true,
                'message' => 'Başvurunuz alındı.',
                'data' => [],
            ]);
        }

        $data = $request->validate([
            'egitim_id' => ['required', 'integer', 'min:1'],
            'ad' => ['required', 'string', 'max:100'],
            'soyad' => ['required', 'string', 'max:100'],
            'telefon' => ['required', 'string', 'max:40'],
            'e_posta' => ['nullable', 'email', 'max:255'],
            'kvkk_onay' => ['required', 'accepted'],
            'alan' => ['nullable', 'array'],
        ], [
            'kvkk_onay.accepted' => 'KVKK onayı zorunludur.',
        ]);

        $payload = [
            'egitim_id' => (int) $data['egitim_id'],
            'ad' => $data['ad'],
            'soyad' => $data['soyad'],
            'telefon' => $data['telefon'],
            'e_posta' => $data['e_posta'] ?? null,
            'kvkk_onay' => 1,
            'alan' => $data['alan'] ?? [],
            'website_url' => '',
        ];

        return $this->forward('POST', '/educations/apply', $payload);
    }

    public function status(): JsonResponse
    {
        if (! $this->api->isConfigured()) {
            return response()->json([
                'ok' => false,
                'message' => 'API yapılandırılmamış.',
            ]);
        }

        try {
            [$status, $body] = $this->api->publicProxy('GET', '/services');
            $ok = $status >= 200 && $status < 300;

            return response()->json([
                'ok' => $ok,
                'message' => $ok ? 'bağlı' : ($body['message'] ?? 'Bağlantı hatası'),
            ], $ok ? 200 : 503);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => $e->getMessage()], 503);
        }
    }

    protected function forward(string $method, string $path, array $data = []): JsonResponse
    {
        [$status, $body] = $this->api->publicProxy($method, $path, $data);

        return response()->json($body, $status >= 100 && $status < 600 ? $status : 502);
    }
}
