// Mobile-Friendly Form Enhancement v2.0

document.addEventListener('DOMContentLoaded', function() {
    initializeMobileForms();
});

function initializeMobileForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Enhance form groups
        enhanceFormGroups(form);
        
        // Enhance inputs
        enhanceInputs(form);
        
        // Enhance labels
        enhanceLabels(form);
        
        // Add validation feedback
        addValidationFeedback(form);
        
        // Improve submit buttons
        improveSubmitButtons(form);
    });
}

function enhanceFormGroups(form) {
    const formGroups = form.querySelectorAll('.form-group, .mb-4, .mb-6');
    
    formGroups.forEach(group => {
        // Ensure proper spacing
        if (!group.classList.contains('form-group-enhanced')) {
            group.classList.add('form-group-enhanced');
            group.style.marginBottom = '1.5rem';
        }
    });
}

function enhanceInputs(form) {
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        // Add mobile-input class
        input.classList.add('mobile-input');
        
        // Ensure minimum height of 44px for touch targets
        const computedStyle = window.getComputedStyle(input);
        const height = parseInt(computedStyle.height);
        if (height < 44) {
            input.style.minHeight = '44px';
        }
        
        // Set font size to 16px to prevent iOS zoom
        if (['text', 'email', 'password', 'number', 'date', 'time', 'tel', 'url', 'search'].includes(input.type)) {
            input.style.fontSize = '16px';
        }
        
        // Add focus styles for better visibility
        input.addEventListener('focus', function() {
            this.classList.add('mobile-input-focused');
            this.parentElement?.classList.add('mobile-input-focused');
        });
        
        input.addEventListener('blur', function() {
            this.classList.remove('mobile-input-focused');
            this.parentElement?.classList.remove('mobile-input-focused');
        });
        
        // Add padding for better touch target
        if (input.tagName === 'TEXTAREA') {
            input.style.padding = '12px';
            input.style.minHeight = '120px';
            input.style.lineHeight = '1.5';
        } else if (input.tagName !== 'INPUT' || input.type !== 'checkbox' && input.type !== 'radio') {
            input.style.padding = '12px';
        }
        
        // Handle select elements
        if (input.tagName === 'SELECT') {
            input.style.padding = '12px';
            input.style.minHeight = '44px';
        }
    });
}

function enhanceLabels(form) {
    const labels = form.querySelectorAll('label');
    
    labels.forEach(label => {
        // Ensure labels are clickable with good touch target
        label.style.cursor = 'pointer';
        label.style.display = 'block';
        label.style.marginBottom = '0.5rem';
        label.style.fontWeight = '600';
        label.style.fontSize = '0.95rem';
        
        // Add focus color indication
        const input = label.querySelector('input, select, textarea');
        if (input) {
            input.addEventListener('focus', function() {
                label.classList.add('focused');
                label.style.color = 'var(--color-primary, #3b82f6)';
            });
            
            input.addEventListener('blur', function() {
                label.classList.remove('focused');
                label.style.color = '';
            });
        }
    });
}

function addValidationFeedback(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        // Add validation listener
        input.addEventListener('invalid', function(e) {
            e.preventDefault();
            this.classList.add('is-invalid');
            this.setAttribute('aria-invalid', 'true');
            
            // Show error message
            showErrorMessage(this);
        });
        
        input.addEventListener('input', function() {
            if (this.validity.valid) {
                this.classList.remove('is-invalid');
                this.removeAttribute('aria-invalid');
                removeErrorMessage(this);
            }
        });
    });
}

function showErrorMessage(input) {
    // Remove existing error message
    removeErrorMessage(input);
    
    // Create error message
    const errorMsg = document.createElement('div');
    errorMsg.className = 'mobile-error-message';
    errorMsg.style.cssText = `
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        padding: 0.5rem;
        background: #fee2e2;
        border-radius: 0.375rem;
    `;
    
    // Determine error type
    if (input.validity.valueMissing) {
        errorMsg.textContent = 'This field is required';
    } else if (input.validity.typeMismatch) {
        errorMsg.textContent = `Please enter a valid ${input.type}`;
    } else if (input.validity.tooShort) {
        errorMsg.textContent = `Minimum ${input.minLength} characters required`;
    } else if (input.validity.tooLong) {
        errorMsg.textContent = `Maximum ${input.maxLength} characters allowed`;
    } else if (input.validity.patternMismatch) {
        errorMsg.textContent = 'Please enter a valid format';
    }
    
    input.parentElement?.appendChild(errorMsg);
}

function removeErrorMessage(input) {
    const errorMsg = input.parentElement?.querySelector('.mobile-error-message');
    if (errorMsg) {
        errorMsg.remove();
    }
}

function improveSubmitButtons(form) {
    const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
    
    submitButtons.forEach(button => {
        // Ensure minimum touch target size
        button.style.minHeight = '44px';
        button.style.minWidth = '100px';
        button.style.padding = '12px 24px';
        button.style.fontSize = '16px';
        button.style.fontWeight = '600';
        button.style.width = window.innerWidth < 768 ? '100%' : 'auto';
        
        // Add loading state handler
        button.addEventListener('click', function(e) {
            if (form.checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
            
            // Show loading state
            const originalText = this.textContent;
            const originalHTML = this.innerHTML;
            this.disabled = true;
            this.textContent = 'Loading...';
            this.classList.add('opacity-75');
            
            // Re-enable after submission (or timeout)
            setTimeout(() => {
                if (this.disabled) {
                    this.disabled = false;
                    this.textContent = originalText;
                    this.innerHTML = originalHTML;
                    this.classList.remove('opacity-75');
                }
            }, 3000);
        });
    });
}

// Handle form stacking on mobile
function stackFormColumns() {
    const formGroups = document.querySelectorAll('[class*="col-"]');
    
    if (window.innerWidth < 768) {
        formGroups.forEach(group => {
            group.style.width = '100%';
            group.style.display = 'block';
        });
    } else {
        formGroups.forEach(group => {
            group.style.width = '';
            group.style.display = '';
        });
    }
}

// Initialize and watch for resize
window.addEventListener('resize', stackFormColumns);
window.addEventListener('load', stackFormColumns);
stackFormColumns();

// Handle file inputs on mobile
function enhanceFileInputs() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        // Create custom file input
        const label = input.nextElementSibling;
        if (!label || !label.classList.contains('file-label')) {
            const customLabel = document.createElement('label');
            customLabel.className = 'file-label mobile-file-input';
            customLabel.style.cssText = `
                display: inline-block;
                padding: 12px 24px;
                background: #3b82f6;
                color: white;
                border-radius: 0.375rem;
                cursor: pointer;
                min-height: 44px;
                display: flex;
                align-items: center;
                text-align: center;
                width: 100%;
                font-weight: 600;
            `;
            customLabel.innerHTML = '<i class="fas fa-upload mr-2"></i>Choose File';
            customLabel.appendChild(input);
            input.parentElement.insertBefore(customLabel, input);
        }
        
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            const label = this.closest('.file-label');
            if (label && fileName) {
                label.innerHTML = `<i class="fas fa-check mr-2"></i>${fileName}`;
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', enhanceFileInputs);

// Re-initialize when content changes
document.addEventListener('htmx:afterSwap', initializeMobileForms);
document.addEventListener('htmx:afterSettle', initializeMobileForms);

// Export functions
window.MobileForm = {
    initialize: initializeMobileForms,
    enhanceInputs: enhanceInputs,
    addValidation: addValidationFeedback,
    stackColumns: stackFormColumns
};
