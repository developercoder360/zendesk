(function() {
    // Prevent multiple injections
    if (document.getElementById('zendesk-widget-container')) return;

    // Get the base URL of the script to know where to load the iframe from
    let scriptTag = document.currentScript;
    let scriptSrc = '';
    
    if (scriptTag && scriptTag.src) {
        scriptSrc = scriptTag.src;
    } else {
        // Fallback for older browsers
        const scripts = document.getElementsByTagName('script');
        for (let i = 0; i < scripts.length; i++) {
            if (scripts[i].src.includes('widget.js')) {
                scriptSrc = scripts[i].src;
                break;
            }
        }
    }

    const url = new URL(scriptSrc);
    const baseUrl = url.origin; // e.g. https://tenant.zendesk.127.0.0.1.nip.io
    
    // Create the container
    const container = document.createElement('div');
    container.id = 'zendesk-widget-container';
    container.style.position = 'fixed';
    container.style.bottom = '20px';
    container.style.right = '20px';
    container.style.zIndex = '999999';
    container.style.display = 'flex';
    container.style.flexDirection = 'column';
    container.style.alignItems = 'flex-end';
    
    // Create the iframe
    const iframe = document.createElement('iframe');
    iframe.id = 'zendesk-widget-iframe';
    iframe.src = `${baseUrl}/widget/frame`;
    iframe.style.width = '380px';
    iframe.style.height = '600px';
    iframe.style.maxHeight = 'calc(100vh - 100px)';
    iframe.style.border = 'none';
    iframe.style.borderRadius = '12px';
    iframe.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1), 0 5px 10px rgba(0,0,0,0.05)';
    iframe.style.display = 'none';
    iframe.style.marginBottom = '16px';
    iframe.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    iframe.style.opacity = '0';
    iframe.style.transform = 'translateY(10px)';
    iframe.style.backgroundColor = 'transparent'; // Let the iframe body handle background
    iframe.allow = 'camera; microphone; fullscreen; display-capture; autoplay';

    // Create the FAB button
    const button = document.createElement('button');
    button.id = 'zendesk-widget-fab';
    button.style.width = '60px';
    button.style.height = '60px';
    button.style.borderRadius = '50%';
    button.style.backgroundColor = '#10b981'; // Emerald 500 to match theme
    button.style.color = '#ffffff';
    button.style.border = 'none';
    button.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    button.style.cursor = 'pointer';
    button.style.display = 'flex';
    button.style.alignItems = 'center';
    button.style.justifyContent = 'center';
    button.style.transition = 'transform 0.2s ease, background-color 0.2s ease';
    button.setAttribute('aria-label', 'Open Help Widget');
    
    // SVG Icons
    const iconOpen = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>`;
    const iconClose = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
    
    button.innerHTML = iconOpen;

    // Toggle Logic
    let isOpen = false;
    button.addEventListener('click', () => {
        isOpen = !isOpen;
        if (isOpen) {
            iframe.style.display = 'block';
            // Trigger reflow
            void iframe.offsetWidth;
            iframe.style.opacity = '1';
            iframe.style.transform = 'translateY(0)';
            button.innerHTML = iconClose;
            button.style.transform = 'rotate(90deg)';
        } else {
            iframe.style.opacity = '0';
            iframe.style.transform = 'translateY(10px)';
            button.innerHTML = iconOpen;
            button.style.transform = 'rotate(0deg)';
            setTimeout(() => {
                if (!isOpen) iframe.style.display = 'none';
            }, 300); // match transition
        }
    });
    
    // Hover effects
    button.addEventListener('mouseenter', () => {
        button.style.transform = isOpen ? 'rotate(90deg) scale(1.05)' : 'scale(1.05)';
    });
    button.addEventListener('mouseleave', () => {
        button.style.transform = isOpen ? 'rotate(90deg) scale(1)' : 'scale(1)';
    });

    // Append everything
    container.appendChild(iframe);
    container.appendChild(button);
    document.body.appendChild(container);
    
    // Listen for messages from the iframe (e.g., to close the widget automatically)
    window.addEventListener('message', (event) => {
        // In a real app, verify event.origin matches baseUrl
        if (event.data === 'closeWidget' && isOpen) {
            button.click(); // Programmatically click to close
        }
    });
})();
