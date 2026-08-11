const CACHE_VERSION = 'professor-tracker-v3';
const STATIC_CACHE = `${CACHE_VERSION}-static`;
const RUNTIME_CACHE = `${CACHE_VERSION}-runtime`;

const PRECACHE_URLS = [
  '/manifest.json',
  '/offline.html',
  '/icons/icon-192.png',
  '/icons/icon-512.png',
  '/icons/maskable-192.png',
  '/icons/maskable-512.png',
  '/images/isulogo.jpg',
  '/images/hero-premium-green.png',
  '/images/support-premium-green.png',
  '/build/assets/app-D06fwgrx.css',
  '/build/assets/app-BlfHLOm2.js',
];

const STATIC_DESTINATIONS = new Set(['style', 'script', 'image', 'font', 'manifest']);

self.addEventListener('install', (event) => {
  event.waitUntil(
    precacheStaticAssets()
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches
      .keys()
      .then((keys) => Promise.all(
        keys
          .filter((key) => ![STATIC_CACHE, RUNTIME_CACHE].includes(key))
          .map((key) => caches.delete(key))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  if (request.method !== 'GET') {
    return;
  }

  const url = new URL(request.url);

  if (url.origin !== self.location.origin) {
    return;
  }

  if (request.mode === 'navigate') {
    event.respondWith(networkFirst(request));
    return;
  }

  if (isStaticAsset(request, url)) {
    event.respondWith(cacheFirst(request));
  }
});

function isStaticAsset(request, url) {
  return STATIC_DESTINATIONS.has(request.destination)
    || url.pathname.startsWith('/build/')
    || url.pathname.startsWith('/icons/')
    || url.pathname.startsWith('/images/')
    || url.pathname === '/manifest.json'
    || url.pathname === '/offline.html';
}

async function precacheStaticAssets() {
  const cache = await caches.open(STATIC_CACHE);

  await Promise.all(
    PRECACHE_URLS.map(async (url) => {
      try {
        const response = await fetch(url, { cache: 'reload' });

        if (isCacheable(response)) {
          await cache.put(url, response);
        }
      } catch (error) {
        console.warn(`PWA precache skipped: ${url}`, error);
      }
    })
  );
}

async function cacheFirst(request) {
  const cached = await caches.match(request);

  if (cached) {
    return cached;
  }

  const response = await fetch(request);

  if (isCacheable(response)) {
    const cache = await caches.open(STATIC_CACHE);
    cache.put(request, response.clone());
  }

  return response;
}

async function networkFirst(request) {
  try {
    const response = await fetch(request);

    if (isCacheable(response)) {
      const cache = await caches.open(RUNTIME_CACHE);
      cache.put(request, response.clone());
    }

    return response;
  } catch (error) {
    return (await caches.match(request))
      || (await caches.match('/offline.html'))
      || Response.error();
  }
}

function isCacheable(response) {
  return response && response.ok && response.type === 'basic';
}
