@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
            <i class="fas fa-brain text-blue-600 mr-3"></i>Intelligent Analytics Dashboard
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">AI-Powered System Intelligence & Insights</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">System Status</p>
                    <p class="text-3xl font-bold text-green-600">Active</p>
                </div>
                <i class="fas fa-check-circle text-green-600 text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Students Analyzed</p>
                    <p class="text-3xl font-bold text-blue-600" id="studentsCount">0</p>
                </div>
                <i class="fas fa-users text-blue-600 text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">At Risk</p>
                    <p class="text-3xl font-bold text-red-600" id="atRiskCount">0</p>
                </div>
                <i class="fas fa-exclamation-circle text-red-600 text-4xl opacity-20"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Last Updated</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white" id="lastUpdated">Now</p>
                </div>
                <i class="fas fa-sync text-gray-600 text-4xl opacity-20"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>System Recommendations
                </h2>
                <div id="recommendationsContainer" class="space-y-4">
                    <p class="text-gray-500 dark:text-gray-400">Loading recommendations...</p>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-bell text-blue-500 mr-2"></i>Quick Actions
                </h2>
                <div class="space-y-2">
                    <button onclick="generateNotifications()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        <i class="fas fa-envelope mr-2"></i>Generate Notifications
                    </button>
                    <button onclick="loadDashboard()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                        <i class="fas fa-redo mr-2"></i>Refresh Data
                    </button>
                    <button onclick="showChatbot()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition">
                        <i class="fas fa-comments mr-2"></i>AI Chatbot
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Anomalies Detected
            </h2>
            <div id="anomaliesContainer" class="space-y-3">
                <p class="text-gray-500 dark:text-gray-400">Loading anomalies...</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-chart-bar text-green-500 mr-2"></i>Performance Overview
            </h2>
            <div id="performanceContainer" class="space-y-3">
                <p class="text-gray-500 dark:text-gray-400">Loading performance data...</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-robot text-indigo-500 mr-2"></i>Intelligent Chatbot
        </h2>
        <div class="space-y-4">
            <div id="chatHistory" class="bg-gray-50 dark:bg-gray-700 rounded p-4 h-64 overflow-y-auto space-y-2">
                <p class="text-gray-500 text-sm">Chat history will appear here...</p>
            </div>
            <div class="flex gap-2">
                <input type="text" id="chatInput" placeholder="Ask me about student performance, recommendations, anomalies..." 
                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white">
                <button onclick="sendChatQuery()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <div id="suggestionsContainer" class="flex flex-wrap gap-2">
                <button onclick="quickQuery('What is the performance of students?')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                    Performance
                </button>
                <button onclick="quickQuery('Which students are at risk?')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                    Risk Analysis
                </button>
                <button onclick="quickQuery('What recommendations do you have?')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                    Recommendations
                </button>
                <button onclick="quickQuery('Are there any anomalies?')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500">
                    Anomalies
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let chatHistory = [];

function loadDashboard() {
    fetch('/api/v1/intelligence/dashboard', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayDashboard(data.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function displayDashboard(data) {
    document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();

    const recommendationsHtml = data.system_recommendations.recommendations
        .slice(0, 5)
        .map(rec => `
            <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 p-3">
                <p class="font-semibold text-gray-900 dark:text-white text-sm">${rec.type}</p>
                <p class="text-gray-600 dark:text-gray-300 text-sm">${rec.description}</p>
            </div>
        `).join('');
    document.getElementById('recommendationsContainer').innerHTML = recommendationsHtml || '<p class="text-gray-500">No recommendations</p>';

    const anomaliesHtml = [
        ...data.grading_anomalies.anomalies,
        ...data.data_anomalies.anomalies
    ].slice(0, 5).map(anom => `
        <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-3">
            <p class="font-semibold text-gray-900 dark:text-white text-sm">${anom.type}</p>
            <p class="text-gray-600 dark:text-gray-300 text-sm">${anom.description}</p>
        </div>
    `).join('');
    document.getElementById('anomaliesContainer').innerHTML = anomaliesHtml || '<p class="text-gray-500">No anomalies detected</p>';

    document.getElementById('atRiskCount').textContent = data.students_at_risk_count;
}

function generateNotifications() {
    fetch('/api/v1/intelligence/generate-notifications', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Generated ${data.data.total} notifications successfully!`);
            loadDashboard();
        }
    })
    .catch(error => console.error('Error:', error));
}

function sendChatQuery() {
    const query = document.getElementById('chatInput').value.trim();
    if (!query) return;

    addChatMessage('You', query, 'user');
    document.getElementById('chatInput').value = '';

    fetch('/api/v1/chatbot/query', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('api_token')}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ query })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addChatMessage('AI Assistant', data.data.response, 'bot');
        }
    })
    .catch(error => console.error('Error:', error));
}

function quickQuery(query) {
    document.getElementById('chatInput').value = query;
    sendChatQuery();
}

function addChatMessage(sender, message, type) {
    const chatHistory = document.getElementById('chatHistory');
    const messageElement = document.createElement('div');
    messageElement.className = type === 'user' ? 'text-right' : 'text-left';
    messageElement.innerHTML = `
        <div class="inline-block max-w-xs px-3 py-2 rounded ${type === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-900 dark:text-white'}">
            <p class="text-sm">${message}</p>
        </div>
    `;
    chatHistory.appendChild(messageElement);
    chatHistory.scrollTop = chatHistory.scrollHeight;
}

document.addEventListener('DOMContentLoaded', function() {
    loadDashboard();
});
</script>
@endsection
