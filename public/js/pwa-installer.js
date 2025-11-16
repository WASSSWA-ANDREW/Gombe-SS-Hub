// PWA Installation Handler
class PWAInstaller {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.init();
    }

    init() {
        this.checkIfInstalled();
        this.setupInstallPrompt();
        this.setupUpdateNotification();
    }

    checkIfInstalled() {
        // Check if app is running in standalone mode
        if (window.navigator.standalone === true || document.referrer.includes('android-app://')) {
            this.isInstalled = true;
            console.log('PWA is installed and running in standalone mode');
            document.body.classList.add('pwa-installed');
        }
    }

    setupInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallPrompt();
        });

        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            this.isInstalled = true;
            document.body.classList.add('pwa-installed');
            this.deferredPrompt = null;
            this.showNotification('App Installed', 'Gombe Hub has been installed on your device!');
        });
    }

    showInstallPrompt() {
        const installButton = this.createInstallButton();
        const promptContainer = document.createElement('div');
        promptContainer.id = 'pwa-install-prompt';
        promptContainer.className = 'pwa-install-prompt';
        promptContainer.innerHTML = `
            <div class="pwa-prompt-content">
                <div class="pwa-prompt-header">
                    <h3>Install Gombe Hub</h3>
                    <button class="pwa-prompt-close" aria-label="Close">×</button>
                </div>
                <p>Install our app on your device for better performance and offline access!</p>
                <div class="pwa-prompt-buttons">
                    <button class="pwa-install-yes">Install Now</button>
                    <button class="pwa-install-no">Later</button>
                </div>
            </div>
        `;

        document.body.appendChild(promptContainer);

        // Handle install button click
        promptContainer.querySelector('.pwa-install-yes').addEventListener('click', () => {
            this.triggerInstall();
            promptContainer.remove();
        });

        // Handle close button click
        promptContainer.querySelector('.pwa-install-no').addEventListener('click', () => {
            promptContainer.remove();
            this.hideInstallPromptForDay();
        });

        promptContainer.querySelector('.pwa-prompt-close').addEventListener('click', () => {
            promptContainer.remove();
            this.hideInstallPromptForDay();
        });

        // Also add to install button in navbar if exists
        if (installButton) {
            installButton.style.display = 'block';
            installButton.addEventListener('click', () => this.triggerInstall());
        }
    }

    createInstallButton() {
        let button = document.getElementById('install-app-btn');
        if (!button) {
            button = document.createElement('button');
            button.id = 'install-app-btn';
            button.className = 'btn btn-outline-primary';
            button.innerHTML = '<i class="fas fa-download"></i> Install App';
            button.style.display = 'none';
            // You can append this to a specific location in your navbar
        }
        return button;
    }

    async triggerInstall() {
        if (this.deferredPrompt) {
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            console.log(`User response to install prompt: ${outcome}`);
            this.deferredPrompt = null;
        }
    }

    setupUpdateNotification() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                this.showUpdateNotification();
            });
        }
    }

    showUpdateNotification() {
        const notification = document.createElement('div');
        notification.className = 'pwa-update-notification';
        notification.innerHTML = `
            <div class="pwa-notification-content">
                <span>App update available!</span>
                <button class="pwa-reload-btn">Reload</button>
            </div>
        `;

        document.body.appendChild(notification);

        notification.querySelector('.pwa-reload-btn').addEventListener('click', () => {
            window.location.reload();
        });

        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    showNotification(title, message) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: '/img/pwa/icon-192x192.svg',
                badge: '/img/pwa/icon-96x96.svg'
            });
        }
    }

    hideInstallPromptForDay() {
        const expiryDate = new Date();
        expiryDate.setDate(expiryDate.getDate() + 1);
        localStorage.setItem('pwa-prompt-hidden', expiryDate.getTime());
    }

    shouldShowPrompt() {
        const hidden = localStorage.getItem('pwa-prompt-hidden');
        if (!hidden) return true;
        return new Date().getTime() > parseInt(hidden);
    }

    requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
}

// Initialize PWA Installer when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const pwaInstaller = new PWAInstaller();
    window.pwaInstaller = pwaInstaller;
});
