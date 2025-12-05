window.PWADebug = {
    checkInstallability: async function() {
        console.clear();
        console.log('%c=== PWA Installation Debugging ===', 'color: #3B82F6; font-weight: bold; font-size: 16px;');
        
        const checks = {
            'HTTPS or localhost': location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1',
            'Service Worker Support': 'serviceWorker' in navigator,
            'Web Manifest Present': !!document.querySelector('link[rel="manifest"]'),
            'Manifest Accessible': await this.checkManifest(),
            'Icons Accessible': await this.checkIcons(),
            'Service Worker Registered': await this.checkServiceWorker(),
            'Not Already Installed': window.navigator.standalone !== true && !document.referrer.includes('android-app://'),
            'User Gesture Provided': true
        };
        
        console.log('%c--- Check Results ---', 'color: #10B981; font-weight: bold;');
        Object.entries(checks).forEach(([check, result]) => {
            const status = result ? '✓' : '✗';
            const color = result ? '#10B981' : '#EF4444';
            console.log(`%c${status}%c ${check}`, `color: ${color}; font-weight: bold;`, 'color: inherit;');
        });
        
        const allPassed = Object.values(checks).every(Boolean);
        console.log('');
        console.log(`%c${allPassed ? 'Installation should be available!' : 'Installation NOT available - see issues above'}`, 
            `color: white; background: ${allPassed ? '#10B981' : '#EF4444'}; padding: 8px 12px; border-radius: 4px; font-weight: bold;`);
        
        return checks;
    },
    
    checkManifest: async function() {
        try {
            const manifestLink = document.querySelector('link[rel="manifest"]');
            if (!manifestLink) return false;
            
            const response = await fetch(manifestLink.href);
            if (!response.ok) {
                console.warn('Manifest fetch failed:', response.status, response.statusText);
                return false;
            }
            
            const manifest = await response.json();
            if (!manifest.name || !manifest.icons || manifest.icons.length === 0) {
                console.warn('Manifest invalid or missing required fields');
                return false;
            }
            
            console.log('✓ Manifest loaded successfully:', {
                name: manifest.name,
                start_url: manifest.start_url,
                icons: manifest.icons.length,
                display: manifest.display
            });
            
            return true;
        } catch (error) {
            console.error('Manifest check error:', error);
            return false;
        }
    },
    
    checkIcons: async function() {
        try {
            const manifestLink = document.querySelector('link[rel="manifest"]');
            if (!manifestLink) return false;
            
            const response = await fetch(manifestLink.href);
            const manifest = await response.json();
            
            if (!manifest.icons || manifest.icons.length === 0) return false;
            
            const missingIcons = [];
            for (const icon of manifest.icons.slice(0, 3)) {
                try {
                    const iconResponse = await fetch(icon.src);
                    if (!iconResponse.ok) {
                        missingIcons.push(icon.src);
                    }
                } catch (error) {
                    missingIcons.push(icon.src);
                }
            }
            
            if (missingIcons.length > 0) {
                console.warn('Missing icons:', missingIcons);
                return false;
            }
            
            console.log('✓ All required icons are accessible');
            return true;
        } catch (error) {
            console.error('Icon check error:', error);
            return false;
        }
    },
    
    checkServiceWorker: async function() {
        try {
            if (!('serviceWorker' in navigator)) {
                console.warn('Service Workers not supported');
                return false;
            }
            
            const registrations = await navigator.serviceWorker.getRegistrations();
            if (registrations.length === 0) {
                console.warn('No Service Workers registered');
                return false;
            }
            
            console.log(`✓ Service Worker registered (${registrations.length}):`, {
                scope: registrations[0].scope,
                active: !!registrations[0].active,
                installing: !!registrations[0].installing,
                waiting: !!registrations[0].waiting
            });
            
            return true;
        } catch (error) {
            console.error('Service Worker check error:', error);
            return false;
        }
    },
    
    getInstallPromptStatus: function() {
        console.log('%c--- Install Prompt Status ---', 'color: #10B981; font-weight: bold;');
        
        if (window.pwaInstaller) {
            console.log('PWA Installer initialized:', !!window.pwaInstaller);
            console.log('Deferred prompt available:', !!window.pwaInstaller.deferredPrompt);
            console.log('Is installed:', window.pwaInstaller.isInstalled);
        } else {
            console.warn('PWA Installer not initialized');
        }
        
        if (window.pwaCompatibility) {
            console.log('PWA Compatibility:', {
                pwaInstallable: window.pwaCompatibility.capabilities.pwaInstallable,
                deviceType: window.pwaCompatibility.capabilities.iosSupport ? 'iOS' : 
                           window.pwaCompatibility.capabilities.androidSupport ? 'Android' : 'Desktop'
            });
        }
    },
    
    triggerInstall: function() {
        console.log('%c--- Attempting Installation ---', 'color: #10B981; font-weight: bold;');
        
        if (window.pwaInstaller && window.pwaInstaller.triggerInstall) {
            window.pwaInstaller.triggerInstall();
        } else {
            console.error('PWA Installer not available or triggerInstall method missing');
        }
    },
    
    clearCache: async function() {
        console.log('%c--- Clearing Service Worker Cache ---', 'color: #10B981; font-weight: bold;');
        
        try {
            const cacheNames = await caches.keys();
            console.log('Found caches:', cacheNames);
            
            const deleted = await Promise.all(cacheNames.map(name => caches.delete(name)));
            console.log(`✓ Deleted ${deleted.filter(Boolean).length} caches`);
            
            if ('serviceWorker' in navigator) {
                const registrations = await navigator.serviceWorker.getRegistrations();
                for (const reg of registrations) {
                    await reg.unregister();
                    console.log('✓ Unregistered Service Worker');
                }
            }
            
            console.log('Please reload the page to re-register the Service Worker');
        } catch (error) {
            console.error('Cache clearing error:', error);
        }
    },
    
    simulateInstallation: function() {
        console.log('%c--- Simulating Installation Event ---', 'color: #10B981; font-weight: bold;');
        
        window.dispatchEvent(new Event('appinstalled'));
        console.log('✓ Dispatched appinstalled event');
    },
    
    help: function() {
        console.clear();
        console.log('%c PWA Debug Helper Commands', 'color: #3B82F6; font-weight: bold; font-size: 14px;');
        console.log(`
%c
PWADebug.checkInstallability()        %c- Check if PWA is installable
PWADebug.getInstallPromptStatus()     %c- Get current installation status
PWADebug.triggerInstall()              %c- Attempt to trigger installation
PWADebug.clearCache()                  %c- Clear all Service Worker caches
PWADebug.simulateInstallation()       %c- Simulate installation event
PWADebug.help()                        %c- Show this help message
        `, 
        'color: #10B981; font-family: monospace;', 'color: #6B7280;',
        'color: #10B981; font-family: monospace;', 'color: #6B7280;',
        'color: #10B981; font-family: monospace;', 'color: #6B7280;',
        'color: #10B981; font-family: monospace;', 'color: #6B7280;',
        'color: #10B981; font-family: monospace;', 'color: #6B7280;',
        'color: #10B981; font-family: monospace;', 'color: #6B7280;');
    }
};

console.log('%cPWA Debug loaded. Type PWADebug.help() for commands.', 'color: #3B82F6; font-weight: bold;');
