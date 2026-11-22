/* EMERALS RP — interazioni del sito */
(() => {
  'use strict';
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const CFG = window.EMERALS || { districts: {} };
  document.documentElement.classList.add('js');
  const T0 = Date.now();

  const toast = (msg) => {
    const t = $('#toast'); if (!t) return;
    t.textContent = msg; t.classList.add('show');
    clearTimeout(toast.id); toast.id = setTimeout(() => t.classList.remove('show'), 2400);
  };
  const copy = async (text) => {
    try { await navigator.clipboard.writeText(text); return true; }
    catch { const a = document.createElement('textarea'); a.value = text; document.body.append(a); a.select();
      const ok = document.execCommand('copy'); a.remove(); return ok; }
  };

  /* ---------- NAV ---------- */
  const nav = $('#nav'), burger = $('#burger'), links = $('#navLinks'), bar = $('#progress');
  const onScroll = () => {
    nav.classList.toggle('is-stuck', scrollY > 24);
    const h = document.documentElement.scrollHeight - innerHeight;
    bar.style.width = (h > 0 ? (scrollY / h) * 100 : 0) + '%';
  };
  addEventListener('scroll', onScroll, { passive: true }); onScroll();
  burger?.addEventListener('click', () => {
    const open = links.classList.toggle('open');
    burger.setAttribute('aria-expanded', open);
  });
  links?.addEventListener('click', (e) => { if (e.target.closest('a')) { links.classList.remove('open'); burger.setAttribute('aria-expanded', false); } });

  // scroll-spy
  const spy = new IntersectionObserver((es) => es.forEach((en) => {
    if (en.isIntersecting) $$('a', links).forEach((a) => a.classList.toggle('is-on', a.getAttribute('href') === '#' + en.target.id));
  }), { rootMargin: '-45% 0px -50% 0px' });
  $$('main section[id]').forEach((s) => spy.observe(s));

  // reveal
  if ('IntersectionObserver' in window && !reduce) {
    const io = new IntersectionObserver((es) => es.forEach((en) => {
      if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
    }), { threshold: .12 });
    $$('.reveal').forEach((el, i) => { el.style.transitionDelay = (i % 4) * 70 + 'ms'; io.observe(el); });
  } else $$('.reveal').forEach((el) => el.classList.add('is-in'));

  $('#copyInvite')?.addEventListener('click', async (e) => {
    toast(await copy(e.currentTarget.dataset.copy) ? 'Invito copiato negli appunti' : 'Copia non riuscita');
  });

  /* ---------- CENTRALE OPERATIVA (feed simulato) ---------- */
  const feed = $('#feed');
  if (feed) {
    const pick = (a) => a[Math.floor(Math.random() * a.length)];
    const n = (a, b) => String(Math.floor(a + Math.random() * (b - a)));
    const zones = ['Centro', 'Porto', 'Stazione', 'Lungofiume', 'Zona Industriale', 'Quartiere Nord'];
    const gen = [
      () => ['pd', 'POLIZIA', `Unità P-${n(10, 40)} in pattuglia, ${pick(zones)}. Tutto regolare.`],
      () => ['pd', 'POLIZIA', `Posto di blocco attivo, ${pick(zones)}. Rallentare.`],
      () => ['ss', 'SOCCORSO', `Ambulanza S-${n(1, 9)} in servizio. Turno SSU aperto.`],
      () => ['ss', 'SOCCORSO', `Intervento concluso, ${pick(zones)}. Unità di nuovo libera.`],
      () => ['is', 'ISTITUZIONI', `Richiesta di cittadinanza approvata. Benvenuto in città!`],
      () => ['is', 'ISTITUZIONI', `Patente rilasciata. Guida con prudenza.`],
      () => ['bk', 'BANCA', `Bonifico completato. Premio giornaliero riscosso.`],
      () => ['cs', 'SUPPORTO', `Ticket #${n(100, 900)} preso in carico dallo staff.`],
      () => ['md', 'MODERAZIONE', `Controllo regolamento completato, nessuna violazione.`],
    ];
    const clk = () => new Date().toLocaleTimeString('it-IT', { hour12: false });
    const push = () => {
      const [c, who, txt] = pick(gen)();
      const li = document.createElement('li');
      li.innerHTML = `<time>${clk()}</time><span><b class="u-${c}">${who}</b> ${txt}</span>`;
      feed.append(li); while (feed.children.length > 7) feed.firstChild.remove();
    };
    for (let i = 0; i < 6; i++) push();
    if (!reduce) setInterval(push, 2600);
    const c = $('#clock'); const tick = () => { c.textContent = clk(); }; tick(); setInterval(tick, 1000);
  }

  /* ---------- STATISTICHE LIVE ---------- */
  const countUp = (el, to) => {
    if (reduce || !isFinite(to)) { el.textContent = to.toLocaleString('it-IT'); return; }
    const s = performance.now(), d = 900;
    const f = (now) => { const p = Math.min((now - s) / d, 1); el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3))).toLocaleString('it-IT'); if (p < 1) requestAnimationFrame(f); };
    requestAnimationFrame(f);
  };
  const loadStats = async () => {
    try {
      const r = await fetch((CFG.api && CFG.api.stats) || 'api/stats.php', { cache: 'no-store' }); if (!r.ok) throw 0;
      const j = await r.json();
      if (j.discord) { countUp($('[data-stat=members]'), j.discord.members); countUp($('[data-stat=online]'), j.discord.online); }
      if (j.roblox) { $('[data-roblox]').hidden = false; countUp($('[data-stat=playing]'), j.roblox.playing); }
    } catch { /* statistiche non disponibili: restano i trattini */ }
  };
  loadStats(); setInterval(loadStats, 90000);

  /* ---------- MAPPA CITTÀ ---------- */
  const map = $('#cityMap');
  if (map) {
    const ICONS = {
      shield: '<path d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/>',
      cross: '<rect x="4" y="4" width="16" height="16" rx="4"/><path d="M12 8v8M8 12h8"/>',
      landmark: '<path d="M3 10l9-6 9 6"/><path d="M5 10v8M9.5 10v8M14.5 10v8M19 10v8"/><path d="M3 21h18"/>',
      briefcase: '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M3 13h18"/>',
      coins: '<ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/>',
      headset: '<path d="M4 14v-2a8 8 0 0116 0v2"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/><path d="M19 20c0 1.5-2 2-5 2"/>',
      scale: '<path d="M12 3v18M6 21h12M5 7h14"/><path d="M5 7l-3 7a3 3 0 006 0L5 7zM19 7l-3 7a3 3 0 006 0l-3-7z"/>',
    };
    const panel = $('#panel');
    const esc = (s) => s.replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const show = (key, animate = true) => {
      const d = CFG.districts[key]; if (!d) return;
      $$('.dist', map).forEach((g) => g.classList.toggle('is-on', g.dataset.d === key));
      $('#pIcon').innerHTML = `<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">${ICONS[d.icon] || ''}</svg>`;
      $('#pTag').textContent = d.tag; $('#pName').textContent = d.name; $('#pText').textContent = d.text;
      $('#pPoints').innerHTML = d.points.map((p) => `<li>${esc(p)}</li>`).join('');
      $('#pCmds').innerHTML = d.cmds.map((c) => `<code>${esc(c)}</code>`).join('');
      if (animate && !reduce) { panel.classList.remove('swap'); void panel.offsetWidth; panel.classList.add('swap'); }
    };
    $$('.dist', map).forEach((g) => {
      g.addEventListener('click', () => show(g.dataset.d));
      g.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); show(g.dataset.d); } });
    });
    show('polizia', false);
  }

  /* ---------- CARTA D'IDENTITÀ ---------- */
  const cvs = $('#idCanvas'), form = $('#idForm');
  if (cvs && form) {
    const x = cvs.getContext('2d'), W = cvs.width, H = cvs.height;
    const vals = () => Object.fromEntries(new FormData(form).entries());
    const up = (s) => s.normalize('NFD').replace(/[̀-ͯ]/g, '').toUpperCase().replace(/[^A-Z]/g, '<');
    const hash = (s) => { let h = 2166136261; for (const c of s) { h ^= c.charCodeAt(0); h = Math.imul(h, 16777619); } return h >>> 0; };
    const fmtDate = (iso) => iso ? iso.split('-').reverse().join('/') : '--/--/----';
    const rr = (a, b, w, h, r) => { x.beginPath(); x.roundRect(a, b, w, h, r); };
    let loaded = false;
    const draw = () => {
      const v = vals(), h = hash((v.nome || '') + (v.cognome || '') + (v.nascita || ''));
      const num = 'EM' + String(h % 10000000).padStart(7, '0');
      // sfondo
      const g = x.createLinearGradient(0, 0, W, H); g.addColorStop(0, '#34234a'); g.addColorStop(.55, '#1f1630'); g.addColorStop(1, '#0d0914');
      x.fillStyle = g; rr(0, 0, W, H, 34); x.fill();
      x.save(); rr(0, 0, W, H, 34); x.clip();
      // guilloche
      x.strokeStyle = 'rgba(246,185,59,.09)'; x.lineWidth = 1;
      for (let i = 0; i < 38; i++) { x.beginPath(); for (let px = 0; px <= W; px += 6) { const py = H * .55 + Math.sin(px / 60 + i * .35) * (30 + i * 3) + (i - 19) * 7; px ? x.lineTo(px, py) : x.moveTo(px, py); } x.stroke(); }
      const sh = x.createLinearGradient(0, 0, W, 0); sh.addColorStop(0, 'rgba(255,255,255,.14)'); sh.addColorStop(.5, 'rgba(255,255,255,0)');
      x.fillStyle = sh; x.fillRect(0, 0, W, H / 2);
      x.restore();
      // testata
      x.fillStyle = 'rgba(0,0,0,.28)'; x.fillRect(0, 0, W, 92);
      x.fillStyle = '#009246'; x.fillRect(0, 92, W / 3, 7); x.fillStyle = '#fff'; x.fillRect(W / 3, 92, W / 3, 7); x.fillStyle = '#ce2b37'; x.fillRect(2 * W / 3, 92, W / 3 + 1, 7);
      x.fillStyle = '#fff'; x.font = '800 30px "Bricolage Grotesque", Inter, sans-serif'; x.textBaseline = 'alphabetic';
      x.fillText('CITTÀ DI EMERALS', 40, 48);
      x.fillStyle = '#ffd77a'; x.font = '500 15px "JetBrains Mono", monospace'; x.fillText('CARTA D\'IDENTITÀ · ROLEPLAY', 40, 74);
      x.fillStyle = '#f6b93b'; x.beginPath(); x.moveTo(W - 82, 22); x.lineTo(W - 46, 40); x.lineTo(W - 46, 62); x.lineTo(W - 82, 80); x.lineTo(W - 118, 62); x.lineTo(W - 118, 40); x.closePath(); x.fill();
      x.fillStyle = '#04140e'; x.font = '800 26px "Bricolage Grotesque", sans-serif'; x.textAlign = 'center'; x.fillText('E', W - 82, 61); x.textAlign = 'left';
      // foto
      x.fillStyle = '#f3dcc0'; rr(40, 124, 220, 270, 18); x.fill();
      x.save(); rr(40, 124, 220, 270, 18); x.clip();
      const pg = x.createLinearGradient(0, 124, 0, 394); pg.addColorStop(0, '#ffd9a0'); pg.addColorStop(1, '#e89a5c'); x.fillStyle = pg; x.fillRect(40, 124, 220, 270);
      x.fillStyle = '#2b2030'; x.beginPath(); x.arc(150, 230, 52, 0, 7); x.fill();
      x.beginPath(); x.ellipse(150, 394, 100, 112, 0, Math.PI, 0); x.fill(); x.restore();
      // campi
      const field = (label, val, px, py, size = 30) => {
        x.fillStyle = '#f6b93b'; x.font = '500 13px "JetBrains Mono", monospace'; x.fillText(label, px, py);
        x.fillStyle = '#fff'; x.font = `700 ${size}px "Bricolage Grotesque", Inter, sans-serif`; x.fillText(val, px, py + size + 2);
      };
      field('COGNOME', (v.cognome || '—').toUpperCase(), 296, 138, 34);
      field('NOME', (v.nome || '—').toUpperCase(), 296, 212, 34);
      field('NATO IL', fmtDate(v.nascita), 296, 288, 26);
      field('NAZIONALITÀ', (v.nazione || '—').toUpperCase(), 520, 288, 26);
      field('ROBLOX', v.roblox || '—', 296, 352, 24);
      field('N. DOCUMENTO', num, 620, 150, 22);
      // MRZ
      x.fillStyle = 'rgba(0,0,0,.34)'; x.fillRect(0, H - 104, W, 104);
      x.fillStyle = '#f2e4cc'; x.font = '500 22px "JetBrains Mono", monospace';
      const l1 = ('IDEMR' + num + '<<' + up(v.roblox || '')).padEnd(34, '<').slice(0, 34);
      const l2 = (up(v.cognome || '') + '<<' + up(v.nome || '')).padEnd(34, '<').slice(0, 34);
      x.fillText(l1, 40, H - 62); x.fillText(l2, 40, H - 28);
      x.fillStyle = 'rgba(255,255,255,.55)'; x.font = '500 12px "JetBrains Mono", monospace'; x.textAlign = 'right';
      x.fillText('DOCUMENTO DI FANTASIA · SOLO ROLEPLAY', W - 40, 118); x.textAlign = 'left';
    };
    const redraw = () => draw();
    form.addEventListener('input', redraw);
    (document.fonts?.ready || Promise.resolve()).then(() => { loaded = true; redraw(); });
    redraw();

    // inclinazione 3D
    const wrapEl = cvs.parentElement;
    if (!reduce && matchMedia('(hover:hover)').matches) {
      wrapEl.addEventListener('pointermove', (e) => {
        const r = cvs.getBoundingClientRect(), px = (e.clientX - r.left) / r.width - .5, py = (e.clientY - r.top) / r.height - .5;
        cvs.style.transform = `rotateY(${px * 10}deg) rotateX(${-py * 10}deg)`;
      });
      wrapEl.addEventListener('pointerleave', () => { cvs.style.transform = ''; });
    }
    $('#idDownload').addEventListener('click', () => {
      const v = vals(), a = document.createElement('a');
      a.download = `carta-identita-${(v.nome || 'rp')}-${(v.cognome || '')}.png`.toLowerCase().replace(/\s+/g, '-');
      a.href = cvs.toDataURL('image/png'); a.click(); toast('Carta scaricata');
    });
    $('#idCopy').addEventListener('click', async () => {
      const v = vals();
      const txt = `Username Roblox: ${v.roblox}\nNome e cognome RP: ${v.nome} ${v.cognome}\nData di nascita: ${fmtDate(v.nascita)}\nNazione di provenienza: ${v.nazione}`;
      toast(await copy(txt) ? 'Dati copiati: incollali nella richiesta' : 'Copia non riuscita');
    });
  }

  /* ---------- FORM CONTATTI ---------- */
  const cf = $('#contactForm');
  if (cf) {
    const msg = $('#cMsg'), btn = $('#cSend');
    cf.addEventListener('submit', async (e) => {
      e.preventDefault(); msg.className = 'form__msg'; msg.textContent = '';
      if (!cf.checkValidity()) { cf.reportValidity(); return; }
      btn.disabled = true; btn.textContent = 'Invio in corso…';
      try {
        const body = Object.fromEntries(new FormData(cf).entries()); body.t = Date.now() - T0;
        const r = await fetch((CFG.api && CFG.api.contact) || 'api/contact.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) });
        const j = await r.json().catch(() => ({}));
        if (r.ok && j.ok) { msg.className = 'form__msg ok'; msg.textContent = 'Messaggio inviato. Ti risponderemo su Discord.'; cf.reset(); if (j.csrf) cf.elements.csrf.value = j.csrf; }
        else { msg.className = 'form__msg err'; msg.textContent = j.error || 'Invio non riuscito. Riprova tra poco.'; }
      } catch { msg.className = 'form__msg err'; msg.textContent = 'Connessione non riuscita. Riprova.'; }
      btn.disabled = false; btn.textContent = 'Invia messaggio';
    });
  }
})();
