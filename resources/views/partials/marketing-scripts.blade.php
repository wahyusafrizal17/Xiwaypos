<script>
(function initMobileNav() {
    const siteNav = document.getElementById('siteNav');
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (! siteNav || ! navToggle || ! navMenu) {
        return;
    }

    function setNavOpen(open) {
        navMenu.classList.toggle('is-open', open);
        siteNav.classList.toggle('nav-open', open);
        document.body.classList.toggle('nav-menu-open', open);
        navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        navToggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    }

    navToggle.addEventListener('click', () => {
        setNavOpen(! navMenu.classList.contains('is-open'));
    });

    navMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => setNavOpen(false));
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            setNavOpen(false);
        }
    });
})();

document.querySelectorAll('.faq-item').forEach(item => {
    item.querySelector('.faq-question').addEventListener('click', () => {
        const open = item.classList.contains('open');
        document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
        if (! open) {
            item.classList.add('open');
        }
    });
});

function initSlider(trackId, prevId, nextId, dotsId) {
    const track = document.getElementById(trackId);
    if (! track) {
        return;
    }

    const cards = track.children;
    const total = cards.length;
    let cur = 0;
    const dotsEl = dotsId ? document.getElementById(dotsId) : null;
    const gap = trackId === 'testiTrack' ? 20 : 24;

    function visibleCount() {
        return window.innerWidth < 900 ? 1 : 3;
    }

    function maxIndex() {
        return Math.max(0, total - visibleCount());
    }

    function cardWidth() {
        const w = track.parentElement.offsetWidth;
        const count = visibleCount();
        return (w - (count - 1) * gap) / count;
    }

    function update() {
        if (cur > maxIndex()) {
            cur = maxIndex();
        }
        const cardW = cardWidth();
        Array.from(cards).forEach(c => { c.style.flex = `0 0 ${cardW}px`; });
        track.style.transform = `translateX(-${cur * (cardW + gap)}px)`;
        if (dotsEl) {
            dotsEl.querySelectorAll('.slider-dot').forEach((d, i) => d.classList.toggle('active', i === cur));
        }
    }

    if (dotsEl && dotsEl.childElementCount === 0) {
        for (let i = 0; i < Math.max(1, total - 2); i++) {
            const d = document.createElement('button');
            d.type = 'button';
            d.className = 'slider-dot' + (i === 0 ? ' active' : '');
            d.setAttribute('aria-label', `Slide ${i + 1}`);
            d.addEventListener('click', () => { cur = i; update(); });
            dotsEl.appendChild(d);
        }
    }

    document.getElementById(prevId)?.addEventListener('click', () => {
        if (cur > 0) { cur--; update(); }
    });

    document.getElementById(nextId)?.addEventListener('click', () => {
        if (cur < maxIndex()) { cur++; update(); }
    });

    window.addEventListener('resize', update);
    update();
}

initSlider('testiTrack', 'testiPrev', 'testiNext', null);

function initHeroSlider() {
    const slider = document.getElementById('heroSlider');
    const track = document.getElementById('heroCarouselTrack');
    if (! slider || ! track) {
        return;
    }

    const slides = track.children;
    const dots = Array.from(slider.querySelectorAll('.hero-carousel-dot'));
    const total = slides.length;
    let cur = 0;
    let timer = null;

    function goTo(index) {
        cur = (index + total) % total;
        track.style.transform = `translateX(-${cur * 100}%)`;
        dots.forEach((dot, i) => dot.classList.toggle('active', i === cur));
    }

    function next() { goTo(cur + 1); }
    function prev() { goTo(cur - 1); }

    function startAutoplay() {
        stopAutoplay();
        timer = window.setInterval(next, 6000);
    }

    function stopAutoplay() {
        if (timer !== null) {
            window.clearInterval(timer);
            timer = null;
        }
    }

    document.getElementById('heroNext')?.addEventListener('click', () => {
        next();
        startAutoplay();
    });

    document.getElementById('heroPrev')?.addEventListener('click', () => {
        prev();
        startAutoplay();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            goTo(Number(dot.dataset.heroDot));
            startAutoplay();
        });
    });

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    window.addEventListener('resize', () => goTo(cur));

    goTo(0);
    startAutoplay();
}

initHeroSlider();

const revealObserver = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('is-visible');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.marketing-reveal').forEach(el => revealObserver.observe(el));

(function initDemoModal() {
    const modal = document.getElementById('demoModal');
    const playBtn = document.getElementById('demoPlayBtn');
    if (! modal) {
        return;
    }

    function openModal() {
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
    }

    playBtn?.addEventListener('click', openModal);
    modal.querySelectorAll('[data-demo-close]').forEach(el => {
        el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && ! modal.hidden) {
            closeModal();
        }
    });
})();

(function initStickyCta() {
    const sticky = document.getElementById('stickyCta');
    const hero = document.getElementById('heroSection');
    if (! sticky || ! hero) {
        return;
    }

    const observer = new IntersectionObserver(entries => {
        const pastHero = ! entries[0]?.isIntersecting;
        sticky.classList.toggle('is-visible', pastHero);
        sticky.setAttribute('aria-hidden', pastHero ? 'false' : 'true');
        document.body.classList.toggle('has-sticky-cta', pastHero);
    }, { threshold: 0, rootMargin: '-80px 0px 0px 0px' });

    observer.observe(hero);
})();
</script>
