@extends('layouts.admin')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-question-circle"></i> Frequently Asked Questions
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Search Bar -->
                    <div class="row mb-4">
                        <div class="col-md-8 mx-auto">
                            <div class="input-group">
                                <input type="text" id="faqSearch" class="form-control" placeholder="Search FAQs...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Category Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="btn-group flex-wrap" role="group">
                                <button type="button" class="btn btn-outline-primary active" data-category="all">All</button>
                                <button type="button" class="btn btn-outline-primary" data-category="general">General</button>
                                <button type="button" class="btn btn-outline-primary" data-category="students">Students</button>
                                <button type="button" class="btn btn-outline-primary" data-category="staff">Staff</button>
                                <button type="button" class="btn btn-outline-primary" data-category="reports">Reports</button>
                                <button type="button" class="btn btn-outline-primary" data-category="technical">Technical</button>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Accordion -->
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQs will be loaded here -->
                    </div>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="text-center py-4" style="display: none;">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No FAQs found</h5>
                        <p class="text-muted">Try adjusting your search terms or category filter.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqAccordion = document.getElementById('faqAccordion');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const noResults = document.getElementById('noResults');
    const searchInput = document.getElementById('faqSearch');
    const categoryButtons = document.querySelectorAll('[data-category]');

    let currentCategory = 'all';
    let currentSearch = '';

    // Load FAQs on page load
    loadFAQs();

    // Search functionality
    searchInput.addEventListener('input', function() {
        currentSearch = this.value;
        loadFAQs();
    });

    // Category filter functionality
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.dataset.category;
            loadFAQs();
        });
    });

    function loadFAQs() {
        showLoading();
        
        const params = new URLSearchParams({
            category: currentCategory,
            search: currentSearch
        });

        fetch(`/api/faqs?${params}`)
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success && data.faqs.length > 0) {
                    displayFAQs(data.faqs);
                } else {
                    showNoResults();
                }
            })
            .catch(error => {
                hideLoading();
                showNoResults();
                console.error('Error loading FAQs:', error);
            });
    }

    function displayFAQs(faqs) {
        faqAccordion.innerHTML = '';
        noResults.style.display = 'none';

        faqs.forEach((faq, index) => {
            const faqItem = document.createElement('div');
            faqItem.className = 'accordion-item';
            faqItem.innerHTML = `
                <h2 class="accordion-header" id="heading${index}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapse${index}" aria-expanded="false" aria-controls="collapse${index}">
                        <div class="d-flex justify-content-between align-items-center w-100 me-3">
                            <span>${faq.question}</span>
                            <span class="badge bg-secondary">${faq.category}</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse${index}" class="accordion-collapse collapse" 
                     aria-labelledby="heading${index}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        ${faq.answer}
                        ${faq.tags && faq.tags.length > 0 ? `
                            <div class="mt-3">
                                <small class="text-muted">Tags: </small>
                                ${faq.tags.map(tag => `<span class="badge bg-light text-dark me-1">${tag}</span>`).join('')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            faqAccordion.appendChild(faqItem);
        });
    }

    function showLoading() {
        loadingSpinner.style.display = 'block';
        faqAccordion.style.display = 'none';
        noResults.style.display = 'none';
    }

    function hideLoading() {
        loadingSpinner.style.display = 'none';
        faqAccordion.style.display = 'block';
    }

    function showNoResults() {
        faqAccordion.style.display = 'none';
        noResults.style.display = 'block';
    }
});
</script>
@endsection