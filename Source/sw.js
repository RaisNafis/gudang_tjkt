/**
 * Service Worker for Gudang Sekolah PWA Offline Performance
 */
const CACHE_NAME = 'gudang-sekolah-v3';
const ASSETS_TO_CACHE = [
  'assets/js/tailwind.min.js',
  'assets/js/chart.min.js',
  'assets/js/JsBarcode.all.min.js',
  'assets/js/qrcode.min.js',
  'assets/img/belmoti.png'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE).catch(() => {});
    }).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  // 1. Ignore non-http(s) requests (e.g. chrome-extension://)
  if (!event.request.url.startsWith('http://') && !event.request.url.startsWith('https://')) {
    return;
  }

  // 2. Handle Favicon.ico cleanly if not found
  if (event.request.url.includes('/favicon.ico')) {
    event.respondWith(
      caches.match('assets/img/belmoti.png').then((cached) => {
        return cached || fetch(event.request).catch(() => new Response('', { status: 200 }));
      })
    );
    return;
  }

  // 3. Network first strategy for HTML navigation & PHP / API endpoints
  if (event.request.mode === 'navigate' || event.request.url.includes('/api/') || event.request.url.includes('.php')) {
    event.respondWith(
      fetch(event.request).catch(async () => {
        const cached = await caches.match(event.request);
        if (cached) return cached;
        return new Response('<html lang="id"><head><meta charset="UTF-8"><title>Offline - Gudang Sekolah</title><style>body{font-family:sans-serif;text-align:center;padding:50px;background:#f8fafc;color:#334155;}</style></head><body><h2>⚠️ Koneksi Terputus</h2><p>Anda sedang dalam mode offline. Silakan periksa koneksi internet Anda.</p></body></html>', {
          headers: { 'Content-Type': 'text/html; charset=utf-8' }
        });
      })
    );
    return;
  }

  // 4. Cache first strategy for static assets (JS, CSS, PNG, SVG)
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      if (cachedResponse) {
        return cachedResponse;
      }
      return fetch(event.request).then((response) => {
        if (response && response.status === 200 && event.request.method === 'GET' && response.type === 'basic') {
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone)).catch(() => {});
        }
        return response;
      }).catch(() => {
        return new Response('', { status: 404, statusText: 'Asset not found' });
      });
    })
  );
});
