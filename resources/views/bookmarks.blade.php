@extends('layouts.admin')

@section('title', 'My Bookmarks')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-bookmark"></i> My Bookmarks
                            </h4>
                            <small>Manage your saved pages and quick links</small>
                        </div>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addBookmarkModal">
                            <i class="fas fa-plus"></i> Add Bookmark
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group position-relative shadow-sm rounded overflow-hidden hover:shadow-md transition-all duration-300">
                                <span class="input-group-text bg-transparent border-end-0 text-muted group-hover:text-primary transition-colors duration-300">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="searchBookmarks" 
                                    class="form-control border-start-0 ps-0 
                                    transition-all duration-300 
                                    focus-within:shadow-md focus-within:border-primary
                                    bg-white dark:bg-gray-800" 
                                    placeholder="Search bookmarks..."
                                    autocomplete="off">
                                <div class="position-absolute end-0 top-0 bottom-0 d-flex align-items-center pe-3 d-none search-clear">
                                    <button class="btn btn-sm text-muted p-0 border-0 hover:text-danger transition-colors duration-300" type="button" id="clearSearch">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All Categories</option>
                                <option value="students">Students</option>
                                <option value="staff">Staff</option>
                                <option value="reports">Reports</option>
                                <option value="admin">Administration</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-outline-primary active" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Bookmarks Container -->
                    <div id="bookmarksContainer">
                        <!-- Grid View -->
                        <div id="gridView" class="row">
                            <!-- Bookmarks will be loaded here -->
                        </div>

                        <!-- List View -->
                        <div id="listView" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>URL</th>
                                            <th>Category</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookmarksTableBody">
                                        <!-- Table rows will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- No Bookmarks Message -->
                    <div id="noBookmarks" class="text-center py-5" style="display: none;">
                        <i class="fas fa-bookmark fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No bookmarks found</h5>
                        <p class="text-muted">Start by adding your first bookmark!</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookmarkModal">
                            <i class="fas fa-plus"></i> Add Bookmark
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bookmark Modal -->
<div class="modal fade" id="addBookmarkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Bookmark</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookmarkForm">
                    @csrf
                    <div class="mb-3">
                        <label for="bookmarkTitle" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="bookmarkTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookmarkUrl" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="bookmarkUrl" name="url" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookmarkCategory" class="form-label">Category</label>
                        <select class="form-select" id="bookmarkCategory" name="category">
                            <option value="other">Other</option>
                            <option value="students">Students</option>
                            <option value="staff">Staff</option>
                            <option value="reports">Reports</option>
                            <option value="admin">Administration</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="bookmarkDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="bookmarkDescription" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveBookmark()">Save Bookmark</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentView = 'grid';
    let bookmarks = [];

    // Load bookmarks on page load
    loadBookmarks();

    // Search functionality
    const searchInput = document.getElementById('searchBookmarks');
    const clearSearchBtn = document.querySelector('.search-clear');
    
    searchInput.addEventListener('input', function() {
        filterBookmarks();
        
        // Show/hide clear button based on input content
        if (this.value.trim() !== '') {
            clearSearchBtn.classList.remove('d-none');
        } else {
            clearSearchBtn.classList.add('d-none');
        }
    });
    
    // Clear search functionality
    document.getElementById('clearSearch').addEventListener('click', function() {
        searchInput.value = '';
        clearSearchBtn.classList.add('d-none');
        filterBookmarks();
        searchInput.focus();
    });
    
    // Add focus effects
    searchInput.addEventListener('focus', function() {
        this.parentElement.classList.add('shadow-md');
        this.parentElement.classList.add('border-primary');
    });
    
    searchInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.parentElement.classList.remove('shadow-md');
            this.parentElement.classList.remove('border-primary');
        }
    });

    // Category filter
    document.getElementById('categoryFilter').addEventListener('change', function() {
        filterBookmarks();
    });

    // View toggle
    document.querySelectorAll('[data-view]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('[data-view]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            currentView = this.dataset.view;
            toggleView();
        });
    });

    function loadBookmarks() {
        showLoading();
        
        fetch('/api/bookmarks')
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    bookmarks = data.bookmarks;
                    displayBookmarks(bookmarks);
                } else {
                    showNoBookmarks();
                }
            })
            .catch(error => {
                hideLoading();
                showNoBookmarks();
                console.error('Error loading bookmarks:', error);
            });
    }

    function displayBookmarks(bookmarksToShow) {
        if (bookmarksToShow.length === 0) {
            showNoBookmarks();
            return;
        }

        document.getElementById('noBookmarks').style.display = 'none';
        document.getElementById('bookmarksContainer').style.display = 'block';

        if (currentView === 'grid') {
            displayGridView(bookmarksToShow);
        } else {
            displayListView(bookmarksToShow);
        }
    }

    function displayGridView(bookmarksToShow) {
        const gridContainer = document.getElementById('gridView');
        gridContainer.innerHTML = '';

        bookmarksToShow.forEach(bookmark => {
            const bookmarkCard = document.createElement('div');
            bookmarkCard.className = 'col-md-4 col-lg-3 mb-3';
            bookmarkCard.innerHTML = `
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">${bookmark.title}</h6>
                        <p class="card-text text-muted small">${bookmark.description || 'No description'}</p>
                        <span class="badge bg-secondary">${bookmark.category}</span>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group w-100" role="group">
                            <a href="${bookmark.url}" class="btn btn-sm btn-primary" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteBookmark(${bookmark.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            gridContainer.appendChild(bookmarkCard);
        });
    }

    function displayListView(bookmarksToShow) {
        const tableBody = document.getElementById('bookmarksTableBody');
        tableBody.innerHTML = '';

        bookmarksToShow.forEach(bookmark => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${bookmark.title}</td>
                <td><a href="${bookmark.url}" target="_blank" class="text-decoration-none">${bookmark.url}</a></td>
                <td><span class="badge bg-secondary">${bookmark.category}</span></td>
                <td>${new Date(bookmark.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="${bookmark.url}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteBookmark(${bookmark.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function toggleView() {
        if (currentView === 'grid') {
            document.getElementById('gridView').style.display = 'block';
            document.getElementById('listView').style.display = 'none';
        } else {
            document.getElementById('gridView').style.display = 'none';
            document.getElementById('listView').style.display = 'block';
        }
        displayBookmarks(bookmarks);
    }

    function filterBookmarks() {
        const searchTerm = document.getElementById('searchBookmarks').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;

        const filtered = bookmarks.filter(bookmark => {
            const matchesSearch = bookmark.title.toLowerCase().includes(searchTerm) ||
                                (bookmark.description && bookmark.description.toLowerCase().includes(searchTerm));
            const matchesCategory = !categoryFilter || bookmark.category === categoryFilter;
            
            return matchesSearch && matchesCategory;
        });

        displayBookmarks(filtered);
    }

    function showLoading() {
        document.getElementById('loadingSpinner').style.display = 'block';
        document.getElementById('bookmarksContainer').style.display = 'none';
        document.getElementById('noBookmarks').style.display = 'none';
    }

    function hideLoading() {
        document.getElementById('loadingSpinner').style.display = 'none';
    }

    function showNoBookmarks() {
        document.getElementById('bookmarksContainer').style.display = 'none';
        document.getElementById('noBookmarks').style.display = 'block';
    }

    // Make functions global for onclick handlers
    window.saveBookmark = function() {
        const form = document.getElementById('bookmarkForm');
        const formData = new FormData(form);

        fetch('/api/bookmarks', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('addBookmarkModal').querySelector('.btn-close').click();
                form.reset();
                loadBookmarks();
            } else {
                alert('Error saving bookmark: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            alert('Error saving bookmark. Please try again.');
            console.error('Error:', error);
        });
    };

    window.deleteBookmark = function(bookmarkId) {
        if (confirm('Are you sure you want to delete this bookmark?')) {
            fetch(`/api/bookmarks/${bookmarkId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadBookmarks();
                } else {
                    alert('Error deleting bookmark: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error deleting bookmark. Please try again.');
                console.error('Error:', error);
            });
        }
    };
});
</script>
@endsection