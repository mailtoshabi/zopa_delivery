const CACHE_NAME = 'zopa-cache-v3';
const urlsToCache = [
  '/',
  '/offline',
  '/about-us',
  '/payment-terms',
  '/privacy-policy',
  '/support',
  '/meals/faq',
  '/meals/how-to-use',
  '/meals/site-map',
  '/meal/buy-plans',
  '/meal/buy-single',
  '/meal/buy-addons',
  '/meal/my-wallet',
  '/meal/daily-orders',
  '/meal/my-purchases',
  '/meal/my-leaves',
  '/meal/profile',
  '/meal/profile/change-password',
  '/meal/how-to-use-pdf',
  '/meal/feedbacks',
  '/meal/feedbacks',
  '/register',
  '/login',
  '/favicon',
  '/favicon/android-chrome-192x192.png',
  '/favicon/android-chrome-512x512.png',
  '/favicon/apple-touch-icon.png',
  '/favicon/favicon.ico',
  '/favicon/favicon.svg',
  '/favicon/favicon-16x16.png',
  '/favicon/favicon-32x32.png',
  '/favicon/favicon-96x96.png',
  '/favicon/favicon-96x96.png',
  '/favicon/site.webmanifest',
  '/front',
  '/front/css',
  '/front/css/bootstrap.min.css',
  '/front/css/global.css',
  '/front/font-awesome',
  '/front/font-awesome/css',
  '/front/font-awesome/css/all-6.0.0.min.css',
  '/front/font-awesome/webfonts',
  '/front/font-awesome/webfonts/fa-brands-400.ttf',
  '/front/font-awesome/webfonts/fa-brands-400.woff2',
  '/front/font-awesome/webfonts/fa-regular-400.ttf',
  '/front/font-awesome/webfonts/fa-regular-400.woff2',
  '/front/font-awesome/webfonts/fa-solid-900.ttf',
  '/front/font-awesome/webfonts/fa-solid-900.woff2',
  '/front/font-awesome/webfonts/fa-v4compatibility.ttf',
  '/front/font-awesome/webfonts/fa-v4compatibility.woff2',
  '/front/images',
  '/front/images/about_us.jpeg',
  '/front/images/logo.png',
  '/front/images/logo.svg',
  '/front/images/logo_red.png',
  '/front/images/meals.png',
  '/front/js',
  '/front/js/bootstrap.bundle.min.js',
  '/front/js/jquery-3.6.0.min.js',
  '/front/js/sw.js',
  '/front/js/sweetalert2@11.js',
  '/storage',
  '/storage/addons',
  '/storage/customers',
  '/storage/kitchens',
  '/storage/meals',
  '/js/app.js',
  // Add other static assets if needed
];

// Install service worker and pre-cache static assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Zopa SW] Caching static assets');
        return cache.addAll(urlsToCache);
      })
      .then(() => self.skipWaiting())
  );
});

// Activate service worker and clear old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            console.log('[Zopa SW] Removing old cache:', cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch handler — serve from cache, fallback to network, cache dynamic pages
self.addEventListener('fetch', event => {
  // Only handle GET and same-origin requests
  if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin)) {
    return;
  }

  event.respondWith(
    caches.match(event.request).then(cachedResponse => {
      if (cachedResponse) {
        return cachedResponse;
      }

      return fetch(event.request).then(networkResponse => {
        // Optionally cache the response if it's a page or static asset
        const clonedResponse = networkResponse.clone();

        caches.open(CACHE_NAME).then(cache => {
          const contentType = networkResponse.headers.get('Content-Type');

          // Cache only HTML pages or static assets (avoid API JSON etc.)
          if (contentType && (contentType.includes('text/html') || contentType.includes('text/css') || contentType.includes('application/javascript') || contentType.includes('image'))) {
            cache.put(event.request, clonedResponse);
          }
        });

        return networkResponse;
      }).catch(() => {
        // If both cache and network fail, show offline fallback page for HTML requests
        if (event.request.destination === 'document' || event.request.mode === 'navigate') {
          return caches.match('/offline');
        }
      });
    })
  );
});
