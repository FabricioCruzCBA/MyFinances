self.addEventListener('push', (event)=>{
    const notification = event.data.json();

    event.waitUntil(
        self.registration.showNotification(notification.title, {
            body:notification.body,
            icon:'icons/icon-192x192.png',
            data:{
                url:notification.url,
            }
        })
    );
});

self.addEventListener("notificationclick", (event)=>{
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    )
});