// Font Awesome Icon Fix
(function () {
    // Run immediately to prevent FOUC (Flash of Unstyled Content)
    ensureFontAwesomeLoaded();

    // Also run on DOMContentLoaded to ensure everything is loaded
    document.addEventListener('DOMContentLoaded', ensureFontAwesomeLoaded);

    function ensureFontAwesomeLoaded() {
        // Function to check if Font Awesome is loaded
        function isFontAwesomeLoaded() {
            const links = document.getElementsByTagName('link');
            for (let i = 0; i < links.length; i++) {
                if (links[i].href.includes('font-awesome')) {
                    return true;
                }
            }
            return false;
        }

        // Check if Font Awesome is loaded
        if (!isFontAwesomeLoaded()) {
            console.log('Font Awesome not loaded, loading it now...');

            // Load Font Awesome
            const fontAwesomeLink = document.createElement('link');
            fontAwesomeLink.rel = 'stylesheet';
            fontAwesomeLink.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            fontAwesomeLink.integrity = 'sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==';
            fontAwesomeLink.crossOrigin = 'anonymous';
            fontAwesomeLink.referrerPolicy = 'no-referrer';

            document.head.appendChild(fontAwesomeLink);
            console.log('Font Awesome loaded successfully');
        }

        // Check if any icons are visible after a short delay
        setTimeout(function () {
            const icons = document.querySelectorAll('.fas, .far, .fab, .fa');
            let visibleIcons = 0;

            icons.forEach(function (icon) {
                const style = window.getComputedStyle(icon);
                if (style.fontFamily.includes('Font Awesome') || style.fontFamily.includes('FontAwesome')) {
                    visibleIcons++;
                }
            });

            if (icons.length > 0 && visibleIcons === 0) {
                console.log('Icons found but not visible, applying fallback styles...');

                // Apply fallback styles for Font Awesome
                const style = document.createElement('style');
                style.textContent = `
                    .fas, .far, .fab, .fa {
                        font-family: 'Font Awesome 6 Free', 'FontAwesome', sans-serif !important;
                        font-weight: 900 !important;
                        display: inline-block !important;
                        font-style: normal !important;
                        font-variant: normal !important;
                        text-rendering: auto !important;
                        line-height: 1 !important;
                    }
                    .far {
                        font-weight: 400 !important;
                    }
                    .fab {
                        font-family: 'Font Awesome 6 Brands', 'FontAwesome', sans-serif !important;
                    }
                `;
                document.head.appendChild(style);

                // Force browser to repaint
                document.body.style.display = 'none';
                setTimeout(function () {
                    document.body.style.display = '';
                }, 10);
            }
        }, 500);
    }
})();