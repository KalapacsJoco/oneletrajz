import '../css/app.css';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import { initBackground } from './background';

gsap.registerPlugin(ScrollTrigger);
window.gsap = gsap;

const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Gépelő szerepváltó (Alpine komponens)
document.addEventListener('alpine:init', () => {
    window.Alpine.data('typer', (words = []) => ({
        text: '',
        init() {
            if (!words.length) return;
            if (reduced) { this.text = words[0]; return; }
            let w = 0, c = 0, del = false;
            const tick = () => {
                const word = words[w];
                c += del ? -1 : 1;
                this.text = word.slice(0, c);
                let wait = del ? 35 : 80;
                if (!del && c === word.length) { del = true; wait = 1600; }
                else if (del && c === 0) { del = false; w = (w + 1) % words.length; wait = 350; }
                setTimeout(tick, wait);
            };
            tick();
        },
    }));
});

// Első betöltéskor a fotó „elrontott” osztályokkal indul, majd egyenként lekerülnek róla
document.addEventListener('alpine:init', () => {
    window.Alpine.data('photoIntro', () => ({
        typed: [],
        current: '',
        saved: false,
        visible: false,
        init() {
            const figure = this.$refs.figure;
            figure.style.visibility = '';
            const sameOriginReferrer = document.referrer.startsWith(location.origin);
            let seen = sameOriginReferrer;
            try {
                seen = seen || !!sessionStorage.getItem('photoIntroSeen') || window.name === 'photoIntroSeen';
                sessionStorage.setItem('photoIntroSeen', '1');
            } catch (e) {}
            if (seen || reduced) { window.name = 'photoIntroSeen'; return; }
            window.name = 'photoIntroSeen';

            // A kép osztályok nélkül indul (nagy, formátlan), a végleges osztályokat a „szerkesztő” gépeli be
            const finalClasses = figure.className.split(/\s+/).filter(Boolean);
            figure.className = '';
            this.visible = true;
            figure.classList.add('transition-all', 'duration-500', 'ease-out');

            const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
            (async () => {
                await sleep(1350);
                for (const cls of finalClasses) {
                    for (let i = 1; i <= cls.length; i++) {
                        this.current = cls.slice(0, i);
                        await sleep(34 + Math.random() * 30);
                    }
                    await sleep(190);
                    this.typed.push(cls);
                    this.current = '';
                    this.saved = false;
                    figure.classList.add(cls);
                    await sleep(675);
                }
                this.saved = true;
                await sleep(1050);
                this.visible = false;
                figure.classList.remove('transition-all', 'duration-500', 'ease-out');
            })();
        },
    }));
});

window.addEventListener('DOMContentLoaded', () => {
    initBackground();

    if (reduced) return;

    // Oldalbetöltés: egyetlen, finom megjelenés
    gsap.from('main', { opacity: 0, y: 14, duration: 0.6, ease: 'power2.out', clearProps: 'all' });

    // Görgetésre finom felfedés
    gsap.utils.toArray('[data-reveal]').forEach((el) => {
        gsap.from(el, {
            y: 24, opacity: 0, duration: 0.7, ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 92%', once: true },
        });
    });

    // Számlálók
    gsap.utils.toArray('[data-count]').forEach((el) => {
        const end = parseFloat(el.dataset.count);
        const obj = { v: 0 };
        gsap.to(obj, {
            v: end, duration: 1.6, ease: 'power2.out',
            onUpdate: () => (el.textContent = Math.round(obj.v)),
            scrollTrigger: { trigger: el, start: 'top 95%', once: true },
        });
    });

    // Képességsávok
    gsap.utils.toArray('[data-bar]').forEach((el) => {
        gsap.fromTo(el, { scaleX: 0 }, {
            scaleX: 1, duration: 1.1, ease: 'power2.out',
            scrollTrigger: { trigger: el, start: 'top 95%', once: true },
        });
    });
});
