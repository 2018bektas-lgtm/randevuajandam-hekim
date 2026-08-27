<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hekim Girişi · Yönetim Paneli</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/hipno/css/all.min.css') }}">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

        :root{
            --primary:#1c1c1c;
            --accent:#9B9A84;
            --accent-d:#7a7966;
            --light:#F9F9F9;
            --text-muted:#838383;
            --divider:rgba(255,255,255,.08);
            --f:     'Sora',system-ui,sans-serif;
            --fd:    'Marcellus','Times New Roman',serif;
        }

        html,body{height:100%;font-family:var(--f);background:var(--primary)}

        /* ── LAYOUT ──────────────────────────────────────────── */
        .wrap{
            min-height:100vh;
            display:grid;
            grid-template-columns:1fr 480px;
        }

        /* ── LEFT ────────────────────────────────────────────── */
        .left{
            position:relative;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            padding:3rem;
            background:
                radial-gradient(ellipse 55% 55% at 25% 80%, rgba(155,154,132,.13) 0%,transparent 70%),
                radial-gradient(ellipse 40% 40% at 75% 15%, rgba(155,154,132,.07) 0%,transparent 65%),
                #131313;
            overflow:hidden;
        }

        /* decorative rings */
        .left::after{
            content:'';
            position:absolute;
            right:-120px;
            top:50%;
            transform:translateY(-50%);
            width:360px;
            height:360px;
            border:1px solid rgba(155,154,132,.1);
            border-radius:50%;
            box-shadow:0 0 0 60px rgba(155,154,132,.04),
                       0 0 0 120px rgba(155,154,132,.025);
        }

        .left-top{position:relative;z-index:1}

        .brand-logo{
            display:inline-flex;
            align-items:center;
            gap:.75rem;
            margin-bottom:3rem;
            text-decoration:none;
        }
        .brand-logo .icon{
            width:44px;height:44px;border-radius:10px;
            background:var(--accent);
            display:flex;align-items:center;justify-content:center;
            flex-shrink:0;
        }
        .brand-logo .icon i{color:#fff;font-size:1.1rem}
        .brand-logo span{
            font-family:var(--fd);
            font-size:1.15rem;
            color:#fff;
            line-height:1.2;
        }

        .hero-heading{
            font-family:var(--fd);
            font-size:clamp(2.2rem,3.5vw,3.4rem);
            color:#fff;
            line-height:1.12;
            max-width:420px;
            margin-bottom:1.25rem;
        }
        .hero-heading em{font-style:normal;color:var(--accent)}

        .hero-sub{
            font-size:.9rem;
            color:rgba(255,255,255,.42);
            line-height:1.75;
            max-width:360px;
        }

        .left-bottom{
            position:relative;
            z-index:1;
            border-top:1px solid var(--divider);
            padding-top:2rem;
            display:flex;
            gap:3rem;
        }

        .stat h3{
            font-family:var(--fd);
            font-size:2rem;
            color:#fff;
            line-height:1;
        }
        .stat p{
            font-size:.72rem;
            color:rgba(255,255,255,.35);
            letter-spacing:.04em;
            margin-top:.3rem;
        }

        /* ── RIGHT ───────────────────────────────────────────── */
        .right{
            background:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:2.5rem 2rem;
            border-left:1px solid rgba(0,0,0,.06);
        }

        .form-box{width:100%;max-width:360px}

        .form-eyebrow{
            font-size:.7rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.12em;
            color:var(--accent-d);
            margin-bottom:.5rem;
        }

        .form-title{
            font-family:var(--fd);
            font-size:2rem;
            color:var(--primary);
            line-height:1.15;
            margin-bottom:.4rem;
        }

        .form-sub{
            font-size:.8rem;
            color:#9ca3af;
            line-height:1.6;
            margin-bottom:1.75rem;
        }

        /* badges */
        .badges{display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.4rem}
        .badge{
            font-size:.65rem;font-weight:700;
            text-transform:uppercase;letter-spacing:.07em;
            padding:.28rem .65rem;border-radius:99px;
        }
        .badge-ok  {background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .badge-err {background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
        .badge-warn{background:#fffbeb;color:#92400e;border:1px solid #fde68a}
        .badge-gray{background:#f8fafc;color:#64748b;border:1px solid #e2e8f0}

        /* alerts */
        .alert{
            padding:.8rem 1rem;border-radius:10px;
            font-size:.8rem;line-height:1.6;
            margin-bottom:1rem;
        }
        .alert-error  {background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .alert-success{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
        .alert-warn   {background:#fffbeb;border:1px solid #fde68a;color:#78350f}
        .alert strong {display:block;font-size:.78rem;margin-bottom:.25rem}
        .hint-box{
            background:rgba(255,255,255,.7);
            border:1px solid rgba(251,191,36,.3);
            border-radius:7px;padding:.6rem .8rem;
            font-family:monospace;font-size:.76rem;
            margin-top:.5rem;line-height:1.7;
        }
        .hint-label{
            font-family:var(--f);font-size:.62rem;
            text-transform:uppercase;letter-spacing:.07em;
            font-weight:700;color:var(--accent-d);
            display:block;margin-bottom:.25rem;
        }

        /* inputs */
        .field{margin-bottom:1rem}
        .field label{
            display:block;font-size:.68rem;font-weight:700;
            text-transform:uppercase;letter-spacing:.08em;
            color:#9ca3af;margin-bottom:.35rem;
        }
        .field input{
            width:100%;
            padding:.7rem .95rem;
            border:1.5px solid #e5e7eb;
            border-radius:9px;
            font-family:var(--f);font-size:.88rem;
            color:var(--primary);background:#fafafa;
            outline:none;
            transition:border-color .15s,box-shadow .15s,background .15s;
        }
        .field input:focus{
            border-color:var(--accent);
            background:#fff;
            box-shadow:0 0 0 3px rgba(155,154,132,.13);
        }
        .field input::placeholder{color:#d1d5db}

        /* submit */
        .btn-submit{
            width:100%;margin-top:.5rem;
            padding:.8rem 1rem;
            border:none;border-radius:9px;
            background:var(--primary);color:#fff;
            font-family:var(--f);font-size:.88rem;
            font-weight:600;letter-spacing:.03em;
            cursor:pointer;
            display:flex;align-items:center;justify-content:center;gap:.5rem;
            transition:background .15s,transform .1s;
        }
        .btn-submit:hover{background:#2c2c2c}
        .btn-submit:active{transform:scale(.99)}

        .btn-accent-bar{
            height:3px;background:var(--accent);
            border-radius:0 0 9px 9px;margin-top:-3px;
        }

        .form-footer{
            margin-top:1.5rem;padding-top:1.25rem;
            border-top:1px solid #f1f1f1;
            display:flex;align-items:center;justify-content:space-between;
        }
        .form-footer p{font-size:.72rem;color:#c1c1c1}
        .form-footer a{font-size:.72rem;font-weight:600;color:var(--accent-d);text-decoration:none}
        .form-footer a:hover{color:var(--primary)}

        /* ── MOBILE ──────────────────────────────────────────── */
        @media(max-width:768px){
            .wrap{grid-template-columns:1fr}
            .left{display:none}
            .right{
                background:var(--primary);
                border-left:none;
                min-height:100vh;
            }
            .form-title{color:#fff}
            .form-sub{color:rgba(255,255,255,.4)}
            .field label{color:rgba(255,255,255,.4)}
            .field input{
                background:#1e1e1e;border-color:rgba(255,255,255,.1);color:#fff;
            }
            .field input:focus{background:#222}
            .field input::placeholder{color:#444}
            .btn-submit{background:var(--accent)}
            .btn-submit:hover{background:var(--accent-d)}
            .btn-accent-bar{display:none}
            .form-footer{border-top-color:rgba(255,255,255,.08)}
            .form-footer p{color:rgba(255,255,255,.25)}
            .form-footer a{color:var(--accent)}
            .badge-gray{background:#1e1e1e;color:rgba(255,255,255,.35);border-color:rgba(255,255,255,.08)}
            .alert-warn{background:rgba(120,53,15,.2);border-color:rgba(251,191,36,.25);color:#fde68a}
            .alert-error{background:rgba(153,27,27,.15);border-color:rgba(254,202,202,.2);color:#fca5a5}
            .alert-success{background:rgba(6,95,70,.15);border-color:rgba(167,243,208,.2);color:#6ee7b7}
        }
    </style>
</head>
<body>
@php
    $apiOk         = $apiLive ?? false;
    $apiConfigured = $apiConfigured ?? false;
@endphp

<div class="wrap">

    {{-- ── LEFT ───────────────────────────────────────────── --}}
    <div class="left">
        <div class="left-top">
            <a href="{{ route('frontend.anasayfa') }}" class="brand-logo">
                <div class="icon"><i class="fa-solid fa-stethoscope"></i></div>
                <span>Hekim<br>Yönetim Paneli</span>
            </a>

            <h1 class="hero-heading">
                Panelinize<br><em>hoş geldiniz</em>
            </h1>
            <p class="hero-sub">
                Randevularınızı, hasta dosyalarınızı ve web sitenizin tüm içeriğini tek ekrandan yönetin.
            </p>
        </div>

        <div class="left-bottom">
            <div class="stat">
                <h3>7/24</h3>
                <p>Online randevu</p>
            </div>
            <div class="stat">
                <h3>%100</h3>
                <p>Güvenli bağlantı</p>
            </div>
            <div class="stat">
                <h3>∞</h3>
                <p>Hasta kaydı</p>
            </div>
        </div>
    </div>

    {{-- ── RIGHT ──────────────────────────────────────────── --}}
    <div class="right">
        <div class="form-box">

            <p class="form-eyebrow">Yönetim Paneli</p>
            <h2 class="form-title">Giriş yapın</h2>
            <p class="form-sub">Hekim hesabınızla platform yönetimine erişin.</p>

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

            {{-- Form --}}
            <form method="POST" action="{{ route('panel.giris.post') }}">
                @csrf
                <div class="field">
                    <label for="e_posta">E-posta</label>
                    <input type="email" id="e_posta" name="e_posta"
                           value="{{ old('e_posta') }}" required
                           placeholder="hekim@ornek.com"
                           autocomplete="username">
                </div>
                <div class="field">
                    <label for="sifre">Şifre</label>
                    <input type="password" id="sifre" name="sifre"
                           required placeholder="••••••••"
                           autocomplete="current-password">
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    Giriş Yap
                </button>
                <div class="btn-accent-bar"></div>
            </form>

            <div class="form-footer">
                <p>API anahtarı yalnızca ana sunucuda üretilir.</p>
                <a href="{{ route('frontend.anasayfa') }}">
                    <i class="fa-solid fa-arrow-left" style="font-size:.65rem"></i> Siteye dön
                </a>
            </div>

        </div>
    </div>

</div>
</body>
</html>
