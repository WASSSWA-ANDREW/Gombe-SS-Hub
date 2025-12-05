@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Student Selection Panel -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Select Student</h2>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Level</label>
                <select id="levelFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Levels</option>
                    <option value="olevel">O'Level</option>
                    <option value="alevel">A'Level</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Student</label>
                <div class="relative">
                    <input 
                        type="text" 
                        id="studentSearch" 
                        placeholder="Search student..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <div id="studentList" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg mt-1 hidden shadow-lg">
                        @foreach($students as $student)
                        <button 
                            type="button"
                            class="student-item w-full text-left px-4 py-3 hover:bg-blue-50 border-b border-gray-200 transition"
                            data-student-id="{{ $student->admission_number }}"
                            data-student-name="{{ $student->student_name }}"
                            data-student-level="{{ $student->level ?? 'unknown' }}"
                        >
                            <div class="font-semibold text-gray-800">{{ $student->student_name }}</div>
                            <div class="text-xs text-gray-600">{{ $student->admission_number }} • {{ ucfirst($student->level ?? 'N/A') }} • {{ $student->class ?? 'N/A' }}</div>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="studentInfo" class="hidden mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="mb-2">
                    <span class="text-xs text-gray-600">Name:</span>
                    <div id="selectedName" class="font-semibold text-gray-800"></div>
                </div>
                <div class="mb-2">
                    <span class="text-xs text-gray-600">Admission:</span>
                    <div id="selectedAdmission" class="font-semibold text-gray-800"></div>
                </div>
                <div class="mb-2">
                    <span class="text-xs text-gray-600">Level:</span>
                    <div id="selectedLevel" class="font-semibold text-gray-800"></div>
                </div>
                <div>
                    <span class="text-xs text-gray-600">Class:</span>
                    <div id="selectedClass" class="font-semibold text-gray-800"></div>
                </div>
            </div>
        </div>

        <!-- Performance Chart and Details Panel -->
        <div class="lg:col-span-3">
            <div id="noSelection" class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 text-lg">Select a student to view their performance growth chart</p>
            </div>

            <div id="performancePanel" class="hidden">
                <!-- Performance Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="text-gray-600 text-sm font-semibold">Average Marks</div>
                        <div id="avgMarks" class="text-3xl font-bold text-blue-600 mt-2">-</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="text-gray-600 text-sm font-semibold">Highest Marks</div>
                        <div id="highMarks" class="text-3xl font-bold text-green-600 mt-2">-</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="text-gray-600 text-sm font-semibold">Lowest Marks</div>
                        <div id="lowMarks" class="text-3xl font-bold text-red-600 mt-2">-</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="text-gray-600 text-sm font-semibold">Total Subjects</div>
                        <div id="totalSubjects" class="text-3xl font-bold text-purple-600 mt-2">-</div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Performance Growth Chart</h3>
                    <div class="relative" style="height: 400px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>

                <!-- Performance Details Table -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Performance by Subject</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b-2 border-gray-300">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Subject</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Avg Marks</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Grade</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Trend</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">High</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Low</th>
                                </tr>
                            </thead>
                            <tbody id="performanceTableBody">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let performanceChart = null;
    let allStudents = @json($students);
    let selectedStudentId = null;

    // Student selection functionality
    document.getElementById('studentSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const studentList = document.getElementById('studentList');
        const items = document.querySelectorAll('.student-item');
        const levelFilter = document.getElementById('levelFilter').value;

        let hasVisibleItems = false;

        items.forEach(item => {
            const studentName = item.dataset.studentName.toLowerCase();
            const studentLevel = item.dataset.studentLevel;
            const matches = studentName.includes(searchTerm);
            const levelMatches = !levelFilter || studentLevel === levelFilter;

            if (matches && levelMatches) {
                item.classList.remove('hidden');
                hasVisibleItems = true;
            } else {
                item.classList.add('hidden');
            }
        });

        if (searchTerm.length > 0 || levelFilter) {
            studentList.classList.remove('hidden');
        } else {
            studentList.classList.add('hidden');
        }
    });

    document.getElementById('levelFilter').addEventListener('change', function() {
        const event = new Event('input');
        document.getElementById('studentSearch').dispatchEvent(event);
    });

    // Close student list when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#studentSearch') && !e.target.closest('#studentList')) {
            document.getElementById('studentList').classList.add('hidden');
        }
    });

    // Student selection
    document.querySelectorAll('.student-item').forEach(item => {
        item.addEventListener('click', async function(e) {
            e.preventDefault();
            selectedStudentId = this.dataset.studentId;
            const studentName = this.dataset.studentName;
            const studentLevel = this.dataset.studentLevel;

            // Update UI
            document.getElementById('studentSearch').value = studentName;
            document.getElementById('studentList').classList.add('hidden');
            
            // Find full student data
            const student = allStudents.find(s => s.admission_number === selectedStudentId);
            if (student) {
                document.getElementById('selectedName').textContent = studentName;
                document.getElementById('selectedAdmission').textContent = selectedStudentId;
                document.getElementById('selectedLevel').textContent = studentLevel.toUpperCase();
                document.getElementById('selectedClass').textContent = student.class || 'N/A';
                document.getElementById('studentInfo').classList.remove('hidden');
            }

            // Fetch and display performance data
            await fetchAndDisplayPerformance(selectedStudentId);
        });
    });

    async function fetchAndDisplayPerformance(studentId) {
        try {
            const response = await fetch(`/admin/academics/student-performance/${studentId}/data`);
            const data = await response.json();

            if (data.success) {
                displayPerformancePanel(data);
            }
        } catch (error) {
            console.error('Error fetching performance data:', error);
        }
    }

    function displayPerformancePanel(data) {
        // Show performance panel and hide no selection message
        document.getElementById('noSelection').classList.add('hidden');
        document.getElementById('performancePanel').classList.remove('hidden');

        // Update summary cards
        document.getElementById('avgMarks').textContent = data.overallPerformance.average_marks.toFixed(2);
        document.getElementById('highMarks').textContent = data.overallPerformance.highest_marks ? data.overallPerformance.highest_marks.toFixed(2) : '-';
        document.getElementById('lowMarks').textContent = data.overallPerformance.lowest_marks ? data.overallPerformance.lowest_marks.toFixed(2) : '-';
        document.getElementById('totalSubjects').textContent = data.overallPerformance.total_subjects;

        // Prepare chart data
        const chartLabels = data.chartLabels;
        const chartDatasets = [];
        const colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#EC4899', '#14B8A6', '#F97316', '#6366F1', '#D946EF'
        ];

        data.chartData.forEach((subject, index) => {
            chartDatasets.push({
                label: subject.label,
                data: subject.data,
                borderColor: colors[index % colors.length],
                backgroundColor: colors[index % colors.length] + '20',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: colors[index % colors.length],
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            });
        });

        // Update chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        
        if (performanceChart) {
            performanceChart.destroy();
        }

        performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: chartDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 12 },
                        borderColor: '#ddd',
                        borderWidth: 1,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: '#e5e7eb',
                            drawBorder: false
                        },
                        title: {
                            display: true,
                            text: 'Marks (%)',
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Academic Year - Term',
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });

        // Update performance table
        populatePerformanceTable(data.performanceBySubject);
    }

    function populatePerformanceTable(performanceBySubject) {
        const tbody = document.getElementById('performanceTableBody');
        tbody.innerHTML = '';

        let rowCount = 0;
        for (const [subject, performances] of Object.entries(performanceBySubject)) {
            const latestPerformance = performances[performances.length - 1];
            const trendClass = latestPerformance.performance_trend >= 0 ? 'text-green-600' : 'text-red-600';
            const trendIcon = latestPerformance.performance_trend >= 0 ? '↑' : '↓';

            const row = document.createElement('tr');
            row.className = rowCount % 2 === 0 ? 'bg-gray-50' : 'bg-white';
            row.innerHTML = `
                <td class="px-4 py-3 text-sm text-gray-800 font-semibold">${subject}</td>
                <td class="px-4 py-3 text-center text-sm font-semibold">${latestPerformance.average_marks.toFixed(2)}</td>
                <td class="px-4 py-3 text-center text-sm font-bold">
                    <span class="px-3 py-1 rounded-full text-white ${getGradeColor(latestPerformance.grade)}">
                        ${latestPerformance.grade || '-'}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-sm font-semibold ${trendClass}">
                    ${trendIcon} ${latestPerformance.performance_trend.toFixed(2)}%
                </td>
                <td class="px-4 py-3 text-center text-sm font-semibold text-green-600">${latestPerformance.highest_marks ? latestPerformance.highest_marks.toFixed(2) : '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-semibold text-red-600">${latestPerformance.lowest_marks ? latestPerformance.lowest_marks.toFixed(2) : '-'}</td>
            `;
            tbody.appendChild(row);
            rowCount++;
        }

        if (rowCount === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    No performance data available for this student
                </td>
            `;
            tbody.appendChild(row);
        }
    }

    function getGradeColor(grade) {
        const colors = {
            'A': 'bg-green-600',
            'B': 'bg-blue-600',
            'C': 'bg-yellow-600',
            'D': 'bg-orange-600',
            'E': 'bg-red-600',
            'F': 'bg-red-800'
        };
        return colors[grade] || 'bg-gray-600';
    }
</script>
@endsection
