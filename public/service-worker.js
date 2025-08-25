self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open("diesel-cache").then((cache) => {
      return cache.addAll([
        "/",
        "/login",
        "/css/app.css",
        "/js/app.js"
      ]);
    })
  );
});

self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});

self.addEventListener("push", function(event) {
  const data = event.data.json();

  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: "/icons/icon-192x192.png",
      badge: "/icons/icon-192x192.png"
    })
  );
});