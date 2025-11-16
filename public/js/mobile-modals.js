// Mobile-Friendly Modals & Dropdowns v2.0

class MobileModal {
    constructor(modalElement) {
        this.modal = modalElement;
        this.dialog = this.modal.querySelector('.modal-dialog') || this.modal;
        this.content = this.modal.querySelector('.modal-content');
        this.backdrop = this.modal.querySelector('.modal-backdrop') || document.createElement('div');
        this.isOpen = false;
        this.init();
    }
    
    init() {
        if (!this.backdrop.classList.contains('modal-backdrop')) {
            this.backdrop.className = 'modal-backdrop';
            this.modal.insertBefore(this.backdrop, this.dialog);
        }
        
        // Close button
        const closeBtn = this.modal.querySelector('[data-dismiss="modal"], .btn-close, .close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.close());
        }
        
        // Backdrop click to close
        this.backdrop.addEventListener('click', () => this.close());
        
        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.close();
            }
        });
        
        // Prevent body scroll when modal is open
        this.observeModalState();
    }
    
    open() {
        this.modal.classList.remove('hidden');
        this.modal.classList.add('show');
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
        this.modal.style.display = 'flex';
        
        // Trigger animation
        setTimeout(() => {
            this.dialog.classList.add('modal-slide-up');
        }, 10);
        
        // Focus trap
        this.focusTrap();
    }
    
    close() {
        this.modal.classList.remove('show');
        this.dialog.classList.remove('modal-slide-up');
        
        setTimeout(() => {
            this.modal.classList.add('hidden');
            this.modal.style.display = 'none';
            this.isOpen = false;
            document.body.style.overflow = '';
        }, 300);
    }
    
    toggle() {
        this.isOpen ? this.close() : this.open();
    }
    
    focusTrap() {
        const focusableElements = this.modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab' && this.isOpen) {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });
    }
    
    observeModalState() {
        const observer = new MutationObserver(() => {
            if (this.modal.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            }
        });
        
        observer.observe(this.modal, { attributes: true });
    }
}

// Mobile Dropdown
class MobileDropdown {
    constructor(trigger) {
        this.trigger = trigger;
        this.menu = document.querySelector(trigger.getAttribute('data-target'));
        this.isOpen = false;
        this.init();
    }
    
    init() {
        this.trigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggle();
        });
        
        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.trigger.contains(e.target) && !this.menu.contains(e.target)) {
                this.close();
            }
        });
        
        // Close on menu item click
        this.menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => this.close());
        });
        
        // Mobile: slide up animation
        if (window.innerWidth < 768) {
            this.menu.classList.add('mobile-dropdown-slide');
        }
    }
    
    open() {
        this.menu.classList.remove('hidden');
        this.menu.classList.add('show');
        this.trigger.setAttribute('aria-expanded', 'true');
        this.isOpen = true;
    }
    
    close() {
        this.menu.classList.add('hidden');
        this.menu.classList.remove('show');
        this.trigger.setAttribute('aria-expanded', 'false');
        this.isOpen = false;
    }
    
    toggle() {
        this.isOpen ? this.close() : this.open();
    }
}

// Initialize modals
function initializeMobileModals() {
    const modals = document.querySelectorAll('.modal, [role="dialog"]');
    
    modals.forEach(modal => {
        if (!modal.dataset.mobileInitialized) {
            new MobileModal(modal);
            modal.dataset.mobileInitialized = 'true';
        }
    });
}

// Initialize dropdowns
function initializeMobileDropdowns() {
    const triggers = document.querySelectorAll('[data-toggle="dropdown"]');
    
    triggers.forEach(trigger => {
        if (!trigger.dataset.dropdownInitialized) {
            new MobileDropdown(trigger);
            trigger.dataset.dropdownInitialized = 'true';
        }
    });
}

// Modal trigger buttons
document.addEventListener('DOMContentLoaded', function() {
    initializeMobileModals();
    initializeMobileDropdowns();
    
    // Handle modal triggers via data attributes
    document.querySelectorAll('[data-target*="#"], [data-bs-target]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.getAttribute('data-toggle') === 'modal' || this.getAttribute('data-bs-toggle') === 'modal') {
                e.preventDefault();
                const targetId = this.getAttribute('data-target') || this.getAttribute('data-bs-target');
                const modal = document.querySelector(targetId);
                if (modal) {
                    const mobileModal = new MobileModal(modal);
                    mobileModal.open();
                }
            }
        });
    });
});

// Re-initialize on dynamic content
document.addEventListener('htmx:afterSwap', function() {
    initializeMobileModals();
    initializeMobileDropdowns();
});

document.addEventListener('htmx:afterSettle', function() {
    initializeMobileModals();
    initializeMobileDropdowns();
});

// Alert dialogs for mobile
class MobileAlert {
    static show(message, type = 'info', duration = 3000) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} mobile-alert`;
        alert.innerHTML = `
            <div class="alert-content">
                <i class="fas fa-${this.getIcon(type)} mr-2"></i>
                <span>${message}</span>
            </div>
            <button class="btn-close" aria-label="Close">&times;</button>
        `;
        
        document.body.appendChild(alert);
        
        // Show alert
        setTimeout(() => alert.classList.add('show'), 10);
        
        // Close button
        alert.querySelector('.btn-close').addEventListener('click', () => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        });
        
        // Auto-close
        if (duration > 0) {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, duration);
        }
    }
    
    static getIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Touch-friendly popovers
class MobilePopover {
    constructor(trigger) {
        this.trigger = trigger;
        this.content = trigger.getAttribute('data-content') || trigger.getAttribute('title');
        this.popover = null;
        this.init();
    }
    
    init() {
        this.trigger.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggle();
        });
        
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.trigger.contains(e.target) && this.popover && !this.popover.contains(e.target)) {
                this.close();
            }
        });
    }
    
    create() {
        this.popover = document.createElement('div');
        this.popover.className = 'mobile-popover';
        this.popover.innerHTML = `
            <div class="popover-arrow"></div>
            <div class="popover-body">${this.content}</div>
        `;
        
        document.body.appendChild(this.popover);
        this.position();
    }
    
    position() {
        const rect = this.trigger.getBoundingClientRect();
        this.popover.style.top = rect.top + rect.height + 10 + 'px';
        this.popover.style.left = Math.max(10, rect.left + (rect.width / 2) - (this.popover.offsetWidth / 2)) + 'px';
    }
    
    open() {
        if (!this.popover) {
            this.create();
        }
        this.popover.classList.add('show');
    }
    
    close() {
        if (this.popover) {
            this.popover.classList.remove('show');
        }
    }
    
    toggle() {
        if (this.popover && this.popover.classList.contains('show')) {
            this.close();
        } else {
            this.open();
        }
    }
}

// Initialize popovers
function initializePopovers() {
    document.querySelectorAll('[data-toggle="popover"]').forEach(trigger => {
        if (!trigger.dataset.popoverInitialized) {
            new MobilePopover(trigger);
            trigger.dataset.popoverInitialized = 'true';
        }
    });
}

document.addEventListener('DOMContentLoaded', initializePopovers);

// Export APIs
window.Mobile = {
    Modal: MobileModal,
    Dropdown: MobileDropdown,
    Alert: MobileAlert,
    Popover: MobilePopover,
    initModals: initializeMobileModals,
    initDropdowns: initializeMobileDropdowns,
    initPopovers: initializePopovers
};
