/**
 * J&J GROUP - Service Worker
 * Standar Production dengan strategi Network First untuk halaman dinamis
 * dan Cache First untuk aset statis UI / Google Fonts / Lucide Icons.
 */

const CACHE_VERSION = 'v1.0.1';
const STATIC_CACHE_NAME = `jnj-static-${CACHE_VERSION}`;
const DYNAMIC_CACHE_NAME = `jnj-dynamic-${CACHE_VERSION}`;

// Aset statis inti yang dapat di-cache secara agresif
const STATIC_ASSETS = [
    '/favicon.ico',
    '/img/logo-jnj.png'
];

// Event: Install - Membuat cache awal
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE_NAME).then((cache) => {
            console.log('[Service Worker] Pre-caching core assets');
            return cache.addAll(STATIC_ASSETS).catch((err) => {
                console.warn('[Service Worker] Pre-cache failed for some assets, continuing anyway:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Event: Activate - Pembersihan Cache Lama (Mekanisme Pembaruan Versi)
self.addEventListener('activate', (event) => {
    const cacheWhitelist = [STATIC_CACHE_NAME, DYNAMIC_CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName.startsWith('jnj-') && !cacheWhitelist.includes(cacheName)) {
                        console.log('[Service Worker] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Event: Fetch - Intersepsi Permintaan Jaringan
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Bypass request non-GET (POST, PUT, DELETE, dll. harus langsung ke network)
    if (event.request.method !== 'GET') {
        event.respondWith(
            fetch(event.request).catch((err) => {
                console.error('[Service Worker] Network request failed for non-GET method:', err);
                return Response.error();
            })
        );
        return;
    }

    // Bypass Livewire requests, debug bar, Laravel Pulse, atau routes khusus
    if (
        url.pathname.includes('/livewire/') ||
        url.pathname.includes('/_debugbar/') ||
        url.pathname.includes('/pulse/') ||
        url.pathname.includes('/tinker')
    ) {
        event.respondWith(
            fetch(event.request).catch((err) => {
                console.warn('[Service Worker] Livewire/Debug request failed:', err);
                return Response.error();
            })
        );
        return;
    }

    // Tentukan apakah request adalah untuk aset statis UI
    const isStaticAsset = (
        url.origin === self.location.origin && (
            url.pathname.startsWith('/build/') ||
            url.pathname.startsWith('/assets/') ||
            url.pathname.startsWith('/img/') ||
            url.pathname.match(/\.(png|jpe?g|gif|svg|ico|webp|woff2?|eot|ttf|otf|css|js)$/i)
        )
    ) || 
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com') ||
    url.hostname.includes('unpkg.com') ||
    url.hostname.includes('cdn.jsdelivr.net');

    if (isStaticAsset) {
        // --- STRATEGI: Cache First (dengan Fallback Jaringan) ---
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Update cache di latar belakang (Stale-While-Revalidate)
                    fetch(event.request)
                        .then((networkResponse) => {
                            if (networkResponse && networkResponse.status === 200) {
                                caches.open(STATIC_CACHE_NAME).then((cache) => {
                                    cache.put(event.request, networkResponse);
                                });
                            }
                        })
                        .catch((err) => console.log('[Service Worker] Stale update failed (offline):', err));
                    
                    return cachedResponse;
                }

                return fetch(event.request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            const responseToCache = networkResponse.clone();
                            caches.open(STATIC_CACHE_NAME).then((cache) => {
                                cache.put(event.request, responseToCache);
                            });
                        }
                        return networkResponse;
                    })
                    .catch((err) => {
                        console.error('[Service Worker] Static fetch failed:', err);
                        // Mengembalikan error standard agar tidak memicu uncaught promise rejection
                        return Response.error();
                    });
            })
        );
    } else {
        // --- STRATEGI: Network First (dengan Fallback Cache & Halaman Offline) ---
        event.respondWith(
            fetch(event.request)
                .then((networkResponse) => {
                    // Hanya cache response GET yang valid
                    if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                        const responseToCache = networkResponse.clone();
                        caches.open(DYNAMIC_CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseToCache);
                        });
                    }
                    return networkResponse;
                })
                .catch((err) => {
                    console.log('[Service Worker] Dynamic network failed, falling back to cache:', err);
                    return caches.match(event.request).then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        
                        // Jika offline dan tidak ada di cache, kirimkan halaman fallback yang informatif
                        // (Mencegah TypeError: Failed to convert value to 'Response')
                        return new Response(
                            `<!DOCTYPE html>
                            <html lang="id">
                            <head>
                                <meta charset="utf-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1">
                                <title>Koneksi Terputus | J&J GROUP</title>
                                <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
                                <style>
                                    body {
                                        font-family: 'Plus Jakarta Sans', sans-serif;
                                        background: #0a0f1d;
                                        color: #f8fafc;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        height: 100vh;
                                        margin: 0;
                                        text-align: center;
                                    }
                                    .card {
                                        background: rgba(255, 255, 255, 0.02);
                                        backdrop-filter: blur(24px);
                                        border: 1px solid rgba(255, 255, 255, 0.05);
                                        padding: 3rem;
                                        border-radius: 2rem;
                                        max-width: 420px;
                                        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
                                    }
                                    h1 {
                                        font-size: 1.5rem;
                                        font-weight: 800;
                                        margin-bottom: 1rem;
                                        color: #D4AF37;
                                        text-transform: uppercase;
                                        letter-spacing: 0.05em;
                                    }
                                    p {
                                        font-size: 0.875rem;
                                        color: #94a3b8;
                                        line-height: 1.6;
                                        margin-bottom: 2rem;
                                    }
                                    button {
                                        background: #ffffff;
                                        color: #0a0f1d;
                                        border: none;
                                        padding: 0.85rem 2rem;
                                        border-radius: 1rem;
                                        font-weight: 800;
                                        font-size: 0.75rem;
                                        text-transform: uppercase;
                                        letter-spacing: 0.1em;
                                        cursor: pointer;
                                        transition: all 0.3s ease;
                                    }
                                    button:hover {
                                        background: #D4AF37;
                                        color: #0a0f1d;
                                        box-shadow: 0 0 20px rgba(212,175,55,0.3);
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="card">
                                    <h1>Koneksi Terputus</h1>
                                    <p>Node operasional tidak dapat dihubungi atau Anda sedang offline. Silakan periksa koneksi internet Anda.</p>
                                    <button onclick="window.location.reload()">Muat Ulang</button>
                                </div>
                            </body>
                            </html>`,
                            {
                                status: 503,
                                statusText: 'Service Unavailable',
                                headers: { 'Content-Type': 'text/html; charset=utf-8' }
                            }
                        );
                    });
                })
        );
    }
});
