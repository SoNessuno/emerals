// Cloudflare Pages — API del sito EMERALS RP (sostituisce api/stats.php e api/contact.php)
const DISCORD_INVITE = 'RyScfryWxt';
const TOPICS = { info: 'Informazioni', partnership: 'Partnership', staff: 'Candidatura staff', altro: 'Altro' };
const json = (data, status = 200, extra = {}) =>
  new Response(JSON.stringify(data), { status, headers: { 'Content-Type': 'application/json; charset=utf-8', 'X-Content-Type-Options': 'nosniff', ...extra } });

async function stats(env) {
  const out = { discord: null, roblox: null, updated: Math.floor(Date.now() / 1000) };
  try {
    const r = await fetch(`https://discord.com/api/v10/invites/${DISCORD_INVITE}?with_counts=true`, { cf: { cacheTtl: 60, cacheEverything: true } });
    if (r.ok) { const j = await r.json(); if (j.approximate_member_count != null) out.discord = { members: j.approximate_member_count, online: j.approximate_presence_count ?? 0 }; }
  } catch {}
  const uid = String(env.ROBLOX_UNIVERSE_ID || '');
  if (/^\d+$/.test(uid)) {
    try {
      const r = await fetch(`https://games.roblox.com/v1/games?universeIds=${uid}`, { cf: { cacheTtl: 60, cacheEverything: true } });
      if (r.ok) { const g = (await r.json()).data?.[0]; if (g) out.roblox = { playing: g.playing ?? 0, visits: g.visits ?? 0 }; }
    } catch {}
  }
  return json(out, 200, { 'Cache-Control': 'public, max-age=30' });
}

async function contact(request, env) {
  if (request.method !== 'POST') return json({ ok: false, error: 'Metodo non consentito.' }, 405);
  const origin = request.headers.get('Origin');
  if (origin && origin !== new URL(request.url).origin) return json({ ok: false, error: 'Richiesta non valida.' }, 403);
  let d; try { d = await request.json(); } catch { return json({ ok: false, error: 'Richiesta non valida.' }, 400); }
  if (d.website) return json({ ok: true });                       // honeypot
  if (typeof d.t === 'number' && d.t < 2500) return json({ ok: false, error: 'Troppo veloce, riprova.' }, 429);

  const s = (v, n) => String(v ?? '').trim().slice(0, n);
  const name = s(d.name, 40), discord = s(d.discord, 40), topic = s(d.topic, 20), message = s(d.message, 1000);
  const errs = [];
  if (name.length < 2) errs.push('Inserisci un nome valido (2-40 caratteri).');
  if (discord.length < 2) errs.push('Inserisci il tuo username Discord.');
  if (!TOPICS[topic]) errs.push('Scegli un argomento.');
  if (message.length < 10) errs.push('Il messaggio deve avere tra 10 e 1000 caratteri.');
  if (errs.length) return json({ ok: false, error: errs.join(' ') }, 422);

  const hook = String(env.EMERALS_WEBHOOK || '');
  if (!hook.startsWith('https://discord.com/api/webhooks/'))
    return json({ ok: false, error: 'Il modulo non è ancora attivo. Scrivici da un ticket nel Discord.' }, 503);

  const clean = (x) => x.replaceAll('@', '@​').replaceAll('`', "'");
  const r = await fetch(hook, {
    method: 'POST', headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      username: 'Sito EMERALS RP', allowed_mentions: { parse: [] },
      embeds: [{ title: `Nuovo messaggio dal sito · ${TOPICS[topic]}`, description: clean(message), color: 0x10d98a,
        fields: [{ name: 'Nome', value: clean(name), inline: true }, { name: 'Discord', value: clean(discord), inline: true }],
        timestamp: new Date().toISOString() }],
    }),
  });
  return r.ok ? json({ ok: true }) : json({ ok: false, error: 'Invio non riuscito. Riprova tra poco.' }, 502);
}

export default {
  async fetch(request, env) {
    const { pathname } = new URL(request.url);
    if (pathname === '/api/stats') return stats(env);
    if (pathname === '/api/contact') return contact(request, env);
    return env.ASSETS.fetch(request);
  },
};
