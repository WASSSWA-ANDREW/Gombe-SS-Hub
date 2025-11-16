// Check if CSS is loaded
(function() {
    // Run immediately to prevent FOUC (Flash of Unstyled Content)
    checkAndLoadCSS();
    
    // Also run on DOMContentLoaded to ensure everything is loaded
    document.addEventListener('DOMContentLoaded', checkAndLoadCSS);
    
    function checkAndLoadCSS() {
    // Function to check if a CSS file is loaded
    function isCssLoaded(filename) {
        const links = document.getElementsByTagName('link');
        for (let i = 0; i < links.length; i++) {
            if (links[i].href.includes(filename)) {
                return true;
            }
        }
        return false;
    }

    // Check if our app CSS is loaded
    const cssLoaded = isCssLoaded('app-');
    
    if (!cssLoaded) {
        console.log('CSS not loaded, attempting to load it manually');
        
        // Try to load the CSS manually - use the CSS_PATH from css-manifest.php if available
        const cssLink = document.createElement('link');
        cssLink.rel = 'stylesheet';
        
        // Use the dynamic CSS path if available, otherwise fall back to a static path
        if (window.CSS_PATH) {
            cssLink.href = window.CSS_PATH;
        } else {
            // Fallback to the latest known CSS file
            cssLink.href = '/build/assets/app-CnyAn2T9.css';
        }
        
        document.head.appendChild(cssLink);
        
        // Load fallback CSS files
        if (!isCssLoaded('app.css')) {
            const appCssLink = document.createElement('link');
            appCssLink.rel = 'stylesheet';
            appCssLink.href = '/css/app.css';
            document.head.appendChild(appCssLink);
        }
        
        // Check if we're on the landing page and load landing CSS if needed
        if (window.location.pathname === '/' || window.location.pathname === '/landing') {
            if (!isCssLoaded('landing.css')) {
                const landingCssLink = document.createElement('link');
                landingCssLink.rel = 'stylesheet';
                landingCssLink.href = '/css/landing.css';
                document.head.appendChild(landingCssLink);
            }
        }
        
        // Check if we're on the login page and load login CSS if needed
        if (window.location.pathname === '/admin/login') {
            if (!isCssLoaded('login.css')) {
                const loginCssLink = document.createElement('link');
                loginCssLink.rel = 'stylesheet';
                loginCssLink.href = '/css/login.css';
                document.head.appendChild(loginCssLink);
            }
        }
        
        // Apply theme class to html element if not already set
        if (!document.documentElement.classList.contains('theme-light') && 
            !document.documentElement.classList.contains('theme-dark') &&
            !document.documentElement.classList.contains('theme-green') &&
            !document.documentElement.classList.contains('theme-cream')) {
            
            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.add(`theme-${savedTheme}`);
        }
    }
});