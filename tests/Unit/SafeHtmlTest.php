<?php

namespace Tests\Unit;

use App\Services\HtmlSanitizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * safe_html() — `{!! !!}` ile ham basılacak zengin metin için.
 *
 * Regresyon (P2-3): İçerik kaydetme anında HtmlSanitizer ile temizleniyor ve
 * nitelik değerleri e() ile kaçırılıyordu. Ancak tüketici tarafta çift-encode
 * Türkçe karakterleri düzeltmek için TEKRAR decode_text() uygulanıyor, bu da
 * sanitizer'ın kaçışlarını geri alıyordu:
 *
 *   girdi    : <a href="/x" title='x" onmouseover="alert(1)'>t</a>
 *   sanitize : title="x&quot; onmouseover=&quot;alert(1)"   (güvenli)
 *   decode   : title="x" onmouseover="alert(1)"             (CANLI XSS)
 *
 * Sonuç depolanmış XSS idi: `bio_html`, `icerik_html` ve delogis temasındaki
 * `$hDesc` / `$icerik` bu zincirle üretilip ham basılıyordu.
 */
class SafeHtmlTest extends TestCase
{
    /**
     * Çıktıyı gerçek HTML olarak ayrıştırıp CANLI olay niteliği var mı bakar.
     * Kaçırılmış (`&quot;` içindeki) metin zararsızdır; string araması bunu
     * ayırt edemediği için parser kullanılıyor.
     *
     * @return array<int, string>
     */
    private function canliOlayNitelikleri(string $html): array
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $bulunan = [];
        foreach ((new \DOMXPath($doc))->query('//*') as $el) {
            foreach ($el->attributes ?? [] as $a) {
                if (stripos($a->nodeName, 'on') === 0) {
                    $bulunan[] = $a->nodeName;
                }
            }
        }

        return $bulunan;
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function xssSaglayici(): array
    {
        return [
            // Tek tırnaklı nitelik içindeki çift tırnak: kritik vaka
            'tek tirnak + onmouseover' => ['<a href="/x" title=\'x" onmouseover="alert(1)\'>t</a>'],
            'img alt + onerror' => ['<img src="/a.jpg" alt=\'x" onerror="alert(1)\'>'],
            'dogrudan onclick' => ['<a href="/x" onclick="alert(1)">t</a>'],
            'script etiketi' => ['<p>ok</p><script>alert(1)</script>'],
            'javascript semasi' => ['<a href="javascript:alert(1)">t</a>'],
            'entity encoded js' => ['<a href="&#106;avascript:alert(1)">t</a>'],
        ];
    }

    #[DataProvider('xssSaglayici')]
    public function test_safe_html_canli_olay_niteligi_birakmaz(string $girdi): void
    {
        $cikti = safe_html($girdi);

        $this->assertSame(
            [],
            $this->canliOlayNitelikleri($cikti),
            'safe_html cikisinda canli olay niteligi kaldi: '.$cikti
        );
        $this->assertStringNotContainsStringIgnoringCase('<script', $cikti);
        $this->assertStringNotContainsStringIgnoringCase('javascript:', $cikti);
    }

    /**
     * Eski zincirin gerçekten savunmasız olduğunu sabitler; birisi
     * safe_html() yerine decode_text(clean()) kalıbına dönerse bu test
     * neden geri alınmaması gerektiğini gösterir.
     */
    public function test_eski_zincir_savunmasizdi(): void
    {
        $girdi = '<a href="/x" title=\'x" onmouseover="alert(1)\'>t</a>';

        $eski = decode_text(HtmlSanitizer::clean($girdi));

        $this->assertNotSame(
            [],
            $this->canliOlayNitelikleri($eski),
            'Eski zincir artik savunmasiz degilse bu test guncellenmeli.'
        );
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function turkceSaglayici(): array
    {
        return [
            'cift encode' => ['<p>&amp;Ccedil;ocuk ve &amp;uuml;lke</p>', 'Çocuk ve ülke'],
            'tek encode' => ['<p>&Ccedil;ocuk psikolojisi</p>', 'Çocuk psikolojisi'],
        ];
    }

    /**
     * decode_text'in çözdüğü asıl sorun (çift encode Türkçe karakter)
     * kaybolmamalı — safe_html önce decode edip sonra yeniden sanitize eder.
     */
    #[DataProvider('turkceSaglayici')]
    public function test_turkce_cift_encode_duzeltmesi_korunur(string $girdi, string $beklenen): void
    {
        $this->assertStringContainsString($beklenen, safe_html($girdi));
    }

    public function test_gecerli_bicimlendirme_korunur(): void
    {
        $girdi = '<p>Detay: <a href="https://randevuajandam.com">tıklayın</a></p>'
            .'<p><img src="/uploads/a.jpg" alt=""></p><ul><li>Madde</li></ul>';

        $cikti = safe_html($girdi);

        $this->assertStringContainsString('<a href="https://randevuajandam.com"', $cikti);
        $this->assertStringContainsString('<img src="/uploads/a.jpg"', $cikti);
        $this->assertStringContainsString('<li>Madde</li>', $cikti);
    }

    public function test_bos_girdi(): void
    {
        $this->assertSame('', safe_html(null));
        $this->assertSame('', safe_html(''));
    }
}
