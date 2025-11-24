const CACHE_VERSION = 1;
const CACHE_NAME = `gombe-hub-v${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';
const API_CACHE_NAME = `gombe-hub-api-v${CACHE_VERSION}`;
const IMAGE_CACHE_NAME = `gombe-hub-images-v${CACHE_VERSION}`;

const STATIC_ASSETS = [
  '/',
  '/admin/login',
  '/offline.html',
  '/manifest.json',
  '/css/app.css',
  '/js/app.js',
  '/js/pwa-installer.js',
  '/js/pwa-compatibility.js',
  '/img/pwa/icon-192x192.png',
  '/img/pwa/icon-512x512.png',
  '/img/pwa/icon-384x384.png'
];

const NETWORK_FIRST_PATHS = [
  '/api/',
  '/admin/'
];

const CACHE_FIRST_PATHS = [
  '/img/',
  '/assets/',
  '/css/',
  '/fonts/'
];

// Installation event
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch((err) => {
        console.log('Cache addAll error:', err);
      });
    })
  );
  self.skipWaiting();
});

// Activation event
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          const isOldCache = cacheName.startsWith('gombe-hub-') && 
                           cacheName !== CACHE_NAME && 
                           cacheName !== API_CACHE_NAME && 
                           cacheName !== IMAGE_CACHE_NAME;
          
          if (isOldCache) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
  console.log('Service Worker activated');
});

// Fetch event with optimized strategies
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }

  // Skip external URLs (except CDN resources)
  if (url.origin !== location.origin && !isCDN(url)) {
    return;
  }

  // External CDN resources - Cache with stale-while-revalidate
  if (isCDN(url)) {
    event.respondWith(staleWhileRevalidate(request));
    return;
  }

  // API requests - Network first, fall back to cache
  if (NETWORK_FIRST_PATHS.some(path => url.pathname.startsWith(path))) {
    event.respondWith(networkFirst(request));
  }
  // Images - Cache first strategy for better performance
  else if (url.pathname.match(/\.(png|jpg|jpeg|gif|webp|svg)$/i)) {
    event.respondWith(cachePictureFirst(request));
  }
  // Assets (CSS, JS, fonts) - Cache first strategy
  else if (url.pathname.match(/\.(js|css|woff|woff2|ttf|eot)$/i)) {
    event.respondWith(cacheFirst(request));
  }
  // HTML pages - Network first with offline fallback
  else if (url.pathname.match(/\.html$/) || url.pathname === '/') {
    event.respondWith(networkFirst(request));
  }
  // Default - Network first
  else {
    event.respondWith(networkFirst(request));
  }
});

function isCDN(url) {
  const cdnHosts = ['cdnjs.cloudflare.com', 'fonts.googleapis.com', 'fonts.gstatic.com'];
  return cdnHosts.some(host => url.hostname.includes(host));
}

// Cache first strategy
function cacheFirst(request) {
  return caches.match(request).then((response) => {
    if (response) {
      return response;
    }
    return fetch(request)
      .then((response) => {
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }
        const responseToCache = response.clone();
        caches.open(CACHE_NAME).then((cache) => {
          cache.put(request, responseToCache);
        });
        return response;
      })
      .catch(() => {
        return caches.match(OFFLINE_URL);
      });
  });
}

// Network first strategy
function networkFirst(request) {
  return fetch(request)
    .then((response) => {
      if (!response || response.status !== 200 || response.type === 'error') {
        return response;
      }
      const responseToCache = response.clone();
      caches.open(CACHE_NAME).then((cache) => {
        cache.put(request, responseToCache);
      });
      return response;
    })
    .catch(() => {
      return caches.match(request).then((response) => {
        return response || caches.match(OFFLINE_URL);
      });
    });
}

// Cache pictures first strategy (optimized for images)
function cachePictureFirst(request) {
  return caches.match(request).then((response) => {
    if (response) {
      return response;
    }
    return fetch(request)
      .then((response) => {
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }
        const responseToCache = response.clone();
        caches.open(IMAGE_CACHE_NAME).then((cache) => {
          cache.put(request, responseToCache);
        });
        return response;
      })
      .catch(() => {
        // Return a placeholder image or cached fallback
        return caches.match('/img/pwa/icon-192x192.png');
      });
  });
}

// Stale while revalidate strategy
function staleWhileRevalidate(request) {
  return caches.match(request).then((response) => {
    const fetchPromise = fetch(request).then((response) => {
      if (!response || response.status !== 200) {
        return response;
      }
      const responseToCache = response.clone();
      caches.open(CACHE_NAME).then((cache) => {
        cache.put(request, responseToCache);
      });
      return response;
    }).catch(() => {
      // Network request failed, return cached response if available
      return caches.match(request) || new Response('Service unavailable', { status: 503 });
    });

    return response || fetchPromise;
  });
}

// Handle messages from clients
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'CLEAR_CACHE') {
    caches.keys().then((cacheNames) => {
      Promise.all(cacheNames.map(name => caches.delete(name)));
    });
  }
  
  if (event.data && event.data.type === 'GET_CACHE_SIZE') {
    caches.keys().then((cacheNames) => {
      let totalSize = 0;
      Promise.all(
        cacheNames.map((cacheName) => {
          return caches.open(cacheName).then((cache) => {
            return cache.keys().then((requests) => {
              Promise.all(requests.map(request => {
                cache.match(request).then(response => {
                  if (response) {
                    totalSize += response.blob().then(blob => blob.size);
                  }
                });
              }));
            });
          });
        })
      ).then(() => {
        event.ports[0].postMessage({ type: 'CACHE_SIZE', size: totalSize });
      });
    });
  }
});

// Background sync for offline actions
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData());
  }
});

function syncData() {
  return fetch('/api/sync', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  }).catch((err) => {
    console.log('Sync failed:', err);
  });
}
