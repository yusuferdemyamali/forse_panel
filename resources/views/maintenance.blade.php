<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakım Modu</title>
    <style>
        :root{--accent: rgb(34,193,195);--page-bg:#ffffff;--glass:rgba(2,6,23,0.04);--muted:#54606b;--text:#071226}
        html,body{height:100%;margin:0}
        body{
            font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, Arial;
            background: var(--page-bg);
            color:var(--text);
            display:flex;align-items:center;justify-content:center;padding:28px;
            overflow:hidden;
        }

        /* Animated, subtle blobs suitable for light background */
        .bg-blob{position:fixed;filter:blur(64px);opacity:0.22;mix-blend-mode:screen;pointer-events:none}
        .blob-a{width:520px;height:520px;background:radial-gradient(circle at 30% 30%, rgba(34,193,195,0.18), transparent 30%), radial-gradient(circle at 70% 70%, rgba(72,60,255,0.06), transparent 30%);left:-8%;top:-12%;animation:floatA 12s ease-in-out infinite}
        .blob-b{width:420px;height:420px;background:radial-gradient(circle at 10% 30%, rgba(255,100,150,0.02), transparent 30%), radial-gradient(circle at 80% 80%, rgba(34,193,195,0.12), transparent 30%);right:-6%;bottom:-20%;animation:floatB 10s ease-in-out infinite}

        @keyframes floatA{0%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(18px) rotate(6deg)}100%{transform:translateY(0) rotate(0deg)}}
        @keyframes floatB{0%{transform:translateY(0) rotate(0deg)}50%{transform:translateY(-14px) rotate(-6deg)}100%{transform:translateY(0) rotate(0deg)}}

        .container{position:relative;z-index:2;display:flex;align-items:center;gap:28px;max-width:1000px;width:100%;}
        .card{
            flex:1;display:flex;gap:28px;align-items:center;padding:32px;border-radius:18px;
            background: #ffffff;border:1px solid rgba(12,20,30,0.06);box-shadow:0 10px 30px rgba(16,24,40,0.06);
        }

        .left{flex:0 0 200px;text-align:center}
        .logo-wrap{position:relative;display:inline-block}
        .logo{width:140px;height:auto;border-radius:12px;padding:8px;background:linear-gradient(180deg,rgba(255,255,255,0.04),rgba(255,255,255,0.02));}
        .logo-ring{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:192px;height:192px;border-radius:50%;pointer-events:none}
        .ring-anim{stroke:rgba(34,193,195,0.18);stroke-width:6;fill:none;stroke-dasharray:480;stroke-dashoffset:480;animation:dash 3s linear infinite}
        @keyframes dash{0%{stroke-dashoffset:480}50%{stroke-dashoffset:120}100%{stroke-dashoffset:480}}
    .logo-pulse{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:220px;height:220px;border-radius:50%;background:radial-gradient(circle, rgba(34,193,195,0.12), transparent 40%);filter:blur(18px);opacity:0.9;animation:pulse 2.6s ease-in-out infinite}
        @keyframes pulse{0%{transform:translate(-50%,-50%) scale(.96);opacity:.9}50%{transform:translate(-50%,-50%) scale(1.04);opacity:1}100%{transform:translate(-50%,-50%) scale(.96);opacity:.9}}

        .right{flex:1}
    h1{margin:0;font-size:28px;color:var(--accent);letter-spacing:-0.02em}
    p.lead{margin:8px 0 14px 0;color:var(--text);font-size:17px}
    p.muted{margin:0;color:var(--muted);font-size:14px}

        /* Animated loader */
    .loader{display:inline-flex;gap:8px;margin-top:18px}
    .dot{width:12px;height:12px;background:var(--accent);border-radius:50%;animation:dot 1s infinite ease-in-out;box-shadow:0 6px 20px rgba(34,193,195,0.16)}
        .dot:nth-child(2){animation-delay:0.12s}
        .dot:nth-child(3){animation-delay:0.24s}
        @keyframes dot{0%{transform:translateY(0);opacity:.6}50%{transform:translateY(-10px);opacity:1}100%{transform:translateY(0);opacity:.6}}

        .actions{margin-top:22px;display:flex;gap:12px;flex-wrap:wrap;align-items:center}
    .btn{background:transparent;border:1px solid rgba(12,20,30,0.06);padding:10px 14px;border-radius:10px;color:var(--text);text-decoration:none;font-weight:600}
    .btn-primary{background:linear-gradient(90deg,var(--accent),#22c1c3);color:#061018;border:none;box-shadow:0 8px 24px rgba(34,193,195,0.12)}

        @media (max-width:820px){.container{flex-direction:column}.card{flex-direction:column;align-items:center}}
    </style>
    <!-- Modern, animated maintenance page. Logo loaded via Laravel asset() -->
</head>
<body>
    <!-- decorative blobs -->
    <div class="bg-blob blob-a" aria-hidden="true"></div>
    <div class="bg-blob blob-b" aria-hidden="true"></div>

    <div class="container" role="main" aria-labelledby="maintenance-title">
        <div class="card" aria-live="polite">
            <div class="left">
                <div class="logo-wrap" aria-hidden="true">
                    <div class="logo-pulse" aria-hidden="true"></div>
                    <img src="{{ asset('images/forse_logo.png') }}" alt="Forse" class="logo" loading="eager">
                    <svg class="logo-ring" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle class="ring-anim" cx="100" cy="100" r="76"></circle>
                    </svg>
                </div>
            </div>

            <div class="right">
                <h1 id="maintenance-title">Site kısa süreliğine bakımda</h1>
                <p class="lead">Planlı bakım çalışması yapıyoruz. Hizmetlerimizi daha iyi ve daha güvenli hale getirmek için çalışıyoruz — yakında geri döneceğiz.</p>
                <p class="muted">Bu süre içerisinde bazı özellikler sınırlı olabilir. Destek gerekli ise bize ulaşın.</p>

                <div class="loader" aria-hidden="false" role="status" aria-label="Yükleniyor">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>

                <div class="actions">
                    <a class="btn btn-primary" href="/">Anasayfaya Dön</a>
                    <a class="btn" href="mailto:destek@forse.com">İletişime Geç</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Basit başarı/fallback: eğer logo bulunamazsa, yerine kısa başlık göster
        document.addEventListener('DOMContentLoaded', ()=>{
            const img = document.querySelector('.logo');
            if(img){
                img.addEventListener('error', ()=>{
                    img.style.display='none';
                    const left = document.querySelector('.left');
                    const h = document.createElement('div');
                    h.style.padding='18px';
                    h.style.borderRadius='12px';
                    h.style.background='linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01))';
                    h.innerHTML = '<strong style="color:var(--accent);font-size:18px">Forse</strong>';
                    left.appendChild(h);
                });
            }
        });
    </script>
</body>
</html>