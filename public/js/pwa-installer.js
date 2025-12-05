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
            this.hideSidebarInstallButton();
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
            this.hideSidebarInstallButton();
            this.showNotification('App Installed', 'Gombe Hub has been installed on your device!');
        });
    }

    showInstallPrompt() {
        const installButton = this.createInstallButton();
        const sidebarButton = document.getElementById('install-app-btn-sidebar');
        
        // Show sidebar button if available
        if (sidebarButton) {
            sidebarButton.classList.remove('hidden');
        }
        
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
            this.setButtonLoading(true);
            
            try {
                this.deferredPrompt.prompt();
                const { outcome } = await this.deferredPrompt.userChoice;
                
                console.log(`User response to install prompt: ${outcome}`);
                
                if (outcome === 'accepted') {
                    console.log('User accepted the install prompt');
                    this.showInstallationFeedback();
                } else {
                    console.log('User dismissed the install prompt');
                    this.setButtonLoading(false);
                    this.hideSidebarInstallButton();
                }
                
                this.deferredPrompt = null;
            } catch (error) {
                console.error('Error during installation:', error);
                this.setButtonLoading(false);
                this.showErrorMessage('Installation failed. Please try again.');
            }
        } else {
            console.log('Deferred prompt not available - Install prompt may not have fired yet');
            console.log('This could be due to:', {
                'serviceWorkerReady': 'serviceWorker' in navigator,
                'manifestExists': !!document.querySelector('link[rel="manifest"]'),
                'isStandalone': window.navigator.standalone === true,
                'isInAndroidApp': document.referrer.includes('android-app://')
            });
            this.showErrorMessage('Installation is not available at this moment. Please try again later or check the browser console for details.');
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
                badge: '/img/pwa/icon-96x96.svg',
                tag: 'gombe-notification',
                requireInteraction: false
            });
        }
    }

    hideInstallPromptForDay() {
        const expiryDate = new Date();
        expiryDate.setDate(expiryDate.getDate() + 1);
        localStorage.setItem('pwa-prompt-hidden', expiryDate.getTime());
    }

    hideSidebarInstallButton() {
        const sidebarButton = document.getElementById('install-app-btn-sidebar');
        if (sidebarButton) {
            sidebarButton.classList.add('hidden');
        }
    }

    setButtonLoading(isLoading) {
        const sidebarButton = document.getElementById('install-app-btn-sidebar');
        const navbarButton = document.getElementById('install-app-btn');
        const buttons = [sidebarButton, navbarButton].filter(Boolean);
        
        buttons.forEach(button => {
            if (isLoading) {
                button.disabled = true;
                button.classList.add('loading');
                button.style.opacity = '0.7';
                button.style.pointerEvents = 'none';
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-spinner fa-spin';
                }
                const text = button.querySelector('span');
                if (text) {
                    text.textContent = 'Installing...';
                }
            } else {
                button.disabled = false;
                button.classList.remove('loading');
                button.style.opacity = '1';
                button.style.pointerEvents = 'auto';
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = 'fas fa-download';
                }
                const text = button.querySelector('span');
                if (text) {
                    text.textContent = 'Install App';
                }
            }
        });
    }

    showInstallationFeedback() {
        const notification = document.createElement('div');
        notification.className = 'pwa-install-feedback';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            animation: slideDown 0.3s ease-out;
            max-width: 90vw;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #10b981; color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); flex-wrap: wrap;">
                <i class="fas fa-check-circle" style="font-size: 1.25rem; flex-shrink: 0;"></i>
                <span style="flex: 1; min-width: 200px;">Installation in progress... The app will appear on your home screen.</span>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s ease-out';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }

    showErrorMessage(message) {
        const notification = document.createElement('div');
        notification.className = 'pwa-error-message';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            animation: slideDown 0.3s ease-out;
            max-width: 90vw;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #ef4444; color: white; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); flex-wrap: wrap;">
                <i class="fas fa-exclamation-circle" style="font-size: 1.25rem; flex-shrink: 0;"></i>
                <span style="flex: 1; min-width: 200px;">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="margin-left: auto; background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem; padding: 0; flex-shrink: 0;">×</button>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.style.opacity = '0';
                notification.style.transition = 'opacity 0.3s ease-out';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
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
