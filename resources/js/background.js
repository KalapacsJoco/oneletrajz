// Háttér: csillagos ég csillagképekkel, sakk / matek / történelem / csillagászat motívumok

const GLYPHS = {
    chess: { color: 'white', items: ['♔', '♕', '♖', '♗', '♘', '♙', '♚', '♛', '♜', '♝', '♞', '♟', 'e4', 'Nf3', 'Qxf7#', '1. e4 e5', 'O-O'] },
    math: { color: 'green', items: ['π', '∑', '∫', '∞', 'φ', '√2', 'e^{iπ}+1=0', 'a²+b²=c²', '∇', '∂', 'ℵ', 'Δx', '∀', 'ζ(s)', '3,14159'] },
    history: { color: 'red', items: ['SPQR', 'MDCCCXLVIII', '⚜', '⚔', 'Ω', 'Α', '⚱', 'ANNO', 'MCMXLIV', '†'] },
    music: { color: 'green', items: ['♩', '♪', '♫', '♬', '𝄞', '𝄢', '♭', '♯', '♮', 'Allegro', 'Adagio', 'C–E–G', 'ƒ', 'pp', 'A = 440 Hz', '4/4'] },
    astronomy: { color: 'white', items: ['☉', '☽', '♁', '♂', '♃', '♄', '♅', '☄', 'M31', 'c = 299 792 458 m/s', '✦', 'H₀'] },
};

const CONSTELLATIONS = [
    { // Göncölszekér
        pts: [[0, .3], [.2, .35], [.38, .45], [.55, .55], [.62, .85], [.95, .8], [.92, .45]],
        edges: [[0, 1], [1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 3]],
        ax: .12, ay: .18, size: 300, depth: .12,
    },
    { // Kassziopeia
        pts: [[0, .2], [.2, .8], [.45, .4], [.7, .85], [1, .25]],
        edges: [[0, 1], [1, 2], [2, 3], [3, 4]],
        ax: .72, ay: .1, size: 240, depth: .16,
    },
    { // Orion
        pts: [[.2, 0], [.8, .05], [.35, .5], [.5, .48], [.65, .46], [.25, 1], [.75, .95]],
        edges: [[0, 1], [0, 2], [1, 4], [2, 3], [3, 4], [2, 5], [4, 6]],
        ax: .78, ay: .62, size: 280, depth: .1,
    },
];

const hexToRgb = (hex) => {
    const h = hex.replace('#', '').trim();
    const n = parseInt(h.length === 3 ? h.split('').map((c) => c + c).join('') : h, 16);
    return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
};

const mod = (a, n) => ((a % n) + n) % n;
const rand = (a, b) => a + Math.random() * (b - a);

