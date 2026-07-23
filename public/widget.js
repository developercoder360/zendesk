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

    var scriptUrl = new URL(scriptTag.src);
    var baseUrl = scriptUrl.origin;
    var iframeUrl = baseUrl + '/widget/frame?key=' + encodeURIComponent(embedKey);

    // Create floating container
    var container = document.createElement('div');
    container.id = 'zendesk-widget-container';
    container.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:999999;border:none;overflow:hidden;transition:all 0.3s ease;width:380px;height:600px;max-width:calc(100vw - 40px);max-height:calc(100vh - 40px);';

    // Create iframe
    var iframe = document.createElement('iframe');
    iframe.src = iframeUrl;
    iframe.title = 'Support Chat Widget';
    iframe.style.cssText = 'width:100%;height:100%;border:none;background:transparent;';
    iframe.allow = 'clipboard-write';

    container.appendChild(iframe);
    document.body.appendChild(container);

    // Handle postMessage events from iframe
    window.addEventListener('message', function (event) {
        if (!event.data) return;
        if (event.data === 'closeWidget') {
            container.style.display = 'none';
        } else if (event.data === 'openWidget') {
            container.style.display = 'block';
        }
    });
})();
