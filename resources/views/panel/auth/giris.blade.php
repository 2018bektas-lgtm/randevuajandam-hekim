<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hekim Girişi · Yönetim Paneli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:  #1a1a1a;
            --accent:   #9B9A84;
            --accent-d: #7a7966;
            --light:    #F9F9F9;
            --text:     #838383;
            --divider:  #2e2e2e;
            --font:     'Sora', system-ui, sans-serif;
            --display:  'Marcellus', 'Times New Roman', serif;
        }

        html, body {
            height: 100%;
            font-family: var(--font);
            background: var(--primary);
            color: #fff;
        }

        .login-wrap {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ---- LEFT PANEL ---- */
        .login-left {
            position: relative;
            background: #111111;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 70%, rgba(155,154,132,.12) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 80% 20%, rgba(155,154,132,.07) 0%, transparent 60%);
        }

        .left-brand {
            position: relative;
            z-index: 1;
        }

        .left-brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2.5rem;
        }

        .left-brand-icon svg { width: 26px; height: 26px; }

        .left-tagline {
            font-family: var(--display);
            font-size: clamp(2rem, 3vw, 3rem);
            line-height: 1.15;
            color: #fff;
            margin-bottom: 1.25rem;
            max-width: 380px;
        }

        .left-tagline em {
            font-style: normal;
            color: var(--accent);
        }

        .left-sub {
            font-size: .875rem;
            color: rgba(255,255,255,.45);
            line-height: 1.7;
            max-width: 340px;
        }

        .left-footer {
            position: relative;
            z-index: 1;
        }

        .left-stats {
            display: flex;
            gap: 2.5rem;
            border-top: 1px solid var(--divider);
            padding-top: 1.75rem;
        }

        .stat-item h3 {
            font-family: var(--display);
            font-size: 1.75rem;
            color: #fff;
            line-height: 1;
        }

        .stat-item p {
            font-size: .75rem;
            color: rgba(255,255,255,.4);
            margin-top: .25rem;
            letter-spacing: .03em;
        }

        /* Deco corner lines */
        .deco-lines {
            position: absolute;
            right: -60px;
            top: 50%;
            transform: translateY(-50%);
            width: 180px;
            height: 180px;
            border: 1px solid rgba(155,154,132,.12);
            border-radius: 50%;
            z-index: 0;
        }
        .deco-lines::before {
            content: '';
            position: absolute;
            inset: 20px;
            border: 1px solid rgba(155,154,132,.08);
            border-radius: 50%;
        }

        /* ---- RIGHT PANEL ---- */
        .login-right {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-title {
            font-family: var(--display);
            font-size: 1.9rem;
            color: var(--primary);
            margin-bottom: .5rem;
            line-height: 1.2;
        }

        .login-sub {
            font-size: .8rem;
            color: #888;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Status badges */
        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
            margin-bottom: 1.5rem;
        }

        .badge {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: .3rem .7rem;
            border-radius: 99px;
        }

        .badge-ok    { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-err   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .badge-warn  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .badge-gray  { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        /* Alerts */
        .alert {
            padding: .875rem 1rem;
            border-radius: 10px;
            font-size: .82rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .alert-error  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .alert-warn   { background: #fffbeb; border: 1px solid #fde68a; color: #78350f; }

        .alert strong { display: block; margin-bottom: .3rem; font-size: .8rem; }
        .alert .hint-box {
            background: rgba(255,255,255,.7);
            border: 1px solid rgba(251,191,36,.3);
            border-radius: 7px;
            padding: .6rem .75rem;
            font-family: monospace;
            font-size: .78rem;
            margin-top: .6rem;
            line-height: 1.7;
        }
        .hint-label {
            font-family: var(--font);
            font-size: .65rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 700;
            color: var(--accent-d);
            display: block;
            margin-bottom: .3rem;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-label {
            display: block;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #6b7280;
            margin-bottom: .4rem;
        }
        .form-label-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: .75rem;
        }
        .form-label-row .form-label { margin-bottom: .4rem; }
        .form-label-link {
            font-size: .68rem;
            font-weight: 600;
            color: #6b7280;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: color .15s, border-color .15s;
        }
        .form-label-link:hover { color: var(--accent); border-bottom-color: currentColor; }

        .form-input {
            width: 100%;
            padding: .75rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-family: var(--font);
            font-size: .9rem;
            color: #111827;
            background: #fafafa;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        .form-input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(155,154,132,.12);
        }

        .form-input::placeholder { color: #c1c1c1; }

        .btn-login {
            width: 100%;
            padding: .85rem 1rem;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: #fff;
            font-family: var(--font);
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .1s;
            margin-top: .5rem;
            letter-spacing: .03em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .btn-login:hover { background: #2d2d2d; }
        .btn-login:active { transform: scale(.99); }

        .btn-accent-line {
            width: 100%;
            height: 3px;
            background: var(--accent);
            border-radius: 0 0 10px 10px;
            margin-top: -3px;
            margin-bottom: 1.25rem;
            transition: opacity .15s;
        }

        .login-footer {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .login-footer p {
            font-size: .75rem;
            color: #9ca3af;
        }

        .login-footer a {
            font-size: .75rem;
            font-weight: 600;
            color: var(--accent-d);
            text-decoration: none;
        }

        .login-footer a:hover { color: var(--primary); }

        /* Mobile */
        @media (max-width: 768px) {
            .login-wrap { grid-template-columns: 1fr; }
            .login-left { display: none; }
            .login-right { background: var(--primary); }
            .login-box { max-width: 440px; }
            .login-title { color: #fff; }
            .login-sub { color: rgba(255,255,255,.5); }
            .form-label { color: rgba(255,255,255,.5); }
            .form-label-link { color: rgba(255,255,255,.55); }
            .form-input { background: #1e1e1e; border-color: var(--divider); color: #fff; }
            .form-input:focus { background: #222; }
            .form-input::placeholder { color: #555; }
            .btn-login { background: var(--accent); }
            .btn-login:hover { background: var(--accent-d); }
            .btn-accent-line { display: none; }
            .login-footer { border-top-color: var(--divider); }
            .login-footer p { color: rgba(255,255,255,.3); }
            .login-footer a { color: var(--accent); }
            .badges .badge-gray { background: #1e1e1e; color: rgba(255,255,255,.4); border-color: var(--divider); }
            .alert-warn { background: rgba(120,53,15,.15); border-color: rgba(251,191,36,.2); color: #fde68a; }
            .alert-error { background: rgba(153,27,27,.12); border-color: rgba(254,202,202,.2); color: #fca5a5; }
            .alert-success { background: rgba(6,95,70,.12); border-color: rgba(167,243,208,.2); color: #6ee7b7; }
        }
    </style>
</head>
<body>
@php
    $apiOk = $apiLive ?? false;
    $apiConfigured = $apiConfigured ?? false;
@endphp

<div class="login-wrap">

    {{-- LEFT PANEL --}}
    <div class="login-left">
        <div class="left-brand">
            <div class="left-brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
            </div>
            <h1 class="left-tagline">
                Yönetim panelinize<br><em>hoş geldiniz</em>
            </h1>
            <p class="left-sub">
                Randevu sisteminizi, hasta kayıtlarınızı ve web sitenizi tek yerden yönetin.
            </p>
        </div>

        <div class="left-footer">
            <div class="left-stats">
                <div class="stat-item">
                    <h3>7/24</h3>
                    <p>Online randevu</p>
                </div>
                <div class="stat-item">
                    <h3>%100</h3>
                    <p>Güvenli bağlantı</p>
                </div>
                <div class="stat-item">
                    <h3>∞</h3>
                    <p>Hasta kaydı</p>
                </div>
            </div>
        </div>

        <div class="deco-lines"></div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="login-right">
        <div class="login-box">

            <h2 class="login-title">Giriş yapın</h2>
            <p class="login-sub">Hekim hesabınızla platform yönetimine erişin.</p>

            {{-- Durum rozetleri --}}
            <div class="badges">
                @if($apiOk)
                    <span class="badge badge-ok">API Canlı</span>
                @elseif($apiConfigured)
                    <span class="badge badge-err">API Geçersiz</span>
                @else
                    <span class="badge badge-warn">API Bağlantısı Yok</span>
                @endif
                <span class="badge badge-gray">Panel Açık</span>
            </div>

            {{-- Bildirimler --}}
            @if(session('hata'))
                <div class="alert alert-error">{{ session('hata') }}</div>
            @endif
            @if(session('basari'))
                <div class="alert alert-success">{{ session('basari') }}</div>
            @endif
            @if(session('uyari'))
                <div class="alert alert-warn">{{ session('uyari') }}</div>
            @endif

            @if(!$apiOk)
                <div class="alert alert-warn">
                    <strong>Platform bağlantısı yok</strong>
                    @if(!empty($apiError))
                        <span style="display:block;font-size:.78rem;margin-bottom:.5rem;opacity:.8">{{ $apiError }}</span>
                    @endif
                    Yerel yönetici hesabıyla panele girip <strong>API Entegrasyonu</strong> sayfasından
                    ana sunucu bilgilerini güncelleyebilirsiniz.
                    @if(!empty($showLocalHints))
                        <div class="hint-box">
                            <span class="hint-label">Yalnızca yerel geliştirme</span>
                            E-posta: <strong>{{ $localAdminEmail ?? 'admin@site.local' }}</strong><br>
                            Şifre: <strong>LOCAL_ADMIN_PASSWORD</strong> (.env)
                        </div>
                    @endif
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('panel.giris.post') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="e_posta">E-posta</label>
                    <input class="form-input" type="email" id="e_posta" name="e_posta"
                           value="{{ old('e_posta') }}" required placeholder="hekim@ornek.com"
                           autocomplete="username">
                </div>
                <div class="form-group">
                    <div class="form-label-row">
                        <label class="form-label" for="sifre">Şifre</label>
                        {{-- Hekim hesabı platformda tutulur; sıfırlama da orada yapılır. --}}
                        @if($sifreSifirlaUrl ?? null)
                            <a class="form-label-link" href="{{ $sifreSifirlaUrl }}"
                               target="_blank" rel="noopener">Şifremi unuttum</a>
                        @endif
                    </div>
                    <input class="form-input" type="password" id="sifre" name="sifre"
                           required placeholder="••••••••" autocomplete="current-password">
                </div>
                <button type="submit" class="btn-login">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                    Giriş Yap
                </button>
                <div class="btn-accent-line"></div>
            </form>

            <div class="login-footer">
                <p>API anahtarı yalnızca ana sunucuda üretilir.</p>
                <a href="{{ route('frontend.anasayfa') }}">← Siteye dön</a>
            </div>

        </div>
    </div>

</div>
</body>
</html>