export function initBackground() {
    const canvas = document.getElementById('bg-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
    const motifs = JSON.parse(canvas.dataset.motifs || '[]');
    const styles = getComputedStyle(document.documentElement);
    const red = hexToRgb(styles.getPropertyValue('--accent-from') || '#e63946');
    const green = hexToRgb(styles.getPropertyValue('--accent-to') || '#2ec27e');
    const colorOf = { white: [235, 235, 245], red, green };

    let W = 0, H = 0, dpr = 1;
    let mx = 0, my = 0, tmx = 0, tmy = 0;
    let stars = [], glyphs = [], shooting = null, nextShot = 3;

    const build = () => {
        stars = [];
        glyphs = [];

        if (motifs.includes('astronomy')) {
            const count = Math.min(260, Math.round((W * H) / 7000));
            for (let i = 0; i < count; i++) {
                stars.push({
                    x: Math.random(), y: Math.random(), r: rand(.4, 1.5), depth: rand(.03, .2),
                    phase: rand(0, 6.28), speed: rand(.6, 2), warm: Math.random() < .15,
                });
            }
        }

        const per = Math.max(6, Math.round(W / 70));
        motifs.forEach((key) => {
            const set = GLYPHS[key];
            if (!set) return;
            for (let i = 0; i < per; i++) {
                const txt = set.items[Math.floor(Math.random() * set.items.length)];
                const long = txt.length > 3;
                glyphs.push({
                    txt, color: colorOf[set.color], x: Math.random(), y: Math.random() * (H + 300),
                    size: long ? rand(18, 30) : rand(36, 110), depth: rand(.08, .5),
                    rot: rand(-.5, .5), vr: rand(-.1, .1) * .3, ph: rand(0, 6.28),
                    alpha: rand(.035, .09),
                });
            }
        });
    };

    const resize = () => {
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        W = innerWidth; H = innerHeight;
        canvas.width = W * dpr; canvas.height = H * dpr;
        canvas.style.width = W + 'px'; canvas.style.height = H + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        build();
    };

    const draw = (t) => {
        ctx.clearRect(0, 0, W, H);
        mx += (tmx - mx) * .05; my += (tmy - my) * .05;
        const sy = scrollY;

        // csillagok
        stars.forEach((s) => {
            const m = 40;
            const y = mod(s.y * (H + m * 2) - sy * s.depth, H + m * 2) - m + my * s.depth * 60;
            const x = s.x * W + mx * s.depth * 60;
            const a = reduced ? .6 : .35 + .65 * (.5 + .5 * Math.sin(t * s.speed + s.phase));
            const c = s.warm ? red : [255, 255, 255];
            ctx.fillStyle = `rgba(${c[0]},${c[1]},${c[2]},${a * .85})`;
            ctx.beginPath(); ctx.arc(x, y, s.r, 0, 6.283); ctx.fill();
        });

        // csillagképek
        if (motifs.includes('astronomy')) {
            CONSTELLATIONS.forEach((c) => {
                const m = c.size + 80;
                const baseY = c.ay * H;
                const oy = mod(baseY - sy * c.depth + m, H + m * 2) - m + my * c.depth * 60;
                const ox = c.ax * W - c.size / 2 + mx * c.depth * 60;
                const scale = Math.min(1, W / 900);
                const p = c.pts.map(([x, y]) => [ox + x * c.size * scale, oy + y * c.size * scale]);
                ctx.strokeStyle = `rgba(${green[0]},${green[1]},${green[2]},.22)`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                c.edges.forEach(([a, b]) => { ctx.moveTo(p[a][0], p[a][1]); ctx.lineTo(p[b][0], p[b][1]); });
                ctx.stroke();
                p.forEach(([x, y], i) => {
                    const tw = .7 + .3 * Math.sin(t * 1.5 + i);
                    const g = ctx.createRadialGradient(x, y, 0, x, y, 10);
                    g.addColorStop(0, `rgba(255,255,255,${.9 * tw})`);
                    g.addColorStop(1, 'rgba(255,255,255,0)');
                    ctx.fillStyle = g;
                    ctx.beginPath(); ctx.arc(x, y, 10, 0, 6.283); ctx.fill();
                });
            });

            // hullócsillag
            if (!reduced) {
                if (!shooting && t > nextShot) {
                    shooting = { x: rand(.1, .9) * W, y: rand(0, .4) * H, life: 0 };
                    nextShot = t + rand(5, 11);
                }
                if (shooting) {
                    shooting.life += .016;
                    const k = shooting.life / .9;
                    const x = shooting.x + k * 320, y = shooting.y + k * 160;
                    const g = ctx.createLinearGradient(x, y, x - 120, y - 60);
                    g.addColorStop(0, 'rgba(255,255,255,.9)');
                    g.addColorStop(1, 'rgba(255,255,255,0)');
                    ctx.strokeStyle = g; ctx.lineWidth = 1.6;
                    ctx.beginPath(); ctx.moveTo(x, y); ctx.lineTo(x - 120, y - 60); ctx.stroke();
                    if (k >= 1) shooting = null;
                }
            }
        }

        // lebegő motívumok
        glyphs.forEach((g) => {
            const m = 160;
            const y = mod(g.y - sy * g.depth - (reduced ? 0 : t * 4 * g.depth), H + m * 2) - m + my * g.depth * 40;
            const x = g.x * W + Math.sin(t * .2 + g.ph) * 18 + mx * g.depth * 60;
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(g.rot + (reduced ? 0 : Math.sin(t * .15 + g.ph) * .15 + t * g.vr * .1));
            ctx.font = `${g.size}px "Space Grotesk Variable", "Segoe UI Symbol", "Apple Symbols", serif`;
            ctx.fillStyle = `rgba(${g.color[0]},${g.color[1]},${g.color[2]},${g.alpha})`;
            ctx.textAlign = 'center';
            ctx.fillText(g.txt, 0, 0);
            ctx.restore();
        });
    };

    let raf;
    const loop = (ms) => {
        draw(ms / 1000);
        raf = requestAnimationFrame(loop);
    };

    addEventListener('resize', resize);
    addEventListener('mousemove', (e) => { tmx = e.clientX / W - .5; tmy = e.clientY / H - .5; });
    document.addEventListener('visibilitychange', () => {
        cancelAnimationFrame(raf);
        if (!document.hidden && !reduced) raf = requestAnimationFrame(loop);
    });

    resize();
    if (reduced) {
        draw(0);
        addEventListener('scroll', () => draw(0), { passive: true });
    } else {
        raf = requestAnimationFrame(loop);
    }
}
