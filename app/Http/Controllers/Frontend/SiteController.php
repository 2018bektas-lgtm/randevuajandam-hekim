<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SiteContentService;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function __construct(protected SiteContentService $content) {}

    protected function doktor(): array
    {
        return $this->content->doktor();
    }

    public function sayfa(string $slug): View
    {
        $page = \App\Models\SitePage::query()
            ->where('slug', $slug)
            ->where('aktif', true)
            ->firstOrFail();

        return $this->themePage('sayfa', [
            'doktor' => $this->doktor(),
            'page' => $page,
            'baslik' => $page->baslik,
            'icerik' => (string) ($page->icerik ?? ''),
        ]);
    }

    /**
     * Tema blade (frontend.themes.{id}.*) yoksa varsayılan frontend.*
     *
     * @param  array<string, mixed>  $data
     */
    protected function themePage(string $page, array $data = []): View
    {
        $doktor = $data['doktor'] ?? $this->doktor();
        $data['doktor'] = $doktor;

        return theme_view('pages.'.$page, $data, current_theme_id($doktor));
    }

    public function anasayfa(): View
    {
        return $this->themePage('anasayfa');
    }

    public function hakkimda(): View
    {
        return $this->themePage('hakkimda');
    }

    public function hizmetler(): View
    {
        return $this->themePage('hizmetler');
    }

    public function hizmetDetay(string $slug): View
    {
        $doktor = $this->doktor();
        $hizmet = collect($doktor['hizmetler'] ?? [])->first(function ($h) use ($slug) {
            $hSlug = $h['slug'] ?? Str::slug($h['baslik'] ?? '');

            return $hSlug === $slug || (string) ($h['id'] ?? '') === $slug;
        });

        abort_if(! $hizmet, 404);

        return $this->themePage('hizmet-detay', [
            'doktor' => $doktor,
            'hizmet' => $hizmet,
        ]);
    }

    public function galeri(): View
    {
        return $this->themePage('galeri');
    }

    public function blog(): View
    {
        return $this->themePage('blog');
    }

    public function blogDetay(string $slug): View
    {
        $doktor = $this->doktor();
        $yazi = collect($doktor['bloglar'] ?? [])->firstWhere('slug', $slug);

        abort_if(! $yazi, 404);

        return $this->themePage('blog-detay', [
            'doktor' => $doktor,
            'yazi' => $yazi,
        ]);
    }

    public function egitimler(): View
    {
        return $this->themePage('egitimler');
    }

    public function egitimDetay(string $slug): View
    {
        $doktor = $this->doktor();
        $egitim = collect($doktor['egitimler'] ?? [])->first(function ($e) use ($slug) {
            return ($e['slug'] ?? '') === $slug || (string) ($e['id'] ?? '') === $slug;
        });

        if ($egitim && empty($egitim['form_alanlari'])) {
            try {
                $api = app(\App\Services\PlatformApiClient::class);
                $detail = $api->publicGet('/educations/'.($egitim['slug'] ?? $egitim['id']))['data'] ?? null;
                if (is_array($detail)) {
                    $egitim = array_merge($egitim, $detail);
                    if (! empty($detail['kapak'])) {
                        $egitim['image'] = media_url($detail['kapak']);
                    }
                }
            } catch (\Throwable) {
                //
            }
        }

        abort_if(! $egitim, 404);

        return $this->themePage('egitim-detay', [
            'doktor' => $doktor,
            'egitim' => $egitim,
        ]);
    }

    public function sss(): View
    {
        return $this->themePage('sss');
    }

    public function iletisim(): View
    {
        return $this->themePage('iletisim');
    }

    public function sitemap(): Response
    {
        $doktor = $this->doktor();
        $urls = [
            ['loc' => route('frontend.anasayfa'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('frontend.hakkimda'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('frontend.hizmetler'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('frontend.galeri'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('frontend.blog'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('frontend.egitimler'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('frontend.sss'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('frontend.iletisim'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('frontend.randevu'), 'priority' => '0.9', 'changefreq' => 'monthly'],
        ];

        foreach ($doktor['hizmetler'] ?? [] as $h) {
            $slug = $h['slug'] ?? Str::slug($h['baslik'] ?? '');
            if ($slug === '') {
                continue;
            }
            $urls[] = [
                'loc' => route('frontend.hizmet.detay', $slug),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ];
        }

        foreach ($doktor['bloglar'] ?? [] as $b) {
            $slug = $b['slug'] ?? '';
            if ($slug === '') {
                continue;
            }
            $urls[] = [
                'loc' => route('frontend.blog.detay', $slug),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        }

        foreach ($doktor['egitimler'] ?? [] as $e) {
            $slug = $e['slug'] ?? '';
            if ($slug === '') {
                continue;
            }
            $urls[] = [
                'loc' => route('frontend.egitim.detay', $slug),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.e($u['loc']).'</loc>'."\n";
            $xml .= '    <changefreq>'.$u['changefreq'].'</changefreq>'."\n";
            $xml .= '    <priority>'.$u['priority'].'</priority>'."\n";
            $xml .= '  </url>'."\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
