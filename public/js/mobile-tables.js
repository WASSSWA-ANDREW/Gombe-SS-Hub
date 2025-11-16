// Mobile-Friendly Table Enhancement v2.0

document.addEventListener('DOMContentLoaded', function() {
    initializeMobileTables();
});

function initializeMobileTables() {
    const tables = document.querySelectorAll('table');
    
    tables.forEach((table, tableIndex) => {
        // Skip if already processed
        if (table.dataset.mobilized === 'true') {
            return;
        }
        
        // Get headers
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => 
            th.textContent.trim() || th.innerHTML
        );
        
        if (headers.length === 0) {
            return;
        }
        
        // Process each row
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, cellIndex) => {
                if (headers[cellIndex]) {
                    cell.setAttribute('data-label', headers[cellIndex]);
                    
                    // Add mobile-cell class for CSS targeting
                    cell.classList.add('mobile-cell');
                }
            });
        });
        
        // Add wrapper if not already wrapped
        if (!table.parentElement.classList.contains('table-wrapper')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-wrapper mobile-table-responsive';
            table.parentElement.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        } else {
            table.parentElement.classList.add('mobile-table-responsive');
        }
        
        // Mark as processed
        table.dataset.mobilized = 'true';
    });
}

// Re-initialize tables when content is dynamically loaded via AJAX
document.addEventListener('htmx:afterSwap', initializeMobileTables);
document.addEventListener('htmx:afterSettle', initializeMobileTables);

// Also handle common AJAX libraries
if (window.jQuery) {
    jQuery(document).on('ajaxComplete', function() {
        initializeMobileTables();
    });
}

// Handle dynamically added content
function observeNewTables() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.tagName === 'TABLE' || node.querySelector('table')) {
                            initializeMobileTables();
                        }
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

observeNewTables();

// Mobile table utilities
window.MobileTable = {
    refresh: initializeMobileTables,
    
    // Make table scrollable on mobile
    makeScrollable: function(table) {
        if (!table.parentElement.classList.contains('table-scrollable')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-scrollable';
            wrapper.style.overflowX = 'auto';
            wrapper.style.webkitOverflowScrolling = 'touch';
            table.parentElement.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        }
    },
    
    // Convert table to cards on mobile
    makeCardView: function(tableSelector) {
        const table = document.querySelector(tableSelector);
        if (!table) return;
        
        const headers = Array.from(table.querySelectorAll('thead th')).map(th => 
            th.textContent.trim()
        );
        
        const rows = table.querySelectorAll('tbody tr');
        const cards = [];
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const card = {};
            cells.forEach((cell, index) => {
                card[headers[index]] = cell.textContent.trim();
            });
            cards.push(card);
        });
        
        return cards;
    },
    
    // Show/hide table columns on mobile
    toggleColumn: function(tableSelector, columnIndex, show = true) {
        const table = document.querySelector(tableSelector);
        if (!table) return;
        
        const th = table.querySelector(`thead th:nth-child(${columnIndex + 1})`);
        const tds = table.querySelectorAll(`tbody td:nth-child(${columnIndex + 1})`);
        
        if (show) {
            if (th) th.style.display = '';
            tds.forEach(td => td.style.display = '');
        } else {
            if (th) th.style.display = 'none';
            tds.forEach(td => td.style.display = 'none');
        }
    }
};
