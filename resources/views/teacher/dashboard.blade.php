<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $teacher->getDisplayName() }} - Staff Profile Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Ubuntu', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); min-height: 100vh; }
        .container-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 40px 20px; display: flex; flex-direction: column; position: fixed; height: 100vh; left: 0; top: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.3); z-index: 1000; }
        .profile-pic { width: 140px; height: 140px; border-radius: 50%; background: white; margin: 0 auto 30px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.2); border: 4px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; }
        .profile-pic img { width: 100%; height: 100%; object-fit: cover; }
        .profile-pic.no-image { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 48px; color: white; }
        .sidebar-menu { list-style: none; margin-top: 20px; flex-grow: 1; }
        .menu-item { padding: 0; margin-bottom: 8px; }
        .menu-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s; font-size: 15px; cursor: pointer; border: none; background: none; width: 100%; text-align: left; }
        .menu-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .menu-link.active { background: rgba(102,126,234,0.8); color: white; font-weight: 600; }
        .menu-link i { font-size: 18px; width: 20px; }
        .logout-btn { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: rgba(255,255,255,0.2); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 15px; transition: all 0.3s; width: 100%; font-weight: 500; }
        .logout-btn:hover { background: rgba(255,68,68,0.8); }
        .main-content { margin-left: 280px; flex: 1; padding: 40px 30px; overflow-y: auto; }
        .profile-card { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); margin-bottom: 30px; display: flex; align-items: flex-start; gap: 40px; }
        .profile-pic-large { width: 120px; height: 120px; border-radius: 50%; background: white; overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.15); border: 3px solid #f0f4ff; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .profile-pic-large img { width: 100%; height: 100%; object-fit: cover; }
        .profile-pic-large.no-image { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 48px; color: white; }
        .profile-info { flex: 1; }
        .profile-name { font-size: 32px; font-weight: 700; color: #1e1e1e; margin-bottom: 5px; }
        .profile-title { font-size: 18px; color: #666; margin-bottom: 20px; }
        .profile-details { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .detail-item { display: flex; flex-direction: column; }
        .detail-label { font-size: 12px; color: #999; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; font-weight: 600; }
        .detail-value { font-size: 15px; color: #333; font-weight: 500; }
        .section-title { font-size: 24px; font-weight: 700; color: white; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        .tabs { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
        .tab-btn { padding: 10px 20px; border: 2px solid #ddd; background: white; color: #666; border-radius: 25px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .tab-btn.active { background: #4a90e2; color: white; border-color: #4a90e2; }
        .tab-btn:hover { border-color: #4a90e2; }
        .class-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-left: 5px solid #4a90e2; margin-bottom: 15px; }
        .class-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px; }
        .class-name { font-size: 20px; font-weight: 700; color: #1e1e1e; }
        .view-details-btn { background: #2c3e50; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; transition: background 0.3s; }
        .view-details-btn:hover { background: #1a252f; }
        .class-details { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 14px; }
        .class-detail { display: flex; flex-direction: column; }
        .class-detail-label { color: #999; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; font-weight: 600; }
        .class-detail-value { color: #333; font-weight: 500; }
        .two-column { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px; }
        .card-box { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .card-title { font-size: 18px; font-weight: 700; color: #1e1e1e; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .card-title i { color: #4a90e2; font-size: 20px; }
        .subjects-list { list-style: none; display: flex; flex-direction: column; gap: 12px; }
        .subject-badge { display: inline-flex; align-items: center; gap: 10px; padding: 12px; background: #f8f9fa; border-radius: 8px; }
        .subject-name { color: #333; font-weight: 500; flex: 1; }
        .subject-category { font-size: 12px; color: #999; background: white; padding: 4px 10px; border-radius: 4px; font-weight: 600; text-transform: uppercase; }
        .empty-state { text-align: center; padding: 40px 20px; color: #999; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.3; display: block; }
        .events-list { list-style: none; }
        .event-item { padding: 15px; border-left: 3px solid #4a90e2; background: #f8f9fa; margin-bottom: 12px; border-radius: 4px; }
        .event-title { color: #333; font-weight: 600; margin-bottom: 5px; }
        .event-date { color: #999; font-size: 12px; }
        @media (max-width: 1200px) { .two-column { grid-template-columns: 1fr; } .profile-details { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 768px) { .sidebar { width: 100%; height: auto; position: relative; flex-direction: row; padding: 20px; } .profile-pic { width: 80px; height: 80px; margin: 0 20px 0 0; } .main-content { margin-left: 0; padding: 20px; } .profile-card { flex-direction: column; align-items: center; text-align: center; } .profile-pic-large { margin-bottom: 20px; } .sidebar-menu { display: flex; gap: 10px; } }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="sidebar">
            <div class="profile-pic">
                @if ($teacher->photo_path)
                    <img src="{{ asset($teacher->photo_path) }}" alt="{{ $teacher->getDisplayName() }}">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item"><button class="menu-link active" onclick="navigateTo('dashboard')"><i class="fas fa-chart-line"></i><span>Dashboard</span></button></li>
                <li class="menu-item"><button class="menu-link" onclick="navigateTo('classes')"><i class="fas fa-door-open"></i><span>My Classes</span></button></li>
                <li class="menu-item"><button class="menu-link" onclick="navigateTo('students')"><i class="fas fa-users"></i><span>Students</span></button></li>
                <li class="menu-item"><button class="menu-link" onclick="navigateTo('grades')"><i class="fas fa-file-alt"></i><span>Grades</span></button></li>
                <li class="menu-item"><button class="menu-link" onclick="navigateTo('attendance')"><i class="fas fa-clipboard-check"></i><span>Attendance</span></button></li>
                <li class="menu-item"><button class="menu-link" onclick="navigateTo('calendar')"><i class="fas fa-calendar-alt"></i><span>Calendar</span></button></li>
                <li class="menu-item"><button class="menu-link" onclick="navigateTo('settings')"><i class="fas fa-cog"></i><span>Settings</span></button></li>
            </ul>
            
            <form method="POST" action="{{ route('teacher.logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></button>
            </form>
        </div>
        
        <div class="main-content">
            <div class="profile-card">
                <div class="profile-pic-large">
                    @if ($teacher->photo_path)
                        <img src="{{ asset($teacher->photo_path) }}" alt="{{ $teacher->getDisplayName() }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                
                <div class="profile-info">
                    <div class="profile-name">{{ $teacher->getDisplayName() }}</div>
                    <div class="profile-title">{{ $teacher->designation_of_current_appt ?? 'Teacher' }}</div>
                    
                    <div class="profile-details">
                        <div class="detail-item"><div class="detail-label">Employee ID</div><div class="detail-value">{{ $teacher->registration_no ?? $teacher->ipps_no ?? 'N/A' }}</div></div>
                        <div class="detail-item"><div class="detail-label">Department</div><div class="detail-value">{{ $teacher->department ?? 'N/A' }}</div></div>
                        <div class="detail-item"><div class="detail-label">Gender</div><div class="detail-value">{{ $teacher->sex ?? 'N/A' }}</div></div>
                        <div class="detail-item"><div class="detail-label">Staff Type</div><div class="detail-value">{{ ucfirst($teacher->staff_type ?? 'N/A') }}</div></div>
                        <div class="detail-item"><div class="detail-label">Role</div><div class="detail-value">{{ $teacher->role ?? 'Teacher' }}</div></div>
                        <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value">{{ $teacher->email ?? 'N/A' }}</div></div>
                    </div>
                </div>
            </div>
            
            <div class="section-title"><i class="fas fa-door-open"></i> My Classes</div>
            <div class="tabs">
                <button class="tab-btn active" onclick="filterClasses('current')">Current Semester</button>
                <button class="tab-btn" onclick="filterClasses('past')">Past Semesters</button>
                <button class="tab-btn" onclick="filterClasses('all')">All</button>
            </div>
            
            <div id="classesContainer">
                @if ($teacherSubjects && $teacherSubjects->count() > 0)
                    @php
                        $groupedByClass = collect();
                        foreach ($teacherSubjects as $subject) {
                            $classes = $subject->classes ?? [];
                            foreach ($classes as $class) {
                                if (!$groupedByClass->has($class)) {
                                    $groupedByClass->put($class, []);
                                }
                                $groupedByClass[$class][] = $subject;
                            }
                        }
                    @endphp
                    
                    @if ($groupedByClass->count() > 0)
                        @foreach ($groupedByClass as $className => $subjects)
                            <div class="class-card">
                                <div class="class-header">
                                    <div class="class-name">{{ $className ?? 'Unknown Class' }}</div>
                                    <button class="view-details-btn" onclick="viewClassDetails('{{ $className }}')">View Details</button>
                                </div>
                                <div class="class-details">
                                    <div class="class-detail">
                                        <div class="class-detail-label">Subjects Teaching</div>
                                        <div class="class-detail-value">{{ count($subjects) }}</div>
                                    </div>
                                    <div class="class-detail">
                                        <div class="class-detail-label">Status</div>
                                        <div class="class-detail-value">Active</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state"><i class="fas fa-inbox"></i><p>No classes assigned yet</p></div>
                    @endif
                @else
                    <div class="empty-state"><i class="fas fa-inbox"></i><p>No classes assigned yet</p></div>
                @endif
            </div>
            
            <div class="two-column">
                <div class="card-box">
                    <div class="card-title"><i class="fas fa-book"></i> My Subjects</div>
                    @if ($teacherSubjects && $teacherSubjects->count() > 0)
                        <ul class="subjects-list">
                            @foreach ($teacherSubjects as $subject)
                                <li>
                                    <div class="subject-badge">
                                        <div class="subject-name">
                                            @if ($subject->olevelSubject)
                                                {{ $subject->olevelSubject->subject_name }}
                                            @elseif ($subject->alevelSubject)
                                                {{ $subject->alevelSubject->subject_name }}
                                            @else
                                                Unknown Subject
                                            @endif
                                        </div>
                                        <span class="subject-category">
                                            @if ($subject->specialty)
                                                {{ ucfirst($subject->specialty) }}
                                            @else
                                                {{ $subject->level ? strtoupper($subject->level) : 'General' }}
                                            @endif
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-state"><i class="fas fa-inbox"></i><p>No subjects assigned</p></div>
                    @endif
                </div>
                
                <div class="card-box">
                    <div class="card-title"><i class="fas fa-calendar-check"></i> Upcoming Events</div>
                    <ul class="events-list">
                        <li class="event-item">
                            <div class="event-title">Parent-Teacher Conference</div>
                            <div class="event-date">Oct 26, 2024</div>
                        </li>
                        <li class="event-item">
                            <div class="event-title">Department Meeting</div>
                            <div class="event-date">Oct 28, 2024</div>
                        </li>
                        <li class="event-item">
                            <div class="event-title">Term Begins</div>
                            <div class="event-date">Nov 1, 2024</div>
                        </li>
                        <li class="event-item">
                            <div class="event-title">Mid-Term Exams</div>
                            <div class="event-date">Nov 15, 2024</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function navigateTo(section) { console.log('Navigation to:', section); }
        function filterClasses(filter) { const buttons = document.querySelectorAll('.tab-btn'); buttons.forEach(btn => btn.classList.remove('active')); event.target.classList.add('active'); }
        function viewClassDetails(className) { alert('Viewing details for: ' + className); }
    </script>
</body>
</html>
