(function () {
    if (window.ZendeskWidgetInitialized) return;
    window.ZendeskWidgetInitialized = true;

    var scriptTag = document.currentScript || (function() {
        var scripts = document.getElementsByTagName('script');
        return scripts[scripts.length - 1];
    })();

    var embedKey = scriptTag ? scriptTag.getAttribute('data-embed-key') : null;
    if (!embedKey) {
        console.error('[ZendeskWidget] Error: Missing data-embed-key attribute on script tag.');
        return;
    }

    // 1. Session Token Persistence (localStorage with memory fallback)
    var storageKey = 'zendesk_wsession_' + embedKey;
    var wsession = null;
    try {
        wsession = window.localStorage.getItem(storageKey);
    } catch (e) {
        wsession = null;
    }

    if (!wsession) {
        wsession = 'w_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        try {
            window.localStorage.setItem(storageKey, wsession);
        } catch (e) {}
    }

    var scriptUrl = new URL(scriptTag.src);
    var baseUrl = scriptUrl.origin;
    var iframeUrl = baseUrl + '/widget/frame?key=' + encodeURIComponent(embedKey) + '&wsession=' + encodeURIComponent(wsession);

    // 2. Create Floating Widget Container
    var container = document.createElement('div');
    container.id = 'zendesk-widget-container';
    container.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:999999;border:none;overflow:hidden;transition:all 0.3s cubic-bezier(0.16, 1, 0.3, 1);width:380px;height:600px;max-width:calc(100vw - 32px);max-height:calc(100vh - 32px);box-shadow:0 12px 32px rgba(0,0,0,0.15);border-radius:16px;background:transparent;display:block;';

    // 3. Create Iframe
    var iframe = document.createElement('iframe');
    iframe.src = iframeUrl;
    iframe.title = 'Support Live Chat';
    iframe.style.cssText = 'width:100%;height:100%;border:none;background:transparent;';
    iframe.allow = 'clipboard-write';

    container.appendChild(iframe);
    document.body.appendChild(container);

    // 4. Listen for postMessage Events
    window.addEventListener('message', function (event) {
        if (!event.data) return;
        if (event.data === 'closeWidget' || (typeof event.data === 'object' && event.data.type === 'closeWidget')) {
            container.style.height = '64px';
            container.style.width = '64px';
            container.style.borderRadius = '32px';
            container.style.boxShadow = 'none';
        } else if (event.data === 'openWidget' || (typeof event.data === 'object' && event.data.type === 'openWidget')) {
            container.style.height = '600px';
            container.style.width = '380px';
            container.style.borderRadius = '16px';
            container.style.boxShadow = '0 12px 32px rgba(0,0,0,0.15)';
        }
    });
})();
