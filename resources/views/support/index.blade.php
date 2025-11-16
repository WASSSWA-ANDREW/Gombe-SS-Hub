@extends('layouts.admin')

@section('title', 'Support Center')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-life-ring"></i> Support Center
                    </h4>
                    <small>Get help with the school management system</small>
                </div>
                <div class="card-body">
                    <!-- Support Options -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-ticket-alt fa-3x text-primary mb-3"></i>
                                    <h5>Submit a Ticket</h5>
                                    <p class="text-muted">Create a support ticket for technical issues or questions</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal">
                                        Create Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-3x text-info mb-3"></i>
                                    <h5>Knowledge Base</h5>
                                    <p class="text-muted">Browse articles and guides for common questions</p>
                                    <button class="btn btn-info" onclick="showKnowledgeBase()">
                                        Browse Articles
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-history fa-3x text-success mb-3"></i>
                                    <h5>My Tickets</h5>
                                    <p class="text-muted">View and track your submitted support tickets</p>
                                    <button class="btn btn-success" onclick="showMyTickets()">
                                        View Tickets
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div id="contentArea">
                        <!-- Welcome Message -->
                        <div id="welcomeContent">
                            <div class="text-center py-5">
                                <i class="fas fa-headset fa-4x text-muted mb-4"></i>
                                <h3>Welcome to Support Center</h3>
                                <p class="lead text-muted">Choose an option above to get started</p>
                            </div>
                        </div>

                        <!-- Knowledge Base Content -->
                        <div id="knowledgeBaseContent" style="display: none;">
                            <h5><i class="fas fa-book"></i> Knowledge Base Articles</h5>
                            <div class="row" id="articlesContainer">
                                <!-- Articles will be loaded here -->
                            </div>
                        </div>

                        <!-- My Tickets Content -->
                        <div id="myTicketsContent" style="display: none;">
                            <h5><i class="fas fa-history"></i> My Support Tickets</h5>
                            <div class="table-responsive">
                                <table class="table table-striped" id="ticketsTable">
                                    <thead>
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Subject</th>
                                            <th>Category</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Tickets will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Support Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="ticketForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ticketName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ticketName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ticketEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="ticketEmail" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ticketCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="ticketCategory" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="technical">Technical Issue</option>
                                    <option value="account">Account Problem</option>
                                    <option value="feature">Feature Request</option>
                                    <option value="bug">Bug Report</option>
                                    <option value="general">General Question</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ticketPriority" class="form-label">Priority</label>
                                <select class="form-select" id="ticketPriority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ticketSubject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ticketSubject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="ticketDescription" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="ticketDescription" name="description" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="ticketAttachments" class="form-label">Attachments</label>
                        <input type="file" class="form-control" id="ticketAttachments" name="attachments[]" multiple>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitTicket()">Create Ticket</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadKnowledgeBaseArticles();
    loadMyTickets();
});

function showKnowledgeBase() {
    hideAllContent();
    document.getElementById('knowledgeBaseContent').style.display = 'block';
}

function showMyTickets() {
    hideAllContent();
    document.getElementById('myTicketsContent').style.display = 'block';
}

function hideAllContent() {
    document.getElementById('welcomeContent').style.display = 'none';
    document.getElementById('knowledgeBaseContent').style.display = 'none';
    document.getElementById('myTicketsContent').style.display = 'none';
}

function loadKnowledgeBaseArticles() {
    fetch('/api/support/knowledge-base')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayArticles(data.articles);
            }
        })
        .catch(error => console.error('Error loading articles:', error));
}

function displayArticles(articles) {
    const container = document.getElementById('articlesContainer');
    container.innerHTML = '';

    articles.forEach(article => {
        const articleCard = document.createElement('div');
        articleCard.className = 'col-md-6 mb-3';
        articleCard.innerHTML = `
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title">${article.title}</h6>
                    <p class="card-text text-muted">${article.summary}</p>
                    <span class="badge bg-secondary">${article.category}</span>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-outline-primary" onclick="viewArticle(${article.id})">
                        Read More
                    </button>
                </div>
            </div>
        `;
        container.appendChild(articleCard);
    });
}

function loadMyTickets() {
    fetch('/api/support/my-tickets')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTickets(data.tickets);
            }
        })
        .catch(error => console.error('Error loading tickets:', error));
}

function displayTickets(tickets) {
    const tbody = document.querySelector('#ticketsTable tbody');
    tbody.innerHTML = '';

    tickets.forEach(ticket => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${ticket.ticket_number}</td>
            <td>${ticket.subject}</td>
            <td><span class="badge bg-info">${ticket.category}</span></td>
            <td><span class="badge bg-${getPriorityColor(ticket.priority)}">${ticket.priority}</span></td>
            <td><span class="badge bg-${getStatusColor(ticket.status)}">${ticket.status}</span></td>
            <td>${new Date(ticket.created_at).toLocaleDateString()}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="viewTicket('${ticket.ticket_number}')">
                    View
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getPriorityColor(priority) {
    const colors = {
        'low': 'success',
        'medium': 'warning',
        'high': 'danger',
        'urgent': 'dark'
    };
    return colors[priority] || 'secondary';
}

function getStatusColor(status) {
    const colors = {
        'open': 'primary',
        'in_progress': 'warning',
        'resolved': 'success',
        'closed': 'secondary'
    };
    return colors[status] || 'secondary';
}

function submitTicket() {
    const form = document.getElementById('ticketForm');
    const formData = new FormData(form);

    fetch('/api/support/tickets', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ticket created successfully! Ticket number: ' + data.ticket_number);
            document.getElementById('ticketModal').querySelector('.btn-close').click();
            form.reset();
            loadMyTickets();
        } else {
            alert('Error creating ticket: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error creating ticket. Please try again.');
        console.error('Error:', error);
    });
}

function viewArticle(articleId) {
    // Implementation for viewing full article
    alert('Article view functionality would be implemented here');
}

function viewTicket(ticketNumber) {
    // Implementation for viewing ticket details
    alert('Ticket view functionality would be implemented here');
}
</script>
@endsection