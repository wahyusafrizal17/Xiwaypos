<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --red:#E01010;--red-dark:#B50000;--red-light:#FFF0F0;--red-mid:#FDDADA;
  --black:#111111;--black2:#1A1A1A;
  --white:#ffffff;--off:#F8F8F6;--off2:#F2F1EE;
  --gray:#E4E2DD;--gray2:#C8C5BE;--gray3:#A09C95;
  --text:#1A1A1A;--text2:#4A4845;--text3:#8A8782;
  --font-sans:'Plus Jakarta Sans',ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
  --tracking-tight:-0.03em;
  --tracking-normal:-0.011em;
  --tracking-wide:0.06em;
}
html{scroll-behavior:smooth}
body.marketing-page{
  background:var(--white);color:var(--text);font-family:var(--font-sans);
  font-size:16px;line-height:1.65;letter-spacing:var(--tracking-normal);
  overflow-x:hidden;
  -webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;
  text-rendering:optimizeLegibility;
}
::-webkit-scrollbar{width:6px}::-webkit-scrollbar-track{background:var(--off)}::-webkit-scrollbar-thumb{background:var(--red);border-radius:3px}

.site-nav{position:fixed;top:0;left:0;right:0;z-index:100;display:flex;align-items:center;padding:18px 60px;padding-top:max(18px,env(safe-area-inset-top));background:rgba(255,255,255,0.96);backdrop-filter:blur(20px);border-bottom:1px solid var(--gray)}
.site-nav .nav-bar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-shrink:0}
.site-nav .nav-menu{display:flex;align-items:center;justify-content:space-between;flex:1;min-width:0;margin-left:40px}
.nav-logo{display:flex;align-items:center;gap:5px;text-decoration:none;min-width:0}
.nav-logo img{height:30px;width:auto;display:block;flex-shrink:0}
.nav-logo span{font-family:var(--font-sans);font-size:25px;font-weight:700;color:var(--text);letter-spacing:var(--tracking-tight);white-space:nowrap}
.nav-logo span em{color:var(--red);font-style:normal}
.nav-toggle{display:none;flex-direction:column;justify-content:center;align-items:center;gap:5px;width:44px;height:44px;padding:0;border-radius:10px;border:1px solid var(--gray);background:var(--white);cursor:pointer;flex-shrink:0;transition:border-color .2s,background .2s}
.nav-toggle span{display:block;width:18px;height:2px;background:var(--text);border-radius:2px;transition:transform .25s ease,opacity .2s ease}
.site-nav.nav-open .nav-toggle{border-color:var(--red);background:var(--red-light)}
.site-nav.nav-open .nav-toggle span:nth-child(1){transform:translateY(7px) rotate(45deg)}
.site-nav.nav-open .nav-toggle span:nth-child(2){opacity:0}
.site-nav.nav-open .nav-toggle span:nth-child(3){transform:translateY(-7px) rotate(-45deg)}
.nav-links{display:flex;gap:32px;list-style:none;align-items:center}
.nav-links a{color:var(--text3);text-decoration:none;font-size:14px;font-weight:500;letter-spacing:0;transition:color .2s}
.nav-links a:hover{color:var(--text)}
.nav-cta{display:flex;gap:10px;align-items:center;flex-shrink:0}
body.nav-menu-open{overflow:hidden}
.btn-ghost{padding:9px 20px;border-radius:8px;border:1px solid var(--gray);color:var(--text);background:transparent;font-family:var(--font-sans);font-size:14px;font-weight:500;letter-spacing:0;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-ghost:hover{border-color:var(--red);color:var(--red)}
.btn-red{padding:9px 20px;border-radius:8px;background:var(--red);border:none;color:#fff;font-family:var(--font-sans);font-size:14px;font-weight:600;letter-spacing:0;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-red:hover{background:var(--red-dark)}

.hero{min-height:100vh;position:relative;overflow:hidden;display:block}
.hero-full{background:#0a0a0a}
.hero-full .hero-carousel{position:relative;z-index:1;max-width:none;margin:0;height:100vh;min-height:640px}
.hero-full .hero-carousel-viewport{overflow:hidden;height:100%}
.hero-full .hero-carousel-track{display:flex;transition:transform .65s cubic-bezier(.4,0,.2,1);will-change:transform;height:100%}
.hero-full .hero-carousel-slide{flex:0 0 100%;min-width:100%;position:relative;height:100vh;min-height:640px}
.hero-slide-bg{position:absolute;inset:0;background-size:cover;background-position:center right;background-repeat:no-repeat}
.hero-slide-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(8,8,8,0.92) 0%,rgba(8,8,8,0.72) 42%,rgba(8,8,8,0.35) 68%,rgba(8,8,8,0.15) 100%)}
.hero-slide-inner{position:relative;z-index:2;width:100%;max-width:none;margin:0;padding:140px 60px 120px;height:100%;display:flex;align-items:center;justify-content:flex-start}
.hero-content{max-width:580px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border-radius:999px;border:1px solid rgba(224,16,16,0.45);background:rgba(224,16,16,0.12);font-size:12px;color:#ff8a8a;font-weight:600;margin-bottom:28px;letter-spacing:0}
.hero-badge-dot{width:6px;height:6px;border-radius:50%;background:var(--red);animation:pulse 1.5s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(1.4)}}
.hero h1{font-family:var(--font-sans);font-size:clamp(38px,4.2vw,58px);font-weight:700;line-height:1.15;letter-spacing:var(--tracking-tight);margin-bottom:20px;color:#fff}
.hero h1 span{color:#ff6b6b}
.hero-desc{font-size:16px;color:rgba(255,255,255,0.78);line-height:1.7;margin-bottom:36px;font-weight:400}
.hero-btns{display:flex;gap:12px;flex-wrap:wrap}
.btn-red-lg{padding:15px 32px;border-radius:10px;background:var(--red);border:none;color:#fff;font-family:var(--font-sans);font-size:15px;font-weight:600;letter-spacing:0;cursor:pointer;transition:all .25s;text-decoration:none;display:inline-block;box-shadow:0 4px 20px rgba(224,16,16,0.35)}
.btn-red-lg:hover{background:var(--red-dark);transform:translateY(-2px);box-shadow:0 8px 30px rgba(224,16,16,0.45)}
.btn-outline-lg{padding:15px 32px;border-radius:10px;border:1.5px solid var(--gray);background:transparent;color:var(--text);font-family:var(--font-sans);font-size:15px;font-weight:500;letter-spacing:0;cursor:pointer;transition:all .2s;text-decoration:none;display:inline-block}
.btn-outline-lg:hover{border-color:var(--text);background:var(--off)}
.btn-outline-light{border-color:rgba(255,255,255,0.45);color:#fff}
.btn-outline-light:hover{border-color:#fff;background:rgba(255,255,255,0.08);color:#fff}
.hero-stats{display:flex;gap:32px;margin-top:40px;flex-wrap:wrap}
.hero-stat-sep{width:1px;background:rgba(255,255,255,0.18);align-self:stretch;min-height:40px}
.hero-stat-num{font-family:var(--font-sans);font-size:24px;font-weight:700;letter-spacing:var(--tracking-tight);color:#fff}
.hero-stat-num span{color:#ff6b6b}
.hero-stat-label{font-size:13px;color:rgba(255,255,255,0.55);margin-top:4px;font-weight:500}
.hero-full .hero-carousel-nav{display:flex;align-items:center;justify-content:space-between;position:absolute;left:0;right:0;bottom:36px;z-index:5;padding:0 60px;pointer-events:none}
.hero-full .hero-carousel-nav>*{pointer-events:auto}
.hero-nav-arrow{width:48px;height:48px;border-radius:50%;border:none;background:var(--white);color:var(--text);font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;flex-shrink:0;box-shadow:0 4px 20px rgba(0,0,0,0.08);border:1px solid var(--gray)}
.hero-nav-arrow-light{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.25);color:#fff;backdrop-filter:blur(8px);box-shadow:none}
.hero-nav-arrow-light:hover{border-color:rgba(255,255,255,0.6);background:rgba(255,255,255,0.18);color:#fff;transform:scale(1.05)}
.hero-carousel-dots{display:flex;align-items:center;justify-content:center;gap:10px;position:absolute;left:50%;transform:translateX(-50%)}
.hero-carousel-dot{width:10px;height:10px;border-radius:50%;background:var(--gray2);border:none;padding:0;cursor:pointer;transition:all .25s ease;opacity:.55}
.hero-carousel-dot-light{background:rgba(255,255,255,0.35);opacity:1}
.hero-carousel-dot.active{background:var(--red);opacity:1;width:28px;border-radius:5px}
.hero-carousel-dot-light.active{background:var(--red)}
.hero-carousel-dot:hover{opacity:.85;background:var(--red)}
.hero-carousel-dot-light:hover{background:rgba(255,255,255,0.65)}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}

.logo-strip{background:var(--off);border-top:1px solid var(--gray);border-bottom:1px solid var(--gray);padding:26px 60px;display:flex;align-items:center;gap:20px;overflow:hidden}
.logo-strip-label{font-size:13px;color:var(--text3);white-space:nowrap;flex-shrink:0}
.logo-strip-track{display:flex;gap:48px;animation:marquee 22s linear infinite;align-items:center}
@keyframes marquee{0%{transform:translateX(0)}100%{transform:translateX(-50%)}}
.logo-brand{font-family:var(--font-sans);font-size:14px;font-weight:600;color:var(--gray2);white-space:nowrap;transition:color .2s;letter-spacing:0}

.section-label{display:inline-block;font-size:11px;font-weight:600;letter-spacing:var(--tracking-wide);text-transform:uppercase;color:var(--red);margin-bottom:12px}
#fitur,#cara-kerja,#harga,#faq,#hubungi-kami{scroll-margin-top:96px}
.section-title{font-family:var(--font-sans);font-size:clamp(26px,3.2vw,40px);font-weight:700;letter-spacing:var(--tracking-tight);line-height:1.2;color:var(--text)}
.slider-btn{width:44px;height:44px;border-radius:50%;border:1.5px solid var(--gray);background:transparent;color:var(--text);font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s}
.slider-btn:hover{border-color:var(--red);background:var(--red-light);color:var(--red)}

.how-section{padding:100px 60px;background:var(--off);position:relative;overflow:hidden}
.how-section::after{content:'';position:absolute;right:-60px;top:50%;transform:translateY(-50%);width:400px;height:400px;background:radial-gradient(circle,rgba(224,16,16,0.05) 0%,transparent 70%)}
.how-inner{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;max-width:1100px;margin:0 auto;position:relative;z-index:1}
.how-img-wrap{position:relative;border-radius:20px;overflow:hidden;box-shadow:0 20px 70px rgba(0,0,0,0.12)}
.how-img-wrap img{width:100%;height:480px;object-fit:cover;display:block}
.how-img-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(17,17,17,0.5) 0%,transparent 50%)}
.how-step-badge{position:absolute;top:24px;left:24px;background:var(--red);color:#fff;font-family:var(--font-sans);font-size:11px;font-weight:600;letter-spacing:var(--tracking-wide);padding:6px 14px;border-radius:6px;text-transform:uppercase}
.how-content .section-label{margin-bottom:14px}
.how-content h2{margin-bottom:24px}
.how-demo-desc{font-size:16px;color:var(--text2);line-height:1.75;margin-bottom:32px;font-weight:400;max-width:480px}

.features{background:var(--off);padding:100px 60px;position:relative;overflow:hidden}
.features::before{content:'';position:absolute;top:-120px;left:50%;transform:translateX(-50%);width:720px;height:360px;background:radial-gradient(ellipse,rgba(224,16,16,0.06) 0%,transparent 72%);pointer-events:none}
.features-head{text-align:center;margin-bottom:64px;position:relative;z-index:1}
.features-head .section-label{margin-bottom:14px}
.features-head .section-title{max-width:640px;margin:0 auto 16px}
.section-sub{font-size:16px;color:var(--text2);line-height:1.7;max-width:480px;font-weight:400}
.features-head .section-sub{margin:0 auto}
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:1100px;margin:0 auto;position:relative;z-index:1}
.feat-card{background:var(--white);border:1px solid rgba(0,0,0,0.06);border-radius:20px;padding:28px 28px 32px;transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease;position:relative;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.04)}
.feat-card::after{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--red),#ff6b6b);opacity:0;transition:opacity .25s ease}
.feat-card:hover{transform:translateY(-4px);border-color:rgba(224,16,16,0.18);box-shadow:0 18px 44px rgba(224,16,16,0.1)}
.feat-card:hover::after{opacity:1}
.feat-index{position:absolute;top:22px;right:24px;font-family:var(--font-sans);font-size:12px;font-weight:700;color:var(--gray2);letter-spacing:0.08em}
.feat-icon{width:50px;height:50px;border-radius:14px;background:linear-gradient(145deg,#fff5f5 0%,#fff 100%);border:1px solid rgba(224,16,16,0.12);display:flex;align-items:center;justify-content:center;margin-bottom:20px;transition:background .25s ease,border-color .25s ease}
.feat-card:hover .feat-icon{background:linear-gradient(145deg,var(--red-light) 0%,#fff 100%);border-color:rgba(224,16,16,0.22)}
.feat-icon svg{width:22px;height:22px;stroke:var(--red);fill:none;stroke-width:1.8}
.feat-card h3{font-family:var(--font-sans);font-size:17px;font-weight:700;margin-bottom:10px;color:var(--text);letter-spacing:var(--tracking-tight)}
.feat-card p{font-size:14px;color:var(--text2);line-height:1.75;font-weight:400}

.testi-section{padding:100px 60px;background:var(--off);overflow:hidden}
.testi-head{text-align:center;margin-bottom:52px}
.testi-track-wrap{overflow:hidden}
.testi-track{display:flex;gap:20px;transition:transform .5s cubic-bezier(.4,0,.2,1)}
.testi-card{flex:0 0 calc(33.33% - 14px);background:var(--white);border:1px solid var(--gray);border-radius:16px;padding:30px;transition:all .3s}
.testi-card:hover{border-color:rgba(224,16,16,0.3);box-shadow:0 8px 28px rgba(224,16,16,0.08)}
.testi-stars{display:flex;gap:3px;margin-bottom:14px;color:var(--red);font-size:14px}
.testi-text{font-size:15px;line-height:1.75;color:var(--text2);margin-bottom:22px;font-weight:400;font-style:normal}
.testi-author{display:flex;align-items:center;gap:12px}
.testi-avatar{width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid var(--gray)}
.testi-name{font-size:14px;font-weight:600;color:var(--text);letter-spacing:var(--tracking-normal)}
.testi-role{font-size:13px;color:var(--text3);font-weight:400}
.testi-nav{display:flex;gap:10px;justify-content:center;margin-top:32px}

.pricing{background:var(--white);padding:100px 60px}
.pricing-head{text-align:center;margin-bottom:60px}
.pricing-head .section-sub{margin:0 auto}
.pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:960px;margin:0 auto}
.price-card{background:var(--off);border:1.5px solid var(--gray);border-radius:16px;padding:36px;position:relative;transition:transform .25s,box-shadow .25s;display:flex;flex-direction:column}
.price-card:hover{transform:translateY(-5px);box-shadow:0 16px 48px rgba(0,0,0,0.08)}
.price-card.popular{border-color:var(--red);background:var(--white);box-shadow:0 8px 40px rgba(224,16,16,0.12)}
.price-card.popular:hover{box-shadow:0 20px 56px rgba(224,16,16,0.18)}
.popular-badge{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--red);color:#fff;font-size:11px;font-weight:600;letter-spacing:var(--tracking-wide);text-transform:uppercase;padding:5px 16px;border-radius:999px;white-space:nowrap}
.price-tier{font-size:13px;color:var(--text3);font-weight:500;margin-bottom:10px;letter-spacing:0}
.price-amount{font-family:var(--font-sans);font-size:36px;font-weight:700;letter-spacing:var(--tracking-tight);line-height:1.1;margin-bottom:4px;color:var(--text)}
.price-amount .price-currency{font-size:16px;font-weight:500;color:var(--text3)}
.price-period{font-size:13px;color:var(--text3);margin-bottom:8px;font-weight:400}
.price-yearly-save{font-size:12px;font-weight:700;color:var(--red);margin:-12px 0 8px;line-height:1.4}
.price-yearly{font-size:13px;color:var(--text2);margin-bottom:24px;line-height:1.45}
.price-yearly span{color:var(--text3);font-weight:400}
.price-divider{height:1px;background:var(--gray);margin-bottom:22px}
.price-features{list-style:none;display:flex;flex-direction:column;gap:11px;margin-bottom:30px;flex:1}
.price-features li{display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text2);font-weight:400;line-height:1.5}
.price-check{width:18px;height:18px;border-radius:50%;background:var(--red-mid);border:1px solid rgba(224,16,16,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.price-check svg{width:10px;height:10px;stroke:var(--red);fill:none;stroke-width:2.5}
.btn-red-full{display:block;width:100%;padding:13px;border-radius:10px;background:var(--red);border:none;color:#fff;font-family:var(--font-sans);font-size:15px;font-weight:600;letter-spacing:0;cursor:pointer;transition:all .2s;text-align:center;text-decoration:none;box-shadow:0 4px 16px rgba(224,16,16,0.22)}
.btn-red-full:hover{background:var(--red-dark);transform:translateY(-1px)}
.btn-outline-full{display:block;width:100%;padding:13px;border-radius:10px;border:1.5px solid var(--gray);background:transparent;color:var(--text);font-family:var(--font-sans);font-size:15px;font-weight:500;letter-spacing:0;cursor:pointer;transition:all .2s;text-align:center;text-decoration:none}
.btn-outline-full:hover{border-color:var(--red);color:var(--red)}

.support-section{background:var(--off);padding:100px 60px}
.support-card{display:grid;grid-template-columns:minmax(0,0.95fr) minmax(0,1.05fr);gap:0;max-width:1080px;margin:0 auto;background:var(--white);border:1px solid var(--gray);border-radius:24px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,0.06)}
.support-visual{position:relative;display:flex;align-items:flex-end;justify-content:center;min-height:380px;background:linear-gradient(160deg,#fff5f5 0%,#fff 55%,#fafafa 100%);padding:24px 24px 0}
.support-visual-glow{position:absolute;left:50%;bottom:0;transform:translateX(-50%);width:280px;height:280px;background:radial-gradient(circle,rgba(224,16,16,0.12) 0%,transparent 70%);pointer-events:none}
.support-visual img{position:relative;z-index:1;max-height:400px;width:auto;max-width:100%;object-fit:contain;display:block;filter:drop-shadow(0 18px 28px rgba(0,0,0,0.12))}
.support-content{display:flex;flex-direction:column;justify-content:center;padding:56px 56px 56px 40px}
.support-content .section-label{margin-bottom:14px}
.support-content h2{font-family:var(--font-sans);font-size:clamp(30px,3.4vw,42px);font-weight:700;letter-spacing:var(--tracking-tight);line-height:1.15;margin-bottom:18px;color:var(--text)}
.support-content h2 span{color:var(--red)}
.support-content p{font-size:16px;color:var(--text2);line-height:1.75;margin-bottom:32px;max-width:420px;font-weight:400}
.support-actions{display:flex;flex-direction:column;align-items:flex-start;gap:14px}
.support-wa-btn{display:inline-flex;align-items:center;gap:10px;padding:14px 24px;border-radius:12px;background:#25D366;color:#fff;font-family:var(--font-sans);font-size:15px;font-weight:600;text-decoration:none;transition:transform .2s,box-shadow .2s,background .2s;box-shadow:0 8px 24px rgba(37,211,102,0.28)}
.support-wa-btn svg{width:20px;height:20px;fill:currentColor;flex-shrink:0}
.support-wa-btn:hover{background:#1ebe57;transform:translateY(-2px);box-shadow:0 12px 28px rgba(37,211,102,0.34)}
.support-note{font-size:13px;color:var(--text3);font-weight:500}

.faq{background:var(--white);padding:100px 60px}
.faq-inner{max-width:700px;margin:0 auto}
.faq-head{text-align:center;margin-bottom:52px}
.faq-item{border-bottom:1px solid var(--gray);padding:22px 0;cursor:pointer}
.faq-question{display:flex;justify-content:space-between;align-items:center;font-size:15px;font-weight:600;color:var(--text);gap:16px;letter-spacing:var(--tracking-normal)}
.faq-icon{width:28px;height:28px;flex-shrink:0;border:1.5px solid var(--gray);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--text3);font-size:16px;font-weight:400;transition:all .3s;line-height:1}
.faq-item.open .faq-icon{background:var(--red);border-color:var(--red);color:#fff;transform:rotate(45deg)}
.faq-answer{max-height:0;overflow:hidden;transition:max-height .4s ease,padding .3s;font-size:14px;color:var(--text2);line-height:1.75;font-weight:400}
.faq-item.open .faq-answer{max-height:240px;padding-top:14px}

.cta-section{background:#0a0a0a;padding:100px 60px;text-align:center;position:relative;overflow:hidden}
.cta-bg-img{position:absolute;inset:0;z-index:0}
.cta-bg-img img{width:100%;height:100%;object-fit:cover;display:block;filter:brightness(.35) saturate(.6)}
.cta-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(8,8,8,0.55) 0%,rgba(8,8,8,0.82) 100%)}
.cta-content{position:relative;z-index:1;max-width:640px;margin:0 auto}
.cta-section h2{font-family:var(--font-sans);font-size:clamp(32px,4.5vw,52px);font-weight:700;letter-spacing:var(--tracking-tight);line-height:1.2;margin-bottom:18px;color:#fff}
.cta-section h2 span{color:#ff6b6b}
.cta-section p{font-size:16px;color:rgba(255,255,255,0.78);margin-bottom:36px;font-weight:400;line-height:1.7}

footer.marketing-footer{background:#161616;color:rgba(255,255,255,0.82);padding:64px 60px 32px;position:relative}
.footer-main{max-width:1180px;margin:0 auto}
.footer-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr 1fr;gap:48px}
.footer-logo{font-family:var(--font-sans);font-size:28px;font-weight:700;color:#fff;text-decoration:none;letter-spacing:var(--tracking-tight);display:inline-block;margin-bottom:14px}
.footer-logo em{color:var(--red);font-style:normal}
.footer-tagline{font-size:14px;line-height:1.7;color:rgba(255,255,255,0.55);max-width:240px;font-weight:400}
.footer-heading{font-family:var(--font-sans);font-size:13px;font-weight:600;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:18px}
.footer-menu{list-style:none;display:flex;flex-direction:column;gap:12px}
.footer-menu-spaced{margin-top:28px}
.footer-menu a{font-size:14px;color:rgba(255,255,255,0.78);text-decoration:none;font-weight:500;transition:color .2s}
.footer-menu a:hover{color:#fff}
.footer-divider{max-width:1180px;margin:40px auto 28px;height:1px;background:rgba(255,255,255,0.1)}
.footer-bottom{max-width:1180px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:24px;flex-wrap:wrap}
.footer-bottom-left{display:flex;align-items:center;gap:28px;flex-wrap:wrap}
.footer-lang{font-size:14px;color:rgba(255,255,255,0.72);font-weight:500}
.footer-legal{display:flex;align-items:center;gap:24px;list-style:none;flex-wrap:wrap}
.footer-legal a{font-size:14px;color:rgba(255,255,255,0.55);text-decoration:none;font-weight:500;transition:color .2s}
.footer-legal a:hover{color:#fff}
.footer-social{display:flex;align-items:center;gap:12px}
.footer-social-link{width:36px;height:36px;border-radius:50%;border:1px solid rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;transition:background .2s,border-color .2s,transform .2s}
.footer-social-link svg{width:16px;height:16px;fill:currentColor;stroke:currentColor;stroke-width:0}
.footer-social-link svg rect,.footer-social-link svg circle{fill:none;stroke:currentColor;stroke-width:1.6}
.footer-social-link svg circle:last-child{fill:currentColor;stroke:none}
.footer-social-link:hover{background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.35);transform:translateY(-1px)}
.footer-copy{max-width:1180px;margin:24px auto 0;font-size:13px;color:rgba(255,255,255,0.38);font-weight:400}
.footer-fab{position:fixed;right:28px;bottom:28px;z-index:90;display:inline-flex;align-items:center;gap:10px;padding:14px 20px;border-radius:12px;background:#111;border:1px solid rgba(255,255,255,0.12);color:#fff;font-family:var(--font-sans);font-size:14px;font-weight:600;text-decoration:none;box-shadow:0 12px 32px rgba(0,0,0,0.28);transition:transform .2s,box-shadow .2s,background .2s}
.footer-fab svg{width:18px;height:18px;fill:currentColor;flex-shrink:0}
.footer-fab:hover{background:#1f1f1f;transform:translateY(-2px);box-shadow:0 16px 36px rgba(0,0,0,0.34)}

.marketing-reveal{opacity:0;transform:translateY(20px);transition:opacity .5s ease,transform .5s ease}
.marketing-reveal.is-visible{opacity:1;transform:translateY(0)}

@media(max-width:900px){
  #fitur,#cara-kerja,#harga,#faq,#hubungi-kami{scroll-margin-top:80px}
  .site-nav{
    flex-direction:column;
    flex-wrap:nowrap;
    align-items:stretch;
    padding:12px 16px 0;
    padding-top:max(12px,env(safe-area-inset-top));
  }
  .site-nav .nav-bar{
    width:100%;
    flex:0 0 auto;
    margin:0;
  }
  .site-nav .nav-menu{
    display:none;
    flex:none;
    flex-basis:auto;
    flex-grow:0;
    flex-shrink:0;
    flex-direction:column;
    align-items:stretch;
    justify-content:flex-start;
    width:100%;
    max-width:100%;
    min-width:0;
    margin:0;
    padding:8px 0 16px;
    border-top:1px solid var(--gray);
    gap:0;
    overflow:visible;
  }
  .site-nav .nav-menu.is-open{display:flex}
  .nav-toggle{display:flex}
  .nav-links{
    flex-direction:column;
    align-items:stretch;
    gap:0;
    width:100%;
    max-width:100%;
  }
  .nav-links li{width:100%;list-style:none}
  .nav-links a{
    display:block;
    width:100%;
    padding:14px 4px;
    font-size:15px;
    color:var(--text2);
    border-bottom:1px solid var(--off2);
    white-space:normal;
  }
  .nav-links li:last-child a{border-bottom:none}
  .nav-cta{
    flex:none;
    flex-direction:column;
    align-items:stretch;
    width:100%;
    max-width:100%;
    gap:10px;
    margin-top:16px;
  }
  .nav-cta .btn-ghost,.nav-cta .btn-red{
    width:100%;
    max-width:100%;
    text-align:center;
    padding:12px 18px;
    font-size:15px;
    box-sizing:border-box;
  }
  .hero-full .hero-carousel,.hero-full .hero-carousel-slide{min-height:100svh;min-height:100dvh}
  .hero-slide-bg{background-position:center center}
  .hero-slide-overlay{background:linear-gradient(180deg,rgba(8,8,8,0.35) 0%,rgba(8,8,8,0.88) 55%,rgba(8,8,8,0.95) 100%)}
  .hero-full .hero-slide-inner{padding:96px 20px 128px;align-items:flex-end}
  .hero-content{max-width:none;width:100%}
  .hero h1{font-size:clamp(28px,8vw,40px);margin-bottom:14px}
  .hero-desc{font-size:15px;margin-bottom:24px;line-height:1.65}
  .hero-badge{font-size:11px;margin-bottom:18px;padding:5px 12px}
  .hero-btns{flex-direction:column;width:100%;gap:10px}
  .hero-btns .btn-red-lg,.hero-btns .btn-outline-lg{width:100%;text-align:center;padding:14px 20px;font-size:15px}
  .hero-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px 8px;margin-top:28px;width:100%}
  .hero-stat-sep{display:none}
  .hero-stat-num{font-size:20px}
  .hero-stat-label{font-size:11px}
  .hero-full .hero-carousel-nav{padding:0 16px;bottom:max(20px,env(safe-area-inset-bottom));align-items:flex-end}
  .hero-nav-arrow{width:40px;height:40px;font-size:15px}
  .hero-carousel-dots{position:static;transform:none;margin-bottom:8px}
  .how-section,.features,.testi-section,.pricing,.support-section,.faq,.cta-section{padding:64px 20px}
  .how-inner{grid-template-columns:1fr;gap:32px}
  .how-img-wrap{display:none}
  .how-content h2{font-size:clamp(24px,6vw,32px)}
  .how-demo-desc{font-size:15px}
  .how-content .btn-red-lg{width:100%;text-align:center}
  .features-head{margin-bottom:40px}
  .features-grid{grid-template-columns:1fr;gap:16px}
  .feat-card{padding:24px}
  .pricing-grid{grid-template-columns:1fr}
  .price-card{padding:28px 24px}
  .testi-card{flex:0 0 calc(88% - 8px)}
  .testi-head{margin-bottom:36px}
  .logo-strip{flex-direction:column;align-items:flex-start;gap:12px;padding:18px 20px}
  .support-card{grid-template-columns:1fr}
  .support-visual{min-height:240px;padding-top:12px}
  .support-visual img{max-height:260px;margin:0 auto}
  .support-content{padding:28px 24px 32px}
  .support-content h2{font-size:clamp(26px,6vw,34px)}
  .support-content p{max-width:none;font-size:15px}
  .support-wa-btn{width:100%;justify-content:center}
  .faq-question{font-size:14px;gap:12px}
  .cta-section{padding:72px 20px}
  .cta-section .btn-red-lg{width:100%;text-align:center}
  footer.marketing-footer{padding:48px 20px 28px;padding-bottom:max(28px,env(safe-area-inset-bottom))}
  .footer-grid{grid-template-columns:1fr;gap:28px}
  .footer-col-brand{grid-column:auto}
  .footer-bottom{flex-direction:column;align-items:flex-start}
  .footer-fab{right:16px;bottom:max(16px,env(safe-area-inset-bottom));width:52px;height:52px;padding:0;border-radius:999px;justify-content:center;font-size:0;gap:0}
  .footer-fab svg{width:22px;height:22px}
}
@media(max-width:480px){
  .nav-logo span{font-size:22px}
  .nav-logo img{height:26px}
  .hero-stats{grid-template-columns:1fr 1fr;gap:16px 12px}
  .hero-stats>div:last-child{grid-column:1/-1}
  .hero-full .hero-slide-inner{padding-bottom:140px}
  .footer-legal{flex-direction:column;align-items:flex-start;gap:10px}
}
</style>
