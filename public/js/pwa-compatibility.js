class PWACompatibility {
    constructor() {
        this.capabilities = {};
        this.init();
    }

    init() {
        this.detectCapabilities();
        this.enhanceForDevice();
        this.setupFallbacks();
        this.logCompatibilityInfo();
    }

    detectCapabilities() {
        this.capabilities = {
            serviceWorker: 'serviceWorker' in navigator,
            webManifest: document.querySelector('link[rel="manifest"]') !== null,
            notifications: 'Notification' in window,
            vibration: 'vibrate' in navigator,
            geolocation: 'geolocation' in navigator,
            offline: navigator.onLine !== undefined,
            backgroundSync: 'sync' in ServiceWorkerRegistration.prototype,
            webShare: navigator.share !== undefined,
            clipboard: navigator.clipboard !== undefined,
            localStorage: this.checkLocalStorage(),
            indexedDB: 'indexedDB' in window,
            websocket: 'WebSocket' in window,
            webGL: this.checkWebGL(),
            mediaDevices: 'mediaDevices' in navigator,
            permissions: 'permissions' in navigator,
            screenOrientation: 'orientation' in screen,
            cameraAccess: 'getUserMedia' in (navigator.mediaDevices || {}),
            microphone: 'getUserMedia' in (navigator.mediaDevices || {}),
            pwaInstallable: this.checkPWAInstallable(),
            iosSupport: this.checkIOSSupport(),
            androidSupport: this.checkAndroidSupport(),
            desktopSupport: this.checkDesktopSupport(),
            darkMode: window.matchMedia('(prefers-color-scheme: dark)').matches,
            touchSupport: this.checkTouchSupport(),
            highDPI: window.devicePixelRatio > 1
        };
    }

    enhanceForDevice() {
        const isIOS = this.capabilities.iosSupport;
        const isAndroid = this.capabilities.androidSupport;

        if (isIOS) {
            this.enhanceIOSExperience();
        }

        if (isAndroid) {
            this.enhanceAndroidExperience();
        }

        if (this.capabilities.darkMode) {
            this.applyDarkModeOptimizations();
        }

        if (!this.capabilities.touchSupport) {
            this.applyDesktopOptimizations();
        }
    }

    enhanceIOSExperience() {
        const style = document.createElement('style');
        style.textContent = `
            body {
                -webkit-user-select: none;
                -webkit-touch-callout: none;
                -webkit-app-region: drag;
            }
            input, textarea, select {
                font-size: 16px !important;
            }
            .ios-safe-area {
                padding-top: max(12px, env(safe-area-inset-top));
                padding-left: max(12px, env(safe-area-inset-left));
                padding-right: max(12px, env(safe-area-inset-right));
                padding-bottom: max(12px, env(safe-area-inset-bottom));
            }
        `;
        document.head.appendChild(style);

        document.documentElement.style.setProperty('--safe-top', 'env(safe-area-inset-top)');
        document.documentElement.style.setProperty('--safe-left', 'env(safe-area-inset-left)');
        document.documentElement.style.setProperty('--safe-right', 'env(safe-area-inset-right)');
        document.documentElement.style.setProperty('--safe-bottom', 'env(safe-area-inset-bottom)');
    }

    enhanceAndroidExperience() {
        const style = document.createElement('style');
        style.textContent = `
            body {
                overflow-y: auto;
                -webkit-tap-highlight-color: rgba(59, 130, 246, 0.1);
            }
            * {
                -webkit-touch-callout: none;
            }
            input[type="range"] {
                -webkit-appearance: slider-horizontal;
            }
        `;
        document.head.appendChild(style);
    }

    applyDarkModeOptimizations() {
        const meta = document.querySelector('meta[name="theme-color"]');
        if (meta) {
            meta.setAttribute('content', '#1E293B');
        }
    }

    applyDesktopOptimizations() {
        const style = document.createElement('style');
        style.textContent = `
            * {
                cursor: default;
            }
            a, button, [role="button"] {
                cursor: pointer;
            }
            .mobile-only {
                display: none;
            }
        `;
        document.head.appendChild(style);
    }

    setupFallbacks() {
        if (!this.capabilities.localStorage) {
            this.setupMemoryStorage();
        }

        if (!this.capabilities.notifications && this.capabilities.serviceWorker) {
            this.setupFallbackNotifications();
        }

        if (!this.capabilities.webShare) {
            this.setupFallbackShare();
        }

        this.monitorConnectivity();
    }

    setupMemoryStorage() {
        window.localStorage = window.localStorage || {
            data: {},
            getItem: function(key) { return this.data[key] || null; },
            setItem: function(key, value) { this.data[key] = value; },
            removeItem: function(key) { delete this.data[key]; },
            clear: function() { this.data = {}; }
        };
    }

    setupFallbackNotifications() {
        window.showAppNotification = function(title, options = {}) {
            const notif = document.createElement('div');
            notif.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #3B82F6;
                color: white;
                padding: 16px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            `;
            notif.innerHTML = `<strong>${title}</strong><p>${options.body || ''}</p>`;
            document.body.appendChild(notif);

            setTimeout(() => {
                notif.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        };
    }

    setupFallbackShare() {
        navigator.shareCompat = function(data) {
            if (navigator.share) {
                return navigator.share(data);
            } else {
                const text = `${data.title}\n${data.text}\n${data.url}`;
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text);
                    alert('Link copied to clipboard!');
                } else {
                    alert(text);
                }
                return Promise.resolve();
            }
        };
    }

    monitorConnectivity() {
        window.addEventListener('online', () => {
            this.showConnectivityNotification('You are back online', 'success');
            if (window.pwaInstaller && window.pwaInstaller.setupUpdateNotification) {
                window.pwaInstaller.setupUpdateNotification();
            }
        });

        window.addEventListener('offline', () => {
            this.showConnectivityNotification('You are offline', 'warning');
        });
    }

    showConnectivityNotification(message, type) {
        const notif = document.createElement('div');
        notif.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            background: ${type === 'success' ? '#10b981' : '#f59e0b'};
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease-out;
        `;
        notif.textContent = message;
        document.body.appendChild(notif);

        setTimeout(() => {
            notif.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => notif.remove(), 300);
        }, 3000);
    }

    checkLocalStorage() {
        try {
            const test = '__test__';
            localStorage.setItem(test, test);
            localStorage.removeItem(test);
            return true;
        } catch(e) {
            return false;
        }
    }

    checkWebGL() {
        try {
            const canvas = document.createElement('canvas');
            return !!(window.WebGLRenderingContext && (canvas.getContext('webgl') || canvas.getContext('experimental-webgl')));
        } catch(e) {
            return false;
        }
    }

    checkPWAInstallable() {
        return this.capabilities.serviceWorker && this.capabilities.webManifest;
    }

    checkIOSSupport() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    checkAndroidSupport() {
        return /Android/.test(navigator.userAgent);
    }

    checkDesktopSupport() {
        return !this.checkIOSSupport() && !this.checkAndroidSupport();
    }

    checkTouchSupport() {
        return ('ontouchstart' in window) ||
               (navigator.maxTouchPoints > 0) ||
               (navigator.msMaxTouchPoints > 0);
    }

    logCompatibilityInfo() {
        console.log('PWA Compatibility Check:', this.capabilities);
        if (!this.capabilities.serviceWorker) {
            console.warn('Service Workers not supported on this device');
        }
        if (!this.capabilities.notifications) {
            console.warn('Notifications not supported on this device');
        }
    }

    getCapabilities() {
        return this.capabilities;
    }

    isFullyCompatible() {
        const required = ['serviceWorker', 'webManifest', 'localStorage'];
        return required.every(cap => this.capabilities[cap]);
    }

    getSupportLevel() {
        const supported = Object.values(this.capabilities).filter(Boolean).length;
        const total = Object.keys(this.capabilities).length;
        const percentage = (supported / total) * 100;

        if (percentage === 100) return 'excellent';
        if (percentage >= 80) return 'good';
        if (percentage >= 60) return 'moderate';
        return 'limited';
    }

    getUnsupportedFeatures() {
        return Object.keys(this.capabilities).filter(key => !this.capabilities[key]);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.pwaCompatibility = new PWACompatibility();
    console.log('PWA Support Level:', window.pwaCompatibility.getSupportLevel());
});
