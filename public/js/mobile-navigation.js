// Mobile Navigation Enhancement v2.0

document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar functionality
    const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const sidebarBackdrop = document.querySelector('.sidebar-backdrop');
    
    if (mobileSidebarToggle && sidebar) {
        // Enhanced mobile sidebar toggle with backdrop
        mobileSidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileSidebar();
        });
        
        // Close sidebar when clicking backdrop
        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', closeMobileSidebar);
        }
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !mobileSidebarToggle.contains(e.target)) {
                closeMobileSidebar();
            }
        });
        
        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });
        
        // Close sidebar when clicking on a link
        const sidebarLinks = sidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    closeMobileSidebar();
                }
            });
        });
    }
    
    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (window.innerWidth >= 768) {
                closeMobileSidebar();
            }
        }, 250);
    });
    
    // Detect and prevent page scroll when mobile sidebar is open
    const originalOverflow = document.body.style.overflow;
    const observer = new MutationObserver(function() {
        if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = originalOverflow;
        }
    });
    
    if (sidebar) {
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    }
    
    // Prevent pinch zoom on touch devices (optional)
    // Uncomment if needed
    // document.addEventListener('touchmove', function(e) {
    //     if (e.touches.length > 1) {
    //         e.preventDefault();
    //     }
    // }, { passive: false });
});

function toggleMobileSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar) {
        sidebar.classList.toggle('-translate-x-full');
        
        // Save state to localStorage
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        localStorage.setItem('mobile-sidebar-open', isOpen);
    }
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.add('-translate-x-full');
        localStorage.setItem('mobile-sidebar-open', 'false');
    }
}

function openMobileSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar && sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        localStorage.setItem('mobile-sidebar-open', 'true');
    }
}

// Mobile-friendly table handling
function initializeMobileTables() {
    const tables = document.querySelectorAll('table');
    
    tables.forEach(table => {
        // Only apply mobile styles to data tables, not layout tables
        if (!table.classList.contains('layout-table')) {
            const rows = table.querySelectorAll('tbody tr');
            const headers = table.querySelectorAll('thead th');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index].textContent.trim());
                    }
                });
            });
            
            // Add responsive wrapper if needed
            if (!table.parentElement.classList.contains('table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentElement.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        }
    });
}

// Initialize mobile tables on load
document.addEventListener('DOMContentLoaded', initializeMobileTables);

// Reinitialize tables when content is dynamically loaded
if (window.MutationObserver) {
    const observer = new MutationObserver(function() {
        initializeMobileTables();
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

// Mobile form enhancement
function initializeMobileForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            // Add touch-friendly class for styling
            input.classList.add('mobile-input');
            
            // Increase font size to prevent iOS zoom on focus
            if (input.type === 'text' || input.type === 'email' || input.type === 'password' || input.type === 'number' || input.type === 'date') {
                input.style.fontSize = '16px';
            }
            
            // Add focus feedback
            input.addEventListener('focus', function() {
                this.parentElement?.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement?.classList.remove('focused');
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeMobileForms);

// Mobile dropdown menu handling
function initializeDropdowns() {
    const dropdownTriggers = document.querySelectorAll('[data-toggle="dropdown"]');
    
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const menu = document.querySelector(this.getAttribute('data-target'));
            if (menu) {
                menu.classList.toggle('hidden');
                
                // Close other dropdowns
                document.querySelectorAll('[data-toggle="dropdown"][data-target]').forEach(other => {
                    if (other !== trigger) {
                        const otherMenu = document.querySelector(other.getAttribute('data-target'));
                        if (otherMenu) otherMenu.classList.add('hidden');
                    }
                });
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-toggle="dropdown"]') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', initializeDropdowns);

// Mobile search enhancement
function initializeMobileSearch() {
    const searchInput = document.getElementById('global-search');
    
    if (searchInput) {
        // Prevent body scroll when search is focused
        searchInput.addEventListener('focus', function() {
            document.body.style.overflow = 'hidden';
        });
        
        searchInput.addEventListener('blur', function() {
            document.body.style.overflow = '';
        });
        
        // Add keyboard handling
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.blur();
                const suggestions = document.getElementById('search-suggestions');
                if (suggestions) suggestions.classList.add('hidden');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', initializeMobileSearch);

// Touch-friendly menu handling
function enhanceTouchTargets() {
    const allButtons = document.querySelectorAll('button, a.btn, [role="button"]');
    
    allButtons.forEach(btn => {
        // Ensure minimum touch target size
        const rect = btn.getBoundingClientRect();
        
        if (rect.width < 44 || rect.height < 44) {
            btn.classList.add('touch-target-enhance');
        }
        
        // Add ripple effect on touch
        btn.addEventListener('touchstart', function() {
            this.style.opacity = '0.8';
        });
        
        btn.addEventListener('touchend', function() {
            this.style.opacity = '1';
        });
    });
}

document.addEventListener('DOMContentLoaded', enhanceTouchTargets);

// Safe area support for notched devices
function applySafeArea() {
    if (CSS.supports('padding-left', 'env(safe-area-inset-left)')) {
        const hasNotch = navigator.userAgentData?.mobile || window.navigator.userAgent.includes('iPhone');
        if (hasNotch) {
            document.documentElement.style.setProperty('--safe-area-inset-left', 'env(safe-area-inset-left)');
            document.documentElement.style.setProperty('--safe-area-inset-right', 'env(safe-area-inset-right)');
            document.documentElement.style.setProperty('--safe-area-inset-top', 'env(safe-area-inset-top)');
            document.documentElement.style.setProperty('--safe-area-inset-bottom', 'env(safe-area-inset-bottom)');
        }
    }
}

document.addEventListener('DOMContentLoaded', applySafeArea);

// Mobile viewport optimization
function optimizeViewport() {
    const viewport = document.querySelector('meta[name="viewport"]');
    
    if (viewport && window.innerWidth < 768) {
        // Ensure proper viewport settings for mobile
        const viewportContent = 'width=device-width, initial-scale=1.0, viewport-fit=cover, user-scalable=yes, maximum-scale=5.0';
        viewport.setAttribute('content', viewportContent);
    }
}

document.addEventListener('DOMContentLoaded', optimizeViewport);

// Detect orientation changes
window.addEventListener('orientationchange', function() {
    // Reapply styles if needed
    document.body.dispatchEvent(new Event('orientationchange'));
    
    // Close mobile sidebar on orientation change
    closeMobileSidebar();
});

// Loading state handling for mobile
function showLoadingIndicator(element) {
    const spinner = document.createElement('div');
    spinner.className = 'spinner';
    spinner.innerHTML = '<div class="animate-spin h-5 w-5 border-4 border-current border-r-transparent"></div>';
    
    if (element) {
        element.appendChild(spinner);
    } else {
        document.body.appendChild(spinner);
    }
    
    return spinner;
}

function hideLoadingIndicator(spinner) {
    if (spinner) {
        spinner.remove();
    }
}

// Export functions for global use
window.MobileNav = {
    toggleSidebar: toggleMobileSidebar,
    closeSidebar: closeMobileSidebar,
    openSidebar: openMobileSidebar,
    showLoading: showLoadingIndicator,
    hideLoading: hideLoadingIndicator
};
