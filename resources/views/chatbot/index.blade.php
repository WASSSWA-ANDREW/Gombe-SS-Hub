@extends('layouts.admin')

@section('title', 'School Assistant Chatbot')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-robot"></i> School Assistant Chatbot
                        </h4>
                        <small>Ask me anything about the school management system</small>
                    </div>
                    <div class="card-body" style="height: 500px; overflow-y: auto;" id="chatMessages">
                        <div class="chat-message bot-message mb-3">
                            <div class="d-flex">
                                <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="message-content">
                                    <div class="bg-light p-3 rounded">
                                        Hello! I'm your school management assistant. I can help you with:
                                        <ul class="mb-0 mt-2">
                                            <li>Student and staff management</li>
                                            <li>System navigation</li>
                                            <li>Report generation</li>
                                            <li>General questions about the system</li>
                                        </ul>
                                        <div class="mt-3 p-2 bg-success bg-opacity-10 rounded">
                                            <i class="fas fa-comment-dots text-success"></i> 
                                            <strong>Need to send feedback?</strong> Click the <span class="text-success">Feedback</span> button below to send a message directly to the Super Admin via WhatsApp!
                                        </div>
                                    </div>
                                    <small class="text-muted">Just now</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form id="chatForm" class="d-flex">
                            <input type="text" id="messageInput" class="form-control me-2"
                                placeholder="Type your message here..." required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                            <button type="button" class="btn btn-success ms-2" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                                <i class="fas fa-comment-dots"></i> Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="feedbackModalLabel">
                        <i class="fas fa-comment-dots"></i> Send Feedback to Super Admin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedbackForm">
                        @csrf
                        <div class="mb-3">
                            <label for="feedbackMessage" class="form-label">Your Feedback</label>
                            <textarea class="form-control" id="feedbackMessage" name="feedback" rows="5" 
                                placeholder="Share your feedback, suggestions, or report issues..." required></textarea>
                            <small class="text-muted">Your feedback will be sent directly to the Super Admin via WhatsApp</small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This will open WhatsApp to send your message to the Super Admin
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="sendFeedbackBtn">
                        <i class="fab fa-whatsapp"></i> Send via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .chat-message {
            margin-bottom: 1rem;
        }

        .user-message .d-flex {
            flex-direction: row-reverse;
        }

        .user-message .message-content {
            text-align: right;
        }

        .user-message .bg-primary {
            background-color: #007bff !important;
            color: white;
        }

        .bot-message .bg-light {
            background-color: #f8f9fa !important;
        }

        #chatMessages {
            scroll-behavior: smooth;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatForm = document.getElementById('chatForm');
            const messageInput = document.getElementById('messageInput');
            const chatMessages = document.getElementById('chatMessages');

            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const message = messageInput.value.trim();
                if (!message) return;

                // Add user message
                addMessage(message, 'user');
                messageInput.value = '';

                // Show typing indicator
                showTypingIndicator();

                // Send to chatbot API
                fetch('/api/chatbot/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: message })
                })
                    .then(response => response.json())
                    .then(data => {
                        hideTypingIndicator();
                        if (data.success) {
                            addMessage(data.response, 'bot');
                        } else {
                            addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                        }
                    })
                    .catch(error => {
                        hideTypingIndicator();
                        addMessage('Sorry, I encountered an error. Please try again.', 'bot');
                    });
            });

            function addMessage(message, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${sender}-message mb-3`;

                const avatar = sender === 'user' ?
                    '<i class="fas fa-user"></i>' :
                    '<i class="fas fa-robot"></i>';

                const bgClass = sender === 'user' ? 'bg-primary text-white' : 'bg-light';

                messageDiv.innerHTML = `
                <div class="d-flex">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        ${avatar}
                    </div>
                    <div class="message-content">
                        <div class="${bgClass} p-3 rounded">
                            ${message}
                        </div>
                        <small class="text-muted">Just now</small>
                    </div>
                </div>
            `;

                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function showTypingIndicator() {
                const typingDiv = document.createElement('div');
                typingDiv.id = 'typingIndicator';
                typingDiv.className = 'chat-message bot-message mb-3';
                typingDiv.innerHTML = `
                <div class="d-flex">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="message-content">
                        <div class="bg-light p-3 rounded">
                            <div class="typing-dots">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                chatMessages.appendChild(typingDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function hideTypingIndicator() {
                const typingIndicator = document.getElementById('typingIndicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
            }

            // Feedback functionality
            const sendFeedbackBtn = document.getElementById('sendFeedbackBtn');
            const feedbackMessage = document.getElementById('feedbackMessage');
            const feedbackModal = document.getElementById('feedbackModal');

            sendFeedbackBtn.addEventListener('click', function() {
                const feedback = feedbackMessage.value.trim();
                
                if (!feedback) {
                    alert('Please enter your feedback message');
                    return;
                }

                // Get current user info
                const userName = '{{ auth()->user()->name ?? "User" }}';
                const userEmail = '{{ auth()->user()->email ?? "N/A" }}';
                
                // Format the message for WhatsApp
                const whatsappMessage = `*CHATBOT FEEDBACK*\n\n` +
                    `*From:* ${userName}\n` +
                    `*Email:* ${userEmail}\n` +
                    `*Date:* ${new Date().toLocaleString()}\n\n` +
                    `*Feedback:*\n${feedback}`;

                // Super Admin WhatsApp number (Uganda format)
                const phoneNumber = '256779201801'; // 0779201801 in international format
                
                // Create WhatsApp URL
                const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(whatsappMessage)}`;
                
                // Open WhatsApp
                window.open(whatsappUrl, '_blank');
                
                // Clear the form and close modal
                feedbackMessage.value = '';
                const modalInstance = bootstrap.Modal.getInstance(feedbackModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                
                // Show success message in chat
                addMessage('Your feedback has been prepared for WhatsApp. Please send the message to complete the feedback submission.', 'bot');
            });
        });
    </script>

    <style>
        .typing-dots {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #6c757d;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }

        @keyframes typing {

            0%,
            80%,
            100% {
                transform: scale(0);
                opacity: 0.5;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
@endsection